<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            [
                'institution' => 'Universitas Gadjah Mada',
                'degree' => 'D-IV Teknologi Rekayasa Perangkat Lunak',
                'field_of_study' => 'Departemen Teknik Elektro dan Informatika, Sekolah Vokasi',
                'start_year' => '2025',
                'end_year' => null,
                'is_current' => true,
                'description' => 'Mempelajari pemrograman web lanjutan, basis data, rekayasa perangkat lunak, dan struktur data.',
                'sort_order' => 1,
            ],
            [
                'institution' => 'SMA / Sederajat',
                'degree' => 'Jurusan IPA / MIPA',
                'field_of_study' => 'Matematika dan Ilmu Pengetahuan Alam',
                'start_year' => '2022',
                'end_year' => '2025',
                'is_current' => false,
                'description' => 'Menyelesaikan pendidikan menengah atas dengan fokus sains dan peminatan teknologi informasi.',
                'sort_order' => 2,
            ],
        ];

        foreach ($educations as $item) {
            Education::updateOrCreate(
                ['institution' => $item['institution'], 'degree' => $item['degree']],
                $item
            );
        }
    }
}
