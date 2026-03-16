<?php

namespace App\Http\Controllers;

use App\Models\Item;

class CustomerItemController extends Controller
{
    public function index()
    {
        $items = Item::where('is_listed', true)
            ->whereHas('storeFront', function ($query) {
                $query->where('confirmation_status', 'accepted');
            })
            ->get();

        return view('customer.items.index', compact('items'));
    }
}
