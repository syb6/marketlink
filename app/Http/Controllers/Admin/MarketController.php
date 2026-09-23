<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MarketController extends Controller
{
    public function index(): View
    {
        $markets = Market::withCount('creator')->latest()->paginate(15);

        return view('admin.markets.index', compact('markets'));
    }

    public function create(): View
    {
        return view('admin.markets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'operating_days' => ['nullable', 'array'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'address', 'city', 'latitude', 'longitude', 'operating_days', 'opening_time', 'closing_time', 'description']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('markets', 'public');
        }

        Market::create($data);

        return redirect()->route('admin.markets.index')->with('success', 'Market created successfully!');
    }

    public function edit(Market $market): View
    {
        return view('admin.markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'operating_days' => ['nullable', 'array'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'address', 'city', 'latitude', 'longitude', 'operating_days', 'opening_time', 'closing_time', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($market->image) {
                Storage::disk('public')->delete($market->image);
            }
            $data['image'] = $request->file('image')->store('markets', 'public');
        }

        $market->update($data);

        return redirect()->route('admin.markets.index')->with('success', 'Market updated successfully!');
    }

    public function destroy(Market $market): RedirectResponse
    {
        if ($market->image) {
            Storage::disk('public')->delete($market->image);
        }
        $market->delete();

        return back()->with('success', 'Market deleted.');
    }
}
