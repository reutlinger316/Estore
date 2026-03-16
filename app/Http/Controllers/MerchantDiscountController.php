<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantDiscountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $selectedBranchId = $request->branch;

        $storeFronts = StoreFront::where('merchant_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('branch_name', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $selectedStoreFront = null;

        if ($selectedBranchId) {
            $selectedStoreFront = StoreFront::where('merchant_id', Auth::id())
                ->with('items')
                ->find($selectedBranchId);
        }

        return view('merchant.discounts.index', compact(
            'storeFronts',
            'selectedStoreFront',
            'search',
            'selectedBranchId'
        ));
    }

    public function updateItemDiscount(Request $request, Item $item)
    {
        // SECURITY CHECK
        if ($item->storeFront->merchant_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'discount' => 'required|numeric|min:0|max:100'
        ]);

        $item->update([
            'discount' => $request->discount
        ]);

        return back()->with('success', 'Item discount updated.');
    }

    public function updateBranchDiscount(Request $request, StoreFront $storeFront)
    {
        // SECURITY CHECK
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'discount' => 'required|numeric|min:0|max:100'
        ]);

        $storeFront->items()->update([
            'discount' => $request->discount
        ]);

        return back()->with('success', 'Branch discount updated.');
    }

    public function updateGlobalDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:0|max:100'
        ]);

        Item::whereHas('storeFront', function ($query) {
            $query->where('merchant_id', Auth::id());
        })->update([
            'discount' => $request->discount
        ]);

        return back()->with('success', 'Global discount applied.');
    }
}
