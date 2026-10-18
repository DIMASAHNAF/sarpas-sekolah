# 🏢 Sistem Informasi Manajemen Sarana & Prasarana (SARPRAS)
### SMKN 1 Beringin — Digital Asset & Inventory Management System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg?style=for-the-badge)](https://github.com/DIMASAHNAF/sarpas-sekolah)

Aplikasi web modern berbasis **Laravel 11** yang dirancang khusus untuk mengelola, melacak, dan mengaudit seluruh aset serta sarana & prasarana di lingkungan **SMKN 1 Beringin** secara transparan, akuntabel, dan terstruktur.

---

## 🌟 Fitur Utama

### 📦 1. Manajemen Inventaris & Aset
- **Katalog Lengkap**: Pencatatan kode barang, nama, kategori, sumber dana, kondisi fisik (Baik/Rusak Ringan/Rusak Berat), dan penempatan lokasi/ruangan.
- **Bulk Import & Template Excel**: Kemudahan input data aset ratusan item sekaligus menggunakan file Excel beserta template yang dapat diunduh langsung.
- **Ekspor Dokumen Resmi**: Cetak rekapitulasi inventaris ke format **PDF (Letter/A4)** dan **Excel (.xlsx)** lengkap dengan kop surat dan lembar pengesahan dinamis.

### 📝 2. Modul Pengajuan & Usulan Barang (Procurement)
- Alur pengajuan pengadaan barang/sarpras baru oleh Kaprodi / Staf Unit Kerja.
- Cetak lembar disposisi dan form cetak pengajuan barang siap tanda tangan.
- Ekspor rekapitulasi usulan barang ke file Excel.

### 📜 3. Berita Acara Serah Terima (BAST)
- Pengarsipan dan penerbitan dokumen resmi Berita Acara Serah Terima untuk mutasi atau penerimaan aset baru sekolah.

### 🔐 4. Hak Akses Bertingkat (RBAC)
- **Administrator (Waka Sarpras / Admin)**: Akses penuh untuk manajemen aset, hapus massal, ekspor laporan, pengaturan profil sekolah, dan verifikasi pengajuan.
- **Kaprodi / User / Staf**: Input data barang, mengajukan usulan sarana baru, serta monitoring ketersediaan sarana laboratorium/jurusan.

### 🏫 5. Pengaturan Profil Sekolah & Lembar Pengesahan
- Konfigurasi terpusat nama kepala sekolah, NIP, penanggung jawab sarpras, kop surat, dan informasi institusi secara dinamis.

---

## 🛠️ Tech Stack & Dependencies

- **Framework**: [Laravel 11](https://laravel.com/)
- **Bahasa**: [PHP 8.2+](https://php.net/)
- **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Spreadsheet Processor**: [maatwebsite/excel](https://github.com/SpartnerNL/Laravel-Excel)
- **Frontend / Styling**: Blade Templating, Responsive UI Dashboard
- **Database**: MySQL / MariaDB

---

## 🚀 Panduan Instalasi Lokal

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/DIMASAHNAF/sarpas-sekolah.git
   cd sarpas-sekolah
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env`.*

4. **Migrasi Database**:
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Buka di browser: `http://localhost:8000`

---

## 👨‍💻 Developer & Author

Dikembangkan dan dikelola oleh:
- **Nama**: Dimas Ahnaf
- **GitHub**: [@DIMASAHNAF](https://github.com/DIMASAHNAF)
- **Institusi**: SMKN 1 Beringin

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah lisensi [MIT](LICENSE).
