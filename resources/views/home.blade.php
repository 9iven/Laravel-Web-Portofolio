@extends('layouts.app')

@section('title', 'Home - Portofolio')

@section('content')
<div class="my-6">
    <h1 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Given Gerald Chandra</h1>
    <p class="text-gray-700 dark:text-gray-300 mb-4">Mahasiswa Teknologi Rekayasa Perangkat Lunak, SV UGM.</p>
    <p class="mb-4 text-sm text-gray-800 dark:text-gray-300 leading-relaxed">
        Selamat datang di website portofolio saya. Website ini dibuat untuk memenuhi tugas praktikum pertemuan 4 sampai akhir Pemrograman Web Lanjutan.
    </p>
    <div class="flex gap-2">
        <a href="{{ route('about') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm transition-colors">Tentang Saya</a>
        <a href="{{ route('projects.index') }}" class="border border-gray-300 dark:border-gray-600 px-3 py-1.5 rounded text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Lihat Proyek</a>
    </div>
</div>
@endsection
