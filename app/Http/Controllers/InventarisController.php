<?php

namespace App\Http\Controllers;

use App\Exports\InventarisExport;
use App\Http\Requests\StoreInventarisRequest;
use App\Http\Requests\UpdateInventarisRequest;
use App\Models\InventarisBarang;
use App\Models\ProfilSekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class InventarisController extends Controller
{
    /**
     * Membangun base query inventaris dengan filter & pencarian teks.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = InventarisBarang::query();

        // 1. Pencarian teks: nama_barang atau kode_barang
        $query->when($request->filled('search'), function ($q) use ($request) {
            $keyword = '%' . trim($request->search) . '%';
            $q->where(function ($sub) use ($keyword) {
                $sub->where('nama_barang', 'LIKE', $keyword)
                    ->orWhere('kode_barang', 'LIKE', $keyword);
            });
        });

        // 2. Filter Tahun Anggaran (menerima 'tahun_anggaran' atau 'tahun')
        $tahun = $request->input('tahun_anggaran') ?: $request->input('tahun');
        $query->when(!empty($tahun), function ($q) use ($tahun) {
            $q->where(function ($sub) use ($tahun) {
                $sub->where('tahun_anggaran', $tahun)
                    ->orWhereYear('tanggal_perolehan', $tahun);
            });
        });

        // 3. Filter Bulan (1 - 12)
        $query->when($request->filled('bulan'), function ($q) use ($request) {
            $bulan = (int) $request->bulan;
            $q->where(function ($sub) use ($bulan) {
                $sub->whereMonth('tanggal_perolehan', $bulan)
                    ->orWhere(function ($s2) use ($bulan) {
                        $s2->whereNull('tanggal_perolehan')
                           ->whereMonth('tanggal_pencatatan', $bulan);
                    });
            });
        });

        // 4. Filter Kondisi
        $query->when($request->filled('kondisi'), function ($q) use ($request) {
            $q->where('kondisi', $request->kondisi);
        });

        // 5. Filter Lokasi / Ruang
        $query->when($request->filled('lokasi_ruang'), function ($q) use ($request) {
            $q->where('lokasi_ruang', $request->lokasi_ruang);
        });

        return $query;
    }

    /**
     * Menampilkan daftar inventaris barang.
     */
    public function index(Request $request): View
    {
        $query = $this->buildFilteredQuery($request);

        $inventaris = $query->latest('id')->paginate(15)->withQueryString();

        // Data opsi untuk filter tahun & bulan
        $yearsFromTahun = InventarisBarang::whereNotNull('tahun_anggaran')->pluck('tahun_anggaran')->map(fn($v) => (int)$v)->toArray();
        $yearsFromDate = InventarisBarang::whereNotNull('tanggal_perolehan')->pluck('tanggal_perolehan')->map(function ($date) {
            return $date ? (int) \Carbon\Carbon::parse($date)->format('Y') : null;
        })->filter()->toArray();
        $listTahunAnggaran = collect(array_merge($yearsFromTahun, $yearsFromDate, [(int)date('Y')]))->unique()->filter()->sortDesc()->values();

        $listBulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $listRuang = InventarisBarang::select('lokasi_ruang')->distinct()->whereNotNull('lokasi_ruang')->orderBy('lokasi_ruang')->pluck('lokasi_ruang');

        // Statistik ringkas
        $totalAset = InventarisBarang::sum('nilai_perolehan');
        $totalBarang = InventarisBarang::sum('jumlah');
        $totalKondisiBaik = InventarisBarang::where('kondisi', 'Baik')->count();
        $totalKondisiRusak = InventarisBarang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();

        // Jumlah seluruh data (tanpa filter) untuk tombol Kosongkan Data
        $seluruhDataInventaris = InventarisBarang::count();

        return view('inventaris.index', compact(
            'inventaris',
            'listTahunAnggaran',
            'listBulan',
            'listRuang',
            'totalAset',
            'totalBarang',
            'totalKondisiBaik',
            'totalKondisiRusak',
            'seluruhDataInventaris'
        ));
    }

    /**
     * Menampilkan form input inventaris baru.
     */
    public function create(): View
    {
        return view('inventaris.create');
    }

    /**
     * Menyimpan data inventaris baru dengan kalkulasi otomatis.
     */
    public function store(StoreInventarisRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // LOGIKA OTOMATIS: nilai_perolehan = jumlah * harga_satuan
        $validated['nilai_perolehan'] = (int) $validated['jumlah'] * (float) $validated['harga_satuan'];

        InventarisBarang::create($validated);

        return redirect()
            ->route('inventaris.index')
            ->with('success', "Inventaris '{$validated['nama_barang']}' berhasil dicatat.");
    }

    /**
     * Menampilkan detail inventaris.
     */
    public function show(InventarisBarang $inventaris): View
    {
        return view('inventaris.show', compact('inventaris'));
    }

    /**
     * Menampilkan form edit inventaris.
     */
    public function edit(InventarisBarang $inventaris): View
    {
        return view('inventaris.edit', compact('inventaris'));
    }

    /**
     * Memperbarui inventaris dengan rekalkulasi nilai_perolehan otomatis.
     */
    public function update(UpdateInventarisRequest $request, InventarisBarang $inventaris): RedirectResponse
    {
        $validated = $request->validated();

        // LOGIKA OTOMATIS: rekalkulasi sebelum update
        $validated['nilai_perolehan'] = (int) $validated['jumlah'] * (float) $validated['harga_satuan'];

        $inventaris->update($validated);

        return redirect()
            ->route('inventaris.index')
            ->with('success', "Data inventaris '{$inventaris->nama_barang}' berhasil diperbarui.");
    }

    /**
     * Menghapus inventaris dari database.
     */
    public function destroy(InventarisBarang $inventaris): RedirectResponse
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route("inventaris.index")
                ->with("error", "Akses ditolak. Hanya Administrator yang berwenang menghapus data barang.");
        }
        $nama = $inventaris->nama_barang;
        $inventaris->delete();
        return redirect()->route("inventaris.index")->with("success", "Barang '{$nama}' berhasil dihapus dari inventaris.");
    }

    /**
     * Menghapus seluruh data inventaris (mengosongkan tabel).
     */
    public function destroyAll(): RedirectResponse
    {
        $total = InventarisBarang::count();

        if ($total === 0) {
            return redirect()
                ->route('inventaris.index')
                ->with('success', 'Buku Inventaris Barang sudah dalam keadaan kosong.');
        }

        // TRUNCATE: hapus seluruh baris + reset auto-increment.
        // Eloquent truncate() otomatis menonaktifkan pengecekan foreign key.
        InventarisBarang::truncate();

        return redirect()
            ->route('inventaris.index')
            ->with('success', "Berhasil menghapus {$total} data inventaris. Buku Inventaris Barang kini kosong.");
    }

    /**
     * Menghapus beberapa data inventaris sekaligus berdasarkan checkbox ID yang dipilih.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return redirect()
                ->route('inventaris.index')
                ->with('error', 'Pilih minimal satu data inventaris untuk dihapus.');
        }

        $count = InventarisBarang::whereIn('id', $ids)->delete();

        return redirect()
            ->route('inventaris.index')
            ->with('success', "Berhasil menghapus {$count} data inventaris yang dipilih.");
    }

    /**
     * Ekspor data inventaris ke Excel (.xlsx) sesuai hasil filter saat ini.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $query = $this->buildFilteredQuery($request);
        $data = $query->orderBy('kode_barang')->get();

        $filename = 'Buku_Inventaris_Barang_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new InventarisExport($data), $filename);
    }

    /**
     * Ekspor data inventaris ke format PDF Landscape dengan Lembar Pengesahan.
     */
    public function exportPdf(Request $request): Response
    {
        $query = $this->buildFilteredQuery($request);
        $inventaris = $query->orderBy('kode_barang')->get();

        $profil = ProfilSekolah::getProfil();

        $namaBulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $namaBulanSelected = $request->filled('bulan') && isset($namaBulan[(int)$request->bulan]) 
            ? $namaBulan[(int)$request->bulan] 
            : null;

        $totalNilai = $inventaris->sum('nilai_perolehan');
        $totalJumlah = $inventaris->sum('jumlah');

        $pdf = Pdf::loadView('inventaris.pdf', compact(
            'inventaris',
            'profil',
            'totalNilai',
            'totalJumlah',
            'request',
            'namaBulanSelected'
        ))->setPaper('a4', 'landscape');

        $filename = 'Buku_Inventaris_Barang_' . date('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Impor data inventaris dari file Excel (.xlsx, .xls, .csv).
     */
    public function importExcel(Request $request): RedirectResponse
    {
        if (!extension_loaded('fileinfo')) {
            return redirect()
                ->back()
                ->withErrors(['file_excel' => 'Ekstensi PHP "fileinfo" belum aktif di server. Silakan aktifkan ekstensi fileinfo di pengaturan PHP server (aaPanel / cPanel / php.ini) lalu restart web server.']);
        }

        $request->validate([
            'file_excel'   => ['required', 'file', 'extensions:xlsx,xls,csv', 'mimes:xlsx,xls,csv', 'max:10240'],
            'truncate_old' => ['nullable', 'boolean'],
        ], [
            'file_excel.required'   => 'Pilih file Excel yang ingin diimpor.',
            'file_excel.extensions' => 'Format file harus berupa Excel (.xlsx, .xls) atau .csv.',
            'file_excel.mimes'      => 'Tipe konten file harus berupa spreadsheet Excel (.xlsx, .xls) atau .csv yang valid.',
            'file_excel.max'        => 'Ukuran file Excel maksimal 10 MB.',
        ]);

        try {
            $truncateOld = $request->boolean('truncate_old');
            $importer = new \App\Imports\InventarisExcelImporter();
            $result = $importer->import($request->file('file_excel'), $truncateOld);

            $pesan = "Berhasil mengimpor {$result['count']} barang ke Buku Inventaris Barang.";
            if (!empty($result['school_name'])) {
                $pesan .= " Profil sekolah diperbarui: {$result['school_name']}.";
            }

            return redirect()
                ->route('inventaris.index')
                ->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['file_excel' => 'Gagal memproses file Excel: ' . $e->getMessage()]);
        }
    }

    /**
     * Unduh format template Excel resmi sekolah.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filename = 'Template_Buku_Inventaris_Barang_Dana_BOS.xlsx';
        return Excel::download(new \App\Exports\InventarisTemplateExport(), $filename);
    }
}
