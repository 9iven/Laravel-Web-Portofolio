@extends('layouts.app')

@section('title', 'Posts - Portofolio')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Post</h1>
            <p class="text-xs text-gray-500">Praktikum Pemrograman Web 2 - Seeder dan Pagination (Pertemuan 8)</p>
        </div>
        <div>
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm inline-flex items-center gap-1">
                + Tambah Post
            </a>
        </div>
    </div>

    {{-- Filter Pencarian dan Pilihan Per Halaman (Latihan Modul 8) --}}
    <form action="{{ route('posts.index') }}" method="GET" class="bg-gray-50 p-3 rounded-xl border border-gray-200 mb-6 flex flex-col sm:flex-row items-center gap-3">
        <div class="flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul post..."
                class="w-full text-xs bg-white border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
            <label for="per_page" class="text-xs text-gray-600 font-medium whitespace-nowrap">Tampilkan:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()"
                class="text-xs bg-white border border-gray-300 rounded-lg px-2.5 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="5" @selected($perPage == 5)>5 data</option>
                <option value="10" @selected($perPage == 10)>10 data</option>
                <option value="25" @selected($perPage == 25)>25 data</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                Cari
            </button>

            @if(request('search') || request('per_page'))
                <a href="{{ route('posts.index') }}" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-2">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="space-y-4">
        @forelse($posts as $post)
            @php
                // Nomor urut berlanjut antar halaman (Latihan Modul 8)
                $nomorUrut = ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration;
            @endphp
            <div class="border border-gray-200 bg-white p-5 rounded-xl shadow-sm hover:border-gray-300 transition-colors space-y-2">
                <div class="flex justify-between items-baseline gap-2">
                    <h2 class="font-bold text-base text-gray-900">
                        <span class="text-blue-600 mr-1.5 font-mono text-sm">#{{ $nomorUrut }}</span>
                        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium {{ $post->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $post->status ?? 'published' }}
                    </span>
                </div>

                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ $post->description }}
                </p>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100 text-xs">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 font-medium hover:underline">
                            Lihat Detail &rarr;
                        </a>
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-indigo-600 font-medium hover:underline">
                            Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus post ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 font-medium hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>

                    <span class="text-gray-400">
                        {{ $post->created_at?->format('d M Y') ?? '-' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-dashed border-gray-300 rounded-xl">
                <p class="text-gray-500 text-sm">
                    @if(request('search'))
                        Tidak ada post yang cocok dengan kata kunci "{{ request('search') }}".
                    @else
                        Belum ada data post di database.
                    @endif
                </p>
                <a href="{{ route('posts.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-medium hover:underline">
                    Tambah post baru &rarr;
                </a>
            </div>
        @endforelse
    </div>

    {{-- Informasi Halaman & Pagination Sesuai Modul 8 Slide 13 --}}
    @if($posts->total() > 0)
        <div class="mt-4 text-sm text-gray-600">
            Halaman : {{ $posts->currentPage() }} <br>
            Jumlah Data : {{ $posts->total() }} <br>
            Data Per Halaman : {{ $posts->perPage() }}
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
