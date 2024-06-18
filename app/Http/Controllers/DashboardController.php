<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index(Request $request, $type)
    {
        $results = [];
        $getFirstTagRegex = function ($content) {
            preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
            // Periksa apakah $matches memiliki kunci indeks 1
            if (isset($matches[1])) {
                // Menghapus tag <figure> jika ada di dalam teks
                $text = strip_tags($matches[1]);
                return $text;
            } else {
                // Jika tidak ada tag <p> yang ditemukan, kembalikan pesan atau nilai default
                return '';
            }
        };
        switch ($type) {
            case "posts":
                $results['posts'] = Post::query()->latest()->get();
                break;
            case "users":
                $results['users'] = User::query()->latest()->get();
                break;
            case "categories":
                $results['categories'] = Category::query()->latest()->get();
                break;
            default:
                $results['posts'] = Post::query()->latest()->get();
                break;
        }

        return view('admin.dashboard', compact('results', 'type', 'getFirstTagRegex'));
    }
}
