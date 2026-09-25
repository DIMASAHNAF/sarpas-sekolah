<?php

namespace Tests\Feature;

use App\Models\InventarisBarang;
use App\Models\ProfilSekolah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_access_inventaris_index_with_filters(): void
    {
        $response = $this->get('/inventaris');
        $response->assertStatus(200);
        $response->assertSee('Buku Inventaris Barang');
        $response->assertSee('INV-BOS-2024-001');

        // Test filter
        $responseFilter = $this->get('/inventaris?tahun_anggaran=2024&kondisi=Baik');
        $responseFilter->assertStatus(200);
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

        $response = $this->post('/inventaris', $data);
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

        $response = $this->put("/inventaris/{$item->id}", $updateData);
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
        $response = $this->get('/profil-sekolah');
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

        $response = $this->put('/profil-sekolah', $data);
        $response->assertRedirect('/profil-sekolah');

        $this->assertDatabaseHas('profil_sekolahs', [
            'id'           => 1,
            'nama_sekolah' => 'SMK Negeri Unggulan Nasional',
            'npsn'         => '99887766',
        ]);
    }

    public function test_can_export_excel(): void
    {
        $response = $this->get('/inventaris/export/excel');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xlsx'));
    }

    public function test_can_export_pdf(): void
    {
        $response = $this->get('/inventaris/export/pdf');
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }
}
