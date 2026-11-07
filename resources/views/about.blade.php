@extends('layouts.app')

@section('title', 'About - Portofolio')

@section('content')
<div class="my-6">
    <h1 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Tentang Saya</h1>
    
    <table class="border border-gray-200 dark:border-gray-700 border-collapse w-full text-sm mb-6">
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

    <h2 class="font-bold text-base mb-2 text-gray-900 dark:text-white">Keahlian:</h2>
    <ul class="list-disc ml-6 text-sm text-gray-700 dark:text-gray-300 space-y-1">
        <li>PHP &amp; Laravel</li>
        <li>SQLite Database</li>
        <li>HTML, CSS, JavaScript</li>
        <li>Git</li>
    </ul>
</div>
@endsection
