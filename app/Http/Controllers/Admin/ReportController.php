<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', '30');

        $startDate = now()->subDays((int) $period);

        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $totalRevenue = Order::where('created_at', '>=', $startDate)
            ->whereIn('status', ['completed', 'accepted', 'ready'])
            ->sum('total_amount');
        $newFarmers = User::where('role', 'farmer')->where('created_at', '>=', $startDate)->count();
        $newCustomers = User::where('role', 'customer')->where('created_at', '>=', $startDate)->count();

        $ordersByStatus = Order::where('created_at', '>=', $startDate)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topFarmers = User::where('role', 'farmer')
            ->withCount(['ordersAsFarmer as order_count' => fn ($q) => $q->where('created_at', '>=', $startDate)])
            ->withSum(['ordersAsFarmer as total_revenue' => fn ($q) => $q->whereIn('status', ['completed', 'accepted', 'ready'])->where('created_at', '>=', $startDate)], 'total_amount')
            ->orderByDesc('order_count')
            ->take(10)
            ->get();

        $topProducts = Product::withCount(['orderItems as times_ordered' => fn ($q) => $q->whereHas('order', fn ($oq) => $oq->where('created_at', '>=', $startDate))])
            ->orderByDesc('times_ordered')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'period',
            'totalOrders',
            'totalRevenue',
            'newFarmers',
            'newCustomers',
            'ordersByStatus',
            'topFarmers',
            'topProducts'
        ));
    }
}
