<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Rute untuk registrasi
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rute untuk login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rute yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
  Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
  Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store');

  // Rute untuk menampilkan formulir edit post
  Route::get('/posts/{slug}/edit', [PostController::class, 'edit'])->name('posts.edit');

  // Rute untuk memproses data post yang telah diedit
  Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');

  // Rute untuk menghapus post
  Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');

  Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image')->middleware('auth');


  Route::post('/toggle-like', [LikeController::class, 'toggleLike'])->name('toggle.like');

  Route::post('/posts/{postId}/comment', [CommentController::class, 'addComment'])->middleware('auth')->name("comment.store");
  Route::put('/comments/{commentId}', [CommentController::class, 'editComment'])->middleware('auth')->name("comment.update");
  Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment'])->middleware('auth')->name("comment.delete");

  Route::post('/toggle-follow/{userId}', [FollowController::class, 'toggleFollow'])->name('toggle.follow');

  Route::get('profile', [ProfileController::class, 'index'])->name("profile");

  Route::post('/toggle-save', [SavedPostController::class, 'toggleSave'])->name('toggle.save');
});

// Rute untuk menampilkan postingan berdasarkan slug
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// Rute utama (root route) untuk menampilkan semua postingan
Route::get('/', [PostController::class, 'index'])->name('home');

// Rute untuk menampilkan semua postingan dan kategori
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
