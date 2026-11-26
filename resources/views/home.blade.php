@extends('layouts.app')

@section('title', 'Home - Portofolio')

@section('content')
<div class="my-6 space-y-10">
    <!-- Hero Section -->
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
        <h1 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Given Gerald Chandra</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Mahasiswa Teknologi Rekayasa Perangkat Lunak, SV UGM.</p>
        <p class="mb-4 text-sm text-gray-800 dark:text-gray-300 leading-relaxed">
            Selamat datang di website portofolio saya. Website ini dibuat untuk memenuhi tugas praktikum pertemuan 4 sampai akhir Pemrograman Web Lanjutan.
        </p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('about') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-lg text-sm font-medium transition-colors">Tentang Saya</a>
            <a href="{{ route('projects') }}" class="border border-gray-300 dark:border-gray-600 px-3.5 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Lihat Proyek</a>
            <a href="{{ route('contact') }}" class="border border-gray-300 dark:border-gray-600 px-3.5 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Hubungi Saya</a>
        </div>
    </section>

    <!-- Proyek Unggulan (Cached: portfolio.featured) -->
    <section class="space-y-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Proyek Unggulan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Karya pilihan yang telah dipublikasikan.</p>
            </div>
            <a href="{{ route('projects') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                Semua Proyek &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($featured ?? [] as $project)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        @if($project->thumbnail_url)
                            <img src="{{ $project->thumbnail_url }}" alt="{{ $project->judul }}" class="w-full h-36 object-cover border-b border-gray-100 dark:border-gray-700">
                        @else
                            <div class="w-full h-28 bg-gray-100 dark:bg-gray-700/60 flex items-center justify-center text-xs text-gray-400">
                                Tidak ada thumbnail
                            </div>
                        @endif
                        <div class="p-4 space-y-2">
                            <span class="inline-block text-[11px] px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold">
                                {{ $project->category->name ?? $project->kategori }}
                            </span>
                            <h3 class="font-bold text-sm text-gray-900 dark:text-white leading-snug">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    {{ $project->judul }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ $project->short_description ?: Str::limit($project->deskripsi, 100) }}
                            </p>
                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-1">
                        <a href="{{ route('projects.show', $project) }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                    Belum ada proyek unggulan yang ditandai.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Keahlian (Cached: portfolio.skills) -->
    <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm space-y-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Keahlian Teknis</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Daftar kompetensi utama yang terus dikembangkan.</p>
            </div>
            <a href="{{ route('about') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                Selengkapnya &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($skills ?? [] as $skill)
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $skill->name }}</span>
                        <span class="text-gray-500 dark:text-gray-400 font-mono">{{ $skill->level }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" @style(['width' => min(100, max(0, $skill->level)) . '%'])></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-500 dark:text-gray-400 col-span-full">Belum ada data keahlian.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
