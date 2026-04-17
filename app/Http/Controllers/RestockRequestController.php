<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RestockRequest;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestockRequestController extends Controller
{
    public function index()
    {
        $requests = RestockRequest::with(['item', 'storeFront', 'requester'])
            ->whereHas('storeFront', function ($query) {
                $query->where('merchant_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('merchant.restock_requests.index', compact('requests'));
    }

    public function create(StoreFront $storeFront)
    {
        if ($storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        $items = $storeFront->items()->orderBy('item_name')->get();

        return view('storefront.restock_requests.create', compact('storeFront', 'items'));
    }

    public function store(Request $request, StoreFront $storeFront)
    {
        if ($storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'requested_quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        $item = Item::where('id', $validated['item_id'])
            ->where('store_front_id', $storeFront->id)
            ->firstOrFail();

        RestockRequest::create([
            'store_front_id' => $storeFront->id,
            'item_id' => $item->id,
            'requested_by' => Auth::id(),
            'requested_quantity' => $validated['requested_quantity'],
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('storefront.dashboard')
            ->with('success', 'Restock request sent to merchant successfully.');
    }

public function updateStatus(Request $request, RestockRequest $restockRequest)
{
    if ($restockRequest->storeFront->merchant_id !== Auth::id()) {
        abort(403);
    }

    // Prevent re-processing
    if ($restockRequest->status !== 'pending') {
        return back()->with('error', 'This request has already been processed.');
    }

    $validated = $request->validate([
        'status' => 'required|in:approved,rejected',
    ]);

    if ($validated['status'] === 'approved') {
        $item = $restockRequest->item;

        $item->increment('stock_quantity', $restockRequest->requested_quantity);
    }

    $restockRequest->update([
        'status' => $validated['status'],
        'reviewed_at' => now(),
    ]);

    return back()->with('success', 'Restock request processed successfully.');
}
}