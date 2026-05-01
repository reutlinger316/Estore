<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPointSetting;
use App\Models\LoyaltyRedeemRule;
use Illuminate\Http\Request;

class AdminLoyaltyPointController extends Controller
{
    public function index()
    {
        $setting = LoyaltyPointSetting::firstOrCreate([
            'owner_type' => 'admin',
            'merchant_id' => null,
        ], [
            'amount_per_point' => 100,
            'is_active' => true,
        ]);

        $rules = LoyaltyRedeemRule::global()
            ->orderBy('discount_percent')
            ->get();

        return view('admin.loyalty-points.index', compact('setting', 'rules'));
    }

    public function updateSetting(Request $request)
    {
        $data = $request->validate([
            'amount_per_point' => 'required|numeric|min:0.01',
            'is_active' => 'nullable|boolean',
        ]);

        LoyaltyPointSetting::updateOrCreate([
            'owner_type' => 'admin',
            'merchant_id' => null,
        ], [
            'amount_per_point' => $data['amount_per_point'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Global loyalty point earning rate updated.');
    }

    public function storeRule(Request $request)
    {
        $data = $request->validate([
            'points_required' => 'required|integer|min:1',
            'discount_percent' => 'required|numeric|min:0.01|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        LoyaltyRedeemRule::create([
            'owner_type' => 'admin',
            'merchant_id' => null,
            'points_required' => $data['points_required'],
            'discount_percent' => $data['discount_percent'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Global redeem rule created.');
    }

    public function updateRule(Request $request, LoyaltyRedeemRule $rule)
    {
        if ($rule->owner_type !== 'admin' || $rule->merchant_id !== null) {
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

        return back()->with('success', 'Global redeem rule updated.');
    }

    public function destroyRule(LoyaltyRedeemRule $rule)
    {
        if ($rule->owner_type !== 'admin' || $rule->merchant_id !== null) {
            abort(403);
        }

        $rule->delete();

        return back()->with('success', 'Global redeem rule deleted.');
    }
}
