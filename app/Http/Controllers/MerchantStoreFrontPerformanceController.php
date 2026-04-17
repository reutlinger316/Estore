<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantStoreFrontPerformanceController extends Controller
{
    public function index()
    {
        $merchantId = Auth::id();

        $storeFronts = StoreFront::where('merchant_id', $merchantId)
            ->withCount([
                'orders',
                'reviews',
            ])
            ->withAvg('reviews', 'rating')
            ->withSum([
                'orders as orders_sum_total_amount' => function ($query) {
                    $query->whereNotNull('paid_at');
                }
            ], 'total_amount')
            ->get();

        return view('merchant.performance.index', compact('storeFronts'));
    }

    public function show(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $summary = [
            'total_orders' => Order::where('store_front_id', $storeFront->id)->count(),
            'total_sales' => Order::where('store_front_id', $storeFront->id)
                ->whereNotNull('paid_at')
                ->sum('total_amount'),
            'average_rating' => Review::where('store_front_id', $storeFront->id)->avg('rating'),
            'total_reviews' => Review::where('store_front_id', $storeFront->id)->count(),
        ];

        return view('merchant.performance.show', compact(
            'storeFront',
            'summary'
        ));
    }

    public function ratings(Request $request, StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $selectedStar = $request->query('star');

        $ratingsQuery = Review::where('store_front_id', $storeFront->id)
            ->with('customer')
            ->latest();

        if (!is_null($selectedStar) && in_array((int) $selectedStar, [1, 2, 3, 4, 5], true)) {
            $ratingsQuery->where('rating', (int) $selectedStar);
        }

        $ratings = $ratingsQuery->get();

        return view('merchant.performance.ratings', compact(
            'storeFront',
            'ratings',
            'selectedStar'
        ));
    }

    public function orders(Request $request, StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $selectedStatus = $request->query('status');

        $ordersQuery = Order::where('store_front_id', $storeFront->id)
            ->with(['customer', 'orderItems.item'])
            ->latest();

        if (!empty($selectedStatus)) {
            $ordersQuery->where('status', $selectedStatus);
        }

        $orders = $ordersQuery->get();

        return view('merchant.performance.orders', compact(
            'storeFront',
            'orders',
            'selectedStatus'
        ));
    }
}