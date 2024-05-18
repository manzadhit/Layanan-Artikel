<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostController::class, "index"]);

Route::get('/post/{post}', [PostController::class, "show"]);

Route::get('/category/{category}', [CategoryController::class, "show"]);

