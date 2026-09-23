<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Order $order): View
    {
        abort_unless($order->customer_id === Auth::id(), 403);
        abort_unless($order->status === 'completed', 403, 'You can only review completed orders.');
        abort_if($order->review()->exists(), 403, 'You have already reviewed this order.');

        $order->load(['farmer.farmerProfile', 'items.product']);

        return view('customer.reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === Auth::id(), 403);
        abort_unless($order->status === 'completed', 403);
        abort_if($order->review()->exists(), 403, 'Already reviewed.');

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        Review::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'farmer_id' => $order->farmer_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_visible' => true,
        ]);

        return redirect()->route('customer.orders.show', $order)->with('success', 'Thank you for your review!');
    }
}
