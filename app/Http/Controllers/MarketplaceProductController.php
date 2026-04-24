<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceProductController extends Controller
{
    public function index()
    {
        $products = MarketplaceProduct::with('seller')
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('customer.marketplace.products.index', compact('products'));
    }

    public function create()
    {
        if (!Auth::user()->hasMarketplaceEligibility()) {
            return redirect()->route('customer.marketplace.account')
            ->with('error', 'Please become eligible first');
        }

        return view('customer.marketplace.products.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasMarketplaceEligibility()) {
            return redirect()->route('customer.marketplace.account')
            ->with('error', 'Please become eligible first');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketplace-products', 'public');
        }

        MarketplaceProduct::create([
            'seller_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()
            ->route('customer.marketplace.products.index')
            ->with('success', 'Marketplace product listed successfully.');
    }

    public function myProducts()
    {
        if (!Auth::user()->hasMarketplaceEligibility()) {
            return redirect()->route('customer.marketplace.account')
            ->with('error', 'Please become eligible first');
        }

        $products = MarketplaceProduct::where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.products.my_products', compact('products'));
    }
}