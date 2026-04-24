<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function store(Request $request, User $seller)
    {
        if ($seller->id === auth()->id()) {
            return back()->with('error', 'You cannot report yourself.');
        }

        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $alreadyReported = \App\Models\UserReport::where('reporter_id', auth()->id())
            ->where('reported_user_id', $seller->id)
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'You have already reported this seller.');
        }

        \App\Models\UserReport::create([
            'reporter_id' => auth()->id(),
            'reported_user_id' => $seller->id,
            'reason' => $request->reason,
        ]);

        return redirect()->route('customer.marketplace.products.index')
            ->with('success', 'Seller reported successfully.');
    }
    
    public function create(User $seller)
    {
        if ($seller->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot report yourself.');
        }

        $alreadyReported = \App\Models\UserReport::where('reporter_id', auth()->id())
            ->where('reported_user_id', $seller->id)
            ->exists();

        if ($alreadyReported) {
            return redirect()->back()->with('error', 'You have already reported this seller.');
        }

        return view('customer.marketplace.report', compact('seller'));
    }
}