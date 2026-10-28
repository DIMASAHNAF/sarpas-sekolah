<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemPengajuanBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_barang_id',
        'nama_barang',
        'gambar',
        'gambar_scale',
        'fungsi',
        'merk',
        'spesifikasi',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total_harga',
        'catatan',
    ];

    protected $casts = [
        'jumlah'       => 'integer',
        'gambar_scale' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total_harga'  => 'decimal:2',
    ];

    public function pengajuanBarang()
    {
        return $this->belongsTo(PengajuanBarang::class);
    }

    public function getFormattedHargaSatuanAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedTotalHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        if (str_starts_with($this->gambar, 'http') || str_starts_with($this->gambar, 'data:')) {
            return $this->gambar;
        }

        return Storage::url($this->gambar);
    }
}
