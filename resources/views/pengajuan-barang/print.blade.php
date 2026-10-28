<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Lembar Pengajuan - {{ $pengajuan->nomor_surat ?: 'SMKN 1 Beringin' }}</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: white;
            color: black;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .a4-landscape {
            width: 297mm;
            min-height: 210mm;
            padding: 12mm 15mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .font-cambria {
            font-family: 'Cambria', 'Caladea', Georgia, serif;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="a4-landscape">
        <!-- 1. KOP SURAT RESMI SMKN 1 BERINGIN (HD BANNER) -->
        <div class="mb-4">
            <img src="{{ asset('assets/kop-surat-landscape.png') }}" class="w-full object-contain" alt="Kop Surat Resmi SMKN 1 Beringin">
        </div>

        <!-- 2. JUDUL PENGAJUAN -->
        <div class="text-center mb-4">
            <h2 class="text-[14px] font-bold uppercase tracking-wide underline">
                {{ $pengajuan->jenis_pengajuan === 'BARANG HABIS PAKAI' ? 'PENGAJUAN BARANG HABIS PAKAI PRAKTIKUM' : 'PENGAJUAN BARANG / ALAT PRAKTIKUM' }}
            </h2>
            @if($pengajuan->nomor_surat)
                <p class="text-[11px] font-mono mt-0.5 text-slate-700">Nomor : {{ $pengajuan->nomor_surat }}</p>
            @endif
        </div>

        <!-- 3. IDENTITAS PENGAJUAN (Row 8 - 12 Sesuai Excel) -->
        <div class="text-[11px] mb-4 space-y-1">
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-3 font-semibold">Program Keahlian</div>
                <div class="col-span-9">: {{ $pengajuan->program_keahlian }}</div>
            </div>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-3 font-semibold">Laboratorium / Bengkel</div>
                <div class="col-span-9">: {{ $pengajuan->laboratorium_bengkel ?: '-' }}</div>
            </div>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-3 font-semibold">Tahun Anggaran</div>
                <div class="col-span-9">: {{ $pengajuan->tahun_anggaran }}</div>
            </div>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-3 font-semibold">Tanggal Pengajuan</div>
                <div class="col-span-9">: {{ $pengajuan->tanggal_pengajuan->translatedFormat('d F Y') }}</div>
            </div>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-3 font-semibold">Penanggung Jawab</div>
                <div class="col-span-9">: {{ $pengajuan->penanggung_jawab }}</div>
            </div>
        </div>

        <!-- 4. TABEL BARANG (11 Kolom Sesuai Excel Template) -->
        <table class="w-full border-collapse border border-black text-[10px] mb-4">
            <thead>
                <tr class="bg-slate-100 text-center font-bold">
                    <th class="border border-black p-1 w-7">No</th>
                    <th class="border border-black p-1 min-w-[130px]">Nama Barang/Alat</th>
                    <th class="border border-black p-1 w-20">Gambar</th>
                    <th class="border border-black p-1 min-w-[110px]">Fungsi</th>
                    <th class="border border-black p-1 w-20">Merk</th>
                    <th class="border border-black p-1 min-w-[150px]">Spesifikasi Lengkap</th>
                    <th class="border border-black p-1 w-12">Jumlah</th>
                    <th class="border border-black p-1 w-14">Satuan</th>
                    <th class="border border-black p-1 w-24">Harga Satuan (Rp)</th>
                    <th class="border border-black p-1 w-28">Total Harga (Rp)</th>
                    <th class="border border-black p-1 min-w-[100px]">Catatan</th>
                </tr>
                <tr class="text-[9px] text-center bg-slate-50 font-normal">
                    <td class="border border-black py-0.5">(1)</td>
                    <td class="border border-black py-0.5">(2)</td>
                    <td class="border border-black py-0.5">(3)</td>
                    <td class="border border-black py-0.5">(4)</td>
                    <td class="border border-black py-0.5">(5)</td>
                    <td class="border border-black py-0.5">(6)</td>
                    <td class="border border-black py-0.5">(7)</td>
                    <td class="border border-black py-0.5">(8)</td>
                    <td class="border border-black py-0.5">(9)</td>
                    <td class="border border-black py-0.5">(10)</td>
                    <td class="border border-black py-0.5">(11)</td>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $idx => $item)
                    <tr>
                        <td class="border border-black p-1 text-center">{{ $idx + 1 }}</td>
                        <td class="border border-black p-1 font-semibold">{{ $item->nama_barang }}</td>
                        <td class="border border-black p-1 text-center align-middle">
                            @if($item->gambar)
                                <div class="flex items-center justify-center p-0.5 overflow-hidden">
                                    <img src="{{ $item->gambar_url }}" 
                                         alt="Foto Barang" 
                                         style="max-height: {{ round(45 * ($item->gambar_scale ?: 100) / 100) }}px; max-width: {{ round(80 * ($item->gambar_scale ?: 100) / 100) }}px;" 
                                         class="object-contain inline-block rounded border border-slate-200">
                                </div>
                            @else
                                <span class="text-[9px] text-slate-400 italic">(Terlampir)</span>
                            @endif
                        </td>
                        <td class="border border-black p-1">{{ $item->fungsi ?: '-' }}</td>
                        <td class="border border-black p-1 text-center">{{ $item->merk ?: '-' }}</td>
                        <td class="border border-black p-1">{{ $item->spesifikasi ?: '-' }}</td>
                        <td class="border border-black p-1 text-center font-bold">{{ $item->jumlah }}</td>
                        <td class="border border-black p-1 text-center">{{ $item->satuan }}</td>
                        <td class="border border-black p-1 text-right font-mono">{{ $item->formatted_harga_satuan }}</td>
                        <td class="border border-black p-1 text-right font-mono font-bold">{{ $item->formatted_total_harga }}</td>
                        <td class="border border-black p-1">{{ $item->catatan ?: '-' }}</td>
                    </tr>
                @endforeach
                <!-- Baris Total -->
                <tr class="font-bold bg-slate-100">
                    <td colspan="9" class="border border-black p-1.5 text-right uppercase">Total Perkiraan Biaya Pengajuan</td>
                    <td class="border border-black p-1.5 text-right font-mono text-[11px] font-black">{{ $pengajuan->formatted_total_biaya }}</td>
                    <td class="border border-black p-1.5 bg-slate-200"></td>
                </tr>
            </tbody>
        </table>

        <!-- 5. TERBILANG -->
        <div class="text-[11px] mb-6">
            <span class="font-bold italic">Terbilang : </span>
            <span class="italic font-semibold underline">{{ $pengajuan->terbilang ?: 'Nol Rupiah' }}</span>
        </div>

        <!-- 6. FORMAT TANDA TANGAN (3 PIHAK) -->
        <div class="text-[11px] mt-6">
            <div class="flex justify-end mb-2 pr-6">
                <p>Beringin, {{ $pengajuan->tanggal_pengajuan->translatedFormat('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-3 gap-6 text-center">
                <!-- Kiri: Mengetahui Kepala Sekolah -->
                <div>
                    <p class="font-semibold">Mengetahui,</p>
                    <p class="font-semibold mb-16">Kepala Sekolah</p>
                    <p class="font-bold underline">{{ $profil->nama_kepsek }}</p>
                    <p class="text-[10px]">NIP. {{ $profil->nip_kepsek }}</p>
                </div>

                <!-- Tengah: Wakil Kepala Sekolah Sarpras -->
                <div>
                    <p class="font-semibold">Wakil Kepala Sekolah</p>
                    <p class="font-semibold mb-16">Bid. Sarana & Prasarana</p>
                    <p class="font-bold underline">{{ $profil->nama_waka_sarpras }}</p>
                    <p class="text-[10px]">NIP. {{ $profil->nip_waka_sarpras }}</p>
                </div>

                <!-- Kanan: Yang Mengajukan Kaprodi -->
                <div>
                    <p class="font-semibold">Yang Mengajukan,</p>
                    <p class="font-semibold mb-16">Kepala Program Keahlian</p>
                    <p class="font-bold underline">{{ $pengajuan->penanggung_jawab }}</p>
                    <p class="text-[10px]">{{ $pengajuan->nip_penanggung_jawab ? 'NIP. ' . $pengajuan->nip_penanggung_jawab : 'NIP. -' }}</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
