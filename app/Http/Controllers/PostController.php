<?php

// app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\PostImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim melalui form
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'content' => 'required',
            'categories' => 'required|array',
        ]);

        // Buat postingan baru
        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
        ]);

        // Simpan gambar yang diunggah melalui CKEditor
        $this->processImages($post, $request->content);

        // Sinkronisasi kategori-kategori terkait postingan
        $post->categories()->sync($request->categories);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $posts = Post::query()
            ->filter($request->only(['search', 'category', 'author'])) // Menggunakan scopeFilter() untuk filter
            ->latest()
            ->get();
        $user = Auth::user();

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

        return view('posts.index', compact('posts', 'categories', 'user', 'getFirstTagRegex'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $comments = $post->comments()->latest()->get();

        $isLiked = Like::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->exists();
        $user = Auth::user();

        $isSaved = $post->savedByUsers()->where('user_id', auth()->id())->exists();

        return view('posts.show', compact('post', 'comments', 'isLiked', 'user', 'isSaved'));
    }

    // Metode untuk menampilkan formulir edit post
    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Pastikan hanya pemilik post yang bisa mengedit
        if (auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }

        $categories = Category::all();
        return view('posts.create', compact('post', 'categories'));
    }

    // Metode untuk memproses data post yang telah diperbarui
    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Pastikan hanya pemilik post yang bisa mengupdate
        if (auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }

        // Validasi data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
        ]);

        // Update post
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->slug = Str::slug($request->title);
        $post->save();

        // Simpan gambar yang diunggah melalui CKEditor
        $this->processImages($post, $request->content);

        // Sinkronisasi kategori-kategori terkait postingan
        $post->categories()->sync($request->categories);

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post updated successfully.');
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Hapus post
        $post->delete();

        // Redirect ke halaman atau tindakan setelah menghapus
        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus.');
    }

    private function processImages(Post $post, $content)
    {
        libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Get all <figure> elements
        $figures = $dom->getElementsByTagName('figure');

        foreach ($figures as $figure) {
            // Get parent node of <figure> to insert <figure> after it
            $parentNode = $figure->parentNode;

            // Get all <p> elements inside this figure
            $paragraphs = $figure->getElementsByTagName('p');
            $toMove = [];

            // Move <p> elements outside of <figure>
            foreach ($paragraphs as $p) {
                $toMove[] = $p;
            }

            foreach ($toMove as $p) {
                // Clone the <p> node to keep original in DOM
                $clone = $p->cloneNode(true);
                $parentNode->insertBefore($clone, $figure->nextSibling);
            }

            // Remove <p> elements from inside <figure>
            foreach ($toMove as $p) {
                $figure->removeChild($p);
            }
        }

        // Get all <img> elements
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // Jika src adalah base64
            if (preg_match('/^data:image\/(\w+);base64,/', $src)) {
                $imageName = time() . '_' . Str::random(10) . '.png';
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $src));

                // Simpan gambar ke folder public/images
                $imagePath = public_path('images/' . $imageName);
                file_put_contents($imagePath, $imageData);

                // Update src in content
                $img->removeAttribute('src');
                $img->setAttribute('src', asset('images/' . $imageName));
                $img->setAttribute('class', 'post-content-image');

                // Simpan informasi gambar di tabel post_images
                $postImage = new PostImage();
                $postImage->post_id = $post->id;
                $postImage->image_name = $imageName;
                $postImage->save();
            } else {
                // Tambahkan class ke gambar yang sudah ada
                $img->setAttribute('class', 'post-content-image');
            }
        }

        $post->content = $dom->saveHTML();
        $post->save();
    }
}
