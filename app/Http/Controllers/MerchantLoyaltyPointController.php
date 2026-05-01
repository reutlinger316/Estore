<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPointSetting;
use App\Models\LoyaltyRedeemRule;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantLoyaltyPointController extends Controller
{
    public function index()
    {
        $merchantId = Auth::id();

        $setting = LoyaltyPointSetting::firstOrCreate([
            'owner_type' => 'merchant',
            'merchant_id' => $merchantId,
        ], [
            'amount_per_point' => 100,
            'is_active' => true,
        ]);

        $rules = LoyaltyRedeemRule::forMerchant($merchantId)
            ->orderBy('discount_percent')
            ->get();

        $storefronts = StoreFront::where('merchant_id', $merchantId)->get();

        return view('merchant.loyalty-points.index', compact('setting', 'rules', 'storefronts'));
    }

    public function updateSetting(Request $request)
    {
        $data = $request->validate([
            'amount_per_point' => 'required|numeric|min:0.01',
            'is_active' => 'nullable|boolean',
        ]);

        LoyaltyPointSetting::updateOrCreate([
            'owner_type' => 'merchant',
            'merchant_id' => Auth::id(),
        ], [
            'amount_per_point' => $data['amount_per_point'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Merchant loyalty point earning rate updated.');
    }

    public function storeRule(Request $request)
    {
        $data = $request->validate([
            'points_required' => 'required|integer|min:1',
            'discount_percent' => 'required|numeric|min:0.01|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        LoyaltyRedeemRule::create([
            'owner_type' => 'merchant',
            'merchant_id' => Auth::id(),
            'points_required' => $data['points_required'],
            'discount_percent' => $data['discount_percent'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Merchant redeem rule created.');
    }

    public function updateRule(Request $request, LoyaltyRedeemRule $rule)
    {
        if ($rule->owner_type !== 'merchant' || $rule->merchant_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'points_required' => 'required|integer|min:1',
            'discount_percent' => 'required|numeric|min:0.01|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $rule->update([
            'points_required' => $data['points_required'],
            'discount_percent' => $data['discount_percent'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Merchant redeem rule updated.');
    }

    public function destroyRule(LoyaltyRedeemRule $rule)
    {
        if ($rule->owner_type !== 'merchant' || $rule->merchant_id !== Auth::id()) {
            abort(403);
        }

        $rule->delete();

        return back()->with('success', 'Merchant redeem rule deleted.');
    }
}
