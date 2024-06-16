<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Http\Request;
use App\Notifications\PostSaved;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SavedPostController extends Controller
{
    // Metode untuk menyimpan postingan
    public function toggleSave(Request $request)
    {
        $postId = $request->post_id;
        $userId = auth()->id();

        $save = SavedPost::where('post_id', $postId)->where('user_id', $userId)->first();
        $post = Post::findOrFail($postId);

        if ($save) {
            $save->delete();
            $status = 'unsaved';
        } else {
            SavedPost::create([
                'post_id' => $postId,
                'user_id' => $userId,
            ]);

            // Kirim notifikasi ke penulis posting
            $post->user->notify(new PostSaved(Auth::user(), $post));

            $status = 'saved';
        }

        return response()->json([
            'status' => $status,
        ]);
    }
}
