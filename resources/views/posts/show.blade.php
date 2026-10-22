@extends('layouts.app')

@section('title', $post->title . ' - Detail Post')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('posts.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Post
        </a>

        <div class="flex items-center gap-3 text-xs">
            <a href="{{ route('posts.edit', $post->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg font-medium hover:bg-indigo-100 transition-colors">
                Edit Post
            </a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus post ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg font-medium hover:bg-rose-100 transition-colors cursor-pointer">
                    Hapus Post
                </button>
            </form>
        </div>
    </div>

    <div class="border border-gray-200 bg-white p-6 rounded-2xl shadow-sm space-y-4">
        <div class="flex justify-between items-start gap-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
            <span class="text-xs px-3 py-1 rounded-full font-semibold border whitespace-nowrap {{ $post->status === 'published' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                {{ $post->status }}
            </span>
        </div>

        <div class="text-xs text-gray-400 border-b border-gray-100 pb-3">
            Dibuat pada: {{ $post->created_at->format('d M Y, H:i') }} | Diperbarui: {{ $post->updated_at->format('d M Y, H:i') }} | ID Post: #{{ $post->id }}
        </div>

        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</h3>
            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $post->description }}
            </p>
        </div>
    </div>
</div>
@endsection