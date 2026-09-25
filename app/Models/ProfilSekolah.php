<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    use HasFactory;

    protected $table = 'profil_sekolahs';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'tahun_anggaran',
        'sumber_dana',
        'nama_kepsek',
        'nip_kepsek',
        'nama_waka_sarpras',
        'nip_waka_sarpras',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
    ];

    /**
     * Helper method static untuk memanggil konfigurasi profil sekolah secara global.
     * Jika belum terdapat data di tabel profil_sekolahs, mengembalikan instance default.
     */
    public static function getProfil(): self
    {
        return static::firstOrNew(['id' => 1], [
            'nama_sekolah'      => 'SMK / SMA Negeri 1 Indonesia',
            'npsn'              => '10203040',
            'tahun_anggaran'    => (int) date('Y'),
            'sumber_dana'       => 'Dana BOS',
            'nama_kepsek'       => 'Drs. H. Ahmad Sudrajat, M.Pd.',
            'nip_kepsek'        => '19750812 200003 1 002',
            'nama_waka_sarpras' => 'Budi Santoso, S.T., M.Kom.',
            'nip_waka_sarpras'  => '19820315 200801 1 015',
        ]);
    }
}
