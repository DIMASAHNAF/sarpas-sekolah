<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Inventaris Barang (BIB) - {{ $profil->nama_sekolah }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 14mm 12mm 14mm;
        }

        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #111827;
            line-height: 1.35;
        }

        /* Kop Surat & Judul Dokumen */
        .header-kop {
            text-align: center;
            border-bottom: 2pt double #111827;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .header-kop h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-kop h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 2px 0;
            color: #1e3a8a;
            text-transform: uppercase;
        }

        .header-kop p {
            font-size: 8pt;
            margin: 2px 0 0 0;
            color: #4b5563;
        }

        /* Informasi Metadata Pelaporan */
        .meta-info {
            width: 100%;
            margin-bottom: 8px;
            font-size: 8pt;
        }

        .meta-info td {
            padding: 1.5px 0;
            border: none;
        }

        /* Tabel Data Inventaris Utama */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        table.data-table th, 
        table.data-table td {
            border: 0.5pt solid #374151;
            padding: 3.5pt 4pt;
            vertical-align: top;
        }

        table.data-table thead {
            display: table-header-group; /* Otomatis tercetak ulang di setiap halaman baru */
        }

        table.data-table tr {
            page-break-inside: avoid;
        }

        table.data-table thead th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 7pt;
        }

        table.data-table tfoot td {
            background-color: #f9fafb;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }

        /* Badge Kondisi */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            font-size: 6.5pt;
            font-weight: bold;
            border-radius: 2px;
            text-align: center;
        }
        .badge-baik { background-color: #dcfce7; color: #15803d; border: 0.5pt solid #86efac; }
        .badge-ringan { background-color: #fef3c7; color: #b45309; border: 0.5pt solid #fcd34d; }
        .badge-berat { background-color: #fee2e2; color: #b91c1c; border: 0.5pt solid #fca5a5; }

        /* BLOK LEMBAR PENGESAHAN (Anti terpotong dengan CSS page-break-inside: avoid) */
        .lembar-pengesahan-container {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 16px;
            width: 100%;
        }

        .box-pengesahan {
            width: 100%;
            border: 0.5pt solid #9ca3af;
            background-color: #ffffff;
            padding: 10px 14px;
        }

        .keterangan-pengesahan {
            font-size: 8pt;
            margin-bottom: 14px;
            border-bottom: 0.5pt dashed #cbd5e1;
            padding-bottom: 8px;
        }

        .keterangan-pengesahan table td {
            border: none;
            padding: 1px 4px 1px 0;
            font-size: 8pt;
        }

        /* Format tanda tangan dua kolom: Kepala Sekolah di kiri, Waka Sarpras di kanan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            vertical-align: top;
            width: 50%;
            padding: 0 10px;
        }

        .sign-title {
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .sign-space {
            height: 55px; /* Ruang tanda tangan dan cap stempel */
        }

        .sign-name {
            font-size: 9pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .sign-nip {
            font-size: 8pt;
            color: #374151;
            margin-top: 1px;
        }

        /* Footer Halaman */
        .footer-note {
            margin-top: 8px;
            font-size: 6.5pt;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Kop Header Dokumen -->
    <div class="header-kop">
        <h1>{{ $profil->nama_sekolah }}</h1>
        <h2>BUKU INVENTARIS BARANG (BIB) SUMBER DANA BOS</h2>
        <p>Alat dan Barang Inventaris Hasil Pengadaan Dana Bantuan Operasional Sekolah (BOS)</p>
    </div>

    <!-- Parameter Filter / Identitas Dokumen -->
    <table class="meta-info">
        <tr>
            <td style="width: 14%;"><strong>NPSN</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 34%;">{{ $profil->npsn }}</td>
            <td style="width: 18%;"><strong>Tanggal Dicetak</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ now()->translatedFormat('d F Y - H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Tahun Anggaran</strong></td>
            <td>:</td>
            <td>{{ request('tahun_anggaran') ?: ($profil->tahun_anggaran ?: 'Semua Tahun') }}</td>
            <td><strong>Filter Kondisi</strong></td>
            <td>:</td>
            <td>{{ request('kondisi') ?: 'Semua Kondisi' }}</td>
        </tr>
        <tr>
            <td><strong>Sumber Dana</strong></td>
            <td>:</td>
            <td>{{ $profil->sumber_dana }}</td>
            <td><strong>Filter Lokasi Ruang</strong></td>
            <td>:</td>
            <td>{{ request('lokasi_ruang') ?: 'Semua Lokasi Ruang' }}</td>
        </tr>
    </table>

    <!-- Tabel Data Inventaris Utama -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 75px;">Kode Barang</th>
                <th style="width: 60px;">Tgl Perolehan</th>
                <th>Nama Barang & Spesifikasi</th>
                <th style="width: 65px;">Kategori</th>
                <th style="width: 38px;">Jml</th>
                <th style="width: 32px;">Sat</th>
                <th style="width: 68px;">Harga Satuan</th>
                <th style="width: 78px;">Nilai Perolehan</th>
                <th style="width: 70px;">Lokasi / Ruang</th>
                <th style="width: 58px;">Kondisi</th>
                <th style="width: 75px;">No. BAST</th>
                <th style="width: 68px;">Penanggung Jawab</th>
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
                        <div style="font-size: 6.8pt; color: #4b5563;">{{ $item->merk_spesifikasi }}</div>
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
                    <td style="font-size: 6.8pt;">{{ $item->no_bast }}</td>
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
                <td colspan="5" class="text-right font-bold">TOTAL KESELURUHAN:</td>
                <td class="text-center font-bold">{{ $totalJumlah }}</td>
                <td colspan="2" class="text-right font-bold">TOTAL NILAI ASET:</td>
                <td class="text-right font-bold">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <!-- LEMBAR PENGESAHAN (Diberikan CSS page-break-inside: avoid agar blok tanda tangan tidak terpisah ke halaman lain) -->
    <div class="lembar-pengesahan-container">
        <div class="box-pengesahan">
            
            <!-- Keterangan Informasi Pengesahan Bagian Atas Kiri -->
            <div class="keterangan-pengesahan">
                <table>
                    <tr>
                        <td style="width: 120px;"><strong>NPSN</strong></td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $profil->npsn }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tahun Anggaran</strong></td>
                        <td>:</td>
                        <td>{{ request('tahun_anggaran') ?: $profil->tahun_anggaran }}</td>
                    </tr>
                    <tr>
                        <td><strong>Sumber Anggaran</strong></td>
                        <td>:</td>
                        <td>{{ $profil->sumber_dana }}</td>
                    </tr>
                </table>
            </div>

            <!-- Format Tanda Tangan: Kepala Sekolah di Kiri, Waka Sarana Prasarana di Kanan -->
            <table class="signature-table">
                <tr>
                    <!-- Sisi Kiri: Kepala Sekolah -->
                    <td class="text-center">
                        <div class="sign-title">Mengetahui,</div>
                        <div class="sign-title">Kepala {{ $profil->nama_sekolah }}</div>
                        <div class="sign-space"></div>
                        <div class="sign-name">{{ $profil->nama_kepsek }}</div>
                        <div class="sign-nip">NIP. {{ $profil->nip_kepsek }}</div>
                    </td>

                    <!-- Sisi Kanan: Waka Sarana Prasarana -->
                    <td class="text-center">
                        <div class="sign-title">Disahkan oleh,</div>
                        <div class="sign-title">Wakil Kepala Sekolah Bid. Sarana & Prasarana</div>
                        <div class="sign-space"></div>
                        <div class="sign-name">{{ $profil->nama_waka_sarpras }}</div>
                        <div class="sign-nip">NIP. {{ $profil->nip_waka_sarpras }}</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <!-- Catatan Cetak -->
    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis melalui Sistem Informasi Buku Inventaris Barang (BIB) Pengadaan Dana BOS.
    </div>

</body>
</html>
