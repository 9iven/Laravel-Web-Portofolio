@extends('layouts.app')

@section('title', 'About - Portofolio')

@section('content')
<div class="my-6 space-y-8">
    <section>
        <h1 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Tentang Saya</h1>

        <table class="border border-gray-200 dark:border-gray-700 border-collapse w-full text-sm">
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="p-2 font-bold w-32 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700">Nama</td>
                <td class="p-2 text-gray-800 dark:text-gray-300">Given Gerald Chandra</td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="p-2 font-bold bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700">NIM</td>
                <td class="p-2 text-gray-800 dark:text-gray-300">25/560235/SV/26410</td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="p-2 font-bold bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700">Kelas</td>
                <td class="p-2 text-gray-800 dark:text-gray-300">B2</td>
            </tr>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="p-2 font-bold bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700">Program Studi</td>
                <td class="p-2 text-gray-800 dark:text-gray-300">D-IV Teknologi Rekayasa Perangkat Lunak</td>
            </tr>
            <tr>
                <td class="p-2 font-bold bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700">Departemen</td>
                <td class="p-2 text-gray-800 dark:text-gray-300">Teknik Elektro dan Informatika, SV UGM</td>
            </tr>
        </table>
    </section>

    <!-- Pengalaman (Cached: portfolio.experiences) -->
    <section class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-lg text-gray-900 dark:text-white">Pengalaman &amp; Aktivitas</h2>
            @auth
                <a href="{{ route('post.experiences.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Kelola Pengalaman &rarr;</a>
            @endauth
        </div>

        <div class="space-y-3">
            @forelse($experiences ?? [] as $exp)
                <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm space-y-1.5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <div>
                            <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $exp->role }}</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $exp->organization }}@if($exp->location) &bull; {{ $exp->location }}@endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-medium">
                                {{ $exp->type }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $exp->start_date?->format('M Y') }} &mdash; {{ $exp->is_current ? 'Sekarang' : ($exp->end_date?->format('M Y') ?? '-') }}
                            </span>
                        </div>
                    </div>
                    @if($exp->description)
                        <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed pt-1">{{ $exp->description }}</p>
                    @endif
                </div>
            @empty
                <div class="border border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-4 text-xs text-gray-500 dark:text-gray-400">
                    Belum ada data pengalaman yang ditambahkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Keahlian (Cached: portfolio.skills) -->
    <section class="space-y-3">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-lg text-gray-900 dark:text-white">Keahlian</h2>
            @auth
                <a href="{{ route('post.skills.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Kelola Skill &rarr;</a>
            @endauth
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($skills ?? [] as $skill)
                <div class="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3.5 rounded-xl shadow-sm space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $skill->name }}</span>
                        <span class="text-gray-500 dark:text-gray-400 font-mono">{{ $skill->level }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" @style(['width' => min(100, max(0, $skill->level)) . '%'])></div>
                    </div>
                </div>
            @empty
                <ul class="list-disc ml-6 text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li>PHP &amp; Laravel</li>
                    <li>SQLite Database</li>
                    <li>HTML, CSS, JavaScript</li>
                    <li>Git</li>
                </ul>
            @endforelse
        </div>
    </section>
</div>
@endsection
