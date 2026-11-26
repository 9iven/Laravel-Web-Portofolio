<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = [
            [
                'role' => 'Web Developer & Praktikan Aktif',
                'organization' => 'Laboratorium TRPL Sekolah Vokasi UGM',
                'type' => 'Akademik',
                'location' => 'Yogyakarta',
                'start_date' => '2025-09-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Mengembangkan aplikasi web berbasis Laravel, REST API, caching, queue worker, dan pengujian otomatis menggunakan Pest.',
                'sort_order' => 1,
            ],
            [
                'role' => 'Anggota Divisi Pengembangan Perangkat Lunak',
                'organization' => 'Komunitas Mahasiswa Teknologi Informasi SV UGM',
                'type' => 'Organisasi',
                'location' => 'Yogyakarta',
                'start_date' => '2025-10-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Berkolaborasi membangun aplikasi portal kegiatan dan berdiskusi mengenai arsitektur backend, Git workflow, dan UI/UX.',
                'sort_order' => 2,
            ],
            [
                'role' => 'Peserta Bootcamp Fullstack Laravel & Tailwind',
                'organization' => 'Pelatihan Pengembangan Web Intensif',
                'type' => 'Pelatihan',
                'location' => 'Daring',
                'start_date' => '2025-06-01',
                'end_date' => '2025-08-15',
                'is_current' => false,
                'description' => 'Membuat purwarupa sistem informasi dan halaman portofolio responsif menggunakan Blade, Tailwind CSS, dan Alpine.js.',
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $item) {
            Experience::updateOrCreate(
                ['role' => $item['role'], 'organization' => $item['organization']],
                $item
            );
        }
    }
}
