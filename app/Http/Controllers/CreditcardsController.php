<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\creditcards;
use Illuminate\Http\Request;

class CreditcardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = creditcards::where('user_id', Auth::id())->get();
        return view('customer.creditcards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.creditcards.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cardNo' => 'required|digits:16|unique:creditcards',
            'cvv' => 'required|digits_between:3,4',
            'expDate' => 'required|date',
            'balance' => 'required|numeric|min:0'
        ]);
        $validated['user_id']=Auth::id();

        creditcards::create($validated);
        return redirect()->route('creditcards.index')->with('success','Credit Card added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(creditcards $creditcards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(creditcards $creditcards)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, creditcards $creditcards)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post=creditcards::findOrFail($id);
        $post->delete();

        return redirect()->route('creditcards.index')->with('success','Card removed successfully!');
    }

}
