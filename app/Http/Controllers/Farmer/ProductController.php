<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->where('farmer_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('farmer.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::where('is_active', true)->get();

        return view('farmer.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:50'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['boolean'],
            'is_recurring' => ['boolean'],
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'unit', 'stock_quantity']);
        $data['farmer_id'] = Auth::id();
        $data['is_available'] = $request->boolean('is_available', true);
        $data['is_recurring'] = $request->boolean('is_recurring');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('farmer.products.index')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product): View
    {
        abort_unless($product->farmer_id === Auth::id(), 403);

        $categories = ProductCategory::where('is_active', true)->get();

        return view('farmer.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->farmer_id === Auth::id(), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:50'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'unit', 'stock_quantity']);
        $data['is_available'] = $request->boolean('is_available');
        $data['is_recurring'] = $request->boolean('is_recurring');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('farmer.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_unless($product->farmer_id === Auth::id(), 403);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function toggleAvailability(Product $product): RedirectResponse
    {
        abort_unless($product->farmer_id === Auth::id(), 403);

        $product->update(['is_available' => ! $product->is_available]);

        return back()->with('success', 'Product availability updated.');
    }
}
