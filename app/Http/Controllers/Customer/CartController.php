<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $products = [];
        $total = 0;

        if (! empty($cart)) {
            $products = Product::with(['farmer.farmerProfile', 'category'])
                ->whereIn('id', array_keys($cart))
                ->get()
                ->keyBy('id');

            foreach ($cart as $productId => $qty) {
                $product = $products->get($productId);
                if ($product) {
                    $total += $product->price * $qty;
                }
            }
        }

        return view('customer.cart.index', compact('cart', 'products', 'total'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->is_available || $product->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Product not available in requested quantity.'], 422);
        }

        $cart = session('cart', []);
        $newQty = ($cart[$product->id] ?? 0) + $request->quantity;

        if ($newQty > $product->stock_quantity) {
            return response()->json(['error' => 'Cannot add more than available stock.'], 422);
        }

        $cart[$product->id] = $newQty;
        session(['cart' => $cart]);

        $cartCount = array_sum($cart);

        return response()->json([
            'success' => true,
            'message' => "{$product->name} added to cart!",
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session('cart', []);

        if ($request->quantity <= 0) {
            unset($cart[$request->product_id]);
        } else {
            $product = Product::findOrFail($request->product_id);
            if ($request->quantity > $product->stock_quantity) {
                return response()->json(['error' => 'Exceeds available stock.'], 422);
            }
            $cart[$request->product_id] = $request->quantity;
        }

        session(['cart' => $cart]);

        $lineProduct = Product::find($request->product_id);
        $qty = (int) ($cart[$request->product_id] ?? 0);
        $subtotal = $lineProduct && $qty > 0 ? $lineProduct->price * $qty : 0;

        // #region agent log
        file_put_contents(base_path('debug-22f056.log'), json_encode(['sessionId' => '22f056', 'hypothesisId' => 'B', 'location' => 'CartController.php:update', 'message' => 'cart update response payload', 'data' => ['product_id' => $request->product_id, 'quantity' => $request->quantity, 'qty_in_cart' => $qty, 'subtotal' => $subtotal, 'cart_count' => array_sum($cart)], 'timestamp' => (int) (microtime(true) * 1000)])."\n", FILE_APPEND);
        // #endregion

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
            'subtotal' => $subtotal,
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => array_sum($cart)]);
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared.');
    }
}
