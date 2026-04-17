<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MerchantItemController extends Controller
{
    public function index(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $items = $storeFront->items;
        return view('merchant.items.index', compact('storeFront', 'items'));
    }

    public function create(StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        return view('merchant.items.create', compact('storeFront'));
    }

    public function store(Request $request, StoreFront $storeFront)
    {
        if ($storeFront->merchant_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'is_pre_order' => 'nullable|boolean',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'store_front_id' => $storeFront->id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->low_stock_threshold,
            'discount' => $request->discount ?? 0,
            'is_pre_order' => $request->has('is_pre_order'),
            'is_listed' => true,
        ]);

        return redirect("/merchant/storefronts/{$storeFront->id}/items")
            ->with('success', 'Item created successfully.');
    }

    public function edit(StoreFront $storeFront, Item $item)
    {
        if ($storeFront->merchant_id !== Auth::id() || $item->store_front_id !== $storeFront->id) {
            abort(403);
        }

        return view('merchant.items.edit', compact('storeFront', 'item'));
    }

    public function update(Request $request, StoreFront $storeFront, Item $item)
    {
        if ($storeFront->merchant_id !== Auth::id() || $item->store_front_id !== $storeFront->id) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'item_name' => $request->item_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->low_stock_threshold,
            'discount' => $request->discount ?? 0,
            'is_pre_order' => $request->has('is_pre_order'),
        ];

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect("/merchant/storefronts/{$storeFront->id}/items")
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(StoreFront $storeFront, Item $item)
    {
        if ($storeFront->merchant_id !== Auth::id() || $item->store_front_id !== $storeFront->id) {
            abort(403);
        }

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect("/merchant/storefronts/{$storeFront->id}/items")
            ->with('success', 'Item deleted successfully.');
    }
}