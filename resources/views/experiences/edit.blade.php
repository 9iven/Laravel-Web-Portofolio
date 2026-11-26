@extends('layouts.app')

@section('title', 'Edit Pengalaman - Portofolio')

@section('content')
<div class="max-w-2xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Pengalaman</h1>
        <a href="{{ route('post.experiences.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            &larr; Batal &amp; Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form action="{{ route('post.experiences.update', $experience->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Peran / Jabatan *</label>
                    <input type="text" name="role" id="role" value="{{ old('role', $experience->role) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="organization" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi / Organisasi *</label>
                    <input type="text" name="organization" id="organization" value="{{ old('organization', $experience->organization) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('organization')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe *</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $experience->type) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="location" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $experience->location) }}"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $experience->start_date?->format('Y-m-d')) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $experience->end_date?->format('Y-m-d')) }}"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $experience->sort_order) }}" min="0"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="checkbox" name="is_current" value="1" @checked(old('is_current', $experience->is_current))
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span>Masih berlangsung (Sekarang)</span>
            </label>

            <div>
                <label for="description" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $experience->description) }}</textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Perbarui Pengalaman
                </button>
                <a href="{{ route('post.experiences.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs py-2 px-4 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
