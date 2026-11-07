<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
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

        if (Category::count() === 0) {
            Category::create(['name' => 'Web Development']);
            Category::create(['name' => 'Mobile App']);
            Category::create(['name' => 'UI/UX Design']);
            Category::create(['name' => 'Sistem Informasi']);
        }

        if (Technology::count() === 0) {
            Technology::create(['name' => 'Laravel 12']);
            Technology::create(['name' => 'Tailwind CSS']);
            Technology::create(['name' => 'PHP 8.5']);
            Technology::create(['name' => 'MySQL']);
            Technology::create(['name' => 'Alpine.js']);
        }

        if (Skill::count() === 0) {
            Skill::create(['name' => 'Backend Laravel', 'level' => 90]);
            Skill::create(['name' => 'Frontend Tailwind CSS', 'level' => 85]);
            Skill::create(['name' => 'Database MySQL & SQLite', 'level' => 80]);
            Skill::create(['name' => 'Git & GitHub Collaboration', 'level' => 85]);
            Skill::create(['name' => 'REST API Design', 'level' => 75]);
        }

        $defaultCategory = Category::first();

        if (Project::count() === 0) {
            Project::create([
                'judul' => 'Sistem Pengelolaan Buku (reCRUD)',
                'kategori' => 'Tugas Praktikum',
                'category_id' => $defaultCategory?->id,
                'status' => 'published',
                'deskripsi' => 'Aplikasi CRUD buku sederhana menggunakan PHP native dan MySQL dari praktikum pertemuan 3 dengan implementasi Post/Redirect/Get dan sanitasi input.',
                'teknologi' => 'PHP, MySQL, Bootstrap',
                'tautan' => 'https://github.com/9iven',
            ]);

            Project::create([
                'judul' => 'Website Portofolio Mahasiswa',
                'kategori' => 'Tugas Praktikum',
                'category_id' => $defaultCategory?->id,
                'status' => 'published',
                'deskripsi' => 'Website portofolio interaktif berbasis Laravel dengan pemisahan master layout Blade, named routes, controller resource, dan model Eloquent.',
                'teknologi' => 'Laravel, Blade, Tailwind CSS, SQLite',
                'tautan' => 'https://github.com/9iven/Laravel-Web-Portofolio',
            ]);

            Project::create([
                'judul' => 'Aplikasi Manajemen Inventaris Klinik',
                'kategori' => 'Proyek Mandiri',
                'category_id' => $defaultCategory?->id,
                'status' => 'draft',
                'deskripsi' => 'Sistem informasi terintegrasi untuk pencatatan stok obat, resep dokter, dan riwayat kunjungan pasien.',
                'teknologi' => 'Laravel, REST API, Tailwind CSS',
                'tautan' => 'https://github.com/9iven',
            ]);
        } else {
            Project::whereNull('category_id')->update(['category_id' => $defaultCategory?->id]);
        }

        $this->call([
            PostTableSeeder::class,
        ]);
    }
}
