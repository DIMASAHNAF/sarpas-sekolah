-- ========================================================
-- Export Database MySQL: sarpas_sekolah
-- Aplikasi Sarana & Prasarana Sekolah (Buku Inventaris Barang - Dana BOS)
-- Profil Sekolah: SMKN 1 Beringin (Tahun Anggaran 2026 - BOSP Reguler)
-- Total Data: 28 Item Barang Inventaris (Total Rp 597.238.700)
-- Generated for phpMyAdmin / MySQL / MariaDB
-- ========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sarpas_sekolah`
--
CREATE DATABASE IF NOT EXISTS `sarpas_sekolah` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sarpas_sekolah`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_inventaris_barangs_table', 1),
(5, '2024_01_01_000004_create_profil_sekolahs_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Sarpras', 'admin@sarpas.sch.id', '2026-09-26 12:54:58', '$2y$12$e6mZt/J.iZz6m7qHlRkm0uS.HqC5Y9gXGkI2f7Hw2l2o2F9A9sW.a', NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_sekolahs`
--

DROP TABLE IF EXISTS `profil_sekolahs`;
CREATE TABLE `profil_sekolahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(255) NOT NULL,
  `npsn` varchar(20) NOT NULL,
  `tahun_anggaran` year(4) NOT NULL,
  `sumber_dana` varchar(255) NOT NULL DEFAULT 'BOSP Reguler',
  `nama_kepsek` varchar(255) NOT NULL,
  `nip_kepsek` varchar(30) NOT NULL,
  `nama_waka_sarpras` varchar(255) NOT NULL,
  `nip_waka_sarpras` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profil_sekolahs`
--

INSERT INTO `profil_sekolahs` (`id`, `nama_sekolah`, `npsn`, `tahun_anggaran`, `sumber_dana`, `nama_kepsek`, `nip_kepsek`, `nama_waka_sarpras`, `nip_waka_sarpras`, `created_at`, `updated_at`) VALUES
(1, 'SMKN 1 Beringin', '10101001', 2026, 'BOSP Reguler', 'Drs. H. Mulyadi, M.Pd.', '19680512 199303 1 004', 'Ahmad Rifa\'i, S.T., M.Kom.', '19790418 200501 1 008', '2026-09-26 12:54:58', '2026-09-26 12:54:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris_barangs`
--

DROP TABLE IF EXISTS `inventaris_barangs`;
CREATE TABLE `inventaris_barangs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(255) NOT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `tanggal_pencatatan` date DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merk_spesifikasi` text DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `satuan` varchar(50) NOT NULL DEFAULT 'Buah',
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `nilai_perolehan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `no_bast` varchar(255) DEFAULT NULL,
  `sumber_dana` varchar(255) NOT NULL DEFAULT 'BOSP Reguler',
  `tahun_anggaran` year(4) DEFAULT NULL,
  `lokasi_ruang` varchar(255) DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL DEFAULT 'Baik',
  `penanggung_jawab` varchar(255) DEFAULT NULL,
  `nomor_register` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tautan_dokumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaris_barangs_kode_barang_index` (`kode_barang`),
  KEY `inventaris_barangs_nama_barang_index` (`nama_barang`),
  KEY `inventaris_barangs_kategori_index` (`kategori`),
  KEY `inventaris_barangs_tahun_anggaran_index` (`tahun_anggaran`),
  KEY `inventaris_barangs_lokasi_ruang_index` (`lokasi_ruang`),
  KEY `inventaris_barangs_kondisi_index` (`kondisi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `inventaris_barangs`
--
INSERT INTO `inventaris_barangs` (`id`, `kode_barang`, `tanggal_perolehan`, `tanggal_pencatatan`, `nama_barang`, `merk_spesifikasi`, `kategori`, `jumlah`, `satuan`, `harga_satuan`, `nilai_perolehan`, `no_bast`, `sumber_dana`, `tahun_anggaran`, `lokasi_ruang`, `kondisi`, `penanggung_jawab`, `nomor_register`, `keterangan`, `tautan_dokumen`, `created_at`, `updated_at`) VALUES
(1, '1.3.05.01.01.0001.00005-1-520501010001', '2026-03-16', '2026-03-02', 'BUKU PERPUSTAKAAN-BUKU PERPUSTAKAAN', 'TKA Bahasa Indonesia XII', 'Buku', 400, 'Buah', 119000.00, 47600000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(2, '1.3.05.01.01.0001.00005-1-520501010001', '2026-03-16', '2026-03-02', 'BUKU PERPUSTAKAAN-BUKU PERPUSTAKAAN', 'TKA Bahasa Inggris XII', 'Buku', 400, 'Buah', 119000.00, 47600000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(3, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'TKA Matematika XII', 'Buku', 400, 'Buah', 119000.00, 47600000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(4, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'TKA KIK XII', 'Buku', 400, 'Buah', 119000.00, 47600000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(5, '1.3.05.01.01.0001.00005-1-520501010001', '2026-03-16', '2026-03-02', 'BUKU PERPUSTAKAAN-BUKU PERPUSTAKAAN', 'TKA Bahasa Indonesia XII', 'Buku', 63, 'Buah', 119000.00, 7497000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(6, '1.3.05.01.01.0001.00005-1-520501010001', '2026-03-16', '2026-03-02', 'BUKU PERPUSTAKAAN-BUKU PERPUSTAKAAN', 'TKA Bahasa Inggris XII', 'Buku', 63, 'Buah', 119000.00, 7497000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(7, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'TKA Matematika XII', 'Buku', 63, 'Buah', 119000.00, 7497000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(8, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'TKA KIK XII', 'Buku', 63, 'Buah', 119000.00, 7497000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(9, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'Koding XII', 'Buku', 354, 'Buah', 95000.00, 33630000.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(10, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'Koding X', 'Buku', 394, 'Buah', 78200.00, 30810800.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(11, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-02', 'Buku Perpustakaan-Buku Perpustakaan', 'Koding XII', 'Buku', 367, 'Buah', 79700.00, 29249900.00, '400.3.13.2/002/SMKN.01/III/2026', 'BOSP Reguler', 2026, 'Perpustakaan', 'Baik', 'Kepala Perpustakaan', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(12, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-16', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 82, 'Buah', 230000.00, 18860000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(13, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-16', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 210, 'Buah', 230000.00, 48300000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(14, '1.3.05.01.01.0001.00006-1-520501010001', '2026-03-16', '2026-03-16', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 210, 'Buah', 230000.00, 48300000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(15, '1.3.02.05.02.0005.00092-1-520205020005', '2026-04-20', '2026-04-20', 'Troli tiga tingkat Krisbow-', NULL, 'Peralatan', 1, 'Buah', 1000000.00, 1000000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(16, '1.3.05.01.01.0001.00006-1-520501010001', '2026-04-20', '2026-04-20', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 216, 'Buah', 176000.00, 38016000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(17, '1.3.05.01.01.0001.00006-1-520501010001', '2026-04-20', '2026-04-20', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 251, 'Buah', 112000.00, 28112000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(18, '1.3.05.01.01.0001.00006-1-520501010001', '2026-04-20', '2026-04-20', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 251, 'Buah', 112000.00, 28112000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(19, '1.3.05.01.01.0001.00006-1-520501010001', '2026-04-20', '2026-04-20', 'Buku Perpustakaan-Buku Perpustakaan', NULL, 'Buku', 200, 'Buah', 88000.00, 17600000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(20, '1.1.12.01.05.0001.00396-1-520205020005', '2026-04-20', '2026-04-22', 'Mixer--', 'Cosmos', 'Peralatan Kuliner', 2, 'Buah', 3500000.00, 7000000.00, '400.3.13.2/011/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Lab Kuliner', 'Baik', 'Kaprog Kuliner', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(21, '1.3.02.08.03.0016.00040-1-520208030016', '2026-04-20', '2026-04-22', 'Mesin Jahit High Speed-Konfigurasi Minimal :Kecepatan Hingga 5000 Rpm, Motor Penggerak Langsung, Pemangkas Benang Otomatis, Pengangkat Kaki Presser Otomatis, Termasuk Meja/Dudukan.', 'Typical', 'Peralatan Busana', 3, 'Buah', 4300000.00, 12900000.00, '400.3.13.2/010/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Lab Busana', 'Baik', 'Kaprog Busana', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(22, '1.3.02.10.02.0004.00047-1-520210020004', '2026-04-20', '2026-04-22', 'Splicer--', 'Signal Al-6A+Fusion (No Elektroda)', 'Peralatan TKJ', 1, 'Buah', 9000000.00, 9000000.00, '400.3.13.2/012/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Lab TKJ', 'Baik', 'Kaprog TKJ', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(23, '1.3.02.10.02.0004.00058-1-520210020004', '2026-04-20', '2026-04-22', 'Optical Time Domain Reflectometer-Joinwit MINI OTDR JW3302S', 'JOINWT JW3302SJ', 'Peralatan TKJ', 1, 'Buah', 4000000.00, 4000000.00, '400.3.13.2/012/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Lab TKJ', 'Baik', 'Kaprog TKJ', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(24, '1.3.02.10.02.0005.00027-1-520210020005', '2026-04-20', '2026-04-22', 'Printer--', 'Epson L3211', 'Peralatan', 1, 'Buah', 4200000.00, 4200000.00, '400.3.13.2/008/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Ruang Waka Kesiswaan', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(25, '1.3.02.10.02.0005.00027-1-520210020005', '2026-04-20', '2026-04-22', 'Printer--', 'Epson L3211', 'Peralatan UPW', 1, 'Buah', 4200000.00, 4200000.00, '400.3.13.2/008/SMKN.01/IV/2026', 'BOSP Reguler', 2026, 'Lab UPW', 'Baik', 'Kaprog UPW', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(26, '1.3.02.10.02.0005.00027-1-520210020005', '2026-04-20', '2026-04-20', 'Printer--', 'Epson L3211', 'Peralatan', 1, 'Buah', 4200000.00, 4200000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(27, '1.3.02.10.02.0005.00027-1-520210020005', '2026-04-20', '2026-04-20', 'Printer--', 'Epson L3211', 'Peralatan', 1, 'Buah', 4200000.00, 4200000.00, NULL, 'BOSP Reguler', 2026, 'Ruang Inventaris', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58'),
(28, '1.3.02.05.02.0004.00006-1-520205020004', '2026-05-11', '2026-09-04', 'AC Split-1/2 PK', 'Sharp', 'Peralatan', 1, 'Buah', 5160000.00, 5160000.00, '400.3.13.2/020/SMKN.01/IX/2026', 'BOSP Reguler', 2026, 'Ruang Kepala Sekolah', 'Baik', 'Pengurus Barang', NULL, NULL, NULL, '2026-09-26 12:54:58', '2026-09-26 12:54:58');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
