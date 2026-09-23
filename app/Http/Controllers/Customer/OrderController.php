<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['farmer', 'items.product'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->customer_id === Auth::id(), 403);
        $order->load(['farmer.farmerProfile', 'items.product', 'review']);

        return view('customer.orders.show', compact('order'));
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('customer.products.index')->with('error', 'Your cart is empty.');
        }

        // Group by farmer and validate stock
        $productIds = array_keys($cartItems);
        $products = Product::with(['farmer.farmerProfile'])->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cartItems as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product || ! $product->is_available || $product->stock_quantity < $qty) {
                return redirect()->route('customer.cart')->with('error', "Sorry, '{$product?->name}' is no longer available in the requested quantity.");
            }
        }

        // Build $ordersByFarmer for view
        $ordersByFarmer = collect();
        foreach ($cartItems as $productId => $qty) {
            $product = $products->get($productId);
            $farmerId = $product->farmer_id;

            if (! $ordersByFarmer->has($farmerId)) {
                $ordersByFarmer->put($farmerId, [
                    'farmer' => $product->farmer,
                    'items' => [],
                ]);
            }

            $existing = $ordersByFarmer->get($farmerId);
            $existing['items'][] = [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $product->price * $qty,
            ];
            $ordersByFarmer->put($farmerId, $existing);
        }

        $cart = $cartItems;

        return view('customer.orders.checkout', compact('ordersByFarmer', 'cart', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pickup_date' => ['required', 'date', 'after:today'],
            'pickup_slot' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }

        $products = Product::with('farmer')->whereIn('id', array_keys($cartItems))->get()->keyBy('id');

        // Group by farmer
        $byFarmer = [];
        foreach ($cartItems as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product) {
                continue;
            }
            $byFarmer[$product->farmer_id][] = ['product' => $product, 'quantity' => $qty];
        }

        try {
            DB::transaction(function () use ($request, $byFarmer) {
                foreach ($byFarmer as $farmerId => $items) {
                    $total = 0;
                    $orderItems = [];

                    foreach ($items as $item) {
                        $product = Product::lockForUpdate()->findOrFail($item['product']->id);

                        if ($product->stock_quantity < $item['quantity']) {
                            throw new \Exception("Insufficient stock for {$product->name}.");
                        }

                        $product->decrement('stock_quantity', $item['quantity']);
                        $total += $product->price * $item['quantity'];

                        $orderItems[] = [
                            'product_id' => $product->id,
                            'quantity' => $item['quantity'],
                            'unit_price' => $product->price,
                        ];
                    }

                    $order = Order::create([
                        'customer_id' => Auth::id(),
                        'farmer_id' => $farmerId,
                        'pickup_date' => $request->pickup_date,
                        'pickup_slot' => $request->pickup_slot,
                        'status' => 'placed',
                        'total_amount' => $total,
                        'notes' => $request->notes,
                        'cutoff_time' => now()->addDay(),
                    ]);

                    foreach ($orderItems as $item) {
                        $item['order_id'] = $order->id;
                        OrderItem::create($item);
                    }
                }

                // Clear cart after all orders placed
                session()->forget('cart');
            });
        } catch (\Exception $e) {
            return redirect()->route('customer.cart')->with('error', $e->getMessage());
        }

        return redirect()->route('customer.orders.index')->with('success', 'Order(s) placed successfully! Farmers will confirm soon.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === Auth::id(), 403);

        if (! $order->canBeCancelledByCustomer()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        DB::transaction(function () use ($order) {
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
        });

        return back()->with('success', 'Order cancelled and stock has been restored.');
    }
}
