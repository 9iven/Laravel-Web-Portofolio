@extends('layouts.app')

@section('title', 'Projects - Portofolio')

@section('content')
<div class="my-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Proyek</h1>
            <p class="text-xs text-gray-500">Data proyek portofolio dari database Model Eloquent.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3.5 py-2 rounded-lg transition-colors shadow-sm">
            + Tambah Proyek
        </a>
    </div>

    <div class="space-y-4">
        @forelse($projects as $project)
            <div class="border border-gray-200 bg-white p-5 rounded-xl shadow-sm hover:border-gray-300 transition-colors space-y-2">
                <div class="flex justify-between items-baseline">
                    <h2 class="font-bold text-base text-gray-900">
                        <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $project->judul }}
                        </a>
                    </h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium">
                        {{ $project->kategori }}
                    </span>
                </div>

                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ $project->deskripsi }}
                </p>

                <p class="text-xs text-gray-500">
                    <strong class="text-gray-700">Teknologi:</strong> {{ $project->teknologi }}
                </p>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100 text-xs">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 font-medium hover:underline">
                            Lihat Detail &rarr;
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}" class="text-indigo-600 font-medium hover:underline">
                            Edit
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 font-medium hover:underline cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>

                    @if($project->tautan)
                        <a href="{{ $project->tautan }}" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-gray-800 underline">
                            Tautan Repositori &nearr;
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-dashed border-gray-300 rounded-xl">
                <p class="text-gray-500 text-sm">Belum ada data proyek di database.</p>
                <a href="{{ route('projects.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-medium hover:underline">
                    Tambah proyek pertama &rarr;
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
