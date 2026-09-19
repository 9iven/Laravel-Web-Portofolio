@extends('layouts.app')

@section('title', 'Tambah Proyek Baru')

@section('content')
<div class="my-6 max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('projects.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Proyek
        </a>
    </div>

    <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 mb-1">Tambah Data Proyek Baru</h1>
        <p class="text-xs text-slate-500 mb-4">Lengkapi informasi proyek untuk disimpan ke dalam database.</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-lg space-y-1">
                <div class="font-bold">Terjadi kesalahan validasi:</div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="judul" class="block text-xs font-semibold text-slate-700 mb-1">Judul Proyek <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Contoh: Sistem Informasi Pengelolaan Buku" required>
            </div>

            <div>
                <label for="kategori" class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Contoh: Tugas Praktikum / Proyek Mandiri" required>
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Proyek <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Jelaskan ringkasan fungsionalitas proyek..." required>{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label for="teknologi" class="block text-xs font-semibold text-slate-700 mb-1">Teknologi yang Digunakan <span class="text-rose-500">*</span></label>
                <input type="text" name="teknologi" id="teknologi" value="{{ old('teknologi') }}" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Contoh: Laravel, Blade, Tailwind CSS, SQLite" required>
            </div>

            <div>
                <label for="tautan" class="block text-xs font-semibold text-slate-700 mb-1">Tautan GitHub / Repositori (Opsional)</label>
                <input type="url" name="tautan" id="tautan" value="{{ old('tautan') }}" 
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="https://github.com/username/project">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition shadow-sm">
                    Simpan Proyek
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
