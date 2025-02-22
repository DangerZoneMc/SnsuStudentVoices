<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AnnouncementController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $request->user()->announcements()->create($validated);

        return back();
    }
} 