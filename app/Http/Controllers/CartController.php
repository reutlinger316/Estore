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
            'delivery_phone' => 'required_if:order_type,delivery',
            'delivery_address' => 'required_if:order_type,delivery',
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
        DB::transaction(function () use ($request,$cart, $cartItems) {
            if ($request->order_type === 'delivery') {
                Auth::user()->update([
                    'phone' => $request->delivery_phone,
                    'default_delivery_address' => $request->delivery_address,
                    'default_delivery_lat' => $request->delivery_lat,
                    'default_delivery_lng' => $request->delivery_lng,
                ]);
            }
            $total = 0;
            foreach ($cartItems as $cartItem) {
                
                $item = $cartItem->item()->lockForUpdate()->first();               
                if ($item->stock_quantity < $cartItem->quantity) {
                    abort(422, "Sorry, '{$item->name}' only has {$item->stock_quantity} left in stock.");
                }                
                $item->decrement('stock_quantity', $cartItem->quantity);
                $total += $item->price * $cartItem->quantity;
            }


            $order = Order::create([
                'customer_id' => Auth::id(),
                'store_front_id' => $cart->store_front_id,
                'total_amount' => $total,
                'status' => 'pending',
                'type' => $request->order_type,
                'delivery_phone' => $request->order_type === 'delivery' ? $request->delivery_phone : null,
                'delivery_address' => $request->order_type === 'delivery' ? $request->delivery_address : null,
                'delivery_lat' => $request->order_type === 'delivery' ? $request->delivery_lat : null,
                'delivery_lng' => $request->order_type === 'delivery' ? $request->delivery_lng : null,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->item->price,
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
