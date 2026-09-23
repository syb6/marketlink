<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:product_categories,name'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Category created!');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function toggle(ProductCategory $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'Category status updated.');
    }
}
