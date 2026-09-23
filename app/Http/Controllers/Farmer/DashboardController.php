<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $profile = $user->farmerProfile;

        $totalOrders = Order::where('farmer_id', $user->id)->count();
        $pendingOrders = Order::where('farmer_id', $user->id)->whereIn('status', ['placed'])->count();
        $acceptedOrders = Order::where('farmer_id', $user->id)->where('status', 'accepted')->count();
        $completedOrders = Order::where('farmer_id', $user->id)->where('status', 'completed')->count();

        $totalRevenue = Order::where('farmer_id', $user->id)
            ->whereIn('status', ['completed', 'accepted', 'ready'])
            ->sum('total_amount');

        $recentOrders = Order::with(['customer', 'items.product'])
            ->where('farmer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $totalProducts = Product::where('farmer_id', $user->id)->count();
        $availableProducts = Product::where('farmer_id', $user->id)->where('is_available', true)->count();

        $avgRating = Review::where('farmer_id', $user->id)->avg('rating') ?? 0;
        $reviewCount = Review::where('farmer_id', $user->id)->count();

        $bestSelling = Product::withCount(['orderItems as total_sold' => fn ($q) => $q->selectRaw('sum(quantity)')])
            ->where('farmer_id', $user->id)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('farmer_id', $user->id)
            ->where('is_available', true)
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity')
            ->take(5)
            ->get();

        $averageRating = $avgRating;

        return view('farmer.dashboard', compact(
            'user',
            'profile',
            'totalOrders',
            'pendingOrders',
            'acceptedOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            'totalProducts',
            'availableProducts',
            'avgRating',
            'averageRating',
            'reviewCount',
            'bestSelling',
            'lowStockProducts'
        ));
    }
}
