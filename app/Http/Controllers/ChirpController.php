<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Announcement;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response 
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments.user'])
                ->latest()
                ->get()
                ->map(function ($chirp) {
                    return [
                        'id' => $chirp->id,
                        'message' => $chirp->message,
                        'created_at' => $chirp->created_at,
                        'updated_at' => $chirp->updated_at,
                        'user' => $chirp->user,
                        'likes' => $chirp->likes->map(function ($like) {
                            return [
                                'id' => $like->id,
                                'user_id' => $like->user_id,
                            ];
                        }),
                        'comments' => $chirp->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'user' => [
                                    'id' => $comment->user->id,
                                    'name' => $comment->user->name,
                                ],
                            ];
                        }),
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
            'announcements' => Announcement::with('user:id,name')
                ->latest()
                ->get(),
            'isAdmin' => auth()->user()->isAdmin(),
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
            'department' => 'required|string|in:ceit,cas,cte,cot',
        ]);

        $chirp = new Chirp([
            'message' => $validated['message'],
            'department' => $validated['department'],
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chirps', $filename, 'public');
            
            $mediaType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
            
            $chirp->media_url = '/storage/' . $path;
            $chirp->media_type = $mediaType;
        }

        $request->user()->chirps()->save($chirp);

        return redirect(route('chirps.' . $validated['department']));
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
            if ($chirp->media_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $chirp->media_url));
            }

            $file = $request->file('media');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chirps', $filename, 'public');
            
            $mediaType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
            
            $chirp->media_url = '/storage/' . $path;
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
 
        if ($chirp->media_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $chirp->media_url));
        }

        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }

    public function storeComment(Request $request, Chirp $chirp): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $chirp->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return back();
    }

    public function ceit(): Response 
    {
        return Inertia::render('Chirps/Ceit', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments.user'])
                ->where('department', 'ceit')
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
                        'comments' => $chirp->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'user' => [
                                    'id' => $comment->user->id,
                                    'name' => $comment->user->name,
                                ],
                            ];
                        }),
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
        ]);
    }

    public function cas(): Response 
    {
        return Inertia::render('Chirps/Cas', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments.user'])
                ->where('department', 'cas')
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
                        'comments' => $chirp->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'user' => [
                                    'id' => $comment->user->id,
                                    'name' => $comment->user->name,
                                ],
                            ];
                        }),
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
        ]);
    }

    public function cte(): Response 
    {
        return Inertia::render('Chirps/Cte', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments.user'])
                ->where('department', 'cte')
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
                        'comments' => $chirp->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'user' => [
                                    'id' => $comment->user->id,
                                    'name' => $comment->user->name,
                                ],
                            ];
                        }),
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
        ]);
    }

    public function cot(): Response 
    {
        return Inertia::render('Chirps/Cot', [
            'chirps' => Chirp::with(['user:id,name', 'likes', 'comments.user'])
                ->where('department', 'cot')
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
                        'comments' => $chirp->comments->map(function ($comment) {
                            return [
                                'id' => $comment->id,
                                'content' => $comment->content,
                                'created_at' => $comment->created_at,
                                'user' => [
                                    'id' => $comment->user->id,
                                    'name' => $comment->user->name,
                                ],
                            ];
                        }),
                        'media_url' => $chirp->media_url,
                        'media_type' => $chirp->media_type,
                    ];
                }),
        ]);
    }
}
