@extends('layouts.app')

@section('title', 'Edit Post - ' . $post->title)

@section('content')
<div class="my-6 max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Post
        </a>
    </div>

    <div class="border border-gray-200 bg-white p-6 rounded-xl shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Postingan</h1>
        <p class="text-xs text-gray-500 mb-6">Perbarui isi konten postingan di database.</p>

        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Form fields partial --}}
            @include('posts.partials._form')

            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Perbarui Post
                </button>
                <a href="{{ route('posts.index') }}" class="text-gray-500 text-sm hover:text-gray-700 cursor-pointer">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
