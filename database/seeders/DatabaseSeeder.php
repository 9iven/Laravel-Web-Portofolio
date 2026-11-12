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
        // 1. Akun Pengguna
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

        // 2. Kategori dengan Slug
        $categoriesData = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Mobile App', 'slug' => 'mobile-app'],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
            ['name' => 'Sistem Informasi', 'slug' => 'sistem-informasi'],
            ['name' => 'Machine Learning', 'slug' => 'machine-learning'],
        ];

        foreach ($categoriesData as $c) {
            Category::updateOrCreate(['name' => $c['name']], ['slug' => $c['slug']]);
        }

        // 3. Teknologi dengan Slug
        $technologiesData = [
            ['name' => 'Laravel 12', 'slug' => 'laravel-12'],
            ['name' => 'Tailwind CSS', 'slug' => 'tailwind-css'],
            ['name' => 'PHP 8.5', 'slug' => 'php-8-5'],
            ['name' => 'MySQL', 'slug' => 'mysql'],
            ['name' => 'Alpine.js', 'slug' => 'alpine-js'],
            ['name' => 'Flutter', 'slug' => 'flutter'],
            ['name' => 'Vue.js', 'slug' => 'vue-js'],
            ['name' => 'Python', 'slug' => 'python'],
        ];

        $techMap = [];
        foreach ($technologiesData as $t) {
            $tech = Technology::updateOrCreate(['name' => $t['name']], ['slug' => $t['slug']]);
            $techMap[$t['slug']] = $tech->id;
        }

        // 4. Skills
        if (Skill::count() === 0) {
            Skill::create(['name' => 'Backend Laravel', 'level' => 90]);
            Skill::create(['name' => 'Frontend Tailwind CSS', 'level' => 85]);
            Skill::create(['name' => 'Database MySQL & SQLite', 'level' => 80]);
            Skill::create(['name' => 'Git & GitHub Collaboration', 'level' => 85]);
            Skill::create(['name' => 'REST API Design', 'level' => 75]);
        }

        // 5. Data Proyek Lengkap (Minimal 10 published untuk melihat pagination 9/halaman)
        $catWeb = Category::where('slug', 'web-development')->first()->id;
        $catMobile = Category::where('slug', 'mobile-app')->first()->id;
        $catDesign = Category::where('slug', 'ui-ux-design')->first()->id;
        $catSI = Category::where('slug', 'sistem-informasi')->first()->id;
        $catML = Category::where('slug', 'machine-learning')->first()->id;

        $projectsData = [
            [
                'name' => 'Website Portofolio Mahasiswa TRPL',
                'slug' => 'website-portofolio-mahasiswa-trpl',
                'category_id' => $catWeb,
                'short_description' => 'Website portofolio interaktif berbasis Laravel 12 dengan Tailwind CSS dan SQLite.',
                'description' => 'Website portofolio interaktif berbasis Laravel dengan pemisahan master layout Blade, named routes, controller resource, sistem upload berkas, dan model Eloquent.',
                'status' => 'published',
                'project_date' => '2026-03-15',
                'is_featured' => true,
                'project_url' => 'https://github.com/9iven/Laravel-Web-Portofolio',
                'repository_url' => 'https://github.com/9iven/Laravel-Web-Portofolio',
                'techs' => ['laravel-12', 'tailwind-css', 'php-8-5', 'alpine-js'],
            ],
            [
                'name' => 'Sistem Informasi E-Commerce TRPL',
                'slug' => 'sistem-informasi-e-commerce-trpl',
                'category_id' => $catWeb,
                'short_description' => 'Aplikasi web toko daring dengan integrasi katalog produk dan keranjang belanja dinamis.',
                'description' => 'Aplikasi web toko daring dengan integrasi payment gateway, katalog produk lengkap, manajemen inventaris stok, serta fitur checkout aman.',
                'status' => 'published',
                'project_date' => '2026-03-20',
                'is_featured' => true,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['laravel-12', 'tailwind-css', 'mysql'],
            ],
            [
                'name' => 'Aplikasi Manajemen Inventaris Klinik Sehat',
                'slug' => 'aplikasi-manajemen-inventaris-klinik-sehat',
                'category_id' => $catSI,
                'short_description' => 'Sistem informasi terintegrasi untuk pencatatan stok obat dan rekam medis pasien.',
                'description' => 'Sistem informasi terintegrasi untuk pencatatan stok obat, resep dokter, verifikasi batch kadaluarsa, dan riwayat kunjungan pasien.',
                'status' => 'published',
                'project_date' => '2026-02-10',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['laravel-12', 'mysql', 'tailwind-css'],
            ],
            [
                'name' => 'Aplikasi Monitoring Presensi Mobile',
                'slug' => 'aplikasi-monitoring-presensi-mobile',
                'category_id' => $catMobile,
                'short_description' => 'Aplikasi mobile presensi berbasis GPS dan deteksi wajah bagi mahasiswa.',
                'description' => 'Aplikasi mobile Flutter terintegrasi dengan backend Laravel REST API untuk geofencing lokasi dan pencatatan presensi real-time.',
                'status' => 'published',
                'project_date' => '2026-01-25',
                'is_featured' => true,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['flutter', 'laravel-12'],
            ],
            [
                'name' => 'Redesain Antarmuka Portal Akademik UGM',
                'slug' => 'redesain-antarmuka-portal-akademik-ugm',
                'category_id' => $catDesign,
                'short_description' => 'Desain purwarupa antarmuka sistem informasi akademik berbasis Design Thinking.',
                'description' => 'Purwarupa UI/UX komprehensif untuk memudahkan mahasiswa melihat jadwal kuliah, mengisi KRS, serta mengakses modul perkuliahan secara intuitif.',
                'status' => 'published',
                'project_date' => '2026-02-28',
                'is_featured' => false,
                'project_url' => 'https://www.figma.com',
                'repository_url' => null,
                'techs' => ['tailwind-css'],
            ],
            [
                'name' => 'Sistem Rekomendasi Lowongan Kerja (JobTracker)',
                'slug' => 'sistem-rekomendasi-lowongan-kerja-jobtracker',
                'category_id' => $catML,
                'short_description' => 'Platform cerdas pelacakan lamaran kerja dengan pemeringkatan berbasis kecocokan keahlian.',
                'description' => 'Aplikasi web pemantau lamaran kerja yang memadukan model machine learning Python untuk analisis relevansi CV dan backend Laravel 12.',
                'status' => 'published',
                'project_date' => '2026-04-05',
                'is_featured' => true,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['python', 'laravel-12', 'vue-js'],
            ],
            [
                'name' => 'Sistem Pengelolaan Buku Perpustakaan (reCRUD)',
                'slug' => 'sistem-pengelolaan-buku-perpustakaan-recrud',
                'category_id' => $catWeb,
                'short_description' => 'Aplikasi CRUD buku dengan arsitektur MVC dan proteksi validasi sisi server.',
                'description' => 'Aplikasi pengelolaan katalog buku, peminjaman anggota, dan pencatatan denda keterlambatan dengan implementasi Post/Redirect/Get.',
                'status' => 'published',
                'project_date' => '2026-01-10',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['php-8-5', 'mysql'],
            ],
            [
                'name' => 'Dashboard Analitik Penjualan Kafe Modern',
                'slug' => 'dashboard-analitik-penjualan-kafe-modern',
                'category_id' => $catSI,
                'short_description' => 'Visualisasi data transaksi harian, stok bahan baku, dan performa menu terlaris.',
                'description' => 'Dashboard interaktif berbasis Vue.js dan Laravel untuk menyajikan grafik tren omset harian dan perhitungan profit otomatis.',
                'status' => 'published',
                'project_date' => '2026-03-01',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['vue-js', 'laravel-12', 'mysql'],
            ],
            [
                'name' => 'Aplikasi Pencatatan Keuangan Pribadi (MoneyMate)',
                'slug' => 'aplikasi-pencatatan-keuangan-pribadi-moneymate',
                'category_id' => $catMobile,
                'short_description' => 'Aplikasi pencatat pengeluaran harian dengan fitur pengingat anggaran.',
                'description' => 'Aplikasi mobile lintas platform dengan sinkronisasi offline-first untuk mengelola cashflow dan alokasi tabungan bulanan.',
                'status' => 'published',
                'project_date' => '2026-02-18',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['flutter'],
            ],
            [
                'name' => 'Sistem Manajemen Antrean Puskesmas Terpadu',
                'slug' => 'sistem-manajemen-antrean-puskesmas-terpadu',
                'category_id' => $catSI,
                'short_description' => 'Aplikasi pemanggil nomor antrean berbasis suara otomatis dan pemantau display poli.',
                'description' => 'Solusi antrean digital dengan display LED dan modul pemanggil suara terintegrasi dengan poli umum, gigi, dan loket farmasi.',
                'status' => 'published',
                'project_date' => '2026-03-28',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['laravel-12', 'alpine-js', 'mysql'],
            ],
            [
                'name' => 'Engine Pemeringkat Dokumen Berbasis TF-IDF',
                'slug' => 'engine-pemeringkat-dokumen-berbasis-tf-idf',
                'category_id' => $catML,
                'short_description' => 'Algoritma pencarian dan penentuan relevansi teks berbasis pemrosesan bahasa alami.',
                'description' => 'Implementasi algoritma Information Retrieval menggunakan Python untuk perankingan dokumen teks berbasis bobot term frequency inverse document frequency.',
                'status' => 'published',
                'project_date' => '2026-04-01',
                'is_featured' => false,
                'project_url' => 'https://github.com/9iven',
                'repository_url' => 'https://github.com/9iven',
                'techs' => ['python'],
            ],
            [
                'name' => 'Prototipe Aplikasi Reservasi Lapangan Futsal (Draft)',
                'slug' => 'prototipe-aplikasi-reservasi-lapangan-futsal-draft',
                'category_id' => $catWeb,
                'short_description' => 'Proyek eksperimental pemesanan jadwal lapangan olahraga (belum dipublikasikan).',
                'description' => 'Aplikasi reservasi jadwal sewa lapangan futsal dengan penanda waktu bentrok dan konfirmasi bukti transfer manual.',
                'status' => 'draft',
                'project_date' => '2026-04-10',
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => null,
                'techs' => ['laravel-12', 'tailwind-css'],
            ],
            [
                'name' => 'Riset Algoritma Deteksi Anomali Jaringan (Draft)',
                'slug' => 'riset-algoritma-deteksi-anomali-jaringan-draft',
                'category_id' => $catML,
                'short_description' => 'Eksperimen pendeteksian paket janggal pada jaringan lokal menggunakan autoencoder.',
                'description' => 'Eksperimen keamanan siber untuk memfilter traffic DDoS dan port scanning pada lingkungan virtual.',
                'status' => 'draft',
                'project_date' => '2026-04-12',
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => null,
                'techs' => ['python'],
            ],
        ];

        foreach ($projectsData as $pData) {
            $techs = $pData['techs'];
            unset($pData['techs']);

            $catModel = Category::find($pData['category_id']);
            $pData['kategori'] = $catModel?->name ?? 'Web Development';
            $pData['teknologi'] = implode(', ', $techs);

            $project = Project::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );

            // Sync ke pivot project_technology
            $idsToSync = [];
            foreach ($techs as $tSlug) {
                if (isset($techMap[$tSlug])) {
                    $idsToSync[] = $techMap[$tSlug];
                }
            }
            $project->technologies()->sync($idsToSync);
        }

        if (class_exists(PostTableSeeder::class)) {
            $this->call([
                PostTableSeeder::class,
            ]);
        }
    }
}
