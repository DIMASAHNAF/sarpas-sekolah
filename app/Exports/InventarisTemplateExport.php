<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarisTemplateExport implements FromArray, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['BUKU INVENTARIS BARANG (BIB)'],
            ['PEMBELANJAAN BARANG MODAL / ASET – SUMBER DANA BOS'],
            [''],
            ['Nama Sekolah', '', ': SMKN 1 Beringin'],
            ['NPSN', '', ': 10101001'],
            ['Alamat Sekolah', '', ': Jl. Pendidikan No. 1'],
            ['Tahun Anggaran', '', ': 2026'],
            ['Sumber Dana', '', ': BOSP Reguler'],
            [''],
            [''],
            [
                'No',
                'Kode Barang',
                'Tanggal Perolehan',
                'Tanggal Pencatatan',
                'Nama Barang/Aset',
                'Merk/Spesifikasi',
                'Kategori',
                'Jumlah',
                'Satuan',
                'Harga Satuan (Rp)',
                'Nilai Perolehan (Rp)',
                'No. BAST',
                'Sumber Dana',
                'Tahun Anggaran',
                'Lokasi/Ruang',
                'Kondisi',
                'Penanggung Jawab',
                'Nomor Register/Label',
                'Keterangan',
                'Tautan/Referensi Dokumen'
            ],
            [
                1,
                '1.3.05.01.01.0001.00005-1-520501010001',
                '16-03-2026',
                '02-03-2026',
                'BUKU PERPUSTAKAAN',
                'TKA Bahasa Indonesia XII',
                'Buku',
                400,
                'Buah',
                119000,
                '=H12*J12',
                '400.3.13.2/002/SMKN.01/III/2026',
                'BOSP Reguler',
                2026,
                'Perpustakaan',
                'Baik',
                'Kepala Perpustakaan',
                'REG-001',
                'Pengadaan koleksi buku perpustakaan',
                ''
            ],
            [
                2,
                '1.3.02.10.02.0005.00027-1-520210020005',
                '20-04-2026',
                '22-04-2026',
                'Printer Epson L3211',
                'Epson L3211 All In One Ink Tank',
                'Peralatan',
                1,
                'Buah',
                4200000,
                '=H13*J13',
                '400.3.13.2/008/SMKN.01/IV/2026',
                'BOSP Reguler',
                2026,
                'Ruang Waka Kesiswaan',
                'Baik',
                'Pengurus Barang',
                'REG-002',
                'Printer operasional kesiswaan',
                ''
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
            8 => ['font' => ['bold' => true]],
            11 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
