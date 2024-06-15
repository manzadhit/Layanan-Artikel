<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($username, $menu = null)
    {
        $user_login = Auth::user();
        $user = User::where('username', $username)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        // Fungsi untuk mengambil teks pertama dalam tag <p>
        $getFirstTagRegex = function ($content) {
            preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
            if (isset($matches[1])) {
                return strip_tags($matches[1]);
            } else {
                return 'No <p> tag found';
            }
        };

        // Mengambil postingan berdasarkan menu yang dipilih
        switch ($menu) {
            case 'saved':
                $posts = $user->savedPosts()->latest('saved_posts.created_at')->get();
                break;
            case 'liked':
                $posts = $user->likes()->with('post')->latest()->get()->pluck('post');
                break;
            default:
                $posts = $user->posts()->latest()->get();
                break;
        }

        // Mengambil daftar pengguna yang diikuti
        $followedUsers = $user->following;
        $posts->each(function ($post) {
            $post->isSaved = $post->savedByUsers()->where('user_id', auth()->id())->exists();
        });

        return view('profile.index', compact('user_login','user', 'posts', 'menu', 'getFirstTagRegex', 'followedUsers'));
    }

}
