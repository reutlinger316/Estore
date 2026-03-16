<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => !$user->status,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }
}
