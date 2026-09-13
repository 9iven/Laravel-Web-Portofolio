@extends('layouts.app')

@section('title', 'About - Portofolio')

@section('content')
<div class="my-6">
    <h1 class="text-xl font-bold mb-4">Tentang Saya</h1>
    
    <table class="border border-collapse w-full text-sm mb-6">
        <tr class="border-b">
            <td class="p-2 font-bold w-32 bg-gray-50 border-r">Nama</td>
            <td class="p-2">Given Gerald Chandra</td>
        </tr>
        <tr class="border-b">
            <td class="p-2 font-bold bg-gray-50 border-r">NIM</td>
            <td class="p-2">25/560235/SV/26410</td>
        </tr>
        <tr class="border-b">
            <td class="p-2 font-bold bg-gray-50 border-r">Kelas</td>
            <td class="p-2">B2</td>
        </tr>
        <tr class="border-b">
            <td class="p-2 font-bold bg-gray-50 border-r">Program Studi</td>
            <td class="p-2">D-IV Teknologi Rekayasa Perangkat Lunak</td>
        </tr>
        <tr>
            <td class="p-2 font-bold bg-gray-50 border-r">Departemen</td>
            <td class="p-2">Teknik Elektro dan Informatika, SV UGM</td>
        </tr>
    </table>

    <h2 class="font-bold text-base mb-2">Keahlian:</h2>
    <ul class="list-disc ml-6 text-sm text-gray-700 space-y-1">
        <li>PHP &amp; Laravel</li>
        <li>MySQL Database</li>
        <li>HTML, CSS, JavaScript</li>
        <li>Git</li>
    </ul>
</div>
@endsection
