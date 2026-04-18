<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CustomerCombo;
use App\Models\Item;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerShopController extends Controller
{
    public function index(Request $request)
    {
        $query = StoreFront::where('confirmation_status', 'accepted');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('branch_name', 'like', '%' . $request->search . '%');
            });
        }

        $shops = $query->get();

        return view('customer.shops.index', compact('shops'));
    }

    public function show(StoreFront $storeFront)
    {
        if ($storeFront->confirmation_status !== 'accepted') {
            abort(404);
        }

        $items = Item::with('reviews')
            ->where('store_front_id', $storeFront->id)
            ->where('is_listed', true)
            ->get();

        $otherBranches = StoreFront::where('merchant_id', $storeFront->merchant_id)
            ->where('id', '!=', $storeFront->id)
            ->where('confirmation_status', 'accepted')
            ->get();

        $cart = Cart::where('customer_id', Auth::id())
            ->with('cartItems.item')
            ->first();

        $cartCount = $cart ? $cart->cartItems->sum('quantity') : 0;

        $combos = CustomerCombo::with('comboItems.item')
            ->where('customer_id', Auth::id())
            ->where('store_front_id', $storeFront->id)
            ->latest()
            ->get();

        return view('customer.shops.show', compact(
            'storeFront',
            'items',
            'otherBranches',
            'cart',
            'cartCount',
            'combos'
        ));
    }
}