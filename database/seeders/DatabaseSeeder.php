<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
            ]);
        }

        if (! User::where('email', 'user@example.com')->exists()) {
            User::create([
                'name' => 'User Biasa',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
            ]);
        }

        if (Project::count() === 0) {
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

        $this->call([
            PostTableSeeder::class,
        ]);
    }
}
