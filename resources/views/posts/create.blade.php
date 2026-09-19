@extends('layouts.app')

@section('title', 'Tambah Post Baru')

@section('content')
<div class="my-6 max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Post
        </a>
    </div>

    <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 mb-4">Tambah Post Baru</h1>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-lg space-y-1">
                <div class="font-bold">Terjadi kesalahan input:</div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">Judul Post</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Masukkan judul post..." required>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="5" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Tuliskan isi deskripsi post..." required>{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('posts.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition shadow-sm">
                    Simpan Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
