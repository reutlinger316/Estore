<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function customerShow(Order $order)
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $order->load([
            'customer',
            'storeFront',
            'orderItems.item',
        ]);

        return view('receipts.show', compact('order'));
    }

    public function storefrontShow(Order $order)
    {
        if (
            !$order->storeFront ||
            $order->storeFront->store_account_id !== Auth::id()
        ) {
            abort(403);
        }

        $order->load([
            'customer',
            'storeFront',
            'orderItems.item',
        ]);

        return view('receipts.show', compact('order'));
    }
}