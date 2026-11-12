@extends('layouts.app')

@section('title', 'Daftar Proyek - Portofolio')

@section('content')
<div class="my-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ Request::routeIs('post.projects.*') ? 'Kelola Proyek (Admin)' : 'Daftar Proyek' }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Pencarian, filter kategori & teknologi, serta pengurutan data portofolio.
            </p>
        </div>
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('post.projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm flex items-center gap-1.5">
                    <span>+</span> Tambah Proyek
                </a>
            @endauth
        </div>
    </div>

    <!-- Toolbar Form Pencarian, Filter & Sorting (Pertemuan 11 & Latihan) -->
    <div class="bg-gray-50 dark:bg-gray-800/80 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-3">
        <form method="GET" action="{{ Request::routeIs('post.projects.*') ? route('post.projects.index') : route('projects') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- 1. Input Pencarian -->
            <div class="lg:col-span-2 relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul, ringkasan, atau deskripsi..."
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 pl-3 pr-8 py-2">
                @if(request('search'))
                    <span class="absolute right-2.5 top-2 text-xs text-gray-400">&times;</span>
                @endif
            </div>

            <!-- 2. Filter Kategori -->
            <div>
                <select name="category" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 py-2">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->slug }}" @selected(request('category') === $c->slug)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Filter Teknologi (Latihan) -->
            <div>
                <select name="technology" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 py-2">
                    <option value="">Semua Teknologi</option>
                    @foreach ($technologies as $t)
                        <option value="{{ $t->slug }}" @selected(request('technology') === $t->slug)>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Sorting (Latihan) -->
            <div>
                <select name="sort" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 py-2">
                    <option value="">Urutkan: Tanggal Terbaru</option>
                    <option value="terlama" @selected(request('sort') === 'terlama')>Tanggal Terlama</option>
                    <option value="project_date" @selected(request('sort') === 'project_date')>Berdasarkan Project Date</option>
                    <option value="az" @selected(request('sort') === 'az')>Nama (A &rarr; Z)</option>
                    <option value="za" @selected(request('sort') === 'za')>Nama (Z &rarr; A)</option>
                </select>
            </div>

            <!-- Filter Status Tambahan untuk Admin (Latihan) -->
            @if(Request::routeIs('post.projects.*') || auth()->check())
                <div class="sm:col-span-2 lg:col-span-1">
                    <select name="status" class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 py-2">
                        <option value="">Semua Status</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-2 {{ (Request::routeIs('post.projects.*') || auth()->check()) ? 'sm:col-span-2 lg:col-span-4' : 'sm:col-span-2 lg:col-span-5' }} justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-sm cursor-pointer">
                    Cari &amp; Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'technology', 'sort', 'status']))
                    <a href="{{ Request::routeIs('post.projects.*') ? route('post.projects.index') : route('projects') }}"
                       class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold px-3 py-2 rounded-lg transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Informasi Jumlah Hasil (Latihan) -->
        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-700/60">
            <div>
                Ditemukan <strong class="text-gray-900 dark:text-white">{{ $projects->total() }}</strong> proyek
                @if(request('search'))
                    untuk kata kunci &ldquo;<span class="text-blue-600 dark:text-blue-400">{{ request('search') }}</span>&rdquo;
                @endif
                @if(request('category'))
                    pada kategori &ldquo;{{ $categories->firstWhere('slug', request('category'))?->name ?? request('category') }}&rdquo;
                @endif
                @if(request('technology'))
                    dengan teknologi &ldquo;{{ $technologies->firstWhere('slug', request('technology'))?->name ?? request('technology') }}&rdquo;
                @endif
            </div>
            @if(request()->hasAny(['search', 'category', 'technology', 'sort', 'status']))
                <a href="{{ Request::routeIs('post.projects.*') ? route('post.projects.index') : route('projects') }}"
                   class="text-blue-600 dark:text-blue-400 hover:underline">
                    &times; Bersihkan semua filter
                </a>
            @endif
        </div>
    </div>

    <!-- Grid Kartu Proyek -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($projects as $project)
            <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:border-gray-300 dark:hover:border-gray-600 transition overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Thumbnail -->
                    @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->name }}"
                             class="w-full h-44 object-cover border-b border-gray-100 dark:border-gray-700">
                    @else
                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-xs">
                            Tidak ada thumbnail
                        </div>
                    @endif

                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <h2 class="font-bold text-base text-gray-900 dark:text-white leading-snug">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    {{ $project->name }}
                                </a>
                            </h2>
                            <div class="flex flex-col items-end gap-1 shrink-0">
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-100 dark:border-blue-800">
                                    {{ $project->category->name ?? $project->kategori }}
                                </span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $project->status === 'published' ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
                                    {{ ucfirst($project->status ?? 'published') }}
                                </span>
                            </div>
                        </div>

                        <!-- Ringkasan Proyek / Short Description -->
                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                            {{ $project->short_description ?: $project->description ?: $project->deskripsi }}
                        </p>

                        <!-- Daftar Badge Teknologi (Pivot project_technology) -->
                        <div class="space-y-1.5 pt-1">
                            <span class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">Teknologi:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($project->technologies as $tech)
                                    <span class="text-[10px] px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium border border-gray-200 dark:border-gray-600">
                                        {{ $tech->name }}
                                    </span>
                                @empty
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $project->teknologi }}</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Info Metadata Tambahan (Tanggal & Galeri) -->
                        <div class="flex items-center justify-between text-[11px] text-gray-400 dark:text-gray-500 pt-2 border-t border-gray-100 dark:border-gray-700/60">
                            <span>
                                @if($project->project_date)
                                    Tanggal: {{ $project->project_date->format('d M Y') }}
                                @endif
                            </span>
                            <span>{{ $project->images->count() }} Dokumentasi Foto</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi Kartu -->
                <div class="p-5 pt-0 border-t border-gray-100 dark:border-gray-700 mt-2 flex justify-between items-center text-xs">
                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 font-medium hover:underline inline-flex items-center gap-1">
                        Lihat Detail &rarr;
                    </a>

                    @auth
                        <div class="flex items-center gap-3">
                            <a href="{{ route('post.projects.edit', $project->id) }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                Edit &amp; Galeri
                            </a>
                            <form action="{{ route('post.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 dark:text-rose-400 font-medium hover:underline cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 text-center py-12 bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-xl space-y-3">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tidak ada project yang cocok.</p>
                @if(request()->hasAny(['search', 'category', 'technology', 'sort', 'status']))
                    <a href="{{ Request::routeIs('post.projects.*') ? route('post.projects.index') : route('projects') }}"
                       class="inline-block text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                        &larr; Bersihkan pencarian &amp; filter
                    </a>
                @elseif(auth()->check())
                    <a href="{{ route('post.projects.create') }}" class="inline-block text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                        Tambah proyek pertama &rarr;
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination Links (Query String Terjaga via withQueryString) -->
    <div class="pt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection
