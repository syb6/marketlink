<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['customer', 'farmer', 'product'])->latest()->paginate(15);
        $flaggedProducts = Product::with(['farmer', 'category'])->latest()->paginate(15);

        return view('admin.moderation.index', compact('reviews', 'flaggedProducts'));
    }

    public function hideReview(Review $review): RedirectResponse
    {
        $review->update(['is_visible' => false]);

        return back()->with('success', 'Review hidden from public view.');
    }

    public function restoreReview(Review $review): RedirectResponse
    {
        $review->update(['is_visible' => true]);

        return back()->with('success', 'Review restored.');
    }

    public function removeProduct(Product $product): RedirectResponse
    {
        $product->update(['is_available' => false]);

        return back()->with('success', 'Product listing removed from public view.');
    }
}
