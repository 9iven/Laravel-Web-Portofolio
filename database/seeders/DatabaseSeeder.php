<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Dummy Posts (Tutorial Modul)
        Post::create([
            'title' => 'Memulai Pengembangan dengan Laravel 13',
            'description' => 'Panduan pengenalan alur Model-View-Controller (MVC), integrasi routing, controller resource, dan Eloquent ORM di Laravel 13.',
        ]);

        Post::create([
            'title' => 'Implementasi Mass Assignment dan Validasi Form',
            'description' => 'Membahas pengamanan properti $fillable pada model Eloquent dan validasi input formulir menggunakan method $request->validate().',
        ]);

        // Seed Dummy Projects (Tugas Portofolio)
        Project::create([
            'judul' => 'Sistem Pengelolaan Buku (reCRUD)',
            'kategori' => 'Tugas Praktikum',
            'deskripsi' => 'Aplikasi CRUD buku sederhana menggunakan PHP native dan MySQL dari praktikum pertemuan 3 dengan implementasi Post/Redirect/Get dan sanitasi input.',
            'teknologi' => 'PHP, MySQL, Bootstrap',
            'tautan' => 'https://github.com/9iven',
        ]);

        Project::create([
            'judul' => 'Website Portofolio Mahasiswa',
            'kategori' => 'Tugas Praktikum',
            'deskripsi' => 'Website portofolio interaktif berbasis Laravel dengan pemisahan master layout Blade, named routes, controller resource, dan model Eloquent.',
            'teknologi' => 'Laravel, Blade, Tailwind CSS, SQLite',
            'tautan' => 'https://github.com/9iven/Laravel-Web-Portofolio',
        ]);

        Project::create([
            'judul' => 'Aplikasi Manajemen Inventaris Klinik',
            'kategori' => 'Proyek Mandiri',
            'deskripsi' => 'Sistem informasi terintegrasi untuk pencatatan stok obat, resep dokter, dan riwayat kunjungan pasien.',
            'teknologi' => 'Laravel, REST API, Tailwind CSS',
            'tautan' => 'https://github.com/9iven',
        ]);
    }
}
