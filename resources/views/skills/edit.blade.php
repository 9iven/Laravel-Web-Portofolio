@extends('layouts.app')

@section('title', 'Edit Skill - Portofolio')

@section('content')
<div class="max-w-md mx-auto py-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Skill</h1>
        <a href="{{ route('post.skills.index') }}" class="text-xs text-blue-600 hover:underline">
            &larr; Batal & Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form action="{{ route('post.skills.update', $skill->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Skill</label>
                <input type="text" name="name" id="name" value="{{ old('name', $skill->name) }}" required
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="level" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Level (0 - 100)%</label>
                <input type="number" name="level" id="level" value="{{ old('level', $skill->level) }}" min="0" max="100" required
                       class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('level')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg transition-colors">
                    Perbarui Skill
                </button>
                <a href="{{ route('post.skills.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs py-2 px-4 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
