<?php

namespace App\Http\Controllers;

use App\Models\StoreFront;
use App\Models\User;

class AdminMerchantStoreFrontController extends Controller
{
    public function show(User $merchant)
    {
        if ($merchant->role !== 'merchant') {
            abort(404);
        }

        $storeFronts = StoreFront::with('storeAccount')
            ->where('merchant_id', $merchant->id)
            ->latest()
            ->get();

        return view('admin.merchants.storefronts', compact('merchant', 'storeFronts'));
    }
}