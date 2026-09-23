<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Market;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        $profile = $user->farmerProfile ?? FarmerProfile::create([
            'user_id' => $user->id,
            'stall_name' => $user->name."'s Stall",
        ]);

        $markets = Market::where('is_active', true)->get();

        return view('farmer.profile.edit', compact('user', 'profile', 'markets'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $updateType = $request->input('update_type', 'user');

        if ($updateType === 'user') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'],
            ]);

            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->update(['profile_photo' => $request->file('profile_photo')->store('profiles', 'public')]);
            }

            return back()->with('success', 'Account information updated successfully!');
        }

        // Stall update
        $request->validate([
            'stall_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'market_ids' => ['nullable', 'array'],
            'operating_days' => ['nullable', 'array'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'stall_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $profile = $user->farmerProfile;
        $profileData = [
            'stall_name' => $request->stall_name,
            'bio' => $request->bio,
            'contact_person' => $request->contact_person,
            'market_ids' => $request->market_ids ?? [],
            'operating_days' => $request->operating_days ?? [],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('stall_image')) {
            if ($profile->stall_image) {
                Storage::disk('public')->delete($profile->stall_image);
            }
            $profileData['stall_image'] = $request->file('stall_image')->store('stalls', 'public');
        }

        $profile->update($profileData);

        return back()->with('success', 'Stall profile updated successfully!');
    }
}
