@extends('layouts.app')

@section('title', $post->title . ' - Detail Post')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Post
        </a>

        <div class="flex items-center gap-3 text-xs">
            <a href="{{ route('posts.edit', $post->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg font-medium hover:bg-indigo-100 transition-colors">
                Edit Post
            </a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg font-medium hover:bg-rose-100 transition-colors cursor-pointer">
                    Hapus Post
                </button>
            </form>
        </div>
    </div>

    <div class="border border-gray-200 bg-white p-6 rounded-2xl shadow-sm space-y-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
        <div class="text-xs text-gray-400 border-b border-gray-100 pb-3">
            Dipublikasikan pada: {{ $post->created_at ? $post->created_at->format('d M Y, H:i') : '-' }} | ID: #{{ $post->id }}
        </div>
        <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
            {{ $post->description }}
        </div>
    </div>
</div>
@endsection
