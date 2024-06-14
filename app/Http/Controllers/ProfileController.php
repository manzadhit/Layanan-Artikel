<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();

        // Panggil latest() pada query builder sebelum mengambil koleksi
        $posts = $user->posts()->latest()->get();
        // Mendefinisikan fungsi getFirstTagRegex sebagai variabel
        $getFirstTagRegex = function ($content) {
            preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
            // Periksa apakah $matches memiliki kunci indeks 1
            if (isset($matches[1])) {
                // Menghapus tag <figure> jika ada di dalam teks
                $text = strip_tags($matches[1]);
                return $text;
            } else {
                // Jika tidak ada tag <p> yang ditemukan, kembalikan pesan atau nilai default
                return 'No <p> tag found';
            }
        };
        $followedUsers = $user->following;

        return view('profile/index', compact('user','posts', 'getFirstTagRegex', 'followedUsers'));
    }
}
