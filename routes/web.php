<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/education', fn() => view('education'))->name('education');

// Routes buat Posts (Modul Tutorial) dan Projects (Tugas Portofolio)
Route::resource('posts', PostController::class);
Route::resource('projects', ProjectController::class);

Route::prefix('demo')->group(function () {
    Route::get('/halo-dunia', function () {
        return '<h1>Halo dunia dari Route Laravel!</h1>';
    })->name('demo.halo-dunia');

    Route::get('/halo/{nama?}', function ($nama = 'Tanpa Nama') {
        return '<h1>Halo ' . htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') . '</h1>';
    })->name('demo.halo-nama');

    Route::redirect('/lama', '/demo/baru')->name('demo.redirect');
    Route::get('/baru', function () {
        return '<h3>Berhasil dialihkan dari route /demo/lama menuju /demo/baru</h3>';
    })->name('demo.baru');

    Route::get('/view-halo', function () {
        return view('halo_dunia');
    })->name('demo.view-halo');

    Route::get('/passing-data', function () {
        $nama = 'Given Gerald Chandra';
        $umur = 20;
        $kota = 'Yogyakarta';
        $matakuliah = [
            'Pemrograman Web Lanjutan',
            'Basis Data',
            'Rekayasa Perangkat Lunak',
            'Struktur Data'
        ];
        return view('profil', compact('nama', 'umur', 'kota', 'matakuliah'));
    })->name('demo.passing-data');
});
