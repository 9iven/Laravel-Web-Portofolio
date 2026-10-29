<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// 1. Route Publik (Bisa diakses siapa saja)
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

// 2. Route Dashboard & Profil (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Posts untuk semua user yang sudah login
    Route::resource('posts', PostController::class);
});

// 3. Route Khusus Admin (Wajib Login + Email admin@example.com)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class);
});

require __DIR__.'/auth.php';