<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Inventaris Barang (BIB) - {{ $profil->nama_sekolah }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* ============ KOP SURAT ============ */
        .header-kop {
            text-align: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 6px;
            margin-bottom: 4px;
            position: relative;
        }

        .header-kop::after {
            content: "";
            display: block;
            border-bottom: 1px solid #1e3a8a;
            margin-top: 2px;
        }

        .header-kop h1 {
            font-size: 15pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #111827;
        }

        .header-kop h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 3px 0 1px 0;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-kop p {
            font-size: 7.5pt;
            margin: 0;
            color: #4b5563;
            font-style: italic;
        }

        /* ============ METADATA ============ */
        .meta-info {
            width: 100%;
            margin: 8px 0 8px 0;
            font-size: 7.5pt;
        }

        .meta-info td {
            padding: 1px 0;
            border: none;
        }

        .meta-info .label {
            color: #374151;
            font-weight: bold;
            width: 17%;
        }

        .meta-info .sep { width: 2%; }

        /* ============ TABEL DATA ============ */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
        }

        table.data-table th,
        table.data-table td {
            border: 0.5pt solid #6b7280;
            padding: 3pt 3.5pt;
            vertical-align: top;
        }

        table.data-table thead {
            display: table-header-group;
        }

        table.data-table tr {
            page-break-inside: avoid;
        }

        table.data-table thead th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 6.5pt;
            letter-spacing: 0.3px;
            border-color: #1e3a8a;
            padding: 4pt 3.5pt;
        }

        /* Zebra striping baris genap */
        table.data-table tbody tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Baris total paling akhir */
        table.data-table tfoot td {
            background-color: #dbeafe;
            color: #1e3a8a;
            font-weight: bold;
            border-color: #1e3a8a;
            padding: 4.5pt 3.5pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }

        /* Badge Kondisi */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            font-size: 6pt;
            font-weight: bold;
            border-radius: 2px;
            text-align: center;
        }
        .badge-baik { background-color: #dcfce7; color: #166534; border: 0.5pt solid #4ade80; }
        .badge-ringan { background-color: #fef3c7; color: #92400e; border: 0.5pt solid #fbbf24; }
        .badge-berat { background-color: #fee2e2; color: #991b1b; border: 0.5pt solid #f87171; }

        .spec {
            font-size: 6pt;
            color: #4b5563;
            margin-top: 1px;
        }

        /* ============ LEMBAR PENGESAHAN ============ */
        .lembar-pengesahan-container {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 14px;
            width: 100%;
        }

        .box-pengesahan {
            width: 100%;
            border: 1pt solid #1e3a8a;
            background-color: #ffffff;
            padding: 12px 16px;
        }

        .box-pengesahan-title {
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .keterangan-pengesahan {
            font-size: 7.5pt;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 0.5pt dashed #94a3b8;
        }

        .keterangan-pengesahan table td {
            border: none;
            padding: 1px 4px 1px 0;
            font-size: 7.5pt;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            vertical-align: top;
            width: 50%;
            padding: 0 12px;
        }

        .sign-title {
            font-size: 7.5pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .sign-space {
            height: 50px;
        }

        .sign-name {
            font-size: 8.5pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .sign-nip {
            font-size: 7.5pt;
            color: #374151;
            margin-top: 1px;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 6pt;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
@php
    $logoFile = public_path("storage/assets/foto.png");
    $logoBase64 = file_exists($logoFile) ? "data:image/png;base64," . base64_encode(file_get_contents($logoFile)) : null;
@endphp
<body>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
        <tr>
            @if($logoBase64)
            <td style="width: 65px; vertical-align: middle; text-align: left; border: none; padding: 0;">
                <img src="{{ $logoBase64 }}" style="width: 52px; height: 52px; object-fit: contain;">
            </td>
            @endif
            <td style="vertical-align: middle; text-align: center; border: none; padding: 0;">
                <div class="header-kop" style="border-bottom: none; margin: 0; padding: 0;">
                    <h1>{{ $profil->nama_sekolah }}</h1>
                    <h2>Buku Inventaris Barang (BIB)</h2>
                    <p>Alat dan Barang Inventaris Hasil Pengadaan {{ $profil->sumber_dana }}</p>
                </div>
            </td>
            @if($logoBase64)
            <td style="width: 65px; border: none; padding: 0;"></td>
            @endif
        </tr>
    </table>
    <div style="border-bottom: 2.5pt solid #1e3a8a; margin-bottom: 2px;"></div>
    <div style="border-bottom: 0.75pt solid #1e3a8a; margin-bottom: 6px;"></div>

    <!-- Parameter Filter / Identitas Dokumen -->
    <table class="meta-info">
        <tr>
            <td class="label">NPSN</td>
            <td class="sep">:</td>
            <td style="width: 31%;">{{ $profil->npsn }}</td>
            <td class="label" style="width: 17%;">Tanggal Dicetak</td>
            <td class="sep">:</td>
            <td style="width: 33%;">{{ now()->translatedFormat('d F Y - H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Tahun Anggaran</td>
            <td class="sep">:</td>
            <td>
                {{ request('tahun_anggaran') ?: (request('tahun') ?: ($profil->tahun_anggaran ?: 'Semua Tahun')) }}
                @if(!empty($namaBulanSelected))
                    (Bulan: {{ $namaBulanSelected }})
                @endif
            </td>
            <td class="label">Filter Kondisi</td>
            <td class="sep">:</td>
            <td>{{ request('kondisi') ?: 'Semua Kondisi' }}</td>
        </tr>
        <tr>
            <td class="label">Sumber Dana</td>
            <td class="sep">:</td>
            <td>{{ $profil->sumber_dana }}</td>
            <td class="label">Filter Lokasi Ruang</td>
            <td class="sep">:</td>
            <td>{{ request('lokasi_ruang') ?: 'Semua Lokasi Ruang' }}</td>
        </tr>
    </table>

    <!-- Tabel Data Inventaris Utama -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18px;">No</th>
                <th style="width: 72px;">Kode Barang</th>
                <th style="width: 58px;">Tgl Perolehan</th>
                <th>Nama Barang &amp; Spesifikasi</th>
                <th style="width: 62px;">Kategori</th>
                <th style="width: 34px;">Jml</th>
                <th style="width: 28px;">Sat</th>
                <th style="width: 64px;">Harga Satuan</th>
                <th style="width: 76px;">Nilai Perolehan</th>
                <th style="width: 66px;">Lokasi / Ruang</th>
                <th style="width: 52px;">Kondisi</th>
                <th style="width: 68px;">No. BAST</th>
                <th style="width: 62px;">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventaris as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono text-center">{{ $item->kode_barang }}</td>
                    <td class="text-center">{{ $item->tanggal_perolehan ? $item->tanggal_perolehan->format('d/m/Y') : '-' }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong>
                        @if($item->merk_spesifikasi)
                            <div class="spec">{{ $item->merk_spesifikasi }}</div>
                        @endif
                    </td>
                    <td>{{ $item->kategori }}</td>
                    <td class="text-center font-bold">{{ $item->jumlah }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
                    <td>{{ $item->lokasi_ruang }}</td>
                    <td class="text-center">
                        @if($item->kondisi === 'Baik')
                            <span class="badge badge-baik">Baik</span>
                        @elseif($item->kondisi === 'Rusak Ringan')
                            <span class="badge badge-ringan">R. Ringan</span>
                        @else
                            <span class="badge badge-berat">R. Berat</span>
                        @endif
                    </td>
                    <td style="font-size: 6pt;">{{ $item->no_bast }}</td>
                    <td>{{ $item->penanggung_jawab }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 20px;">
                        <em>Tidak ada catatan data inventaris barang yang sesuai dengan kriteria filter saat ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totalJumlah }}</td>
                <td colspan="2" class="text-right">TOTAL NILAI ASET</td>
                <td class="text-right">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <!-- LEMBAR PENGESAHAN -->
    <div class="lembar-pengesahan-container">
        <div class="box-pengesahan">
            <div class="box-pengesahan-title">Lembar Pengesahan</div>

            <div class="keterangan-pengesahan">
                <table>
                    <tr>
                        <td style="width: 115px;"><strong>NPSN</strong></td>
                        <td style="width: 8px;">:</td>
                        <td>{{ $profil->npsn }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tahun Anggaran</strong></td>
                        <td>:</td>
                        <td>{{ request('tahun_anggaran') ?: (request('tahun') ?: $profil->tahun_anggaran) }}</td>
                    </tr>
                    @if(!empty($namaBulanSelected))
                    <tr>
                        <td><strong>Bulan</strong></td>
                        <td>:</td>
                        <td>{{ $namaBulanSelected }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Sumber Anggaran</strong></td>
                        <td>:</td>
                        <td>{{ $profil->sumber_dana }}</td>
                    </tr>
                </table>
            </div>

            <table class="signature-table">
                <tr>
                    <td class="text-center">
                        <div class="sign-title">Diketahui :</div>
                        <div class="sign-title">Wakil Kepala Sekolah Bid. Sarana &amp; Prasarana</div>
                        <div class="sign-space"></div>
                        <div class="sign-name">{{ $profil->nama_waka_sarpras }}</div>
                        <div class="sign-nip">NIP. {{ $profil->nip_waka_sarpras }}</div>
                    </td>
                    <td class="text-center">
                        <div class="sign-title">Disahkan Oleh :</div>
                        <div class="sign-title">Kepala {{ $profil->nama_sekolah }}</div>
                        <div class="sign-space"></div>
                        <div class="sign-name">{{ $profil->nama_kepsek }}</div>
                        <div class="sign-nip">NIP. {{ $profil->nip_kepsek }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis melalui Sistem Informasi Buku Inventaris Barang (BIB) Pengadaan {{ $profil->sumber_dana }}.
    </div>

</body>
</html>
