<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceSetting;
use Illuminate\Http\Request;

class AdminMarketplaceSettingController extends Controller
{
    public function edit()
    {
        $setting = MarketplaceSetting::first();

        return view('admin.marketplace.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'registration_fee' => 'required|numeric|min:0',
        ]);

        $setting = MarketplaceSetting::first();
        $setting->update([
            'registration_fee' => $validated['registration_fee'],
        ]);

        return back()->with('success', 'Marketplace registration fee updated successfully.');
    }
}