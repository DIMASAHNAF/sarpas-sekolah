<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisBarang extends Model
{
    use HasFactory;

    protected $table = 'inventaris_barangs';

    protected $fillable = [
        'kode_barang',
        'tanggal_perolehan',
        'tanggal_pencatatan',
        'nama_barang',
        'merk_spesifikasi',
        'kategori',
        'jumlah',
        'satuan',
        'harga_satuan',
        'nilai_perolehan',
        'no_bast',
        'sumber_dana',
        'tahun_anggaran',
        'lokasi_ruang',
        'kondisi',
        'penanggung_jawab',
        'nomor_register',
        'keterangan',
        'tautan_dokumen',
    ];

    protected $casts = [
        'tanggal_perolehan'  => 'date',
        'tanggal_pencatatan' => 'date',
        'jumlah'             => 'integer',
        'harga_satuan'       => 'decimal:2',
        'nilai_perolehan'    => 'decimal:2',
        'tahun_anggaran'     => 'integer',
    ];

    /**
     * Mutator pengaman: jika nilai_perolehan tidak diisi eksplisit,
     * otomatis dikalkulasikan dari perkalian jumlah x harga_satuan.
     */
    protected static function booted(): void
    {
        static::saving(function (InventarisBarang $item) {
            if ($item->jumlah && $item->harga_satuan) {
                $item->nilai_perolehan = (int) $item->jumlah * (float) $item->harga_satuan;
            }
        });
    }

    /**
     * Format Rupiah untuk Nilai Perolehan
     */
    public function getFormattedNilaiPerolehanAttribute(): string
    {
        return 'Rp ' . number_format($this->nilai_perolehan, 2, ',', '.');
    }

    /**
     * Format Rupiah untuk Harga Satuan
     */
    public function getFormattedHargaSatuanAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan, 2, ',', '.');
    }
}
