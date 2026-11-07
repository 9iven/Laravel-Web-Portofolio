@extends('layouts.app')

@section('title', 'Tambah Proyek Baru')

@section('content')
<div class="my-6 max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('post.projects.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Proyek
        </a>
    </div>

    <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Tambah Proyek Baru</h1>
        <p class="text-xs text-gray-500 mb-6">Lengkapi informasi proyek, thumbnail, dan kategori untuk disimpan ke dalam database.</p>

        <form action="{{ route('post.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Form fields partial --}}
            @include('projects.partials._form')

            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Simpan Proyek
                </button>
                <a href="{{ route('post.projects.index') }}" class="text-gray-500 text-sm hover:text-gray-700 cursor-pointer">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
