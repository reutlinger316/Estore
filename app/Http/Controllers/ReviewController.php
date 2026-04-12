<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(StoreFront $storeFront)
    {
        return view('customer.reviews.create', compact('storeFront'));
    }

    public function store(Request $request, StoreFront $storeFront)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        // Check if this customer already has a review for this store
        $existingReview = Review::where('store_front_id', $storeFront->id)
            ->where('customer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('customer.reviews.index', $storeFront)
                ->with('error', 'You can only leave one review for this store. Please edit your existing review.');
        }

        Review::create([
            'store_front_id' => $storeFront->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('customer.reviews.index', $storeFront)
            ->with('success', 'Review submitted successfully.');
    }

    public function index(StoreFront $storeFront)
    {
        $reviews = $storeFront->reviews()->with('customer')->latest()->get();
        return view('customer.reviews.index', compact('storeFront', 'reviews'));
    }

    public function edit(Review $review)
    {
        if ($review->customer_id !== Auth::id()) {
            abort(403);
        }
        return view('customer.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        $review->update($request->only('rating', 'title', 'body'));

        return redirect()->route('customer.reviews.index', $review->storeFront)
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->customer_id !== Auth::id()) {
            abort(403);
        }

        $storeFront = $review->storeFront;
        $review->delete();

        return redirect()->route('customer.reviews.index', $storeFront)
            ->with('success', 'Review deleted successfully.');
    }
}
