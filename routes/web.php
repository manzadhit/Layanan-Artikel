<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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
  Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});

// Rute untuk menampilkan postingan berdasarkan slug
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// Rute utama (root route) untuk menampilkan semua postingan
Route::get('/', [PostController::class, 'index'])->name('home');

// Rute untuk menampilkan semua postingan dan kategori
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
