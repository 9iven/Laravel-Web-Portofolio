@extends('layouts.app')

@section('title', $post->title . ' - Detail Post')

@section('content')
<div class="my-6">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
            &larr; Kembali ke Daftar Post
        </a>
    </div>

    <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm space-y-4">
        <h1 class="text-2xl font-bold text-slate-900">{{ $post->title }}</h1>
        <div class="text-xs text-slate-400 border-b border-slate-100 pb-3">
            Dipublikasikan pada: {{ $post->created_at ? $post->created_at->format('d M Y, H:i') : '-' }} | ID: #{{ $post->id }}
        </div>
        <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">
            {{ $post->description }}
        </div>
    </div>
</div>
@endsection
