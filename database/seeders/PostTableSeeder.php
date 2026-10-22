<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            Post::create([
                'title' => 'Post Judul Ke-'.$i,
                'description' => 'Deskripsi untuk post judul ke-'.$i,
                'status' => 'published',
            ]);
        }
    }
}
