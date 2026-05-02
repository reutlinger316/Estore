<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreFrontOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = trim($request->input('search', ''));

        $validStatuses = [
            'all',
            'pending',
            'accepted',
            'preparing',
            'ready',
            'delivered',
            'handed_over',
            'cancelled',
        ];

        if (!in_array($status, $validStatuses, true)) {
            $status = 'all';
        }

        $orders = Order::whereHas('storeFront', function ($query) {
            $query->where('store_account_id', Auth::id())
                ->where('confirmation_status', 'accepted');
        })
            ->with(['customer', 'storeFront', 'orderItems.item'])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('id', $search)
                        ->orWhere('receipt_number', 'like', '%' . $search . '%')
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('storefront.orders.index', compact('orders', 'status', 'search'));
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

            $order->load(['orderItems', 'customer', 'storeFront']);

            if ($isFinalSuccessfulStatus && $order->hasPendingPreOrderItems()) {
                abort(422, 'This order contains preorder items that are not fulfilled yet.');
            }

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

    public function fulfillPreOrderItem(OrderItem $orderItem)
    {
        $orderItem->load('order.storeFront');

        if (!$orderItem->order->storeFront || $orderItem->order->storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        if (!$orderItem->is_pre_order) {
            return back()->withErrors([
                'preorder' => 'This item is not a preorder item.',
            ]);
        }

        $orderItem->update([
            'pre_order_status' => 'fulfilled',
        ]);

        return back()->with('success', 'Preorder item marked as fulfilled.');
    }
}