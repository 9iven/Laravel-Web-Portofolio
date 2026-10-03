<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Memahami Model Post dan artisan model:show',
                'description' => 'Mempelajari konvensi Model Eloquent melalui artisan model:show, mulai dari nama tabel posts, primary key id, sampai kolom timestamps created_at dan updated_at.',
                'status' => 'published',
            ],
            [
                'title' => 'Mengambil Semua Data dengan all dan get',
                'description' => 'Menarik seluruh baris data memakai method all, lalu mempersempit hasil memakai method get yang disandingkan dengan kondisi where, orderBy, dan limit.',
                'status' => 'published',
            ],
            [
                'title' => 'Mencari Data Berdasarkan Primary Key',
                'description' => 'Menggunakan find, findOrFail, first, firstWhere, firstOrFail, dan exists untuk menemukan satu baris data berdasarkan primary key atau kondisi tertentu.',
                'status' => 'published',
            ],
            [
                'title' => 'Menghitung Data dengan Retrieving Aggregates',
                'description' => 'Menjalankan perhitungan matematis langsung di level database melalui count, max, min, sum, dan avg tanpa memuat seluruh baris data ke memori.',
                'status' => 'published',
            ],
            [
                'title' => 'Menyimpan Data Baru dengan Lima Cara',
                'description' => 'Mencoba instansiasi dan save, create, firstOrCreate, firstOrNew, serta updateOrCreate untuk melihat perbedaan perilaku tiap cara menyimpan data.',
                'status' => 'published',
            ],
            [
                'title' => 'Memperbarui Data dan Mendeteksi Perubahan',
                'description' => 'Mengubah property model lalu menyimpan, atau memakai method update dan mass update, lalu memeriksa status perubahan memakai isDirty, isClean, wasChanged, dan getOriginal.',
                'status' => 'draft',
            ],
            [
                'title' => 'Proteksi Mass Assignment pada Model',
                'description' => 'Memahami daftar putih kolom yang boleh diisi secara massal melalui properti fillable dan membandingkannya dengan daftar hitam guarded.',
                'status' => 'draft',
            ],
            [
                'title' => 'Query Scope untuk Query yang Reusable',
                'description' => 'Mendefinisikan logika query yang sering dipakai di dalam model lewat local scope, lalu merangkainya menjadi satu rangkaian pemanggilan yang ringkas.',
                'status' => 'draft',
            ],
        ];

        foreach ($posts as $index => $post) {
            Post::create($post)->forceFill([
                'created_at' => now()->subDays(($index + 1) * 2),
                'updated_at' => now()->subDays(($index + 1) * 2),
            ])->saveQuietly();
        }

        Post::create([
            'title' => 'Catatan Belajar Eloquent ORM Pertemuan 7',
            'description' => 'Post contoh yang sengaja dibuat pada hari ini dipakai untuk menguji query scope today beserta method search dan chaining antar scope.',
            'status' => 'published',
        ]);
    }
}
