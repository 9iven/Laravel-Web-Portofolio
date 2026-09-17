@extends('layouts.app')

@section('title', 'Demo View Halo Dunia')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center space-y-4">
    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
        🌐
    </div>
    <h1 class="text-3xl font-extrabold text-slate-900">Halo Dunia dari View Blade!</h1>
    <p class="text-slate-600 text-sm">
        File ini dimuat dari <code class="bg-slate-100 px-2 py-1 rounded text-xs text-indigo-700 font-mono">resources/views/halo_dunia.blade.php</code> menggunakan fungsi helper <code class="bg-slate-100 px-2 py-1 rounded text-xs text-indigo-700 font-mono">view('halo_dunia')</code> pada Route.
    </p>
    <div class="pt-4">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
            &larr; Kembali ke Beranda Portofolio
        </a>
    </div>
</div>
@endsection
