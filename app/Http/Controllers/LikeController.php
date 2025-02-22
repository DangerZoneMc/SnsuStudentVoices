<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Chirp;

class LikeController extends Controller
{
    public function store(Request $request, Chirp $chirp)
    {
        $like = $chirp->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            $chirp->likes()->create([
                'user_id' => auth()->id(),
            ]);
            $isLiked = true;
        }

        return back()->with([
            'isLiked' => $isLiked,
            'likes' => $chirp->likes()->count(),
        ]);
    }
}
