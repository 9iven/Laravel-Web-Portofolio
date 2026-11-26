<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik
Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/about', [PortfolioController::class, 'about'])->name('about');
Route::get('/education', [PortfolioController::class, 'education'])->name('education');

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
    Route::post('dashboard/cache/clear', [DashboardController::class, 'clearCache'])->name('dashboard.cache.clear');
    Route::post('dashboard/queue/retry-all', [DashboardController::class, 'retryFailedJobs'])->name('dashboard.queue.retry-all');
    Route::post('dashboard/queue/flush', [DashboardController::class, 'flushFailedJobs'])->name('dashboard.queue.flush');

    Route::resource('projects', ProjectController::class);
    Route::resource('categories', CategoryController::class)->except(['create', 'show']);
    Route::resource('skills', SkillController::class);
    Route::resource('experiences', ExperienceController::class)->except(['create', 'show']);
    Route::resource('educations', EducationController::class)->except(['create', 'show']);
    Route::resource('contacts', ContactAdminController::class)->only(['index', 'show', 'destroy']);

    // Rute Gallery Project
    Route::post('projects/{project}/gallery', [ProjectGalleryController::class, 'store'])->name('projects.gallery.store');
    Route::put('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'updateCaption'])->name('projects.gallery.caption');
    Route::post('projects/{project}/gallery/{image}/thumbnail', [ProjectGalleryController::class, 'setThumbnail'])->name('projects.gallery.set-thumbnail');
    Route::delete('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.gallery.destroy');
});

// Rute Publik Form Kontak dengan Rate Limiting (Maks 3 pesan/menit)
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

require __DIR__.'/auth.php';
