<?php

namespace Database\Seeders;

use App\Models\InventarisBarang;
use App\Models\ProfilSekolah;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Inisialisasi Profil Sekolah (Single Row Config)
        ProfilSekolah::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah'      => 'SMK Negeri 1 Pembangunan',
                'npsn'              => '20108920',
                'tahun_anggaran'    => 2024,
                'sumber_dana'       => 'Dana BOS Reguler',
                'nama_kepsek'       => 'Drs. H. Mulyadi, M.Pd.',
                'nip_kepsek'        => '19680512 199303 1 004',
                'nama_waka_sarpras' => 'Ahmad Rifa\'i, S.T., M.Kom.',
                'nip_waka_sarpras'  => '19790418 200501 1 008',
            ]
        );

        // 2. Data Dummy Awal Inventaris Barang (Dana BOS)
        $items = [
            [
                'kode_barang'        => 'INV-BOS-2024-001',
                'tanggal_perolehan'  => '2024-02-10',
                'tanggal_pencatatan' => '2024-02-12',
                'nama_barang'        => 'Laptop Asus ExpertBook Core i5',
                'merk_spesifikasi'   => 'Intel Core i5-1235U, RAM 16GB, SSD 512GB NVMe, Windows 11 Pro',
                'kategori'           => 'Elektronik & TIK',
                'jumlah'             => 10,
                'satuan'             => 'Unit',
                'harga_satuan'       => 12500000.00,
                'nilai_perolehan'    => 125000000.00,
                'no_bast'            => '021/BAST/BOS/SMKN1/II/2024',
                'sumber_dana'        => 'Dana BOS',
                'tahun_anggaran'     => 2024,
                'lokasi_ruang'       => 'Laboratorium Komputer 1',
                'kondisi'            => 'Baik',
                'penanggung_jawab'   => 'Fajar Nugraha, S.Kom.',
                'nomor_register'     => 'REG-TIK-001-010',
                'keterangan'         => 'Pengadaan unit pembelajaran produktif RPL',
                'tautan_dokumen'     => 'https://drive.google.com/sample-bast-01',
            ],
            [
                'kode_barang'        => 'INV-BOS-2024-002',
                'tanggal_perolehan'  => '2024-03-05',
                'tanggal_pencatatan' => '2024-03-06',
                'nama_barang'        => 'Proyektor Epson EB-E500 3300 Lumens',
                'merk_spesifikasi'   => '3300 ANSI Lumens, XGA resolution, HDMI/VGA Port',
                'kategori'           => 'Media Pembelajaran',
                'jumlah'             => 5,
                'satuan'             => 'Unit',
                'harga_satuan'       => 5400000.00,
                'nilai_perolehan'    => 27000000.00,
                'no_bast'            => '035/BAST/BOS/SMKN1/III/2024',
                'sumber_dana'        => 'Dana BOS',
                'tahun_anggaran'     => 2024,
                'lokasi_ruang'       => 'Ruang Kelas X RPL 1',
                'kondisi'            => 'Baik',
                'penanggung_jawab'   => 'Siti Nurhaliza, S.Pd.',
                'nomor_register'     => 'REG-PRJ-001-005',
                'keterangan'         => 'Terpasang di bracket gantung plafon',
                'tautan_dokumen'     => null,
            ],
            [
                'kode_barang'        => 'INV-BOS-2023-015',
                'tanggal_perolehan'  => '2023-08-14',
                'tanggal_pencatatan' => '2023-08-15',
                'nama_barang'        => 'Printer Epson L3210 All-in-One',
                'merk_spesifikasi'   => 'Print, Scan, Copy - Ink Tank System',
                'kategori'           => 'Elektronik & Perkantoran',
                'jumlah'             => 3,
                'satuan'             => 'Unit',
                'harga_satuan'       => 2450000.00,
                'nilai_perolehan'    => 7350000.00,
                'no_bast'            => '112/BAST/BOS/SMKN1/VIII/2023',
                'sumber_dana'        => 'Dana BOS',
                'tahun_anggaran'     => 2023,
                'lokasi_ruang'       => 'Ruang Tata Usaha',
                'kondisi'            => 'Rusak Ringan',
                'penanggung_jawab'   => 'Hendra Wijaya, A.Md.',
                'nomor_register'     => 'REG-PRN-012-014',
                'keterangan'         => 'Butuh pembersihan head cetak pada 1 unit',
                'tautan_dokumen'     => null,
            ],
            [
                'kode_barang'        => 'INV-BOS-2024-004',
                'tanggal_perolehan'  => '2024-05-18',
                'tanggal_pencatatan' => '2024-05-20',
                'nama_barang'        => 'Meja & Kursi Siswa Set Ergonomis',
                'merk_spesifikasi'   => 'Rangka besi holo anti karat, top table multiplex HPL finishing',
                'kategori'           => 'Mebel & Perabot',
                'jumlah'             => 36,
                'satuan'             => 'Set',
                'harga_satuan'       => 650000.00,
                'nilai_perolehan'    => 23400000.00,
                'no_bast'            => '077/BAST/BOS/SMKN1/V/2024',
                'sumber_dana'        => 'Dana BOS',
                'tahun_anggaran'     => 2024,
                'lokasi_ruang'       => 'Ruang Kelas XI TKJ 2',
                'kondisi'            => 'Baik',
                'penanggung_jawab'   => 'Ratna Dewi, S.Pd.',
                'nomor_register'     => 'REG-MBL-001-036',
                'keterangan'         => 'Penggantian meja kayu lama yang lapuk',
                'tautan_dokumen'     => null,
            ],
            [
                'kode_barang'        => 'INV-BOS-2023-009',
                'tanggal_perolehan'  => '2023-04-10',
                'tanggal_pencatatan' => '2023-04-12',
                'nama_barang'        => 'AC Split Daikin 1.5 PK Standard Thailand',
                'merk_spesifikasi'   => 'Daikin FTC35NV14 1.5 PK R32 Non-Inverter',
                'kategori'           => 'Elektronik & Pendingin',
                'jumlah'             => 2,
                'satuan'             => 'Unit',
                'harga_satuan'       => 5800000.00,
                'nilai_perolehan'    => 11600000.00,
                'no_bast'            => '042/BAST/BOS/SMKN1/IV/2023',
                'sumber_dana'        => 'Dana BOS',
                'tahun_anggaran'     => 2023,
                'lokasi_ruang'       => 'Laboratorium Komputer 1',
                'kondisi'            => 'Baik',
                'penanggung_jawab'   => 'Fajar Nugraha, S.Kom.',
                'nomor_register'     => 'REG-AC-001-002',
                'keterangan'         => 'Rutin service berkala 3 bulan sekali',
                'tautan_dokumen'     => null,
            ],
        ];

        foreach ($items as $item) {
            InventarisBarang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
        }
    }
}
