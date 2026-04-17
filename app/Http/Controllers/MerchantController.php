<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function dashboard()
    {
        $lowStockItems = Item::with('storeFront')
            ->whereHas('storeFront', function ($query) {
                $query->where('merchant_id', Auth::id());
            })
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->get();

        return view('merchant.dashboard', compact('lowStockItems'));
    }
}