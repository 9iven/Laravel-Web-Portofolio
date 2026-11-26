@extends('layouts.app')

@section('title', 'Dashboard Admin - Portofolio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-xs text-gray-500">Ringkasan data agregat, publikasi, keahlian, dan aktivitas terbaru.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('post.projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                + Tambah Project
            </a>
            <a href="{{ route('post.projects.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Projects
            </a>
            <a href="{{ route('post.categories.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Categories
            </a>
            <a href="{{ route('post.skills.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Skills
            </a>
            <a href="{{ route('post.experiences.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Experiences
            </a>
            <a href="{{ route('post.educations.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                Educations
            </a>
        </div>
    </div>

    <!-- Stat Cards Utama -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <x-stat-card label="Total Projects" :value="$totalProjects" color="text-blue-600" />
        <x-stat-card label="Published" :value="$totalPublished" color="text-emerald-600" />
        <x-stat-card label="Draft" :value="$totalDraft" color="text-amber-600" />
        <x-stat-card label="Categories" :value="$totalCategories" color="text-green-600" />
        <x-stat-card label="Technologies" :value="$totalTechnologies" color="text-purple-600" />
        <x-stat-card label="Skills" :value="$totalSkills" color="text-orange-600" />
        <x-stat-card label="Messages" :value="$totalMessages ?? 0" color="text-indigo-600" />
        <x-stat-card label="Unread" :value="$unreadMessages ?? 0" color="text-rose-600" />
    </div>

    <!-- Widget Monitoring Cache & Queue (Pertemuan 13) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Widget Status Cache -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 space-y-4">
            <div class="flex flex-wrap justify-between items-center gap-2">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Status Cache Portofolio</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pemeriksaan langsung dengan <code class="font-mono">Cache::has()</code>.</p>
                </div>
                <form action="{{ route('post.dashboard.cache.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                        Bersihkan Semua Cache
                    </button>
                </form>
            </div>

            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($cacheStatuses ?? [] as $item)
                    <li class="py-2.5 flex justify-between items-center gap-3 text-xs">
                        <div>
                            <span class="font-mono font-semibold text-gray-800 dark:text-gray-200 block">{{ $item['key'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400">{{ $item['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($item['active'])
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-semibold">
                                    Cached (HIT)
                                </span>
                                <form action="{{ route('post.dashboard.cache.clear') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline font-medium cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium">
                                    Kosong (MISS)
                                </span>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="py-2 text-xs text-gray-500">Belum ada key cache yang dipantau.</li>
                @endforelse
            </ul>
        </div>

        <!-- Widget Monitor Queue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 space-y-4">
            <div class="flex flex-wrap justify-between items-center gap-2">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Monitor Antrean (Queue)</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Status job pada tabel <code class="font-mono">jobs</code> dan <code class="font-mono">failed_jobs</code>.</p>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('post.dashboard.queue.retry-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                            Retry All
                        </button>
                    </form>
                    <form action="{{ route('post.dashboard.queue.flush') }}" method="POST" onsubmit="return confirm('Hapus seluruh catatan failed job?')">
                        @csrf
                        <button type="submit" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                            Flush Failed
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pending Jobs</span>
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $pendingJobsCount ?? 0 }}</div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Menunggu diproses worker</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Failed Jobs</span>
                    <div class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $failedJobsCount ?? 0 }}</div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Gagal setelah percobaan habis</p>
                </div>
            </div>

            @if(($recentFailedJobs ?? collect())->isNotEmpty())
                <div class="space-y-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300">Failed Jobs Terbaru</h3>
                    <ul class="space-y-1.5 text-xs">
                        @foreach($recentFailedJobs as $failedJob)
                            <li class="p-2.5 rounded-lg bg-rose-50/70 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800">
                                <div class="flex justify-between font-mono text-[11px] text-rose-800 dark:text-rose-300">
                                    <span>{{ $failedJob->uuid }}</span>
                                    <span>{{ $failedJob->failed_at }}</span>
                                </div>
                                <p class="text-[11px] text-rose-700 dark:text-rose-400 truncate mt-0.5">
                                    {{ Str::limit(strtok($failedJob->exception, "\n"), 120) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
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
