<?php

// app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Category;
use App\Models\PostImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewPostNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\DatabaseNotification;

class PostController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'content' => 'required',
            'categories' => 'required|array',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
        ]);

        $this->processImages($post, $request->content);

        $post->categories()->sync($request->categories);

        // Kirim notifikasi kepada admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewPostNotification($post));
        }

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
                return '';
            }
        };
        // Menambahkan status isSaved untuk setiap post
        $posts->each(function ($post) {
            $post->isSaved = $post->savedByUsers()->where('user_id', auth()->id())->exists();
        });

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

    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }

        $categories = Category::all();

        // Simpan konten asli
        $originalContent = $post->content;

        // Proses konten untuk menampilkan gambar yang benar
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($originalContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (strpos($src, '/images/') === 0) {
                $img->setAttribute('src', asset($src));
            }
        }

        $post->display_content = $dom->saveHTML();

        // Gunakan konten asli untuk ekstraksi path gambar
        $postImages = $this->extractImagePaths($originalContent);

        return view('posts.create', compact('post', 'categories', 'postImages'));
    }

    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
        ]);

        $oldContent = $post->content;

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->slug = Str::slug($request->title);

        // Proses dan simpan gambar baru
        $this->processImagesForUpdate($post, $request->content, $oldContent);

        $post->save();

        $post->categories()->sync($request->categories);

        return redirect()->route('posts.show', $post->slug)->with('success', 'Post updated successfully.');
    }

    private function processImagesForUpdate(Post $post, $newContent, $oldContent)
    {
        $oldImages = $this->extractImagePaths($oldContent);
        $processedImages = [];

        libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML(mb_convert_encoding($newContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (in_array($src, $processedImages)) {
                continue;
            }

            $processedImages[] = $src;

            if (preg_match('/^data:image\/(\w+);base64,/', $src)) {
                $imageName = $this->saveBase64Image($src, $post);
                $this->updateImageAttributes($img, $imageName);
            } elseif (filter_var($src, FILTER_VALIDATE_URL)) {
                $existingImage = basename($src);
                if (in_array($existingImage, $oldImages)) {
                    $this->updateImageAttributes($img, $existingImage);
                } else {
                    $imageName = $this->saveExternalImage($src, $post);
                    $this->updateImageAttributes($img, $imageName);
                }
            } else {
                $this->updateRelativeImagePath($img);
            }
        }

        $post->content = $dom->saveHTML();

        // Remove unused images
        $newImages = $this->extractImagePaths($post->content);
        $unusedImages = array_diff($oldImages, $newImages);

        foreach ($unusedImages as $image) {
            $this->removeImage($image);
        }
    }


    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Hapus semua gambar terkait
        $this->removeAllImages($post->content);

        // Hapus gambar-gambar dari tabel post_images jika ada
        if ($post->images) {
            foreach ($post->images as $image) {
                $this->removeImage($image->image_name);
                $image->delete();
            }
        }

        // Hapus notifikasi terkait dengan post
        DatabaseNotification::where('data->reportable_id', (string) $post->id)
            ->where('data->reportable_type', 'App\\Models\\Post')
            ->delete();

        // Hapus laporan terkait dengan post
        Report::where('reportable_id', $post->id)
            ->where('reportable_type', 'App\\Models\\Post')
            ->delete();

        // Hapus notifikasi dan laporan terkait dengan komentar di post ini
        $comments = $post->comments;
        foreach ($comments as $comment) {
            DatabaseNotification::where('data->reportable_id', (string) $comment->id)
                ->where('data->reportable_type', 'App\\Models\\Comment')
                ->delete();

            Report::where('reportable_id', $comment->id)
                ->where('reportable_type', 'App\\Models\\Comment')
                ->delete();
        }

        // Hapus post
        $post->delete();

        // Cek URL sebelumnya
        $previousUrl = url()->previous();

        // Jika URL sebelumnya adalah /posts/slug, redirect ke /
        if (parse_url($previousUrl, PHP_URL_PATH) == "/posts/$slug") {
            return redirect('/')->with('success', 'Post dan gambar terkait berhasil dihapus.');
        }

        // Jika tidak, redirect ke URL sebelumnya
        return redirect()->back()->with('success', 'Post dan gambar terkait berhasil dihapus.');
    }




    private function removeUnusedImages($oldContent, $newContent)
    {
        $oldImages = $this->extractImagePaths($oldContent);
        $newImages = $this->extractImagePaths($newContent);

        $unusedImages = array_diff($oldImages, $newImages);

        foreach ($unusedImages as $image) {
            $this->removeImage($image);
        }
    }

    private function removeAllImages($content)
    {
        $images = $this->extractImagePaths($content);

        foreach ($images as $image) {
            $this->removeImage($image);
        }
    }

    private function extractImagePaths($content)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $images = [];
        $imgTags = $dom->getElementsByTagName('img');
        foreach ($imgTags as $img) {
            $src = $img->getAttribute('src');
            if (strpos($src, '/images/') === 0) {
                $images[] = str_replace('/images/', '', $src);
            }
        }

        return $images;
    }

    private function removeImage($imageName)
    {
        $path = public_path('images/' . $imageName);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function processImages(Post $post, $content)
    {
        libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        $processedImages = [];

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (in_array($src, $processedImages)) {
                continue;
            }

            $processedImages[] = $src;

            if (preg_match('/^data:image\/(\w+);base64,/', $src)) {
                $imageName = $this->saveBase64Image($src, $post);
                $this->updateImageAttributes($img, $imageName);
            } elseif (filter_var($src, FILTER_VALIDATE_URL)) {
                $imageName = $this->saveExternalImage($src, $post);
                $this->updateImageAttributes($img, $imageName);
            } else {
                $this->updateRelativeImagePath($img);
            }
        }

        $post->content = $dom->saveHTML();
        $post->save();
    }

    private function saveBase64Image($src, $post)
    {
        $imageName = time() . '_' . Str::random(10) . '.png';
        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $src));
        $imagePath = public_path('images/' . $imageName);
        file_put_contents($imagePath, $imageData);

        $this->savePostImage($post, $imageName);

        return $imageName;
    }

    private function saveExternalImage($src, $post)
    {
        // Hapus parameter query string dari URL
        $cleanSrc = strtok($src, '?');

        // Ambil ekstensi file dari URL yang sudah dibersihkan
        $extension = pathinfo($cleanSrc, PATHINFO_EXTENSION);

        // Jika ekstensi tidak ditemukan, gunakan .jpg sebagai default
        if (empty($extension)) {
            $extension = 'jpg';
        }

        $imageName = time() . '_' . Str::random(10) . '.' . $extension;

        try {
            $imageData = file_get_contents($src);

            if ($imageData !== false) {
                $imagePath = public_path('images/' . $imageName);
                file_put_contents($imagePath, $imageData);
                $this->savePostImage($post, $imageName);
            } else {
                // Jika gagal mengambil gambar, gunakan gambar placeholder
                $imageName = 'placeholder.jpg';
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan
            // \Log::error('Failed to save external image: ' . $e->getMessage());
            // $imageName = 'placeholder.jpg';
        }

        return $imageName;
    }

    private function updateImageAttributes($img, $imageName)
    {
        $img->removeAttribute('src');
        $img->setAttribute('src', '/images/' . $imageName);
        $img->setAttribute('class', 'post-content-image');
    }

    private function updateRelativeImagePath($img)
    {
        $currentSrc = $img->getAttribute('src');
        if (strpos($currentSrc, 'http') === 0) {
            $parsedUrl = parse_url($currentSrc);
            $relativePath = $parsedUrl['path'];
            $img->setAttribute('src', $relativePath);
        }
        $img->setAttribute('class', 'post-content-image');
    }

    private function savePostImage($post, $imageName)
    {
        $postImage = new PostImage();
        $postImage->post_id = $post->id;
        $postImage->image_name = $imageName;
        $postImage->save();
    }
}
