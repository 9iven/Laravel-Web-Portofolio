<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use Illuminate\Support\Facades\Route;

// 1. Rute Dashboard Utama (URL: /dashboard | Route Name: dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// 2. Rute Admin Post & Projects (Prefix /post)
Route::prefix('post')->name('post.')->middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);

    // Rute Gallery Project
    Route::post('projects/{project}/gallery', [ProjectGalleryController::class, 'store'])->name('projects.gallery.store');
    Route::delete('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.gallery.destroy');
});

// 3. Rute Publik Project
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');