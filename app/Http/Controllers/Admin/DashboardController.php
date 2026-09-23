<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalFarmers = User::where('role', 'farmer')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalMarkets = Market::where('is_active', true)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['completed', 'accepted', 'ready'])->sum('total_amount');
        $pendingApprovals = User::where('role', 'farmer')
            ->whereHas('farmerProfile', fn ($q) => $q->where('is_approved', false))
            ->count();

        $recentOrders = Order::with(['customer', 'farmer'])->latest()->take(5)->get();
        $recentFarmers = User::where('role', 'farmer')->with('farmerProfile')->latest()->take(5)->get();

        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topFarmers = User::where('role', 'farmer')
            ->withCount(['ordersAsFarmer as order_count'])
            ->orderByDesc('order_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'totalCustomers',
            'totalMarkets',
            'totalOrders',
            'totalRevenue',
            'pendingApprovals',
            'recentOrders',
            'recentFarmers',
            'ordersByStatus',
            'topFarmers'
        ));
    }
}
