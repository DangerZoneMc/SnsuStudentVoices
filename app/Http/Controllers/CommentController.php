<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Chirp $chirp): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $chirp->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back();
    }

    public function update(Request $request, Chirp $chirp, Comment $comment): RedirectResponse
    {
        \Log::info('Update comment request received', [
            'chirp_id' => $chirp->id,
            'comment_id' => $comment->id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        if ($comment->user_id !== auth()->id()) {
            \Log::warning('Unauthorized comment update attempt');
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        try {
            $updated = $comment->update([
                'content' => $validated['content']
            ]);

            \Log::info('Comment updated successfully', [
                'success' => $updated,
                'comment_id' => $comment->id
            ]);

            return back();
        } catch (\Exception $e) {
            \Log::error('Comment update failed: ' . $e->getMessage(), [
                'comment_id' => $comment->id,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'Failed to update comment']);
        }
    }

    public function destroy(Chirp $chirp, Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back();
    }
}
