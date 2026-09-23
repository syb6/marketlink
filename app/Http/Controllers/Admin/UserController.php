<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function farmers(Request $request): View
    {
        $query = User::where('role', 'farmer')->with('farmerProfile');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereHas('farmerProfile', fn ($q) => $q->where('is_approved', false));
            } elseif ($request->status === 'approved') {
                $query->whereHas('farmerProfile', fn ($q) => $q->where('is_approved', true));
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false);
            }
        }

        $farmers = $query->latest()->paginate(15);

        return view('admin.users.farmers', compact('farmers'));
    }

    public function customers(Request $request): View
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(15);

        return view('admin.users.customers', compact('customers'));
    }

    public function approveFarmer(User $user): RedirectResponse
    {
        abort_unless($user->role === 'farmer', 403);

        $user->farmerProfile?->update(['is_approved' => true]);

        return back()->with('success', "{$user->name}'s farmer account has been approved.");
    }

    public function suspendFarmer(User $user): RedirectResponse
    {
        abort_unless($user->role === 'farmer', 403);

        $user->update(['is_active' => false]);
        $user->farmerProfile?->update(['suspended_at' => now()]);

        return back()->with('success', "{$user->name} has been suspended.");
    }

    public function toggleCustomer(User $user): RedirectResponse
    {
        abort_unless($user->role === 'customer', 403);

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "{$user->name} has been {$status}.");
    }
}
