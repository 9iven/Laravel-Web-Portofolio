@extends('layouts.app')

@section('title', 'Daftar Pesan Masuk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pesan Masuk (Contact Messages)</h1>
            <p class="text-xs text-gray-500">Kelola dan baca pesan yang dikirimkan pengunjung dari form kontak publik.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs text-gray-600 dark:text-gray-400 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase font-medium text-gray-500 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Pengirim</th>
                    <th scope="col" class="px-6 py-3">Subjek</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Waktu Kirim</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($contacts as $contact)
                    <tr class="transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $contact->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $contact->subject ?: '(Tanpa Subjek)' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-medium {{ $contact->is_read ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
                                {{ $contact->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $contact->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('post.contacts.show', $contact) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                Baca &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            Belum ada pesan yang masuk dari pengunjung.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $contacts->links() }}
    </div>
</div>
@endsection
