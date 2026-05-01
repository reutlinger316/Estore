<?php

namespace App\Http\Controllers;

use App\Models\CustomerLoyaltyPointWallet;
use App\Models\LoyaltyPointTransaction;
use Illuminate\Support\Facades\Auth;

class CustomerLoyaltyPointController extends Controller
{
    public function index()
    {
        $wallets = CustomerLoyaltyPointWallet::with('merchant')
            ->where('customer_id', Auth::id())
            ->orderBy('owner_type')
            ->orderByDesc('points')
            ->get();

        $transactions = LoyaltyPointTransaction::with(['merchant', 'order'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->limit(50)
            ->get();

        return view('customer.loyalty-points.index', compact('wallets', 'transactions'));
    }
}
