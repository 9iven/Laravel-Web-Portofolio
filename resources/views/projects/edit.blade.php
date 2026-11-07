@extends('layouts.app')

@section('title', 'Edit Proyek - ' . $project->judul)

@section('content')
<div class="my-6 max-w-5xl mx-auto space-y-8">
    <div class="mb-4">
        <a href="{{ route('post.projects.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Proyek
        </a>
    </div>

    <!-- Form Utama Edit Data Proyek -->
    <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Edit Proyek</h1>
        <p class="text-xs text-gray-500 mb-6">Perbarui rincian data proyek, kategori, status, dan thumbnail.</p>

        <form action="{{ route('post.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Form fields partial --}}
            @include('projects.partials._form')

            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Perbarui Proyek
                </button>
                <a href="{{ route('post.projects.index') }}" class="text-gray-500 text-sm hover:text-gray-700 cursor-pointer">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Kelola Galeri Foto Proyek (Pertemuan 10) -->
    <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm space-y-6">
        <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Galeri Foto Proyek</h2>
                <p class="text-xs text-gray-500">Maksimal 10 foto per proyek. Dimensi minimal lebar 800 px, maks. 4MB per berkas.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $project->images->count() >= 10 ? 'bg-rose-100 text-rose-700' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                {{ $project->images->count() }} / 10 Foto
            </span>
        </div>

        @if($project->images->count() < 10)
            <form action="{{ route('post.projects.gallery.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50/70 dark:bg-gray-800/60 p-4 rounded-xl space-y-3 border border-gray-200 dark:border-gray-700">
                @csrf
                <div>
                    <label for="images" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Unggah Foto Galeri
                    </label>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-2">Min. lebar 800 px, maks. 4MB per berkas, maksimal 10 foto per proyek. Anda dapat memilih beberapa foto sekaligus.</p>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" required
                           class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none p-1.5">
                    @error('images')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-colors shadow-sm">
                    + Unggah ke Galeri
                </button>
            </form>
        @else
            <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-lg">
                Proyek ini telah mencapai batas maksimal 10 foto galeri. Hapus foto lama jika ingin menambah yang baru.
            </div>
        @endif

        <!-- Daftar Foto Galeri yang Telah Diunggah -->
        <div class="space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Daftar Foto Galeri</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($project->images as $img)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="relative">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->caption ?: $project->judul }}"
                                     class="w-full h-40 object-cover border-b border-gray-100 dark:border-gray-700">

                                @if($project->thumbnail === $img->image)
                                    <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-xs">
                                        ★ Thumbnail Utama
                                    </span>
                                @endif
                            </div>

                            <!-- Edit Caption -->
                            <div class="p-3 pb-0">
                                <form action="{{ route('post.projects.gallery.caption', [$project->id, $img->id]) }}" method="POST" class="space-y-1">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex gap-1.5">
                                        <input type="text" name="caption" value="{{ old('caption', $img->caption) }}" placeholder="Keterangan foto..."
                                               class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white flex-1 px-2.5 py-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        <button type="submit" class="border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-2.5 py-1.5 rounded-lg font-medium transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Baris Aksi Bawah -->
                        <div class="p-3 pt-2.5 flex justify-between items-center text-xs border-t border-gray-100 dark:border-gray-700 mt-2.5">
                            @if($project->thumbnail !== $img->image)
                                <form action="{{ route('post.projects.gallery.set-thumbnail', [$project->id, $img->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:underline text-xs font-medium cursor-pointer">
                                        Jadikan Thumbnail
                                    </button>
                                </form>
                            @else
                                <span class="text-emerald-600 dark:text-emerald-400 font-medium text-[11px]">Thumbnail Aktif</span>
                            @endif

                            <form action="{{ route('post.projects.gallery.destroy', [$project->id, $img->id]) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline text-xs font-medium cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 col-span-1 sm:col-span-2 lg:col-span-3 text-center py-6 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                        Belum ada foto di galeri proyek ini.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
