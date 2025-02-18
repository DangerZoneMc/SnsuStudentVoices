<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response 
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments'])
                ->latest()
                ->get()
                ->map(function ($chirp) {
                    return [
                        'id' => $chirp->id,
                        'message' => $chirp->message,
                        'created_at' => $chirp->created_at,
                        'updated_at' => $chirp->updated_at,
                        'user' => $chirp->user,
                        'likes' => $chirp->likes,
                        'comments' => $chirp->comments,
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,ogg|max:10240',
        ]);

        $chirp = new Chirp([
            'message' => $validated['message'],
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            // Store file with original name to preserve extension
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chirps', $filename, 'public');
            
            // Determine media type
            $mediaType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
            
            $chirp->media_url = '/storage/' . $path; // Add leading slash
            $chirp->media_type = $mediaType;
        }

        $request->user()->chirps()->save($chirp);

        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,ogg|max:10240',
        ]);

        $chirp->message = $validated['message'];

        if ($request->hasFile('media')) {
            // Delete old media if exists
            if ($chirp->media_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $chirp->media_url));
            }

            $file = $request->file('media');
            // Store file with original name to preserve extension
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chirps', $filename, 'public');
            
            $mediaType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
            
            $chirp->media_url = '/storage/' . $path; // Add leading slash
            $chirp->media_type = $mediaType;
        } elseif ($request->has('media') && $request->input('media') === null) {
            if ($chirp->media_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $chirp->media_url));
                $chirp->media_url = null;
                $chirp->media_type = null;
            }
        }

        $chirp->save();

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);
 
        // Delete associated media file if exists
        if ($chirp->media_url) {
            $path = str_replace('/storage/', '', $chirp->media_url);
            Storage::disk('public')->delete($path);
        }

        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}
