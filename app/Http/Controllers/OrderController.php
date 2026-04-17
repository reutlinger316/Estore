<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', Auth::id())
            ->with(['orderItems.item', 'storeFront'])
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }
}