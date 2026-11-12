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

## 3. Pemisahan Berkas Dokumentasi Non-Kode
- DILARANG menaruh berkas dokumen yang tidak berhubungan langsung dengan kode aplikasi (seperti berkas panduan presentasi, rangkuman materi, modul, berkas .docx, .pdf, atau catatan .md) di dalam folder repositori `portofolio-website`.
- Tempatkan seluruh berkas dokumen pendukung, panduan, atau laporan praktikum di folder modul pertemuan yang sesuai (misalnya di `c:\Users\Given\Downloads\2026UGMS3\ppwl11` untuk Pertemuan 11, `ppwl12` untuk Pertemuan 12, dan seterusnya).
- Repositori `portofolio-website` WAJIB dijaga tetap bersih murni hanya untuk berkas source code proyek Laravel.

## 4. Kebijakan Tangkapan Layar Terminal untuk Laporan Praktikum
- DILARANG men-generate atau membuat gambar terminal tiruan/sintetis secara otomatis (misalnya menggunakan Python/Pillow).
- Seluruh tangkapan layar terminal diambil secara manual oleh mahasiswa dari terminal aslinya.
- Asisten WAJIB menyediakan panduan langkah demi langkah yang jelas, meliputi:
  1. Perintah CLI persis yang harus dijalankan.
  2. Direktori kerja tempat perintah harus dieksekusi.
  3. Bagian output layar yang harus terlihat jelas pada tangkapan layar.
  4. Nama berkas dan lokasi penyimpanan target di folder `pics/` (misalnya `pics/00_terminal_migrate_seed.png`).
