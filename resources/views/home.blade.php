@extends('layouts.app')

@section('title', 'Home - Portofolio')

@section('content')
<div class="my-6">
    <h1 class="text-2xl font-bold mb-2">Given Gerald Chandra</h1>
    <p class="text-gray-700 mb-4">Mahasiswa Teknologi Rekayasa Perangkat Lunak, SV UGM.</p>
    <p class="mb-4 text-sm text-gray-800 leading-relaxed">
        Selamat datang di website portofolio saya. Website ini dibuat untuk memenuhi tugas praktikum pertemuan 4 Pemrograman Web Lanjutan mengenai routing dan Blade view di Laravel.
    </p>
    <div class="flex gap-2">
        <a href="{{ route('about') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm">Tentang Saya</a>
        <a href="{{ route('projects') }}" class="border px-3 py-1.5 rounded text-sm text-gray-700">Lihat Proyek</a>
    </div>
</div>
@endsection
