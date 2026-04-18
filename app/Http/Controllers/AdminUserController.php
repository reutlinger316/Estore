<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::query();

        if (in_array($role, ['customer', 'merchant', 'storefront'])) {
            $query->where('role', $role);
        }

        if ($status === 'banned') {
            $query->where('is_active', false);
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'role', 'status'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function customers()
    {
        $users = User::where('role', 'customer')->latest()->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'customer',
            'status' => null,
        ]);
    }

    public function merchants()
    {
        $users = User::where('role', 'merchant')->latest()->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'merchant',
            'status' => null,
        ]);
    }

    public function storefronts()
    {
        $users = User::where('role', 'storefront')->latest()->get();

        return view('admin.users.index', [
            'users' => $users,
            'role' => 'storefront',
            'status' => null,
        ]);
    }

    public function bannedUsers()
    {
        $users = User::where('is_active', false)->latest()->get();

        return view('admin.users.banned', compact('users'));
    }
}