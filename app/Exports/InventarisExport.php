<?php

namespace App\Exports;

use App\Models\InventarisBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $collection;
    protected int $rowNumber = 0;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * Urutan baris data Excel dari Kode Barang hingga Keterangan
     */
    public function map($item): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $item->kode_barang,
            $item->tanggal_perolehan ? $item->tanggal_perolehan->format('d/m/Y') : '-',
            $item->tanggal_pencatatan ? $item->tanggal_pencatatan->format('d/m/Y') : '-',
            $item->nama_barang,
            $item->merk_spesifikasi,
            $item->kategori,
            $item->jumlah,
            $item->satuan,
            $item->harga_satuan,
            $item->nilai_perolehan,
            $item->no_bast,
            $item->sumber_dana,
            $item->tahun_anggaran,
            $item->lokasi_ruang,
            $item->kondisi,
            $item->penanggung_jawab,
            $item->nomor_register,
            $item->keterangan ?? '-',
        ];
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'NO',
            'KODE BARANG',
            'TANGGAL PEROLEHAN',
            'TANGGAL PENCATATAN',
            'NAMA BARANG',
            'MERK / SPESIFIKASI',
            'KATEGORI',
            'JUMLAH',
            'SATUAN',
            'HARGA SATUAN (RP)',
            'NILAI PEROLEHAN (RP)',
            'NO. BAST',
            'SUMBER DANA',
            'TAHUN ANGGARAN',
            'LOKASI / RUANG',
            'KONDISI',
            'PENANGGUNG JAWAB',
            'NOMOR REGISTER',
            'KETERANGAN',
        ];
    }

    /**
     * Styling Spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'], // Indigo/Navy
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
