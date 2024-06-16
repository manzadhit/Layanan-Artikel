<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\PostLiked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        try {
            $postId = $request->post_id;
            $userId = Auth::id();

            $post = Post::findOrFail($postId);

            $result = DB::transaction(function () use ($post, $userId) {
                $like = $post->likes()->where('user_id', $userId)->first();

                if ($like) {
                    $like->delete();
                    $status = 'unliked';
                } else {
                    $post->likes()->create(['user_id' => $userId]);
                    if ($post->user_id !== $userId) {
                        $post->user->notify(new PostLiked(Auth::user(), $post));
                    }
                    $status = 'liked';
                }

                $likeCount = $post->likes()->count();

                return [
                    'status' => $status,
                    'likeCount' => $likeCount,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
