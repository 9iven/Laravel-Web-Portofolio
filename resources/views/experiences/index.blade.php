@extends('layouts.app')

@section('title', 'Kelola Pengalaman - Portofolio')

@section('content')
<div class="max-w-5xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pengalaman</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Daftar pengalaman kerja, organisasi, dan aktivitas yang tampil di halaman About.</p>
        </div>
        <a href="{{ route('post.dashboard.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Tambah Pengalaman -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Tambah Pengalaman Baru</h2>
        <form action="{{ route('post.experiences.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Peran / Jabatan *</label>
                    <input type="text" name="role" id="role" value="{{ old('role') }}" placeholder="Contoh: Asisten Praktikum" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="organization" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi / Organisasi *</label>
                    <input type="text" name="organization" id="organization" value="{{ old('organization') }}" placeholder="Contoh: SV UGM" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('organization')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="type" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe *</label>
                    <input type="text" name="type" id="type" value="{{ old('type', 'Pengalaman') }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('type')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="location" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Yogyakarta"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="start_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('start_date')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('end_date')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <label class="inline-flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                    <input type="checkbox" name="is_current" value="1" @checked(old('is_current'))
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span>Masih berlangsung (Sekarang)</span>
                </label>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="2" placeholder="Ringkasan tanggung jawab atau capaian..."
                          class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors cursor-pointer">
                    + Simpan Pengalaman
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Pengalaman -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Peran &amp; Instansi</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Periode</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($experiences as $exp)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $exp->role }}</div>
                            <div class="text-gray-500 dark:text-gray-400">{{ $exp->organization }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $exp->type }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $exp->start_date?->format('M Y') }} &mdash; {{ $exp->is_current ? 'Sekarang' : ($exp->end_date?->format('M Y') ?? '-') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('post.experiences.edit', $exp->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                            <form action="{{ route('post.experiences.destroy', $exp->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengalaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada pengalaman yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
