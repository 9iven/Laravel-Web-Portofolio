<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

Route::get('posts/trash', [PostController::class, 'trash'])->name('posts.trash');
Route::patch('posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
Route::delete('posts/{post}/force', [PostController::class, 'forceDestroy'])->name('posts.force-destroy');

Route::resource('posts', PostController::class);
Route::resource('projects', ProjectController::class);
