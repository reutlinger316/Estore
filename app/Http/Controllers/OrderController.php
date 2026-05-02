<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $storeSearch = trim($request->input('store', ''));

        $orders = Order::where('customer_id', Auth::id())
            ->with(['orderItems.item', 'storeFront'])
            ->when($storeSearch !== '', function ($query) use ($storeSearch) {
                $query->whereHas('storeFront', function ($storeQuery) use ($storeSearch) {
                    $storeQuery->where('name', 'like', '%' . $storeSearch . '%')
                        ->orWhere('branch_name', 'like', '%' . $storeSearch . '%');
                });
            })
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders', 'storeSearch'));
    }

    public function orderAgain(Order $order)
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.item', 'storeFront']);

        if (!$order->storeFront || $order->storeFront->confirmation_status !== 'accepted') {
            return back()->withErrors([
                'order_again' => 'This storefront is no longer available.',
            ]);
        }

        $cart = Cart::firstOrCreate(
            ['customer_id' => Auth::id()],
            ['store_front_id' => $order->store_front_id]
        );

        if ($cart->store_front_id === null) {
            $cart->update([
                'store_front_id' => $order->store_front_id,
            ]);
        }

        if ((int) $cart->store_front_id !== (int) $order->store_front_id) {
            return back()->withErrors([
                'order_again' => 'Your cart already contains items from another shop. Please clear your cart first.',
            ]);
        }

        DB::transaction(function () use ($order, $cart) {
            foreach ($order->orderItems as $orderItem) {
                if (!$orderItem->item) {
                    continue;
                }

                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('item_id', $orderItem->item_id)
                    ->first();

                if ($cartItem) {
                    $cartItem->increment('quantity', $orderItem->quantity);
                } else {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'item_id' => $orderItem->item_id,
                        'quantity' => $orderItem->quantity,
                    ]);
                }
            }
        });

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Previous order items added to cart successfully.');
    }
}