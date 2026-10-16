@extends('layouts.app')

@section('title', 'Dokumen Pengajuan ' . $pengajuan->nomor_surat)

@push('styles')
<style>
    /* Format Kertas A4 Landscape (Screen & Print) */
    .a4-landscape {
        width: 297mm;
        min-height: 210mm;
        padding: 1.5cm 1.5cm;
        background: white;
        margin: 0 auto;
        box-sizing: border-box;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 0; /* Menghilangkan URL website, tanggal, judul halaman, dan nomor halaman bawaan browser */
        }

        /* Hilangkan SEMUA elemen di luar lembar cetak */
        header, 
        footer, 
        nav, 
        aside, 
        .no-print, 
        [role="navigation"], 
        .fixed, 
        #mobile-nav {
            display: none !important;
        }

        html, body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 297mm !important;
            height: 210mm !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body, main, #print-wrapper {
            display: block !important;
            overflow: visible !important;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: none !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
        }

        #printArea {
            transform: none !important;
            width: 297mm !important;
            min-height: 210mm !important;
            margin: 0 !important;
            padding: 12mm 15mm !important;
            border: none !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
            page-break-inside: avoid !important;
        }

        .a4-landscape {
            margin: 0 !important;
            box-shadow: none !important;
            padding: 12mm 15mm !important;
            width: 297mm !important;
            min-height: 210mm !important;
        }
    }

    .font-times {
        font-family: 'Times New Roman', Times, serif;
    }
    .font-cambria {
        font-family: 'Cambria', 'Caladea', Georgia, serif;
    }
</style>
@endpush

@section('content')
<div id="print-wrapper" class="space-y-6 max-w-[297mm] mx-auto pb-16">

    <!-- Top Action Bar (No-Print) -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-3 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('pengajuan-barang.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <div>
                <span class="text-xs font-bold text-slate-800 block">{{ $pengajuan->nomor_surat ?: 'Pengajuan Baru' }}</span>
                <span class="text-[11px] text-slate-400">{{ $pengajuan->program_keahlian }} • {{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Unduh Excel Asli -->
            <a href="{{ route('pengajuan-barang.export-excel', $pengajuan) }}" 
               class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-excel"></i> Unduh Excel
            </a>

            <!-- Tombol Cetak / Print -->
            <button type="button" onclick="window.print()" 
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> Cetak / Print A4
            </button>

            <!-- Edit -->
            <a href="{{ route('pengajuan-barang.edit', $pengajuan) }}" 
               class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen"></i> Edit
            </a>

            <!-- Hapus -->
            <form action="{{ route('pengajuan-barang.destroy', $pengajuan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition" title="Hapus">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Info Tip Khusus Tampilan Mobile -->
    <div class="md:hidden bg-indigo-50 border border-indigo-200 text-indigo-700 px-3.5 py-2.5 rounded-xl text-xs flex items-center gap-2 no-print">
        <i class="fa-solid fa-circle-info text-sm shrink-0"></i>
        <span>Format dokumen resmi A4 Landscape. Geser dokumen ke kanan-kiri atau gunakan tombol <strong>Cetak / Unduh Excel</strong> di atas.</span>
    </div>

    <!-- AREA DOKUMEN CETAK RESMI A4 LANDSCAPE -->
    <div class="overflow-x-auto pb-4 -mx-2 sm:mx-0 print:overflow-visible">
        <div id="printArea" class="bg-white rounded-2xl shadow-lg border border-slate-300 print:shadow-none print:border-none p-6 sm:p-8 lg:p-10 a4-landscape text-black">
            
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
        <div class="text-[11px] mb-4 space-y-1 font-sans">
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
                        <td class="border border-black p-1 text-center">
                            @if($item->gambar)
                                <img src="{{ $item->gambar_url }}" alt="Foto Barang" class="w-12 h-10 object-contain mx-auto rounded border border-slate-300">
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
        <div class="text-[11px] font-sans mb-6">
            <span class="font-bold italic">Terbilang : </span>
            <span class="italic font-semibold underline">{{ $pengajuan->terbilang ?: 'Nol Rupiah' }}</span>
        </div>

        <!-- 6. FORMAT TANDA TANGAN (3 PIHAK) -->
        <div class="text-[11px] font-sans mt-6">
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
    </div>

</div>
@endsection
