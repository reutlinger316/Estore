<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreFrontOrderController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('storeFront', function ($query) {
            $query->where('store_account_id', Auth::id())
                ->where('confirmation_status', 'accepted');
        })
            ->with(['customer', 'storeFront', 'orderItems.item'])
            ->latest()
            ->get();

        return view('storefront.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $allowedStatuses = $order->type === 'takeaway'
            ? 'pending,accepted,handed_over,cancelled'
            : 'pending,accepted,preparing,ready,delivered,cancelled';

        $request->validate([
            'status' => 'required|in:' . $allowedStatuses,
        ]);

        if (!$order->storeFront || $order->storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        if ($order->storeFront->confirmation_status !== 'accepted') {
            abort(403);
        }

        DB::transaction(function () use ($request, $order) {
            $newStatus = $request->status;

            $isFinalSuccessfulStatus =
                ($order->type === 'takeaway' && $newStatus === 'handed_over') ||
                ($order->type === 'delivery' && $newStatus === 'delivered');

            if ($isFinalSuccessfulStatus && $order->paid_at === null) {
                $customer = $order->customer;

                if ($customer->balance < $order->total_amount) {
                    abort(422, 'Customer does not have enough balance to complete this order.');
                }

                $customer->update([
                    'balance' => $customer->balance - $order->total_amount,
                ]);

                $storeFront = $order->storeFront;
                $storeFront->update([
                    'balance' => $storeFront->balance + $order->total_amount,
                ]);

                Transaction::create([
                    'order_id' => $order->id,
                    'customer_id' => $customer->id,
                    'amount' => $order->total_amount,
                    'type' => 'debit',
                    'description' => $order->type === 'takeaway'
                        ? 'Payment deducted when takeaway order was handed over.'
                        : 'Payment deducted when delivery order was completed.',
                ]);

                $order->paid_at = now();
            }

            $order->status = $newStatus;
            $order->save();
        });

        return back()->with('success', 'Order status updated successfully.');
    }
}