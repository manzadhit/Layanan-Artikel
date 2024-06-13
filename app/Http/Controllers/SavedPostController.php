<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Http\Request;
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

        if ($save) {
            $save->delete();
            $status = 'unsaved';
        } else {
            SavedPost::create([
                'post_id' => $postId,
                'user_id' => $userId,
            ]);
            $status = 'saved';
        }

        return response()->json([
                'status' => $status,
            ]);
    }
}
