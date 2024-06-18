<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function search(Request $request, $type)
    {
        $query  = $request->input('q');
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
                $results['posts'] = Post::query()
                    ->where('title', 'like', '%' . $query  . '%')
                    ->orWhere('content', 'like', '%' . $query  . '%')
                    ->latest()
                    ->paginate(5);
                break;
            case "authors":
                $results['authors'] = User::query()
                    ->where('name', 'like', '%' . $query  . '%')
                    ->orWhere('email', 'like', '%' . $query  . '%')
                    ->latest()
                    ->paginate(10);
                break;
            case "categories":
                $results['categories'] = Category::query()
                    ->where('name', 'like', '%' . $query  . '%')
                    ->latest()
                    ->paginate(10);
                break;
            default:
                $results['posts'] = Post::query()
                    ->where('title', 'like', '%' . $query  . '%')
                    ->orWhere('content', 'like', '%' . $query  . '%')
                    ->latest()
                    ->paginate(10);
                break;
        }

        return view('search.index', compact('results', 'query', 'type', 'getFirstTagRegex'));
    }
}
