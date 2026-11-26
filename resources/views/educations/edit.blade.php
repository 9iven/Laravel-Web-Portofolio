@extends('layouts.app')

@section('title', 'Edit Pendidikan - Portofolio')

@section('content')
<div class="max-w-2xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Riwayat Pendidikan</h1>
        <a href="{{ route('post.educations.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            &larr; Batal &amp; Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form action="{{ route('post.educations.update', $education->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="institution" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Institusi *</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution', $education->institution) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('institution')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="degree" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Jenjang / Program *</label>
                    <input type="text" name="degree" id="degree" value="{{ old('degree', $education->degree) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('degree')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="field_of_study" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Bidang / Departemen</label>
                    <input type="text" name="field_of_study" id="field_of_study" value="{{ old('field_of_study', $education->field_of_study) }}"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $education->sort_order) }}" min="0"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_year" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Masuk *</label>
                    <input type="text" name="start_year" id="start_year" value="{{ old('start_year', $education->start_year) }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="end_year" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Lulus</label>
                    <input type="text" name="end_year" id="end_year" value="{{ old('end_year', $education->end_year) }}"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="checkbox" name="is_current" value="1" @checked(old('is_current', $education->is_current))
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span>Masih menempuh pendidikan (Sekarang)</span>
            </label>

            <div>
                <label for="description" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $education->description) }}</textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    Perbarui Pendidikan
                </button>
                <a href="{{ route('post.educations.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs py-2 px-4 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
