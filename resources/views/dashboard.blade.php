@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-stat-card label="Projects" :value="$totalProjects" />
        <x-stat-card label="Categories" :value="$totalCategories" color="text-green-600" />
        <x-stat-card label="Technologies" :value="$totalTechnologies" color="text-purple-600" />
        <x-stat-card label="Skills" :value="$totalSkills" color="text-orange-600" />
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Terbaru</h2>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($latestProjects as $project)
                <li class="py-3 flex justify-between items-center">
                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $project->judul }}</span>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full font-medium">
                        {{ $project->category->name ?? $project->kategori }}
                    </span>
                </li>
            @empty
                <li class="py-3 text-sm text-gray-500">Belum ada project.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection