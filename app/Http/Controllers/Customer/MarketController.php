<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Market::where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('day')) {
            $query->whereJsonContains('operating_days', $request->day);
        }

        $markets = $query->latest()->get();

        return view('customer.markets.index', compact('markets'));
    }

    public function show(Market $market): View
    {
        $market->load('creator');

        // Get farmers who operate at this market
        $farmers = FarmerProfile::where('is_approved', true)
            ->whereJsonContains('market_ids', $market->id)
            ->with(['user.products' => fn ($q) => $q->where('is_available', true)])
            ->get();

        return view('customer.markets.show', compact('market', 'farmers'));
    }
}
