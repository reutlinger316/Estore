<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, StoreFront $storeFront)
    {
        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        Review::create([
            'store_front_id' => $storeFront->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }

    public function index(StoreFront $storeFront)
    {
        $reviews = $storeFront->reviews()->with('customer')->latest()->get();

        return view('customer.reviews.index', compact('storeFront', 'reviews'));
    }

    public function create(StoreFront $storeFront)
    {
    return view('customer.reviews.create', compact('storeFront'));
    }
}
