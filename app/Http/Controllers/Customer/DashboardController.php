<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $recentOrders = Order::with(['farmer', 'items.product'])
            ->where('customer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = Order::where('customer_id', $user->id)->count();
        $pendingOrders = Order::where('customer_id', $user->id)
            ->whereIn('status', ['placed', 'accepted'])
            ->count();
        $completedOrders = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $favoriteCount = $user->favorites()->count();

        $announcements = Announcement::published()->latest()->take(2)->get();

        return view('customer.dashboard', compact(
            'user',
            'recentOrders',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'favoriteCount',
            'announcements'
        ));
    }
}
