<?php

namespace App\Imports;

use App\Models\InventarisBarang;
use App\Models\ProfilSekolah;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class InventarisExcelImporter
{
    /**
     * Memproses impor file Excel (.xlsx, .xls, .csv).
     *
     * @param UploadedFile|string $file
     * @param bool $truncateOld Kosongkan database sebelum impor jika true
     * @return array Ringkasan hasil impor
     */
    public function import($file, bool $truncateOld = false): array
    {
        $filePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        if (!file_exists($filePath)) {
            throw new Exception("File Excel tidak ditemukan.");
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Ekstrak Header / Identitas Sekolah (biasanya baris 1 s/d 10)
        $namaSekolah = null;
        $tahunSekolah = null;
        $sumberSekolah = null;

        for ($r = 1; $r <= 10; $r++) {
            $c1 = trim((string)$sheet->getCell([1, $r])->getValue());
            $c3 = trim((string)$sheet->getCell([3, $r])->getValue());
            $c2 = trim((string)$sheet->getCell([2, $r])->getValue());

            $val = !empty($c3) ? $c3 : $c2;
            $val = ltrim($val, ': ');

            if (stripos($c1, 'Nama Sekolah') !== false && !empty($val)) {
                $namaSekolah = $val;
            } elseif (stripos($c1, 'Tahun Anggaran') !== false && !empty($val)) {
                $tahunSekolah = (int) preg_replace('/[^0-9]/', '', $val);
            } elseif (stripos($c1, 'Sumber Dana') !== false && !empty($val)) {
                $sumberSekolah = $val;
            }
        }

        // Simpan / update profil sekolah jika ditemukan nama sekolah
        if ($namaSekolah) {
            $profil = ProfilSekolah::firstOrNew(['id' => 1]);
            $profil->nama_sekolah = $namaSekolah;
            if ($tahunSekolah) {
                $profil->tahun_anggaran = $tahunSekolah;
            }
            if ($sumberSekolah) {
                $profil->sumber_dana = $sumberSekolah;
            }
            $profil->save();
        }

        // 2. Deteksi Baris Header Kolom Tabel (biasanya di baris 1 s/d 15)
        $headerRow = 0;
        for ($r = 1; $r <= 15; $r++) {
            $c1 = strtolower(trim((string)$sheet->getCell([1, $r])->getValue()));
            $c2 = strtolower(trim((string)$sheet->getCell([2, $r])->getValue()));
            $c5 = strtolower(trim((string)$sheet->getCell([5, $r])->getValue()));

            if (str_contains($c2, 'kode') || str_contains($c1, 'kode') || str_contains($c5, 'nama barang')) {
                $headerRow = $r;
                break;
            }
        }

        if ($headerRow === 0) {
            $headerRow = 1; // Default baris 1 jika tabel langsung dimulai dari header
        }

        // 3. Persiapan Database
        if ($truncateOld) {
            InventarisBarang::truncate();
        }

        $importedCount = 0;
        $totalNilai = 0;

        DB::beginTransaction();
        try {
            $highestRow = $sheet->getHighestRow();

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $c1 = trim((string)$sheet->getCell([1, $r])->getValue());
                $c2 = trim((string)$sheet->getCell([2, $r])->getValue());
                $c5 = trim((string)$sheet->getCell([5, $r])->getValue());

                // Lewati atau hentikan jika baris TOTAL / REKAP
                if (stripos($c1, 'TOTAL') !== false || stripos($c2, 'TOTAL') !== false || stripos($c5, 'TOTAL') !== false) {
                    break;
                }

                // Bersihkan kode dan nama barang dari newline
                $kodeBarang = trim(str_replace(["\r\n", "\r", "\n"], '', $c2));
                $namaBarang = trim(str_replace(["\r\n", "\r", "\n"], ' ', $c5));

                // Jika kode dan nama kosong, lewati baris ini
                if (empty($kodeBarang) && empty($namaBarang)) {
                    continue;
                }

                // Ambil nilai setiap kolom
                $tglPerolehan = $this->parseDate($sheet->getCell([3, $r])->getValue());
                $tglPencatatan = $this->parseDate($sheet->getCell([4, $r])->getValue());
                $merk = trim((string)$sheet->getCell([6, $r])->getValue()) ?: null;
                $kategori = trim((string)$sheet->getCell([7, $r])->getValue()) ?: 'Peralatan';
                
                $jumlahVal = $sheet->getCell([8, $r])->getValue();
                $jumlah = is_numeric($jumlahVal) ? (int)$jumlahVal : 1;
                
                $satuan = trim((string)$sheet->getCell([9, $r])->getValue()) ?: 'Buah';
                $hargaSatuan = $this->parseNumber($sheet->getCell([10, $r])->getValue());
                
                // Ambil nilai perolehan (support formula)
                $nilaiCalculated = $this->parseNumber($sheet->getCell([11, $r])->getCalculatedValue());
                if ($nilaiCalculated <= 0 && $jumlah > 0 && $hargaSatuan > 0) {
                    $nilaiCalculated = $jumlah * $hargaSatuan;
                }

                $noBast = trim((string)$sheet->getCell([12, $r])->getValue()) ?: null;
                $sumberDana = trim((string)$sheet->getCell([13, $r])->getValue()) ?: ($sumberSekolah ?: 'BOSP Reguler');
                
                $tahunVal = $sheet->getCell([14, $r])->getValue();
                $tahunAnggaran = is_numeric($tahunVal) ? (int)$tahunVal : ($tahunSekolah ?: 2026);
                
                $lokasiRuang = trim((string)$sheet->getCell([15, $r])->getValue()) ?: 'Ruang Inventaris';
                $kondisi = trim((string)$sheet->getCell([16, $r])->getValue()) ?: 'Baik';
                if (!in_array($kondisi, ['Baik', 'Rusak Ringan', 'Rusak Berat'])) {
                    $kondisi = 'Baik';
                }

                $pj = trim((string)$sheet->getCell([17, $r])->getValue()) ?: 'Pengurus Barang';
                $nomorRegister = trim((string)$sheet->getCell([18, $r])->getValue()) ?: null;
                $keterangan = trim((string)$sheet->getCell([19, $r])->getValue()) ?: null;
                $tautan = trim((string)$sheet->getCell([20, $r])->getValue()) ?: null;

                // Simpan ke database
                InventarisBarang::create([
                    'kode_barang'        => $kodeBarang ?: ('INV-' . str_pad($importedCount + 1, 4, '0', STR_PAD_LEFT)),
                    'tanggal_perolehan'  => $tglPerolehan ?: now()->toDateString(),
                    'tanggal_pencatatan' => $tglPencatatan ?: $tglPerolehan,
                    'nama_barang'        => $namaBarang ?: 'Barang Inventaris',
                    'merk_spesifikasi'   => $merk,
                    'kategori'           => $kategori,
                    'jumlah'             => $jumlah,
                    'satuan'             => $satuan,
                    'harga_satuan'       => $hargaSatuan,
                    'nilai_perolehan'    => $nilaiCalculated,
                    'no_bast'            => $noBast,
                    'sumber_dana'        => $sumberDana,
                    'tahun_anggaran'     => $tahunAnggaran,
                    'lokasi_ruang'       => $lokasiRuang,
                    'kondisi'            => $kondisi,
                    'penanggung_jawab'   => $pj,
                    'nomor_register'     => $nomorRegister,
                    'keterangan'         => $keterangan,
                    'tautan_dokumen'     => $tautan,
                ]);

                $importedCount++;
                $totalNilai += $nilaiCalculated;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'count'        => $importedCount,
            'school_name'  => $namaSekolah,
            'total_nilai'  => $totalNilai,
        ];
    }

    /**
     * Parsing tanggal dari format serial Excel atau string teks.
     */
    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (Exception $e) {}
        }

        try {
            return Carbon::parse(trim($value))->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Parsing angka dari format mata uang, desimal, dan string.
     */
    protected function parseNumber($value): float
    {
        if (is_numeric($value)) {
            return (float)$value;
        }

        if (empty($value)) {
            return 0.0;
        }

        // Hapus simbol mata uang dan karakter non-numerik selain koma/titik
        $cleaned = str_replace(['Rp', 'rp', ' ', '.'], '', (string)$value);
        $cleaned = str_replace(',', '.', $cleaned);

        return is_numeric($cleaned) ? (float)$cleaned : 0.0;
    }
}
