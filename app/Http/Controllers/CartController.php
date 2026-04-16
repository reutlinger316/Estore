<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'customer_id' => Auth::id(),
        ]);

        $cartItems = $cart->cartItems()->with('item.storeFront')->get();

        return view('customer.cart.index', compact('cart', 'cartItems'));
    }

    public function add(Item $item)
    {
        $storeFrontId = $item->store_front_id;

        $cart = Cart::firstOrCreate(
            ['customer_id' => Auth::id()],
            ['store_front_id' => $storeFrontId]
        );

        if ($cart->store_front_id === null) {
            $cart->update([
                'store_front_id' => $storeFrontId,
            ]);
        }

        if ($cart->store_front_id != $storeFrontId) {
            return back()->withErrors([
                'cart' => 'You can only add items from one shop at a time. Please clear your cart first.',
            ]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'item_id' => $item->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    public function remove(CartItem $cartItem)
    {
        $cart = Cart::where('customer_id', Auth::id())->first();

        if (!$cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        if ($cart->cartItems()->count() === 0) {
            $cart->update([
                'store_front_id' => null,
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:takeaway,delivery',
            'delivery_zone' => 'required_if:order_type,delivery|nullable|in:inside,outside',
            'delivery_phone' => 'required_if:order_type,delivery|nullable|string|max:20',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string|max:1000',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
        ]);

        $cart = Cart::firstOrCreate([
            'customer_id' => Auth::id(),
        ]);

        $cartItems = $cart->cartItems()->with('item')->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        if (!$cart->store_front_id) {
            return back()->withErrors([
                'cart' => 'Your cart is not linked to a shop.',
            ]);
        }

        $storeFront = $cart->storeFront;

        DB::transaction(function () use ($request, $cart, $cartItems, $storeFront) {
            if ($request->order_type === 'delivery') {
                Auth::user()->update([
                    'phone' => $request->delivery_phone,
                    'default_delivery_address' => $request->delivery_address,
                    'default_delivery_lat' => $request->delivery_lat,
                    'default_delivery_lng' => $request->delivery_lng,
                ]);
            }

            $itemsTotal = 0;
            $preparedOrderItems = [];

            foreach ($cartItems as $cartItem) {
                $item = $cartItem->item()->lockForUpdate()->first();

                if ($item->stock_quantity < $cartItem->quantity) {
                    abort(422, "Sorry, '{$item->item_name}' only has {$item->stock_quantity} left in stock.");
                }

                $item->decrement('stock_quantity', $cartItem->quantity);

                $originalPrice = (float) $item->price;
                $discountPercent = (float) $item->discount;
                $discountAmount = round(($originalPrice * $discountPercent) / 100, 2);
                $discountedUnitPrice = max(round($originalPrice - $discountAmount, 2), 0);

                $lineTotal = $discountedUnitPrice * $cartItem->quantity;
                $itemsTotal += $lineTotal;

                $preparedOrderItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $discountedUnitPrice, // save discounted unit price
                ];
            }

            $deliveryFee = 0;
            $deliveryZone = null;

            if ($request->order_type === 'delivery') {
                $deliveryZone = $request->delivery_zone;

                if ($deliveryZone === 'inside') {
                    $deliveryFee = (float) $storeFront->inside_delivery_fee;
                } else {
                    $deliveryFee = (float) $storeFront->outside_delivery_fee;
                }
            }

            $grandTotal = $itemsTotal + $deliveryFee;

            $order = Order::create([
                'customer_id' => Auth::id(),
                'store_front_id' => $cart->store_front_id,
                'total_amount' => $grandTotal,
                'status' => 'pending',
                'type' => $request->order_type,
                'delivery_zone' => $request->order_type === 'delivery' ? $deliveryZone : null,
                'delivery_fee' => $request->order_type === 'delivery' ? $deliveryFee : 0,
                'delivery_phone' => $request->order_type === 'delivery' ? $request->delivery_phone : null,
                'delivery_address' => $request->order_type === 'delivery' ? $request->delivery_address : null,
                'delivery_lat' => $request->order_type === 'delivery' ? $request->delivery_lat : null,
                'delivery_lng' => $request->order_type === 'delivery' ? $request->delivery_lng : null,
            ]);

            foreach ($preparedOrderItems as $orderItemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $orderItemData['item_id'],
                    'quantity' => $orderItemData['quantity'],
                    'price' => $orderItemData['price'],
                ]);
            }

            $cart->cartItems()->delete();

            $cart->update([
                'store_front_id' => null,
            ]);
        });

        return redirect('/customer/orders')->with('success', 'Order placed successfully.');
    }
}