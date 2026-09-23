<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favoriteFarmers = Favorite::with('favoritable.user')
            ->where('user_id', Auth::id())
            ->where('favoritable_type', FarmerProfile::class)
            ->get()
            ->map(fn ($fav) => $fav->favoritable)
            ->filter();

        $favoriteProducts = Favorite::with(['favoritable.farmer.farmerProfile', 'favoritable.category'])
            ->where('user_id', Auth::id())
            ->where('favoritable_type', Product::class)
            ->get()
            ->map(fn ($fav) => $fav->favoritable)
            ->filter();

        return view('customer.favorites.index', compact('favoriteFarmers', 'favoriteProducts'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'in:farmer,product'],
            'id' => ['required', 'integer'],
        ]);

        $morphType = $request->type === 'farmer' ? FarmerProfile::class : Product::class;
        $morphId = $request->id;

        $existing = Favorite::where('user_id', Auth::id())
            ->where('favoritable_type', $morphType)
            ->where('favoritable_id', $morphId)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['status' => 'removed']);
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'favoritable_type' => $morphType,
            'favoritable_id' => $morphId,
        ]);

        return response()->json(['status' => 'added']);
    }
}
