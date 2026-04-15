<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemReviewController extends Controller
{
    public function create(Item $item)
    {
        return view('customer.item_reviews.create', compact('item'));
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000',
        ]);

        $existingReview = ItemReview::where('item_id', $item->id)
            ->where('customer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('customer.item-reviews.index', $item)
                ->with('error', 'You can only leave one review for this item. Please edit your existing review.');
        }

        ItemReview::create([
            'item_id' => $item->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('customer.item-reviews.index', $item)
            ->with('success', 'Item review submitted successfully.');
    }

    public function index(Item $item)
    {
        $reviews = $item->reviews()->with('customer')->latest()->get();

        return view('customer.item_reviews.index', compact('item', 'reviews'));
    }

    public function edit(ItemReview $itemReview)
    {
        if ($itemReview->customer_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.item_reviews.edit', compact('itemReview'));
    }

    public function update(Request $request, ItemReview $itemReview)
    {
        if ($itemReview->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000',
        ]);

        $itemReview->update($request->only('rating', 'title', 'body'));

        return redirect()->route('customer.item-reviews.index', $itemReview->item)
            ->with('success', 'Item review updated successfully.');
    }

    public function destroy(ItemReview $itemReview)
    {
        if ($itemReview->customer_id !== Auth::id()) {
            abort(403);
        }

        $item = $itemReview->item;
        $itemReview->delete();

        return redirect()->route('customer.item-reviews.index', $item)
            ->with('success', 'Item review deleted successfully.');
    }
}