<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('admin')->latest()->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:info,warning,success'],
            'publish_now' => ['boolean'],
        ]);

        Announcement::create([
            'admin_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type,
            'published_at' => $request->boolean('publish_now') ? now() : null,
        ]);

        return back()->with('success', 'Announcement created!');
    }

    public function publish(Announcement $announcement): RedirectResponse
    {
        $announcement->update(['published_at' => now()]);

        return back()->with('success', 'Announcement published!');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }
}
