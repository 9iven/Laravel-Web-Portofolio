@extends('layouts.app')

@section('title', 'Dashboard Admin - Portofolio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-xs text-gray-500">Ringkasan data agregat, publikasi, keahlian, dan aktivitas terbaru.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('post.projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                + Tambah Project
            </a>
            <a href="{{ route('post.projects.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Kelola Project
            </a>
        </div>
    </div>

    <!-- Stat Cards Utama -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-2">
        <x-stat-card label="Total Projects" :value="$totalProjects" color="text-blue-600"  />
        <x-stat-card label="Published" :value="$totalPublished" color="text-emerald-600" />
        <x-stat-card label="Draft" :value="$totalDraft" color="text-amber-600" />
        <x-stat-card label="Categories" :value="$totalCategories" color="text-green-600" />
        <x-stat-card label="Technologies" :value="$totalTechnologies" color="text-purple-600" />
        <x-stat-card label="Skills" :value="$totalSkills" color="text-orange-600" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Distribusi Kategori (withCount) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Kategori & Jumlah Proyek</h2>
                <span class="text-xs text-gray-500 font-mono">{{ $categoriesWithCount->count() }} Kategori</span>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($categoriesWithCount as $category)
                    <li class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full font-semibold">
                            {{ $category->projects_count }} Project
                        </span>
                    </li>
                @empty
                    <li class="py-2 text-xs text-gray-500">Belum ada kategori yang terdaftar.</li>
                @endforelse
            </ul>
        </div>

        <!-- Skill Teratas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Skill Teratas</h2>
                <a href="{{ route('post.skills.index') }}" class="text-xs text-blue-600 hover:underline">Kelola Skill &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($topSkills as $skill)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $skill->name }}</span>
                            <span class="text-gray-500">{{ $skill->level }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" @style(['width' => min(100, max(0, $skill->level)) . '%'])></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500">Belum ada data skill.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Project Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">5 Project Terbaru</h2>
            <a href="{{ route('post.projects.index') }}" class="text-xs text-blue-600 hover:underline">Semua Project &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($latestProjects as $project)
                <li class="py-3 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        @if($project->thumbnail_url)
                            <img src="{{ $project->thumbnail_url }}" alt="{{ $project->judul }}" class="w-10 h-10 rounded object-cover border border-gray-200 dark:border-gray-700">
                        @else
                            <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center text-xs">No Img</div>
                        @endif
                        <div>
                            <span class="font-medium text-gray-800 dark:text-gray-200 text-sm block">{{ $project->judul }}</span>
                            <span class="text-xs text-gray-400">{{ $project->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-2.5 py-0.5 rounded-full font-medium">
                            {{ $project->category->name ?? $project->kategori }}
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $project->status === 'published' ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="py-3 text-sm text-gray-500">Belum ada project.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
