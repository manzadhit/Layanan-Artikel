<?php

// app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'title' => 'required|max:255',
            'content' => 'required',
            'categories' => 'required|array'
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
        ]);

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

        return view('posts.index', compact('posts', 'categories'));
    }


    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $comments = Comment::query()->latest()->get();;
        return view('posts.show', compact('post', 'comments'));
    }

    public function likePost(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $like = new Like(['user_id' => $request->user()->id]);
        $post->likes()->save($like);

        return response()->json(['message' => 'Post liked!']);
    }
}
