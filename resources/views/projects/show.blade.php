@extends('layouts.app')

@section('title', $project->judul . ' - Detail Proyek')

@section('content')
<div class="my-6">
    <div class="mb-4">
        <a href="{{ route('projects.index') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Proyek
        </a>
    </div>

    <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm space-y-4">
        <div class="flex justify-between items-start gap-4">
            <h1 class="text-2xl font-bold text-slate-900">{{ $project->judul }}</h1>
            <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100 whitespace-nowrap">
                {{ $project->kategori }}
            </span>
        </div>

        <div class="text-xs text-slate-400 border-b border-slate-100 pb-3">
            Ditambahkan pada: {{ $project->created_at ? $project->created_at->format('d M Y, H:i') : '-' }} | ID Proyek: #{{ $project->id }}
        </div>

        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi Proyek</h3>
            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $project->deskripsi }}
            </p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
            <div>
                <span class="text-xs font-bold text-slate-600">Teknologi yang Digunakan:</span>
                <p class="text-xs text-slate-800 font-mono mt-0.5">{{ $project->teknologi }}</p>
            </div>
            @if($project->tautan)
                <div>
                    <span class="text-xs font-bold text-slate-600">Tautan Proyek:</span>
                    <p class="mt-0.5">
                        <a href="{{ $project->tautan }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 hover:underline break-all">
                            {{ $project->tautan }} &nearr;
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
