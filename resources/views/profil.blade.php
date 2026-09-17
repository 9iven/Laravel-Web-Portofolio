@extends('layouts.app')

@section('title', 'Demo Passing Data ke Blade')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-slate-900">Demo Pengiriman Data ke Blade</h1>
        <p class="text-sm text-slate-500">Mendemonstrasikan passing data via <code class="font-mono text-indigo-600">compact()</code> dan interpolasi variabel Blade.</p>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="text-xs text-slate-500 block font-semibold">Nama Mahasiswa:</span>
                <span class="text-base font-bold text-slate-800">{{ $nama }}</span>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="text-xs text-slate-500 block font-semibold">Usia:</span>
                <span class="text-base font-bold text-slate-800">{{ $umur }} Tahun</span>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="text-xs text-slate-500 block font-semibold">Kota Asal:</span>
                <span class="text-base font-bold text-slate-800">{{ $kota }}</span>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="text-xs text-slate-500 block font-semibold">Status Usia:</span>
                <span class="text-base font-bold text-slate-800">
                    @if($umur >= 17)
                        <span class="text-emerald-600">Dewasa (Kondisi Terpenuhi)</span>
                    @else
                        <span class="text-amber-600">Remaja</span>
                    @endif
                </span>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 mb-2">Daftar Mata Kuliah (Perulangan @@foreach):</h3>
            <ul class="list-disc list-inside space-y-1 text-sm text-slate-700">
                @foreach($matakuliah as $mk)
                    <li>{{ $mk }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
