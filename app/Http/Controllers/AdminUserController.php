<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceTrade;
use App\Models\Order;
use App\Models\Review;
use App\Models\StoreFront;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $status = $request->query('status');
        $search = $request->query('search');

        $users = $this->userQuery($role, $status, $search)
            ->withCount(['reportsReceived', 'reportsMade'])
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'role', 'status', 'search'));
    }

    public function show(User $user)
    {
        $user->loadCount([
            'reportsReceived',
            'reportsMade',
            'marketplaceProducts',
            'marketplaceTradesAsBuyer',
            'marketplaceTradesAsSeller',
            'marketplacePurchases',
            'marketplaceSales',
        ]);

        $activities = collect();

        UserReport::with(['reporter', 'reportedUser'])
            ->where(function ($query) use ($user) {
                $query->where('reporter_id', $user->id)
                    ->orWhere('reported_user_id', $user->id);
            })
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($report) use ($activities, $user) {
                $activities->push([
                    'date' => $report->created_at,
                    'type' => $report->reported_user_id === $user->id ? 'Report received' : 'Report submitted',
                    'description' => $report->reported_user_id === $user->id
                        ? 'Reported by ' . optional($report->reporter)->name . ': ' . $report->reason
                        : 'Reported ' . optional($report->reportedUser)->name . ': ' . $report->reason,
                ]);
            });

        MarketplaceProduct::where('seller_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($product) use ($activities) {
                $activities->push([
                    'date' => $product->created_at,
                    'type' => 'Marketplace product',
                    'description' => 'Listed product: ' . $product->name,
                ]);
            });

        MarketplaceTrade::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($trade) use ($activities, $user) {
                $role = $trade->buyer_id === $user->id ? 'buyer' : 'seller';
                $activities->push([
                    'date' => $trade->created_at,
                    'type' => 'Marketplace trade',
                    'description' => 'Trade as ' . $role . ' with status: ' . ucfirst($trade->status),
                ]);
            });

        MarketplaceOrder::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($order) use ($activities, $user) {
                $role = $order->buyer_id === $user->id ? 'buyer' : 'seller';
                $activities->push([
                    'date' => $order->created_at,
                    'type' => 'Marketplace order',
                    'description' => 'Order as ' . $role . ' worth ' . number_format($order->total_price, 2) . ' Tk',
                ]);
            });

        Order::where('customer_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($order) use ($activities) {
                $activities->push([
                    'date' => $order->created_at,
                    'type' => 'Store order',
                    'description' => 'Placed order #' . $order->id . ' with status: ' . ucfirst($order->status),
                ]);
            });

        Transaction::where('customer_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($transaction) use ($activities) {
                $activities->push([
                    'date' => $transaction->created_at,
                    'type' => 'Transaction',
                    'description' => 'Transaction #' . $transaction->id . ' amount: ' . number_format($transaction->amount, 2) . ' Tk',
                ]);
            });

        StoreFront::where('merchant_id', $user->id)
            ->orWhere('store_account_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($storeFront) use ($activities, $user) {
                $role = $storeFront->merchant_id === $user->id ? 'merchant owner' : 'storefront account';
                $activities->push([
                    'date' => $storeFront->created_at,
                    'type' => 'Storefront',
                    'description' => 'Connected as ' . $role . ' for ' . $storeFront->name,
                ]);
            });

        Review::where('customer_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->each(function ($review) use ($activities) {
                $activities->push([
                    'date' => $review->created_at,
                    'type' => 'Review',
                    'description' => 'Posted storefront review with rating ' . $review->rating . '/5',
                ]);
            });

        $activities = $activities
            ->filter(fn ($activity) => !empty($activity['date']))
            ->sortByDesc('date')
            ->take(50)
            ->values();

        $reportsReceived = UserReport::with('reporter')
            ->where('reported_user_id', $user->id)
            ->latest()
            ->get();

        return view('admin.users.show', compact('user', 'activities', 'reportsReceived'));
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot ban or activate your own admin account.');
        }

        $user->update([
            'status' => !$user->status,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function customers(Request $request)
    {
        $search = $request->query('search');

        $users = $this->userQuery('customer', null, $search)
            ->withCount(['reportsReceived', 'reportsMade'])
            ->latest()
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'customer',
            'status' => null,
            'search' => $search,
        ]);
    }

    public function merchants(Request $request)
    {
        $search = $request->query('search');

        $users = $this->userQuery('merchant', null, $search)
            ->withCount(['reportsReceived', 'reportsMade'])
            ->latest()
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'merchant',
            'status' => null,
            'search' => $search,
        ]);
    }

    public function storefronts(Request $request)
    {
        $search = $request->query('search');

        $users = $this->userQuery('storefront', null, $search)
            ->withCount(['reportsReceived', 'reportsMade'])
            ->latest()
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'storefront',
            'status' => null,
            'search' => $search,
        ]);
    }

    public function bannedUsers(Request $request)
    {
        $search = $request->query('search');

        $users = $this->userQuery(null, 'banned', $search)
            ->withCount(['reportsReceived', 'reportsMade'])
            ->latest()
            ->get();

        return view('admin.users.banned', compact('users', 'search'));
    }

    private function userQuery(?string $role = null, ?string $status = null, ?string $search = null)
    {
        $query = User::query();

        if (in_array($role, ['admin', 'customer', 'merchant', 'storefront'])) {
            $query->where('role', $role);
        }

        if ($status === 'banned') {
            $query->where('status', false);
        } elseif ($status === 'active') {
            $query->where('status', true);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
