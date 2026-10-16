<?php

namespace App\Http\Controllers;

use App\Models\ItemPengajuanBarang;
use App\Models\PengajuanBarang;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PengajuanBarangController extends Controller
{
    /**
     * Tampilkan daftar riwayat pengajuan barang.
     */
    public function index(Request $request)
    {
        $query = PengajuanBarang::with(['items', 'user'])->latest();

        // Filter berdasarkan pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('program_keahlian', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                  ->orWhere('laboratorium_bengkel', 'like', "%{$search}%");
            });
        }

        // Filter jenis pengajuan
        if ($jenis = $request->input('jenis_pengajuan')) {
            $query->where('jenis_pengajuan', $jenis);
        }

        // Filter tahun anggaran
        if ($tahun = $request->input('tahun_anggaran')) {
            $query->where('tahun_anggaran', $tahun);
        }

        // Filter program keahlian / jurusan
        if ($jurusan = $request->input('program_keahlian')) {
            $query->where('program_keahlian', $jurusan);
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pengajuanList = $query->paginate(10)->withQueryString();

        // Ringkasan Statistik
        $totalPengajuan = PengajuanBarang::count();
        $totalBiaya = PengajuanBarang::sum('total_biaya');
        $totalAlatPraktikum = PengajuanBarang::where('jenis_pengajuan', 'ALAT PRAKTIKUM')->count();
        $totalBHP = PengajuanBarang::where('jenis_pengajuan', 'BARANG HABIS PAKAI')->count();

        // Daftar tahun untuk filter
        $availableYears = PengajuanBarang::select('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'desc')
            ->pluck('tahun_anggaran')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [2026, 2025];
        }

        $jurusanList = PengajuanBarang::getJurusanList();

        return view('pengajuan-barang.index', compact(
            'pengajuanList',
            'totalPengajuan',
            'totalBiaya',
            'totalAlatPraktikum',
            'totalBHP',
            'availableYears',
            'jurusanList'
        ));
    }

    /**
     * Form pembuatan pengajuan baru (Input Data & Live Print Preview).
     */
    public function create()
    {
        $profil = ProfilSekolah::first() ?? new ProfilSekolah([
            'nama_sekolah'      => 'SMKN 1 Beringin',
            'tahun_anggaran'    => 2026,
            'nama_kepsek'       => 'Asron Batubara, S.Pd., M.Si',
            'nip_kepsek'        => '197312162005021003',
            'nama_waka_sarpras' => 'Guntoro Pramadarno, S.Pd.',
            'nip_waka_sarpras'  => '198511132011011004',
        ]);

        // Daftar Resmi Program Keahlian / Jurusan di SMKN 1 Beringin
        $jurusanList = PengajuanBarang::getJurusanList();

        return view('pengajuan-barang.create', compact('profil', 'jurusanList'));
    }

    /**
     * Simpan pengajuan barang baru beserta rincian item ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_pengajuan'      => 'required|in:ALAT PRAKTIKUM,BARANG HABIS PAKAI',
            'program_keahlian'     => 'required|string|max:255',
            'laboratorium_bengkel' => 'nullable|string|max:255',
            'tahun_anggaran'       => 'required|integer|min:2020|max:2099',
            'tanggal_pengajuan'    => 'required|date',
            'penanggung_jawab'     => 'required|string|max:255',
            'nip_penanggung_jawab' => 'nullable|string|max:50',
            'catatan'              => 'nullable|string',
            'nomor_surat'          => 'nullable|string|max:100',
            
            // Item pengajuan
            'items'                => 'required|array|min:1',
            'items.*.nama_barang'  => 'required|string|max:255',
            'items.*.fungsi'       => 'nullable|string',
            'items.*.merk'         => 'nullable|string|max:150',
            'items.*.spesifikasi'  => 'nullable|string',
            'items.*.jumlah'       => 'required|integer|min:1',
            'items.*.satuan'       => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.catatan'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Hitung nomor surat otomatis jika kosong
            $nomorSurat = $validated['nomor_surat'] ?: $this->generateNomorSurat($validated['tahun_anggaran']);

            // Hitung total biaya
            $totalBiaya = 0;
            foreach ($validated['items'] as $itemData) {
                $totalBiaya += ($itemData['jumlah'] * $itemData['harga_satuan']);
            }

            // Hitung terbilang
            $terbilang = PengajuanBarang::terbilang($totalBiaya) . ' Rupiah';

            $pengajuan = PengajuanBarang::create([
                'user_id'              => auth()->id(),
                'nomor_surat'          => $nomorSurat,
                'jenis_pengajuan'      => $validated['jenis_pengajuan'],
                'program_keahlian'     => $validated['program_keahlian'],
                'laboratorium_bengkel' => $validated['laboratorium_bengkel'],
                'tahun_anggaran'       => $validated['tahun_anggaran'],
                'tanggal_pengajuan'    => $validated['tanggal_pengajuan'],
                'penanggung_jawab'     => $validated['penanggung_jawab'],
                'nip_penanggung_jawab' => $validated['nip_penanggung_jawab'],
                'status'               => 'Diajukan',
                'total_biaya'          => $totalBiaya,
                'terbilang'            => $terbilang,
                'catatan'              => $validated['catatan'] ?? null,
            ]);

            // Simpan setiap item barang
            foreach ($validated['items'] as $index => $itemData) {
                $gambarPath = null;
                // Cek upload berkas gambar jika ada
                if ($request->hasFile("items.{$index}.gambar")) {
                    $gambarFile = $request->file("items.{$index}.gambar");
                    $gambarPath = $gambarFile->store('pengajuan-barang', 'public');
                } elseif (!empty($itemData['gambar_base64'])) {
                    // Mendukung base64 preview upload
                    $gambarPath = $this->saveBase64Image($itemData['gambar_base64']);
                }

                $totalItem = $itemData['jumlah'] * $itemData['harga_satuan'];

                ItemPengajuanBarang::create([
                    'pengajuan_barang_id' => $pengajuan->id,
                    'nama_barang'         => $itemData['nama_barang'],
                    'gambar'              => $gambarPath,
                    'fungsi'              => $itemData['fungsi'] ?? null,
                    'merk'                => $itemData['merk'] ?? null,
                    'spesifikasi'         => $itemData['spesifikasi'] ?? null,
                    'jumlah'              => $itemData['jumlah'],
                    'satuan'              => $itemData['satuan'],
                    'harga_satuan'        => $itemData['harga_satuan'],
                    'total_harga'         => $totalItem,
                    'catatan'             => $itemData['catatan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('pengajuan-barang.show', $pengajuan)
                ->with('success', 'Pengajuan barang berhasil disimpan ke sistem! Anda dapat langsung mencetak lembar dokumen atau mengunduh berkas Excel.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail pengajuan barang lengkap dengan lembar print / cetak resmi.
     */
    public function show(PengajuanBarang $pengajuan_barang)
    {
        $pengajuan_barang->load('items', 'user');
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('pengajuan-barang.show', [
            'pengajuan' => $pengajuan_barang,
            'profil'    => $profil,
        ]);
    }

    /**
     * Tampilkan halaman cetak khusus A4 Landscape / Portrait.
     */
    public function print(PengajuanBarang $pengajuan_barang)
    {
        $pengajuan_barang->load('items', 'user');
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('pengajuan-barang.print', [
            'pengajuan' => $pengajuan_barang,
            'profil'    => $profil,
        ]);
    }

    /**
     * Form edit pengajuan barang.
     */
    public function edit(PengajuanBarang $pengajuan_barang)
    {
        $pengajuan_barang->load('items');
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        $jurusanList = PengajuanBarang::getJurusanList();

        return view('pengajuan-barang.edit', [
            'pengajuan'   => $pengajuan_barang,
            'profil'      => $profil,
            'jurusanList' => $jurusanList,
        ]);
    }

    /**
     * Update data pengajuan barang dan item-itemnya.
     */
    public function update(Request $request, PengajuanBarang $pengajuan_barang)
    {
        $validated = $request->validate([
            'jenis_pengajuan'      => 'required|in:ALAT PRAKTIKUM,BARANG HABIS PAKAI',
            'program_keahlian'     => 'required|string|max:255',
            'laboratorium_bengkel' => 'nullable|string|max:255',
            'tahun_anggaran'       => 'required|integer|min:2020|max:2099',
            'tanggal_pengajuan'    => 'required|date',
            'penanggung_jawab'     => 'required|string|max:255',
            'nip_penanggung_jawab' => 'nullable|string|max:50',
            'status'               => 'required|string|in:Draft,Diajukan,Disetujui,Ditolak',
            'catatan'              => 'nullable|string',
            'nomor_surat'          => 'nullable|string|max:100',
            
            // Item pengajuan
            'items'                => 'required|array|min:1',
            'items.*.nama_barang'  => 'required|string|max:255',
            'items.*.fungsi'       => 'nullable|string',
            'items.*.merk'         => 'nullable|string|max:150',
            'items.*.spesifikasi'  => 'nullable|string',
            'items.*.jumlah'       => 'required|integer|min:1',
            'items.*.satuan'       => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.catatan'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalBiaya = 0;
            foreach ($validated['items'] as $itemData) {
                $totalBiaya += ($itemData['jumlah'] * $itemData['harga_satuan']);
            }

            $terbilang = PengajuanBarang::terbilang($totalBiaya) . ' Rupiah';

            $pengajuan_barang->update([
                'nomor_surat'          => $validated['nomor_surat'] ?: $pengajuan_barang->nomor_surat,
                'jenis_pengajuan'      => $validated['jenis_pengajuan'],
                'program_keahlian'     => $validated['program_keahlian'],
                'laboratorium_bengkel' => $validated['laboratorium_bengkel'],
                'tahun_anggaran'       => $validated['tahun_anggaran'],
                'tanggal_pengajuan'    => $validated['tanggal_pengajuan'],
                'penanggung_jawab'     => $validated['penanggung_jawab'],
                'nip_penanggung_jawab' => $validated['nip_penanggung_jawab'],
                'status'               => $validated['status'],
                'total_biaya'          => $totalBiaya,
                'terbilang'            => $terbilang,
                'catatan'              => $validated['catatan'] ?? null,
            ]);

            // Hapus item lama dan buat ulang agar urutan sinkron
            $pengajuan_barang->items()->delete();

            foreach ($validated['items'] as $index => $itemData) {
                $gambarPath = $itemData['existing_gambar'] ?? null;
                if ($request->hasFile("items.{$index}.gambar")) {
                    $gambarFile = $request->file("items.{$index}.gambar");
                    $gambarPath = $gambarFile->store('pengajuan-barang', 'public');
                } elseif (!empty($itemData['gambar_base64'])) {
                    $gambarPath = $this->saveBase64Image($itemData['gambar_base64']);
                }

                $totalItem = $itemData['jumlah'] * $itemData['harga_satuan'];

                ItemPengajuanBarang::create([
                    'pengajuan_barang_id' => $pengajuan_barang->id,
                    'nama_barang'         => $itemData['nama_barang'],
                    'gambar'              => $gambarPath,
                    'fungsi'              => $itemData['fungsi'] ?? null,
                    'merk'                => $itemData['merk'] ?? null,
                    'spesifikasi'         => $itemData['spesifikasi'] ?? null,
                    'jumlah'              => $itemData['jumlah'],
                    'satuan'              => $itemData['satuan'],
                    'harga_satuan'        => $itemData['harga_satuan'],
                    'total_harga'         => $totalItem,
                    'catatan'             => $itemData['catatan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('pengajuan-barang.show', $pengajuan_barang)
                ->with('success', 'Data pengajuan barang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengajuan barang.
     */
    public function destroy(PengajuanBarang $pengajuan_barang)
    {
        try {
            $pengajuan_barang->items()->delete();
            $pengajuan_barang->delete();

            return redirect()->route('pengajuan-barang.index')
                ->with('success', 'Data pengajuan barang berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Ekspor pengajuan barang langsung ke berkas Excel asli sesuai Form_Pengajuan_Alat_Praktikum_SMKN1_Beringin.xlsx.
     */
    public function exportExcel(PengajuanBarang $pengajuan_barang)
    {
        $pengajuan_barang->load('items');
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        $templatePath = public_path('Form_Pengajuan_Alat_Praktikum_SMKN1_Beringin.xlsx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template Excel tidak ditemukan di folder public.');
        }

        $spreadsheet = IOFactory::load($templatePath);

        // Pilih sheet berdasarkan jenis pengajuan
        $sheetName = $pengajuan_barang->jenis_pengajuan === 'BARANG HABIS PAKAI' 
            ? 'BARANG HABIS PAKAI' 
            : 'ALAT PRAKTIKUM';

        $sheet = $spreadsheet->getSheetByName($sheetName) ?? $spreadsheet->getSheet(0);

        // Isi Data Identitas Pengajuan
        $sheet->setCellValue('C8', ': ' . $pengajuan_barang->program_keahlian);
        $sheet->setCellValue('C9', ': ' . ($pengajuan_barang->laboratorium_bengkel ?: '-'));
        $sheet->setCellValue('C10', ': ' . $pengajuan_barang->tahun_anggaran);
        $sheet->setCellValue('C11', ': ' . $pengajuan_barang->tanggal_pengajuan->translatedFormat('d F Y'));
        $sheet->setCellValue('C12', ': ' . $pengajuan_barang->penanggung_jawab);

        // Isi Baris Tabel Barang (Mulai dari baris 16)
        $startRow = 16;
        $items = $pengajuan_barang->items;
        
        foreach ($items as $idx => $item) {
            $currentRow = $startRow + $idx;

            $sheet->setCellValue("A{$currentRow}", $idx + 1);
            $sheet->setCellValue("B{$currentRow}", $item->nama_barang);
            $sheet->setCellValue("C{$currentRow}", $item->gambar ? '(Gambar terlampir)' : '-');
            $sheet->setCellValue("D{$currentRow}", $item->fungsi ?: '-');
            $sheet->setCellValue("E{$currentRow}", $item->merk ?: '-');
            $sheet->setCellValue("F{$currentRow}", $item->spesifikasi ?: '-');
            $sheet->setCellValue("G{$currentRow}", $item->jumlah);
            $sheet->setCellValue("H{$currentRow}", $item->satuan);
            $sheet->setCellValue("I{$currentRow}", (float) $item->harga_satuan);
            $sheet->setCellValue("J{$currentRow}", (float) $item->total_harga);
            $sheet->setCellValue("K{$currentRow}", $item->catatan ?: '-');
        }

        // Tanda Tangan & Tanggal
        $tglStr = 'Beringin, ' . $pengajuan_barang->tanggal_pengajuan->translatedFormat('d F Y');
        $sheet->setCellValue('I39', $tglStr);

        // Kepala Sekolah
        $sheet->setCellValue('A44', "( {$profil->nama_kepsek} )\nNIP. {$profil->nip_kepsek}");
        // Waka Sarpras
        $sheet->setCellValue('E44', "( {$profil->nama_waka_sarpras} )\nNIP. {$profil->nip_waka_sarpras}");
        // Kaprodi
        $nipKaprodi = $pengajuan_barang->nip_penanggung_jawab ? "NIP. {$pengajuan_barang->nip_penanggung_jawab}" : 'NIP. -';
        $sheet->setCellValue('I44', "( {$pengajuan_barang->penanggung_jawab} )\n{$nipKaprodi}");

        $fileName = 'Pengajuan_' . str_replace(' ', '_', $pengajuan_barang->program_keahlian) . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Format penomoran otomatis surat pengajuan.
     */
    private function generateNomorSurat($tahun): string
    {
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $currentMonth = $bulanRomawi[(int) date('n')];
        $countThisYear = PengajuanBarang::where('tahun_anggaran', $tahun)->count() + 1;
        $urutan = str_pad($countThisYear, 3, '0', STR_PAD_LEFT);

        return "{$urutan}/PENG-SARPRAS/SMKN.01/{$currentMonth}/{$tahun}";
    }

    /**
     * Simpan gambar base64 ke disk public.
     */
    private function saveBase64Image(string $base64String): ?string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $data = substr($base64String, strpos($base64String, ',') + 1);
            $type = strtolower($type[1]);
            $data = base64_decode($data);

            if ($data === false) {
                return null;
            }

            $fileName = 'pengajuan-barang/' . uniqid('item_') . '.' . $type;
            Storage::disk('public')->put($fileName, $data);
            return $fileName;
        }

        return null;
    }
}
