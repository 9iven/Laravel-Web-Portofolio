@extends('layouts.app')

@section('title', 'Kelola Skill - Portofolio')

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Skill</h1>
            <p class="text-xs text-gray-500">Daftar keahlian teknis dan tingkat penguasaan.</p>
        </div>
        <a href="{{ route('post.dashboard.index') }}" class="text-xs text-blue-600 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Tambah Skill -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Tambah Skill Baru</h2>
        <form action="{{ route('post.skills.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            @csrf
            <div>
                <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Skill</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Laravel / PHP" required
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="level" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Level (0 - 100)%</label>
                <input type="number" name="level" id="level" value="{{ old('level', 80) }}" min="0" max="100" required
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('level')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors">
                    + Simpan Skill
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel / List Skill -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Nama Skill</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase">Tingkat Penguasaan</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($skills as $skill)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $skill->name }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $skill->level }}%"></div>
                                </div>
                                <span class="text-gray-500">{{ $skill->level }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('post.skills.edit', $skill->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('post.skills.destroy', $skill->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus skill ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada skill yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
