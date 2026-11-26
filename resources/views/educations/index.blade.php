@extends('layouts.app')

@section('title', 'Kelola Pendidikan - Portofolio')

@section('content')
<div class="max-w-5xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pendidikan</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Daftar riwayat pendidikan yang tampil di halaman Education (ber-cache).</p>
        </div>
        <a href="{{ route('post.dashboard.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Tambah Pendidikan -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Tambah Riwayat Pendidikan</h2>
        <form action="{{ route('post.educations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="institution" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Institusi *</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution') }}" placeholder="Contoh: Universitas Gadjah Mada" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('institution')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="degree" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Jenjang / Program *</label>
                    <input type="text" name="degree" id="degree" value="{{ old('degree') }}" placeholder="Contoh: D-IV Teknologi Rekayasa Perangkat Lunak" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('degree')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <label for="field_of_study" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Bidang / Departemen</label>
                    <input type="text" name="field_of_study" id="field_of_study" value="{{ old('field_of_study') }}" placeholder="Contoh: Teknik Elektro dan Informatika"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="start_year" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Masuk *</label>
                    <input type="text" name="start_year" id="start_year" value="{{ old('start_year') }}" placeholder="2025" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('start_year')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_year" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Lulus</label>
                    <input type="text" name="end_year" id="end_year" value="{{ old('end_year') }}" placeholder="2029"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <label class="inline-flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                    <input type="checkbox" name="is_current" value="1" @checked(old('is_current'))
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span>Masih menempuh pendidikan (Sekarang)</span>
                </label>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="2" placeholder="Fokus studi atau pencapaian..."
                          class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    + Simpan Pendidikan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Pendidikan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Institusi</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Jenjang / Program</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Tahun</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($educations as $edu)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $edu->institution }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $edu->degree }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $edu->start_year }} &mdash; {{ $edu->is_current ? 'Sekarang' : ($edu->end_year ?: '-') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('post.educations.edit', $edu->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                            <form action="{{ route('post.educations.destroy', $edu->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat pendidikan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
