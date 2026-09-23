<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['farmer.farmerProfile', 'category'])
            ->where('is_available', true)
            ->whereHas('farmer.farmerProfile', fn ($q) => $q->where('is_approved', true));

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
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

        // User's favorite product IDs
        $favoriteIds = [];
        if (Auth::check()) {
            $favoriteIds = Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Product::class)
                ->pluck('favoritable_id')
                ->toArray();
        }

        return view('customer.products.index', compact('products', 'categories', 'farmers', 'favoriteIds'));
    }

    public function show(Product $product): View
    {
        $product->load(['farmer.farmerProfile', 'category', 'reviews.customer']);

        $relatedProducts = Product::with(['farmer', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        $isFavorited = Auth::check()
            ? Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Product::class)
                ->where('favoritable_id', $product->id)
                ->exists()
            : false;

        return view('customer.products.show', compact('product', 'relatedProducts', 'isFavorited'));
    }
}
