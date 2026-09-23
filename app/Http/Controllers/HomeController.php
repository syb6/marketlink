<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $markets = Market::where('is_active', true)->latest()->take(3)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        $featuredProducts = Product::with(['farmer.farmerProfile', 'category'])
            ->where('is_available', true)
            ->whereHas('farmer.farmerProfile', fn ($q) => $q->where('is_approved', true))
            ->latest()
            ->take(8)
            ->get();
        $announcements = Announcement::published()->latest()->take(3)->get();
        $farmerCount = User::where('role', 'farmer')->count();
        $customerCount = User::where('role', 'customer')->count();
        $marketCount = Market::where('is_active', true)->count();

        return view('home', compact(
            'markets',
            'categories',
            'featuredProducts',
            'announcements',
            'farmerCount',
            'customerCount',
            'marketCount'
        ));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }

    // ── Public Product Browsing ──────────────────────────────────────────

    public function products(Request $request): View
    {
        $query = Product::with(['farmer.farmerProfile', 'category'])
            ->where('is_available', true)
            ->whereHas('farmer.farmerProfile', fn ($q) => $q->where('is_approved', true));

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('farmer')) {
            $query->where('farmer_id', $request->farmer);
        }

        $sortBy = $request->get('sort', 'latest');
        match ($sortBy) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::where('is_active', true)->get();
        $farmers = FarmerProfile::where('is_approved', true)->with('user')->get();

        return view('public.products.index', compact('products', 'categories', 'farmers'));
    }

    public function productShow(Product $product): View
    {
        $product->load(['farmer.farmerProfile', 'category', 'reviews.customer']);

        $reviews = Review::where('product_id', $product->id)
            ->where('is_visible', true)
            ->with('customer')
            ->latest()
            ->paginate(10);

        $relatedProducts = Product::with(['farmer.farmerProfile', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('public.products.show', compact('product', 'reviews', 'relatedProducts'));
    }

    // ── Public Market Browsing ───────────────────────────────────────────

    public function markets(Request $request): View
    {
        $query = Market::where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('day')) {
            $query->whereJsonContains('operating_days', $request->day);
        }

        $markets = $query->latest()->get();

        return view('public.markets.index', compact('markets'));
    }

    public function marketShow(Market $market): View
    {
        $market->load('creator');

        $farmers = FarmerProfile::where('is_approved', true)
            ->whereJsonContains('market_ids', $market->id)
            ->with(['user.products' => fn ($q) => $q->where('is_available', true)->with('category')])
            ->get();

        return view('public.markets.show', compact('market', 'farmers'));
    }
}
