@extends('layouts.app')

@section('title', 'Projects - Portofolio')

@section('content')
<div class="my-6">
    <h1 class="text-xl font-bold mb-4">Daftar Proyek</h1>

    <div class="space-y-4">
        @forelse($projects as $project)
            <div class="border p-4 rounded">
                <div class="flex justify-between items-baseline mb-1">
                    <h2 class="font-bold text-base">{{ $project['judul'] }}</h2>
                    <span class="text-xs text-gray-500">{{ $project['kategori'] }}</span>
                </div>
                <p class="text-sm text-gray-700 my-2">{{ $project['deskripsi'] }}</p>
                <p class="text-xs text-gray-600 mb-2">
                    Teknologi: {{ implode(', ', $project['teknologi']) }}
                </p>
                <a href="{{ $project['tautan'] }}" target="_blank" class="text-xs text-blue-600 underline">
                    Link GitHub &rarr;
                </a>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada data proyek.</p>
        @endforelse
    </div>
</div>
@endsection
