<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceAccount;
use App\Models\MarketplaceSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketplaceAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $setting = MarketplaceSetting::first();
        $account = $user->marketplaceAccount;

        return view('customer.marketplace.account', compact('user', 'setting', 'account'));
    }

    public function payAndActivate()
    {
        $user = Auth::user();
        $setting = MarketplaceSetting::first();

        if ($user->marketplaceAccount && $user->marketplaceAccount->is_eligible) {
            return back()->with('success', 'Marketplace account is already active.');
        }

        if ($user->balance < $setting->registration_fee) {
            return back()->with('error', 'Insufficient balance to activate marketplace account.');
        }

        DB::transaction(function () use ($user, $setting) {
            $user->decrement('balance', $setting->registration_fee);

            MarketplaceAccount::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'paid_fee' => $setting->registration_fee,
                    'is_eligible' => true,
                    'paid_at' => now(),
                ]
            );
        });

        return redirect()
            ->route('customer.marketplace.account')
            ->with('success', 'Marketplace eligibility activated successfully.');
    }
}