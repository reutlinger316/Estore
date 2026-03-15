<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCardController extends Controller
{
    public function index()
    {
        $cards = CreditCard::where('user_id', Auth::id())->get();

        return view('customer.creditcards.index', compact('cards'));
    }

    public function create()
    {
        return view('customer.creditcards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_no' => 'required|digits:16|unique:credit_cards,card_no',
            'cvv' => 'required|digits_between:3,4',
            'exp_date' => 'required|date',
            'balance' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        CreditCard::create($validated);

        return redirect('/customer/creditcards')->with('success', 'Credit card added successfully.');
    }

    public function destroy(CreditCard $creditcard)
    {
        if ($creditcard->user_id !== Auth::id()) {
            abort(403);
        }

        $creditcard->delete();

        return back()->with('success', 'Card removed successfully.');
    }
}
