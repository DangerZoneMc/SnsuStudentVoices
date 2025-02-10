<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Chirp;

class CommentController extends Controller
{
    public function store(Request $request, Chirp $chirp)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'chirp_id' => $chirp->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added');
    }
}
