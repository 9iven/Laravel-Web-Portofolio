<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

// Rute Publik Project (Pertemuan 10 & 11)
Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/projects/daftar', fn () => redirect()->route('projects'))->name('projects.index');
Route::get('/projects/{project:slug}', [PortfolioController::class, 'show'])->name('projects.show');

// 2. Rute Dashboard & Profil Pengguna (Wajib Login)
Route::middleware('auth')->group(function () {
    // Akses dashboard utama bawaan Breeze
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/post/profile-saya', [ProfileController::class, 'edit'])->name('profile.saya');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Posts untuk user yang login
    Route::resource('posts', PostController::class);
});

// 3. Rute Admin Dashboard, Projects, Skills & Gallery (Pertemuan 10 - Prefix /post)
Route::prefix('post')->name('post.')->middleware('auth')->group(function () {
    Route::resource('dashboard', DashboardController::class)->only(['index']);
    Route::resource('projects', ProjectController::class);
    Route::resource('skills', SkillController::class);

    // Rute Gallery Project
    Route::post('projects/{project}/gallery', [ProjectGalleryController::class, 'store'])->name('projects.gallery.store');
    Route::put('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'updateCaption'])->name('projects.gallery.caption');
    Route::post('projects/{project}/gallery/{image}/thumbnail', [ProjectGalleryController::class, 'setThumbnail'])->name('projects.gallery.set-thumbnail');
    Route::delete('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.gallery.destroy');
});

require __DIR__.'/auth.php';
