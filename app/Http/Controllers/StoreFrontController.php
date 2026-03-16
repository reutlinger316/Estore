<?php

namespace App\Http\Controllers;

use App\Models\StoreFront;
use Illuminate\Support\Facades\Auth;

class StoreFrontController extends Controller
{
    public function dashboard()
    {
        $branches = StoreFront::where('store_account_id', Auth::id())
            ->where('confirmation_status', 'accepted')
            ->get()
            ->unique(function ($branch) {
                return $branch->name . '|' . $branch->branch_name . '|' . $branch->location;
            })
            ->values();

        return view('storefront.dashboard', compact('branches'));
    }

    public function branchRequests()
    {
        $requests = StoreFront::where('store_account_id', Auth::id())
            ->where('confirmation_status', 'pending')
            ->get();

        return view('storefront.branch_requests', compact('requests'));
    }

    public function acceptBranch(StoreFront $storeFront)
    {
        if ($storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        $storeFront->update([
            'confirmation_status' => 'accepted',
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Branch assignment accepted.');
    }

    public function rejectBranch(StoreFront $storeFront)
    {
        if ($storeFront->store_account_id !== Auth::id()) {
            abort(403);
        }

        $storeFront->update([
            'confirmation_status' => 'rejected',
            'confirmed_at' => null,
        ]);

        return back()->with('success', 'Branch assignment rejected.');
    }
}
