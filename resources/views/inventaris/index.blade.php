@extends('layouts.app')

@section('title', 'Daftar Buku Inventaris Barang (BIB) - Dana BOS')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buku Inventaris Barang (BIB)</h1>
            <p class="text-sm text-slate-500 mt-1">Pencatatan dan monitoring sarana prasarana pengadaan Dana BOS.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Tombol Tambah -->
            <a href="{{ route('inventaris.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <i class="fa-solid fa-plus-circle mr-2 text-blue-200"></i> Tambah Barang
            </a>

            <!-- Tombol Export Excel -->
            <a href="{{ route('inventaris.export.excel', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <i class="fa-solid fa-file-excel mr-2 text-emerald-200"></i> Unduh Excel
            </a>

            <!-- Tombol Cetak PDF -->
            <a href="{{ route('inventaris.export.pdf', request()->query()) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <i class="fa-solid fa-file-pdf mr-2 text-rose-200"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Aset -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-rupiah-sign"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Nilai Aset</p>
                <h3 class="text-lg font-bold text-slate-800">Rp {{ number_format($totalAset, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Unit -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Fisik Barang</p>
                <h3 class="text-lg font-bold text-slate-800">{{ number_format($totalBarang, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">Unit/Set</span></h3>
            </div>
        </div>

        <!-- Kondisi Baik -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kondisi Baik</p>
                <h3 class="text-lg font-bold text-slate-800">{{ $totalKondisiBaik }} <span class="text-xs font-normal text-slate-500">Item</span></h3>
            </div>
        </div>

        <!-- Kondisi Rusak -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perlu Perbaikan</p>
                <h3 class="text-lg font-bold text-slate-800">{{ $totalKondisiRusak }} <span class="text-xs font-normal text-slate-500">Item</span></h3>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('inventaris.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Text -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-xs font-bold text-slate-600 uppercase mb-1">Cari Barang / Kode</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Ketik nama barang atau kode inventaris..." 
                           class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Filter Tahun Anggaran -->
            <div>
                <label for="tahun_anggaran" class="block text-xs font-bold text-slate-600 uppercase mb-1">Tahun Anggaran</label>
                <select name="tahun_anggaran" id="tahun_anggaran" class="w-full py-2 px-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    <option value="">Semua Tahun</option>
                    @foreach($listTahunAnggaran as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun_anggaran') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kondisi -->
            <div>
                <label for="kondisi" class="block text-xs font-bold text-slate-600 uppercase mb-1">Kondisi</label>
                <select name="kondisi" id="kondisi" class="w-full py-2 px-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <!-- Filter Lokasi / Ruang -->
            <div>
                <label for="lokasi_ruang" class="block text-xs font-bold text-slate-600 uppercase mb-1">Lokasi Ruang</label>
                <select name="lokasi_ruang" id="lokasi_ruang" class="w-full py-2 px-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    <option value="">Semua Ruangan</option>
                    @foreach($listRuang as $ruang)
                        <option value="{{ $ruang }}" {{ request('lokasi_ruang') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="lg:col-span-5 flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <a href="{{ route('inventaris.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset Filter
                </a>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Menampilkan {{ $inventaris->total() }} Data Inventaris</span>
            <span class="text-xs text-slate-400">Halaman {{ $inventaris->currentPage() }} dari {{ $inventaris->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-600 uppercase">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Kode Barang</th>
                        <th class="py-3.5 px-4">Nama Barang & Spesifikasi</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Jumlah</th>
                        <th class="py-3.5 px-4 text-right">Harga Satuan</th>
                        <th class="py-3.5 px-4 text-right">Nilai Perolehan</th>
                        <th class="py-3.5 px-4">Lokasi Ruang</th>
                        <th class="py-3.5 px-4 text-center">Kondisi</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventaris as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 text-center text-xs text-slate-400 font-medium">
                                {{ $inventaris->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 font-mono text-xs font-bold text-blue-700 whitespace-nowrap">
                                {{ $item->kode_barang }}
                                <div class="text-[10px] text-slate-400 font-sans font-normal">Thn {{ $item->tahun_anggaran }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('inventaris.show', $item) }}" class="font-bold text-slate-800 hover:text-blue-600 transition-colors">
                                    {{ $item->nama_barang }}
                                </a>
                                <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $item->merk_spesifikasi }}</p>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600 whitespace-nowrap">
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md text-slate-700 font-medium">{{ $item->kategori }}</span>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800 whitespace-nowrap">
                                {{ $item->jumlah }} <span class="text-xs font-normal text-slate-500">{{ $item->satuan }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-slate-700 whitespace-nowrap text-xs">
                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-emerald-700 whitespace-nowrap text-xs">
                                Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600 whitespace-nowrap">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1 text-[11px]"></i> {{ $item->lokasi_ruang }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($item->kondisi === 'Baik')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Baik
                                    </span>
                                @elseif($item->kondisi === 'Rusak Ringan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Rusak Ringan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Rusak Berat
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- Detail -->
                                    <a href="{{ route('inventaris.show', $item) }}" 
                                       title="Lihat Detail" 
                                       class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('inventaris.edit', $item) }}" 
                                       title="Ubah Data" 
                                       class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <!-- Delete -->
                                    <form action="{{ route('inventaris.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data inventaris {{ $item->nama_barang }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus" 
                                                class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada data inventaris yang cocok</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm">Coba sesuaikan kata kunci pencarian atau ubah kombinasi filter Anda.</p>
                                    <a href="{{ route('inventaris.index') }}" class="mt-3 text-xs font-semibold text-blue-600 hover:underline">
                                        Reset Semua Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inventaris->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $inventaris->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
