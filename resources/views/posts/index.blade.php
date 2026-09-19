@extends('layouts.app')

@section('title', 'Daftar Posts')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Postingan (Tutorial)</h1>
        <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
            + Tambah Post
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="border border-slate-200 bg-white p-5 rounded-xl shadow-sm hover:border-slate-300 transition">
                <h2 class="font-bold text-lg text-slate-800 mb-1">
                    <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 transition">
                        {{ $post->title }}
                    </a>
                </h2>
                <p class="text-sm text-slate-600 mb-3 leading-relaxed">
                    {{ Str::limit($post->description, 120) }}
                </p>
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Dibuat: {{ $post->created_at ? $post->created_at->format('d M Y, H:i') : '-' }}</span>
                    <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 font-medium hover:underline">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-10 border border-dashed border-slate-300 rounded-xl">
                <p class="text-slate-500 text-sm">Belum ada data postingan.</p>
                <a href="{{ route('posts.create') }}" class="inline-block mt-3 text-sm text-blue-600 font-medium hover:underline">
                    Buat postingan pertama &rarr;
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
