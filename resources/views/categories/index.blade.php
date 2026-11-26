@extends('layouts.app')

@section('title', 'Kelola Kategori - Portofolio')

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Kategori</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Daftar kategori proyek portofolio (otomatis menghapus cache kategori &amp; featured).</p>
        </div>
        <a href="{{ route('post.dashboard.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Tambah Kategori -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Tambah Kategori Baru</h2>
        <form action="{{ route('post.categories.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            @csrf
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Cloud Computing" required
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Nama Kategori</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Proyek</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-6 py-4 font-mono text-gray-500 dark:text-gray-400">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $category->projects_count }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('post.categories.edit', $category->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                            <form action="{{ route('post.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada kategori yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
