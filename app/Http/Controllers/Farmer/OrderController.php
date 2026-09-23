<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'items.product'])
            ->where('farmer_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('farmer.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->farmer_id === Auth::id(), 403);
        $order->load(['customer', 'items.product', 'review.customer']);

        return view('farmer.orders.show', compact('order'));
    }

    public function accept(Order $order): RedirectResponse
    {
        abort_unless($order->farmer_id === Auth::id(), 403);
        abort_unless($order->status === 'placed', 403);

        $order->update(['status' => 'accepted']);

        return back()->with('success', 'Order accepted! The customer has been notified.');
    }

    public function decline(Order $order): RedirectResponse
    {
        abort_unless($order->farmer_id === Auth::id(), 403);
        abort_unless($order->status === 'placed', 403);

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $order->update(['status' => 'declined']);

        return back()->with('success', 'Order declined and stock restored.');
    }

    public function markReady(Order $order): RedirectResponse
    {
        abort_unless($order->farmer_id === Auth::id(), 403);
        abort_unless($order->status === 'accepted', 403);

        $order->update(['status' => 'ready']);

        return back()->with('success', 'Order marked as ready for pickup.');
    }

    public function complete(Order $order): RedirectResponse
    {
        abort_unless($order->farmer_id === Auth::id(), 403);
        abort_unless($order->status === 'ready', 403);

        $order->update(['status' => 'completed']);

        return back()->with('success', 'Order marked as completed!');
    }
}
