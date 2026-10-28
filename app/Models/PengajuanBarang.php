<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBarang extends Model
{
    use HasFactory;

    /**
     * Daftar Resmi 7 Jurusan Program Keahlian + 2 Unit SMKN 1 Beringin.
     */
    public const DAFTAR_JURUSAN = [
        'Kecantikan dan SPA',
        'Kuliner',
        'PPLG (RPL)',
        'TJKT (TKJ)',
        'Pariwisata',
        'Perhotelan',
        'Tata Busana',
        'UKS',
        'Perpustakaan',
    ];

    public static function getJurusanList(): array
    {
        return self::DAFTAR_JURUSAN;
    }

    /**
     * Mengembalikan daftar alias kata kunci untuk pencocokan jurusan yang fleksibel.
     */
    public static function getAliases(?string $jurusan): array
    {
        if (!$jurusan) {
            return [];
        }

        $map = [
            'Kecantikan dan SPA' => ['Kecantikan dan SPA', 'Kecantikan dan Spa', 'Kecantikan & Spa', 'Tata Kecantikan', 'Kecantikan', 'SPA'],
            'Kuliner'            => ['Kuliner', 'Tata Boga', 'Boga'],
            'PPLG (RPL)'         => ['PPLG (RPL)', 'PPLG', 'RPL', 'Rekayasa Perangkat Lunak'],
            'TJKT (TKJ)'         => ['TJKT (TKJ)', 'TJKT', 'TKJ', 'Teknik Jaringan Komputer dan Telekomunikasi', 'TKJ 1', 'TKJ 2'],
            'Pariwisata'         => ['Pariwisata', 'ULP', 'UPW', 'Usaha Layanan Pariwisata', 'Usaha Perjalanan Wisata'],
            'Perhotelan'         => ['Perhotelan', 'Hotel', 'Akomodasi Perhotelan'],
            'Tata Busana'        => ['Tata Busana', 'Busana', 'Tata Busana (TB)'],
            'UKS'                => ['UKS', 'Unit Kesehatan Sekolah', 'Kesehatan'],
            'Perpustakaan'       => ['Perpustakaan', 'Perpus'],
        ];

        if (isset($map[$jurusan])) {
            return $map[$jurusan];
        }

        foreach ($map as $key => $aliases) {
            if (strcasecmp($key, $jurusan) === 0 || in_array($jurusan, $aliases)) {
                return $aliases;
            }
        }

        return [$jurusan];
    }

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'jenis_pengajuan',
        'program_keahlian',
        'laboratorium_bengkel',
        'tahun_anggaran',
        'tanggal_pengajuan',
        'penanggung_jawab',
        'nip_penanggung_jawab',
        'status',
        'total_biaya',
        'terbilang',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tahun_anggaran'    => 'integer',
        'total_biaya'       => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ItemPengajuanBarang::class);
    }

    public function getFormattedTotalBiayaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }

    /**
     * Konversi angka rupiah ke kalimat terbilang bahasa Indonesia.
     */
    public static function terbilang($angka): string
    {
        $angka = abs((float) $angka);
        $bilangan = [
            '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima',
            'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'
        ];

        if ($angka < 12) {
            $hasil = $bilangan[(int) $angka];
        } elseif ($angka < 20) {
            $hasil = self::terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            $sisa = $angka % 10;
            $hasil = self::terbilang($angka / 10) . ' Puluh' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 200) {
            $sisa = $angka - 100;
            $hasil = 'Seratus' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 1000) {
            $sisa = $angka % 100;
            $hasil = self::terbilang($angka / 100) . ' Ratus' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 2000) {
            $sisa = $angka - 1000;
            $hasil = 'Seribu' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 1000000) {
            $sisa = $angka % 1000;
            $hasil = self::terbilang($angka / 1000) . ' Ribu' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 1000000000) {
            $sisa = $angka % 1000000;
            $hasil = self::terbilang($angka / 1000000) . ' Juta' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } elseif ($angka < 1000000000000) {
            $sisa = fmod($angka, 1000000000);
            $hasil = self::terbilang($angka / 1000000000) . ' Miliar' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        } else {
            $sisa = fmod($angka, 1000000000000);
            $hasil = self::terbilang($angka / 1000000000000) . ' Triliun' . ($sisa ? ' ' . self::terbilang($sisa) : '');
        }

        return trim(preg_replace('/\s+/', ' ', $hasil));
    }
}
