# Pedoman Khusus Praktikum PPWL (Portofolio Website)

## 1. Kebijakan Pengujian (Testing)
- JANGAN membuat berkas pengujian otomatis Pest (`tests/Feature/...`) dan JANGAN menjalankan suite pengujian Pest (`php artisan test` atau `vendor/bin/pest`) kecuali secara eksplisit diminta oleh mahasiswa.
- Verifikasi keberhasilan fitur difokuskan pada:
  - Keberhasilan eksekusi migrasi database (`php artisan migrate:status`).
  - Ketepatan query, relasi model Eloquent, dan alur rute HTTP.
  - Verifikasi tampilan dan fungsionalitas langsung di browser/antarmuka web.

## 2. Sinkronisasi Footer Pertemuan
- Setiap pengerjaan tugas atau materi pertemuan baru, WAJIB memperbarui berkas `resources/views/partials/footer.blade.php`.
- Sesuaikan label teks footer agar mencerminkan pertemuan yang sedang dikerjakan (misalnya: `&copy; {{ date('Y') }} Given Gerald Chandra &bull; PPWL Pertemuan 11`).
