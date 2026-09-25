@extends('layouts.app')

@section('title', 'Detail Inventaris: ' . $inventaris->nama_barang)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('inventaris.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center mb-1">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $inventaris->nama_barang }}</h1>
            <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $inventaris->kode_barang }}</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('inventaris.edit', $inventaris) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-colors">
                <i class="fa-solid fa-pen mr-2 text-xs"></i> Ubah Data
            </a>
            <form action="{{ route('inventaris.destroy', $inventaris) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data inventaris ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200 transition-colors">
                    <i class="fa-solid fa-trash-can mr-2 text-xs"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden divide-y divide-slate-100">
        
        <!-- Status & Nilai Header Banner -->
        <div class="p-6 bg-gradient-to-r from-slate-900 to-blue-950 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs uppercase font-semibold text-blue-300">Nilai Perolehan Aset</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-mono mt-1">
                    {{ $inventaris->formatted_nilai_perolehan }}
                </h2>
                <p class="text-xs text-slate-300 mt-1">
                    {{ $inventaris->jumlah }} {{ $inventaris->satuan }} &bull; @ Rp {{ number_format($inventaris->harga_satuan, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Status / Kondisi</span>
                @if($inventaris->kondisi === 'Baik')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> Baik (Layak Pakai)
                    </span>
                @elseif($inventaris->kondisi === 'Rusak Ringan')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Rusak Ringan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                        <i class="fa-solid fa-circle-xmark mr-1.5"></i> Rusak Berat (Perlu Afkir)
                    </span>
                @endif
            </div>
        </div>

        <!-- Spesifikasi & Detail -->
        <div class="p-6 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Spesifikasi & Barang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-500 block">Kategori</span>
                    <span class="font-semibold text-slate-800">{{ $inventaris->kategori }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Nomor Register</span>
                    <span class="font-semibold text-slate-800 font-mono">{{ $inventaris->nomor_register }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-xs text-slate-500 block mb-1">Merk / Spesifikasi Lengkap</span>
                    <div class="p-3 bg-slate-50 rounded-xl text-slate-700 text-xs leading-relaxed border border-slate-200">
                        {{ $inventaris->merk_spesifikasi }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengadaan & Dokumen Legalitas -->
        <div class="p-6 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengadaan & Dokumen Pendukung</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-500 block">Sumber Dana</span>
                    <span class="font-semibold text-slate-800">{{ $inventaris->sumber_dana }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Tahun Anggaran</span>
                    <span class="font-semibold text-slate-800">{{ $inventaris->tahun_anggaran }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">No. BAST</span>
                    <span class="font-semibold text-slate-800 font-mono">{{ $inventaris->no_bast }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Tanggal Perolehan</span>
                    <span class="font-semibold text-slate-800">{{ $inventaris->tanggal_perolehan ? $inventaris->tanggal_perolehan->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Tanggal Pencatatan</span>
                    <span class="font-semibold text-slate-800">{{ $inventaris->tanggal_pencatatan ? $inventaris->tanggal_pencatatan->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Tautan Dokumen</span>
                    @if($inventaris->tautan_dokumen)
                        <a href="{{ $inventaris->tautan_dokumen }}" target="_blank" class="text-blue-600 hover:underline font-medium inline-flex items-center text-xs">
                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka File Pendukung
                        </a>
                    @else
                        <span class="text-slate-400 text-xs italic">Tidak ada tautan dokumen</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Penempatan & Penanggung Jawab -->
        <div class="p-6 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi & Penanggung Jawab</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-500 block">Lokasi Ruangan</span>
                    <span class="font-bold text-slate-800 flex items-center mt-0.5">
                        <i class="fa-solid fa-location-dot text-rose-500 mr-2"></i> {{ $inventaris->lokasi_ruang }}
                    </span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">Penanggung Jawab</span>
                    <span class="font-bold text-slate-800 flex items-center mt-0.5">
                        <i class="fa-solid fa-user-check text-blue-500 mr-2"></i> {{ $inventaris->penanggung_jawab }}
                    </span>
                </div>
            </div>
            @if($inventaris->keterangan)
                <div class="pt-2">
                    <span class="text-xs text-slate-500 block mb-1">Catatan / Keterangan</span>
                    <p class="text-xs text-slate-600 bg-amber-50/60 border border-amber-200 p-3 rounded-xl">
                        {{ $inventaris->keterangan }}
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
