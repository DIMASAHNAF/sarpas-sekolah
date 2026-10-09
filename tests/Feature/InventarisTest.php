<?php

namespace Tests\Feature;

use App\Models\InventarisBarang;
use App\Models\ProfilSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarisTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->user = User::where('role', 'user')->first();
    }

    public function test_can_access_inventaris_index_with_filters(): void
    {
        $firstItem = InventarisBarang::latest('id')->first();
        $response = $this->actingAs($this->admin)->get('/inventaris');
        $response->assertStatus(200);
        $response->assertSee('Buku Inventaris Barang');
        $response->assertSee($firstItem->kode_barang);

        // Test filter tahun anggaran & bulan
        $responseFilter = $this->actingAs($this->admin)->get('/inventaris?tahun_anggaran=' . $firstItem->tahun_anggaran . '&bulan=3&kondisi=' . $firstItem->kondisi);
        $responseFilter->assertStatus(200);
    }

    public function test_regular_user_can_access_inventaris_and_import_features(): void
    {
        // 1. User biasa bisa melihat daftar inventaris
        $responseIndex = $this->actingAs($this->user)->get('/inventaris');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Import Excel');
        $responseIndex->assertSee('Unduh Format Template');

        // 2. User biasa bisa mengunduh template Excel
        $responseTemplate = $this->actingAs($this->user)->get('/inventaris/template/excel');
        $responseTemplate->assertStatus(200);
        $this->assertTrue(str_contains($responseTemplate->headers->get('content-disposition'), 'Template_Buku_Inventaris_Barang_Dana_BOS.xlsx'));

        // 3. User biasa bisa submit impor file Excel (validasi input berjalan, bukan 403 forbidden)
        $responseImport = $this->actingAs($this->user)->post('/inventaris/import/excel', []);
        $responseImport->assertSessionHasErrors(['file_excel']);

        // 4. User biasa TIDAK BISA mengakses create barang manual atau profil sekolah (khusus admin)
        $responseCreate = $this->actingAs($this->user)->get('/inventaris/create');
        $responseCreate->assertRedirect('/inventaris');
        $responseCreate->assertSessionHas('error');

        $responseProfil = $this->actingAs($this->user)->get('/profil-sekolah');
        $responseProfil->assertRedirect('/inventaris');
        $responseProfil->assertSessionHas('error');
    }

    public function test_can_create_inventaris_with_automatic_calculation(): void
    {
        $data = [
            'kode_barang'        => 'INV-BOS-2024-999',
            'tanggal_perolehan'  => '2024-06-01',
            'tanggal_pencatatan' => '2024-06-02',
            'nama_barang'        => 'Interactive Smart Board 65 Inch',
            'merk_spesifikasi'   => '4K UHD, Android 11, Dual OS Windows',
            'kategori'           => 'Media Pembelajaran Digital',
            'jumlah'             => 2,
            'satuan'             => 'Unit',
            'harga_satuan'       => 25000000.00,
            'no_bast'            => '999/BAST/BOS/2024',
            'sumber_dana'        => 'Dana BOS',
            'tahun_anggaran'     => 2024,
            'lokasi_ruang'       => 'Ruang Multimedia',
            'kondisi'            => 'Baik',
            'penanggung_jawab'   => 'Guru TIK',
            'nomor_register'     => 'REG-SMART-01-02',
            'keterangan'         => 'Termasuk standing bracket beroda',
            'tautan_dokumen'     => 'https://example.com/bast.pdf',
        ];

        $response = $this->actingAs($this->admin)->post('/inventaris', $data);
        $response->assertRedirect('/inventaris');

        $this->assertDatabaseHas('inventaris_barangs', [
            'kode_barang'     => 'INV-BOS-2024-999',
            'jumlah'          => 2,
            'harga_satuan'    => 25000000.00,
            'nilai_perolehan' => 50000000.00, // 2 * 25.000.000 otomatis
        ]);
    }

    public function test_can_update_inventaris_with_recalculation(): void
    {
        $item = InventarisBarang::first();

        $updateData = [
            'kode_barang'        => $item->kode_barang,
            'tanggal_perolehan'  => $item->tanggal_perolehan->format('Y-m-d'),
            'tanggal_pencatatan' => $item->tanggal_pencatatan->format('Y-m-d'),
            'nama_barang'        => 'Updated Laptop Asus Pro',
            'merk_spesifikasi'   => $item->merk_spesifikasi,
            'kategori'           => $item->kategori,
            'jumlah'             => 5,
            'satuan'             => 'Unit',
            'harga_satuan'       => 10000000.00,
            'no_bast'            => $item->no_bast,
            'sumber_dana'        => $item->sumber_dana,
            'tahun_anggaran'     => $item->tahun_anggaran,
            'lokasi_ruang'       => $item->lokasi_ruang,
            'kondisi'            => 'Baik',
            'penanggung_jawab'   => $item->penanggung_jawab,
            'nomor_register'     => $item->nomor_register,
            'keterangan'         => 'Update perolehan',
            'tautan_dokumen'     => null,
        ];

        $response = $this->actingAs($this->admin)->put("/inventaris/{$item->id}", $updateData);
        $response->assertRedirect('/inventaris');

        $this->assertDatabaseHas('inventaris_barangs', [
            'id'              => $item->id,
            'nama_barang'     => 'Updated Laptop Asus Pro',
            'jumlah'          => 5,
            'harga_satuan'    => 10000000.00,
            'nilai_perolehan' => 50000000.00, // 5 * 10.000.000
        ]);
    }

    public function test_can_update_profil_sekolah_single_row(): void
    {
        $response = $this->actingAs($this->admin)->get('/profil-sekolah');
        $response->assertStatus(200);

        $data = [
            'nama_sekolah'      => 'SMK Negeri Unggulan Nasional',
            'npsn'              => '99887766',
            'tahun_anggaran'    => 2024,
            'sumber_dana'       => 'Dana BOS Kinerja',
            'nama_kepsek'       => 'Prof. Dr. Irwan, M.Pd.',
            'nip_kepsek'        => '19700101 199501 1 001',
            'nama_waka_sarpras' => 'Deni Setiawan, S.T.',
            'nip_waka_sarpras'  => '19850202 201001 1 002',
        ];

        $response = $this->actingAs($this->admin)->put('/profil-sekolah', $data);
        $response->assertRedirect('/profil-sekolah');

        $this->assertDatabaseHas('profil_sekolahs', [
            'id'           => 1,
            'nama_sekolah' => 'SMK Negeri Unggulan Nasional',
            'npsn'         => '99887766',
        ]);
    }

    public function test_can_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get('/inventaris/export/excel?tahun_anggaran=2026&bulan=3');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xlsx'));
    }

    public function test_can_export_pdf_with_month_filter_and_valid_lembar_pengesahan(): void
    {
        $response = $this->actingAs($this->user)->get('/inventaris/export/pdf?tahun_anggaran=2026&bulan=3');
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));

        // Render view langsung untuk memverifikasi teks Lembar Pengesahan
        $profil = ProfilSekolah::getProfil();
        $inventaris = InventarisBarang::whereMonth('tanggal_perolehan', 3)->get();
        $totalNilai = $inventaris->sum('nilai_perolehan');
        $totalJumlah = $inventaris->sum('jumlah');
        $namaBulanSelected = 'Maret';

        $renderedHtml = view('inventaris.pdf', [
            'inventaris'        => $inventaris,
            'profil'            => $profil,
            'totalNilai'        => $totalNilai,
            'totalJumlah'       => $totalJumlah,
            'request'           => request()->merge(['tahun_anggaran' => '2026', 'bulan' => '3']),
            'namaBulanSelected' => $namaBulanSelected,
        ])->render();

        $this->assertStringContainsString('Diketahui :', $renderedHtml);
        $this->assertStringContainsString('Wakil Kepala Sekolah Bid. Sarana &amp; Prasarana', $renderedHtml);
        $this->assertStringContainsString('Disahkan Oleh :', $renderedHtml);
        $this->assertStringContainsString('Kepala ' . $profil->nama_sekolah, $renderedHtml);
        $this->assertStringContainsString('Maret', $renderedHtml);
    }

    public function test_can_download_template(): void
    {
        $response = $this->actingAs($this->user)->get('/inventaris/template/excel');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'Template_Buku_Inventaris_Barang_Dana_BOS.xlsx'));
    }
}
