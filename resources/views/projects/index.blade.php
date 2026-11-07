@extends('layouts.app')

@section('title', 'Daftar Proyek - Portofolio')

@section('content')
<div class="my-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Proyek</h1>
            <p class="text-xs text-gray-500">Kelola portofolio, thumbnail, kategori, dan galeri proyek.</p>
        </div>
        <div class="flex gap-2">
            @auth
                <a href="{{ route('post.projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                    + Tambah Proyek
                </a>
            @endauth
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($projects as $project)
            <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:border-gray-300 transition overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Thumbnail -->
                    @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->judul }}"
                             class="w-full h-44 object-cover border-b border-gray-100 dark:border-gray-700">
                    @else
                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-xs">
                            Tidak ada thumbnail
                        </div>
                    @endif

                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <h2 class="font-bold text-base text-gray-900 dark:text-white leading-snug">
                                <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $project->judul }}
                                </a>
                            </h2>
                            <div class="flex flex-col items-end gap-1 shrink-0">
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                                    {{ $project->category->name ?? $project->kategori }}
                                </span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $project->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ ucfirst($project->status ?? 'published') }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                            {{ $project->deskripsi }}
                        </p>

                        <div class="text-xs text-gray-500 space-y-1">
                            <p><strong class="text-gray-700 dark:text-gray-300">Teknologi:</strong> {{ $project->teknologi }}</p>
                            <p><strong class="text-gray-700 dark:text-gray-300">Galeri:</strong> {{ $project->images->count() }} Foto</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0 border-t border-gray-100 dark:border-gray-700 mt-2 flex justify-between items-center text-xs">
                    <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 font-medium hover:underline">
                        Lihat Detail &rarr;
                    </a>

                    @auth
                        <div class="flex items-center gap-3">
                            <a href="{{ route('post.projects.edit', $project->id) }}" class="text-indigo-600 font-medium hover:underline">
                                Edit & Galeri
                            </a>
                            <form action="{{ route('post.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-medium hover:underline cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-white dark:bg-gray-800 border border-dashed border-gray-300 rounded-xl">
                <p class="text-gray-500 text-sm">Belum ada data proyek di database.</p>
                @auth
                    <a href="{{ route('post.projects.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-medium hover:underline">
                        Tambah proyek pertama &rarr;
                    </a>
                @endauth
            </div>
        @endforelse
    </div>
</div>
@endsection
