@extends('layouts.app')

@section('title', 'Posts - Portofolio')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Post</h1>
            <p class="text-xs text-gray-500">Catatan materi Eloquent ORM yang diambil dari database Model.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('posts.trash') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                Trash
            </a>
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                + Tambah Post
            </a>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="border border-gray-200 bg-white p-5 rounded-xl shadow-sm hover:border-gray-300 transition-colors space-y-2">
                <div class="flex justify-between items-baseline">
                    <h2 class="font-bold text-base text-gray-900">
                        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium {{ $post->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $post->status }}
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
                        {{ $post->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-dashed border-gray-300 rounded-xl">
                <p class="text-gray-500 text-sm">Belum ada data post di database.</p>
                <a href="{{ route('posts.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-medium hover:underline">
                    Tambah post pertama &rarr;
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection