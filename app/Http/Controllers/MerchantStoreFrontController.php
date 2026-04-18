<?php

namespace App\Http\Controllers;

use App\Models\StoreFront;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantStoreFrontController extends Controller
{
    public function index(Request $request)
    {
        $merchantId = auth()->id();
        $search = $request->search;

        $query = StoreFront::where('merchant_id', $merchantId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('branch_name', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%")
                    ->orWhere('delivery_city', 'like', "%$search%");
            });
        }

        $storeFronts = $query->get();

        return view('merchant.storefronts.index', compact('storeFronts', 'search'));
    }

    public function create()
    {
        return view('merchant.storefronts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'inside_delivery_fee' => 'required|numeric|min:0',
            'outside_delivery_fee' => 'required|numeric|min:0',
            'store_account_email' => 'required|email',
            'allow_combos' => 'nullable|boolean',
        ]);

        $storeAccount = User::where('email', $request->store_account_email)
            ->where('role', 'storefront')
            ->first();

        if (!$storeAccount) {
            return back()->withErrors([
                'store_account_email' => 'No storefront account found with that email.',
            ])->withInput();
        }

        StoreFront::create([
            'merchant_id' => Auth::id(),
            'store_account_id' => $storeAccount->id,
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'location' => $request->location,
            'delivery_city' => $request->delivery_city,
            'inside_delivery_fee' => $request->inside_delivery_fee,
            'outside_delivery_fee' => $request->outside_delivery_fee,
            'allow_combos' => $request->boolean('allow_combos'),
            'balance' => 0,
            'status' => true,
            'confirmation_status' => 'pending',
            'confirmed_at' => null,
        ]);

        return redirect('/merchant/storefronts')
            ->with('success', 'Branch created and confirmation sent to storefront account.');
    }

    public function toggleComboSettings(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $storeFront->update([
            'allow_combos' => !$storeFront->allow_combos,
        ]);

        return back()->with('success', 'Combo setting updated successfully.');
    }

    public function destroy(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $storeFront->delete();

        return redirect('/merchant/storefronts')
            ->with('success', 'StoreFront deleted successfully.');
    }

    public function transferBalanceToMerchant(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        if ($storeFront->balance <= 0) {
            return back()->withErrors([
                'balance' => 'This branch has no balance to transfer.',
            ]);
        }

        $merchant = Auth::user();
        $amount = $storeFront->balance;

        $merchant->update([
            'balance' => $merchant->balance + $amount,
        ]);

        $storeFront->update([
            'balance' => 0,
        ]);

        return back()->with('success', 'Branch balance transferred to merchant successfully.');
    }
}