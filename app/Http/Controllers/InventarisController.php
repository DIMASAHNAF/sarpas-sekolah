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

        // 2. Filter Tahun Anggaran
        $query->when($request->filled('tahun_anggaran'), function ($q) use ($request) {
            $q->where('tahun_anggaran', $request->tahun_anggaran);
        });

        // 3. Filter Kondisi
        $query->when($request->filled('kondisi'), function ($q) use ($request) {
            $q->where('kondisi', $request->kondisi);
        });

        // 4. Filter Lokasi / Ruang
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

        // Data opsi untuk filter
        $listTahunAnggaran = InventarisBarang::select('tahun_anggaran')->distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');
        $listRuang = InventarisBarang::select('lokasi_ruang')->distinct()->orderBy('lokasi_ruang')->pluck('lokasi_ruang');

        // Statistik ringkas
        $totalAset = InventarisBarang::sum('nilai_perolehan');
        $totalBarang = InventarisBarang::sum('jumlah');
        $totalKondisiBaik = InventarisBarang::where('kondisi', 'Baik')->count();
        $totalKondisiRusak = InventarisBarang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();

        return view('inventaris.index', compact(
            'inventaris',
            'listTahunAnggaran',
            'listRuang',
            'totalAset',
            'totalBarang',
            'totalKondisiBaik',
            'totalKondisiRusak'
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
        $nama = $inventaris->nama_barang;
        $inventaris->delete();

        return redirect()
            ->route('inventaris.index')
            ->with('success', "Barang '{$nama}' berhasil dihapus dari inventaris.");
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

        $totalNilai = $inventaris->sum('nilai_perolehan');
        $totalJumlah = $inventaris->sum('jumlah');

        $pdf = Pdf::loadView('inventaris.pdf', compact(
            'inventaris',
            'profil',
            'totalNilai',
            'totalJumlah',
            'request'
        ))->setPaper('a4', 'landscape');

        $filename = 'Buku_Inventaris_Barang_' . date('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
}
