<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cards = $user->creditCards;

        return view('customer.funds.index', compact('user', 'cards'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:credit_cards,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $card = CreditCard::where('id', $request->card_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->amount > $card->balance) {
            return back()->withErrors([
                'amount' => 'Not enough balance on this card.',
            ]);
        }

        $card->balance -= $request->amount;
        $card->save();

        $user = Auth::user();
        $user->balance += $request->amount;
        $user->save();

        return back()->with('success', 'Funds transferred successfully.');
    }
}
