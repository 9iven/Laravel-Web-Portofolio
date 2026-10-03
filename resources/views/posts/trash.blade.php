@extends('layouts.app')

@section('title', 'Trash - Posts')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Trash</h1>
            <p class="text-xs text-gray-500">Post yang sudah di-soft delete dan masih bisa dikembalikan.</p>
        </div>
        <a href="{{ route('posts.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
            &larr; Kembali ke Daftar Post
        </a>
    </div>

    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="border border-gray-200 bg-white p-5 rounded-xl shadow-sm space-y-3">
                <div class="flex justify-between items-baseline">
                    <h2 class="font-bold text-base text-gray-900">
                        {{ $post->title }}
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 font-medium">
                        Terhapus {{ $post->deleted_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $post->description }}
                </p>

                <div class="flex items-center gap-3 pt-3 border-t border-gray-100 text-xs">
                    <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-green-600 font-medium hover:underline cursor-pointer">
                            Restore
                        </button>
                    </form>
                    <form action="{{ route('posts.force-destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Post akan dihapus permanen dan tidak dapat dikembalikan. Lanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 font-medium hover:underline cursor-pointer">
                            Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-dashed border-gray-300 rounded-xl">
                <p class="text-gray-500 text-sm">Trash kosong, tidak ada post yang sudah dihapus.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection