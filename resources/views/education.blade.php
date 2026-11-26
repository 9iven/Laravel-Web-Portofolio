@extends('layouts.app')

@section('title', 'Education - Portofolio')

@section('content')
<div class="my-6 space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Pendidikan</h1>
        @auth
            <a href="{{ route('post.educations.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Kelola Pendidikan &rarr;</a>
        @endauth
    </div>

    <div class="space-y-4">
        @forelse($educations ?? [] as $edu)
            <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm space-y-1.5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <h2 class="font-bold text-base text-gray-900 dark:text-white">{{ $edu->institution }}</h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $edu->start_year }} &mdash; {{ $edu->is_current ? 'Sekarang' : ($edu->end_year ?: '-') }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $edu->degree }}@if($edu->field_of_study) &bull; {{ $edu->field_of_study }}@endif
                </p>
                @if($edu->description)
                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $edu->description }}</p>
                @endif
            </div>
        @empty
            <div class="border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 rounded-xl text-center text-xs text-gray-500 dark:text-gray-400">
                Belum ada data riwayat pendidikan.
            </div>
        @endforelse
    </div>
</div>
@endsection
