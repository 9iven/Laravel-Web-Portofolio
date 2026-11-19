@extends('layouts.app')

@section('title', 'Detail Pesan - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('post.contacts.index') }}" class="text-xs text-gray-600 dark:text-gray-400 hover:underline">
            &larr; Kembali ke Daftar Pesan
        </a>
        <form action="{{ route('post.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">
                Hapus Pesan
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
        <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $contact->subject ?: '(Tanpa Subjek)' }}</h1>
            <div class="text-xs text-gray-500 mt-2 flex flex-wrap items-center gap-x-4 gap-y-2">
                <span><strong>Dari:</strong> {{ $contact->name }} ({{ $contact->email }})</span>
                <span><strong>Diterima:</strong> {{ $contact->created_at->format('d M Y, H:i') }}</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                    &#10003; Sudah Dibaca
                </span>
            </div>
        </div>

        <div class="text-gray-800 dark:text-gray-200 text-sm whitespace-pre-line leading-relaxed pt-2">
            {{ $contact->message }}
        </div>
    </div>
</div>
@endsection
