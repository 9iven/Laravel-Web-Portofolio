@extends('layouts.app')

@section('title', $project->judul . ' - Detail Proyek')

@section('content')
<div class="my-6 max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('projects') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Proyek
        </a>

        @auth
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('post.projects.edit', $project->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                    Edit & Kelola Galeri
                </a>
                <form action="{{ route('post.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg font-medium hover:bg-rose-100 transition-colors cursor-pointer">
                        Hapus Proyek
                    </button>
                </form>
            </div>
        @endauth
    </div>

    <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm space-y-6">
        <!-- Thumbnail Proyek (Pertemuan 10) -->
        @if ($project->thumbnail_url)
            <div>
                <img src="{{ $project->thumbnail_url }}" alt="{{ $project->judul }}"
                     class="w-full max-h-96 object-cover rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            </div>
        @endif

        <div class="flex justify-between items-start gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->judul }}</h1>
            <div class="flex flex-col items-end gap-1 shrink-0">
                <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100 whitespace-nowrap">
                    {{ $project->category->name ?? $project->kategori }}
                </span>
                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $project->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($project->status ?? 'published') }}
                </span>
            </div>
        </div>

        <div class="text-xs text-gray-400 border-b border-gray-100 dark:border-gray-700 pb-3">
            Ditambahkan pada: {{ $project->created_at ? $project->created_at->format('d M Y, H:i') : '-' }} | ID Proyek: #{{ $project->id }}
        </div>

        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Proyek</h3>
            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                {{ $project->deskripsi }}
            </p>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 space-y-2">
            <div>
                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Teknologi yang Digunakan:</span>
                <p class="text-xs text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $project->teknologi }}</p>
            </div>
            @if($project->tautan)
                <div>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Tautan Proyek:</span>
                    <p class="mt-0.5">
                        <a href="{{ $project->tautan }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 hover:underline break-all">
                            {{ $project->tautan }} &nearr;
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Galeri Foto Publik (Pertemuan 10) -->
        @if($project->images->count() > 0)
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Galeri Foto Proyek</h3>
                    <span class="text-xs text-gray-400">{{ $project->images->count() }} Dokumentasi Foto</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($project->images as $img)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col justify-between">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->caption ?: $project->judul }}"
                                 class="w-full h-44 object-cover">
                            @if($img->caption)
                                <div class="p-2.5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">{{ $img->caption }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
