<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\creditcards;

class FundsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cards = $user->creditcards;
        return view('customer.funds.index', compact('user', 'cards'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:creditcards,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $card = creditcards::where('id', $request->card_id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        if ($request->amount > $card->balance) {
            return back()->with('error', 'Not enough balance on this card.');
        }

        $card->balance -= $request->amount;
        $card->save();

        $user = Auth::user();
        $user->balance += $request->amount;
        $user->save();

        return redirect()->route('funds.index')->with('success', 'Funds transferred successfully!');
    }
}

