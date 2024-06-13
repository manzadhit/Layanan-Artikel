<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $postId = $request->post_id;
        $userId = auth()->id();

        $like = Like::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => $userId,
            ]);
            $status = 'liked';
        }

        $likeCount = Like::where('post_id', $postId)->count();

        return response()->json([
            'status' => $status,
            'likeCount' => $likeCount,
        ]);
    }
}
