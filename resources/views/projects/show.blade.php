@extends('layouts.app')

@section('title', $project->judul . ' - Detail Proyek')

@section('content')
<div class="my-6 max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('projects') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1 font-medium">
            &larr; Kembali ke Daftar Proyek
        </a>

        @auth
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('post.projects.edit', $project->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded-lg font-medium hover:bg-blue-100 dark:hover:bg-blue-800/40 transition-colors">
                    Edit &amp; Kelola Galeri
                </a>
                <form action="{{ route('post.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-lg font-medium hover:bg-rose-100 dark:hover:bg-rose-800/40 transition-colors cursor-pointer">
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
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">{{ $project->judul }}</h1>
                @if($project->slug)
                    <span class="text-xs font-mono text-gray-400 dark:text-gray-500">/projects/{{ $project->slug }}</span>
                @endif
            </div>
            <div class="flex flex-col items-end gap-1 shrink-0">
                <span class="text-xs px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-100 dark:border-blue-800 whitespace-nowrap">
                    {{ $project->category->name ?? $project->kategori }}
                </span>
                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $project->status === 'published' ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
                    {{ ucfirst($project->status ?? 'published') }}
                </span>
            </div>
        </div>

        <div class="text-xs text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700 pb-3 flex flex-wrap gap-4 items-center">
            @if($project->project_date)
                <span>Tanggal Proyek: <strong class="text-gray-700 dark:text-gray-300">{{ $project->project_date->format('d M Y') }}</strong></span>
            @endif
            <span>Ditambahkan pada: {{ $project->created_at ? $project->created_at->format('d M Y, H:i') : '-' }}</span>
            <span>ID Proyek: #{{ $project->id }}</span>
        </div>

        <!-- Short Description -->
        @if($project->short_description)
            <div class="bg-blue-50/60 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-r-xl">
                <p class="text-xs sm:text-sm text-blue-950 dark:text-blue-200 font-medium leading-relaxed">
                    {{ $project->short_description }}
                </p>
            </div>
        @endif

        <div>
            <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Lengkap</h3>
            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                {{ $project->deskripsi }}
            </p>
        </div>

        <!-- Daftar Teknologi (Pivot project_technology) -->
        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
            <div>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Teknologi yang Digunakan:</span>
                <div class="flex flex-wrap gap-2 mt-1.5">
                    @forelse($project->technologies as $tech)
                        <span class="text-xs px-2.5 py-1 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-medium border border-gray-200 dark:border-gray-600 shadow-2xs">
                            {{ $tech->name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $project->teknologi ?? '-' }}</span>
                    @endforelse
                </div>
            </div>

            <!-- Tautan Proyek & Repository -->
            <div class="flex flex-wrap gap-4 pt-2 border-t border-gray-200 dark:border-gray-600/60 text-xs">
                @if($project->tautan)
                    <div>
                        <span class="font-bold text-gray-600 dark:text-gray-400 block mb-0.5">Tautan Demo / Live:</span>
                        <a href="{{ $project->tautan }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1 font-medium">
                            {{ $project->tautan }} &nearr;
                        </a>
                    </div>
                @endif

                @if($project->repository_url)
                    <div>
                        <span class="font-bold text-gray-600 dark:text-gray-400 block mb-0.5">Repositori Source Code:</span>
                        <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1 font-medium">
                            {{ $project->repository_url }} &nearr;
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Galeri Foto Publik (Pertemuan 10) -->
        @if($project->images->count() > 0)
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Galeri Foto Proyek</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $project->images->count() }} Dokumentasi Foto</span>
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
