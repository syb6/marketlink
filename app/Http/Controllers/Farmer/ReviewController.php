<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['customer', 'product', 'order'])
            ->where('farmer_id', Auth::id())
            ->where('is_visible', true)
            ->latest()
            ->paginate(15);

        $avgRating = Review::where('farmer_id', Auth::id())->avg('rating') ?? 0;

        return view('farmer.reviews.index', compact('reviews', 'avgRating'));
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->farmer_id === Auth::id(), 403);

        $request->validate([
            'farmer_reply' => ['required', 'string', 'max:500'],
        ]);

        $review->update(['farmer_reply' => $request->farmer_reply]);

        return back()->with('success', 'Reply posted successfully!');
    }
}
