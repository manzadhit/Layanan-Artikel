<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // Menambahkan comment ke post
    public function addComment(Request $request, $postId)
    {
        // Validasi data
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        // Pastikan post dengan id yang diberikan ada
        $post = Post::findOrFail($postId);

        // Buat instance comment baru
        $comment = new Comment([
            'user_id' => $request->user()->id, // Mengambil user_id dari user yang sedang login
            'post_id' => $postId,
            'content' => $request->comment,
        ]);

        // Simpan comment ke dalam database
        $post->comments()->save($comment);

        // Redirect pengguna kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Comment added!')->with('comment_added', true);
    }

    // Menghapus comment dari post
    public function deleteComment(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Cek apakah user adalah pemilik comment
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized!'], 403);
        }

        // Hapus comment dari database
        $comment->delete();

        // Redirect pengguna kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Comment deleted!');
    }
}

