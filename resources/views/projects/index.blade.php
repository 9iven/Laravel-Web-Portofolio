@extends('layouts.app')

@section('title', 'Projects - Portofolio')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Proyek</h1>
            <p class="text-xs text-slate-500">Data proyek dinamis dari database Model Eloquent.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition shadow-sm">
            + Tambah Proyek
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($projects as $project)
            <div class="border border-slate-200 bg-white p-5 rounded-xl shadow-sm hover:border-slate-300 transition space-y-2">
                <div class="flex justify-between items-baseline">
                    <h2 class="font-bold text-base text-slate-900">
                        <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600 transition">
                            {{ $project->judul }}
                        </a>
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">
                        {{ $project->kategori }}
                    </span>
                </div>

                <p class="text-sm text-slate-700 leading-relaxed">
                    {{ $project->deskripsi }}
                </p>

                <p class="text-xs text-slate-500">
                    <strong class="text-slate-700">Teknologi:</strong> {{ $project->teknologi }}
                </p>

                <div class="flex justify-between items-center pt-2 border-t border-slate-100 text-xs">
                    <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 font-medium hover:underline">
                        Lihat Detail &rarr;
                    </a>
                    @if($project->tautan)
                        <a href="{{ $project->tautan }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-slate-800 underline">
                            Tautan Repositori &nearr;
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10 border border-dashed border-slate-300 rounded-xl">
                <p class="text-slate-500 text-sm">Belum ada data proyek di database.</p>
                <a href="{{ route('projects.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-medium hover:underline">
                    Tambah proyek pertama &rarr;
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
