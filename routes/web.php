<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Route Publik
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');
Route::resource('posts', PostController::class);
Route::resource('projects', ProjectController::class);

// Rute Dashboard bawaan Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rute Profil Pengguna (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route yang membutuhkan Login (Otentikasi)
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

// Route khusus Admin (Otentikasi + Middleware Admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class);
});

require __DIR__.'/auth.php';