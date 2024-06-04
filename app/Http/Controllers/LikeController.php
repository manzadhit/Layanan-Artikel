<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = $request->user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->delete();
            return response()->json(['liked' => false, 'message' => 'Post unliked!']);
        } else {
            $like = new Like(['user_id' => $user->id]);
            $post->likes()->save($like);
            return response()->json(['liked' => true, 'message' => 'Post liked!']);
        }
    }
    
    // Menambahkan like ke post
    public function likePost(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        // Cek apakah user sudah like post ini sebelumnya
        if ($post->likes()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You have already liked this post!'], 400);
        }

        $like = new Like(['user_id' => $request->user()->id]);
        $post->likes()->save($like);

        return response()->json(['message' => 'Post liked!']);
    }

    // Menghapus like dari post
    public function unlikePost(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $like = $post->likes()->where('user_id', $request->user()->id)->first();

        if (!$like) {
            return response()->json(['message' => 'Like not found!'], 404);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked!']);
    }
}
