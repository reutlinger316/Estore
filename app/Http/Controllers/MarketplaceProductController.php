<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceProductController extends Controller
{
    private array $categories = [
        'Electronics' => ['pc', 'computer', 'desktop', 'laptop', 'phone', 'mobile', 'tablet', 'monitor', 'keyboard', 'mouse', 'charger', 'headphone', 'earphone', 'camera', 'printer'],
        'Furniture' => ['chair', 'table', 'desk', 'sofa', 'bed', 'wardrobe', 'cabinet', 'shelf'],
        'Fashion' => ['shirt', 't-shirt', 'pant', 'jeans', 'shoe', 'watch', 'bag', 'dress', 'jacket'],
        'Books' => ['book', 'novel', 'textbook', 'guide', 'magazine', 'comic'],
        'Sports' => ['bat', 'ball', 'racket', 'football', 'cricket', 'jersey', 'gym', 'fitness'],
        'Home & Kitchen' => ['plate', 'spoon', 'cookware', 'oven', 'blender', 'mixer', 'rice cooker', 'pan', 'pot'],
        'Vehicles' => ['cycle', 'bicycle', 'bike', 'motorbike', 'car', 'helmet', 'parts'],
        'Beauty & Personal Care' => ['cream', 'lotion', 'perfume', 'makeup', 'skincare', 'hair', 'trimmer'],
        'Toys & Games' => ['toy', 'game', 'puzzle', 'doll', 'console'],
        'Other' => [],
    ];

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $categoryFilter = $request->input('category');

        $products = MarketplaceProduct::with(['seller.reportsReceived', 'activeTrade'])
            ->where('is_active', true)
            ->when($categoryFilter, function ($query) use ($categoryFilter) {
                $query->where('category', $categoryFilter);
            })
            ->when($search !== '', function ($query) use ($search) {
                $matchingCategories = $this->matchingCategoriesForSearch($search);

                $query->where(function ($q) use ($search, $matchingCategories) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");

                    if (!empty($matchingCategories)) {
                        $q->orWhereIn('category', $matchingCategories);
                    }
                });
            })
            ->latest()
            ->get();

        $categories = array_keys($this->categories);

        return view('customer.marketplace.products.index', compact('products', 'categories', 'search', 'categoryFilter'));
    }

    public function create()
    {
        if (!Auth::user()->hasMarketplaceEligibility()) {
            return redirect()->route('customer.marketplace.account')
            ->with('error', 'Please become eligible first');
        }

        $categories = array_keys($this->categories);

        return view('customer.marketplace.products.create', compact('categories'));
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
            'category' => 'required|string|in:' . implode(',', array_keys($this->categories)),
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
            'category' => $validated['category'],
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

        $products = MarketplaceProduct::with('activeTrade')
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.products.my_products', compact('products'));
    }

    private function matchingCategoriesForSearch(string $search): array
    {
        $search = strtolower(trim($search));

        if ($search === '') {
            return [];
        }

        $matches = [];

        foreach ($this->categories as $category => $keywords) {
            if (str_contains(strtolower($category), $search)) {
                $matches[] = $category;
                continue;
            }

            foreach ($keywords as $keyword) {
                $keyword = strtolower($keyword);

                if (str_contains($keyword, $search) || str_contains($search, $keyword)) {
                    $matches[] = $category;
                    break;
                }
            }
        }

        return array_values(array_unique($matches));
    }
}
