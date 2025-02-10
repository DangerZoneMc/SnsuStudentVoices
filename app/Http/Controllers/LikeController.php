<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Chirp;

class LikeController extends Controller
{
    public function store(Request $request, Chirp $chirp)
    {
        $user = auth()->user();

        // Check if user already liked the chirp
        $existingLike = Like::where('user_id', $user->id)
                            ->where('chirp_id', $chirp->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete(); // Unlike if already liked
            return back()->with('success', 'Like removed');
        }

        Like::create([
            'user_id' => $user->id,
            'chirp_id' => $chirp->id,
        ]);

        return back()->with('success', 'Liked successfully');
    }
}
