@extends('layouts.app')

@section('title', 'Daftar Buku Inventaris Barang (BIB) - Dana BOS')

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="{ 
    showImportModal: false, 
    showPdfModal: false,
    showResetModal: false, 
    showBulkDeleteModal: false,
    filterOpen: false,
    mobileViewMode: 'card',
    fileName: '',
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = Array.from(document.querySelectorAll('.item-checkbox')).map(el => el.value);
        } else {
            this.selectedIds = [];
        }
    },
    updateSelectAll() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        this.selectAll = checkboxes.length > 0 && this.selectedIds.length === checkboxes.length;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Buku Inventaris Barang (BIB)</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pencatatan dan monitoring sarana prasarana pengadaan Dana BOS / BOSP.</p>
        </div>

        <!-- Action Buttons (Responsive Grid on Mobile, Flex on Desktop) -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2">
            <!-- Tombol Tambah Barang (Bisa Diakses Admin & User) -->
            <a href="{{ route('inventaris.create') }}" 
               class="col-span-2 sm:col-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs hover:shadow transition-all duration-150">
                <i class="fa-solid fa-plus-circle mr-1.5 text-blue-200"></i> Tambah Barang
            </a>

            <!-- Tombol Import Excel (Bisa Diakses Semua User & Admin) -->
            <button type="button" 
                    @click="showImportModal = true"
                    class="inline-flex items-center justify-center px-3 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150">
                <i class="fa-solid fa-file-import mr-1.5 text-violet-200"></i> Import Excel
            </button>

            @if(auth()->user()->isAdmin())
                <!-- Tombol Export Excel (Khusus Admin) -->
                <a href="{{ route('inventaris.export.excel', request()->query()) }}" 
                   class="inline-flex items-center justify-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150">
                    <i class="fa-solid fa-file-excel mr-1.5 text-emerald-200"></i> Unduh Excel
                </a>

                <!-- Tombol Cetak PDF (Khusus Admin) -->
                <button type="button" 
                        @click="showPdfModal = true"
                        class="inline-flex items-center justify-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150">
                    <i class="fa-solid fa-file-pdf mr-1.5 text-orange-200"></i> Cetak PDF
                </button>
            @endif

            @if(auth()->user()->isAdmin() && $inventaris->total() > 0)
                <!-- Tombol Hapus Semua Data (Hanya Admin) -->
                <button type="button" 
                        @click="showResetModal = true"
                        onclick="openResetModalFallback()"
                        class="col-span-2 sm:col-auto inline-flex items-center justify-center px-3.5 py-2 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all duration-150 ring-1 ring-rose-300/50">
                    <i class="fa-solid fa-trash-can mr-1.5 text-rose-200"></i> Hapus Semua Data
                </button>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards (2 Columns on Mobile, 4 Columns on Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- Total Aset -->
        <div class="bg-white p-3 sm:p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm sm:text-base shrink-0">
                <i class="fa-solid fa-rupiah-sign"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400 truncate">Total Nilai Aset</p>
                <h3 class="text-xs sm:text-base lg:text-lg font-extrabold text-slate-800 truncate">Rp {{ number_format($totalAset, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Unit -->
        <div class="bg-white p-3 sm:p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm sm:text-base shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400 truncate">Total Fisik</p>
                <h3 class="text-xs sm:text-base lg:text-lg font-extrabold text-slate-800 truncate">
                    {{ number_format($totalBarang, 0, ',', '.') }} <span class="text-[10px] sm:text-xs font-normal text-slate-500">Unit</span>
                </h3>
            </div>
        </div>

        <!-- Kondisi Baik -->
        <div class="bg-white p-3 sm:p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm sm:text-base shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400 truncate">Kondisi Baik</p>
                <h3 class="text-xs sm:text-base lg:text-lg font-extrabold text-slate-800 truncate">
                    {{ $totalKondisiBaik }} <span class="text-[10px] sm:text-xs font-normal text-slate-500">Item</span>
                </h3>
            </div>
        </div>

        <!-- Kondisi Rusak -->
        <div class="bg-white p-3 sm:p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm sm:text-base shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400 truncate">Perlu Perbaikan</p>
                <h3 class="text-xs sm:text-base lg:text-lg font-extrabold text-slate-800 truncate">
                    {{ $totalKondisiRusak }} <span class="text-[10px] sm:text-xs font-normal text-slate-500">Item</span>
                </h3>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Bar (Collapsible on Mobile, Expanded on Desktop) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <!-- Mobile Filter Toggle Header -->
        <div class="lg:hidden p-3 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between cursor-pointer"
             @click="filterOpen = !filterOpen">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-filter text-blue-600 text-xs"></i>
                <span class="text-xs font-bold text-slate-700">Filter & Pencarian Barang</span>
                @if(request('search') || request('tahun_anggaran') || request('bulan') || request('kondisi') || request('lokasi_ruang'))
                    <span class="px-1.5 py-0.5 rounded-full bg-blue-600 text-white text-[9px] font-bold">Aktif</span>
                @endif
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 p-1">
                <i :class="filterOpen ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-xs"></i>
            </button>
        </div>

        <!-- Filter Form Body -->
        <div :class="filterOpen ? 'block' : 'hidden lg:block'" class="p-4">
            <form method="GET" action="{{ route('inventaris.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Search Text -->
                <div class="sm:col-span-2 md:col-span-3 lg:col-span-2">
                    <label for="search" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Barang / Kode</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Ketik nama atau kode barang..." 
                               class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Filter Tahun Anggaran -->
                <div class="lg:col-span-1">
                    <label for="tahun_anggaran" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tahun Anggaran</label>
                    <select name="tahun_anggaran" id="tahun_anggaran" class="w-full py-2 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="">Semua Tahun</option>
                        @foreach($listTahunAnggaran as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun_anggaran') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div class="lg:col-span-1">
                    <label for="bulan" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Bulan</label>
                    <select name="bulan" id="bulan" class="w-full py-2 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="">Semua Bulan</option>
                        @foreach($listBulan as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $num }} - {{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kondisi -->
                <div class="lg:col-span-1">
                    <label for="kondisi" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kondisi</label>
                    <select name="kondisi" id="kondisi" class="w-full py-2 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <!-- Filter Lokasi / Ruang -->
                <div class="lg:col-span-1">
                    <label for="lokasi_ruang" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Lokasi Ruang</label>
                    <select name="lokasi_ruang" id="lokasi_ruang" class="w-full py-2 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="">Semua Ruangan</option>
                        @foreach($listRuang as $ruang)
                            <option value="{{ $ruang }}" {{ request('lokasi_ruang') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="col-span-full flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('inventaris.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                    </a>
                    <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors">
                        <i class="fa-solid fa-filter mr-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Inventaris Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Bar Tabel & Switcher -->
        <div class="px-3.5 sm:px-4 py-2.5 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2 bg-slate-50/70">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    {{ $inventaris->total() }} Data Inventaris
                </span>
            </div>

            <!-- Mobile View Mode Switcher (Kartu vs Tabel) -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <div class="md:hidden flex items-center bg-slate-200/80 p-0.5 rounded-lg text-[10px] font-semibold">
                    <button type="button" 
                            @click="mobileViewMode = 'card'"
                            :class="mobileViewMode === 'card' ? 'bg-white text-blue-600 shadow-xs font-bold' : 'text-slate-600'"
                            class="px-2 py-1 rounded-md transition-all flex items-center gap-1">
                        <i class="fa-solid fa-table-cells-large"></i> Kartu
                    </button>
                    <button type="button" 
                            @click="mobileViewMode = 'table'"
                            :class="mobileViewMode === 'table' ? 'bg-white text-blue-600 shadow-xs font-bold' : 'text-slate-600'"
                            class="px-2 py-1 rounded-md transition-all flex items-center gap-1">
                        <i class="fa-solid fa-table-list"></i> Tabel
                    </button>
                </div>
                <span class="text-[11px] text-slate-400">Hal. {{ $inventaris->currentPage() }}/{{ $inventaris->lastPage() }}</span>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- Batch Action Bar ketika ada item yang dicentang (Admin Only) -->
        <div x-show="selectedIds.length > 0" 
             x-cloak 
             class="px-4 py-2 bg-rose-50 border-b border-rose-200 flex flex-wrap items-center justify-between gap-2 transition-all">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span class="text-xs font-bold text-rose-800"><span x-text="selectedIds.length"></span> data dipilih</span>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" 
                        @click="selectedIds = []; selectAll = false" 
                        class="px-2.5 py-1 text-[11px] font-semibold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 rounded-lg transition-colors">
                    Batal
                </button>
                <button type="button" 
                        @click="showBulkDeleteModal = true" 
                        class="inline-flex items-center px-3 py-1 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs hover:shadow transition-all">
                    <i class="fa-solid fa-trash-can mr-1.5 text-[10px]"></i> Hapus Terpilih
                </button>
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- 1. TAMPILAN MOBILE: KARTU (CARD VIEW)      -->
        <!-- ========================================== -->
        <div class="p-3 space-y-2.5 md:hidden" x-show="mobileViewMode === 'card'">
            @forelse($inventaris as $index => $item)
                <div class="p-3.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:border-blue-300 transition-all space-y-2.5">
                    
                    <!-- Card Top Row: Nama & Kondisi -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('inventaris.show', $item) }}" class="font-bold text-slate-800 text-xs sm:text-sm hover:text-blue-600 line-clamp-2 block leading-snug">
                                {{ $item->nama_barang }}
                            </a>
                            @if($item->merk_spesifikasi)
                                <p class="text-[10px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->merk_spesifikasi }}</p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            @if($item->kondisi === 'Baik')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span> Baik
                                </span>
                            @elseif($item->kondisi === 'Rusak Ringan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span> R. Ringan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1"></span> R. Berat
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Meta Grid: Kode, Kategori, Ruang, Jumlah -->
                    <div class="grid grid-cols-2 gap-2 p-2 bg-slate-50 rounded-lg text-[11px]">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Kode Barang</span>
                            <span class="font-mono text-blue-700 font-bold break-all text-[10px]">{{ $item->kode_barang }}</span>
                            <span class="text-[9px] text-slate-400 block">Thn {{ $item->tahun_anggaran }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Kategori</span>
                            <span class="font-medium text-slate-700 text-[10px]">{{ $item->kategori }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Lokasi Ruang</span>
                            <span class="text-slate-700 text-[10px] flex items-center truncate">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1 text-[9px]"></i> {{ $item->lokasi_ruang }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Jumlah Fisik</span>
                            <span class="font-bold text-slate-800 text-[10px]">{{ $item->jumlah }} {{ $item->satuan }}</span>
                        </div>
                    </div>

                    <!-- Card Bottom: Nilai & Aksi -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase block font-semibold">Nilai Total</span>
                            <span class="text-xs font-extrabold text-emerald-700">Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('inventaris.show', $item) }}" 
                               title="Lihat Detail" 
                               class="px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                                <i class="fa-solid fa-eye mr-1"></i> Detail
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('inventaris.edit', $item) }}" 
                                   title="Ubah Data" 
                                   class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('inventaris.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $item->nama_barang }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Hapus" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="py-10 text-center text-slate-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                    <p class="text-xs font-bold text-slate-700">Tidak ada data inventaris yang cocok</p>
                </div>
            @endforelse
        </div>

        <!-- ========================================== -->
        <!-- 2. TAMPILAN TABEL: DESKTOP & MOBILE TABLE   -->
        <!-- ========================================== -->
        <div class="overflow-x-auto" :class="mobileViewMode === 'table' ? 'block' : 'hidden md:block'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-[10px] sm:text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        @if(auth()->user()->isAdmin())
                        <th class="py-2 px-1.5 w-[24px] text-center">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleAll()" 
                                   title="Pilih Semua" 
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        @endif
                        <th class="py-2 px-1.5 w-[30px] text-center">No</th>
                        <th class="py-2 px-2 w-[120px]">Kode Barang</th>
                        <th class="py-2 px-2 min-w-[160px]">Nama Barang & Spesifikasi</th>
                        <th class="py-2 px-1.5 w-[75px] text-center whitespace-nowrap">Kategori</th>
                        <th class="py-2 px-1 w-[50px] text-center whitespace-nowrap">Jumlah</th>
                        <th class="py-2 px-2 w-[85px] text-right whitespace-nowrap">Harga</th>
                        <th class="py-2 px-2 w-[90px] text-right whitespace-nowrap">Nilai Total</th>
                        <th class="py-2 px-2 w-[100px]">Lokasi Ruang</th>
                        <th class="py-2 px-1.5 w-[75px] text-center whitespace-nowrap">Kondisi</th>
                        <th class="py-2 px-1.5 w-[70px] text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($inventaris as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors" :class="selectedIds.includes('{{ $item->id }}') ? 'bg-blue-50/40' : ''">
                            @if(auth()->user()->isAdmin())
                            <td class="py-1.5 px-1.5 text-center">
                                <input type="checkbox" 
                                       value="{{ $item->id }}" 
                                       x-model="selectedIds" 
                                       @change="updateSelectAll()"
                                       class="item-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            @endif
                            <td class="py-1.5 px-1.5 text-center text-[10px] text-slate-400 font-medium">
                                {{ $inventaris->firstItem() + $index }}
                            </td>
                            <td class="py-1.5 px-2 max-w-[120px]">
                                <div class="font-mono text-[10px] font-bold text-blue-700 tracking-tight break-all leading-tight">
                                    {{ $item->kode_barang }}
                                </div>
                                <div class="text-[9px] text-slate-400 font-sans mt-0.5">Thn {{ $item->tahun_anggaran }}</div>
                            </td>
                            <td class="py-1.5 px-2 min-w-[160px] max-w-[260px]">
                                <a href="{{ route('inventaris.show', $item) }}" 
                                   class="font-bold text-slate-800 hover:text-blue-600 transition-colors line-clamp-2 text-xs leading-snug block" 
                                   title="{{ $item->nama_barang }}">
                                    {{ $item->nama_barang }}
                                </a>
                                @if($item->merk_spesifikasi)
                                    <p class="text-[10px] text-slate-400 line-clamp-1 mt-0.5 block" title="{{ $item->merk_spesifikasi }}">{{ $item->merk_spesifikasi }}</p>
                                @endif
                            </td>
                            <td class="py-1.5 px-1.5 whitespace-nowrap text-center">
                                <span class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-600 font-medium text-[10px]">{{ $item->kategori }}</span>
                            </td>
                            <td class="py-1.5 px-1 text-center whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-[11px]">{{ $item->jumlah }}</span>
                                <span class="text-[9px] text-slate-400 ml-0.5">{{ $item->satuan }}</span>
                            </td>
                            <td class="py-1.5 px-2 text-right font-medium text-slate-600 whitespace-nowrap text-[10px]">
                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-1.5 px-2 text-right font-bold text-emerald-700 whitespace-nowrap text-[11px]">
                                Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}
                            </td>
                            <td class="py-1.5 px-2 text-[10px] text-slate-600 max-w-[100px] truncate" title="{{ $item->lokasi_ruang }}">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1 text-[8px]"></i>{{ $item->lokasi_ruang }}
                            </td>
                            <td class="py-1.5 px-1.5 text-center whitespace-nowrap">
                                @if($item->kondisi === 'Baik')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span> Baik
                                    </span>
                                @elseif($item->kondisi === 'Rusak Ringan')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span> R. Ringan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1"></span> R. Berat
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-1.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- Detail -->
                                    <a href="{{ route('inventaris.show', $item) }}" 
                                       title="Lihat Detail" 
                                       class="w-6 h-6 rounded-md flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    @if(auth()->user()->isAdmin())
                                        <!-- Edit -->
                                        <a href="{{ route('inventaris.edit', $item) }}" 
                                           title="Ubah Data" 
                                           class="w-6 h-6 rounded-md flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('inventaris.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data inventaris {{ $item->nama_barang }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus" 
                                                    class="w-6 h-6 rounded-md flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-2">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-700">Tidak ada data inventaris yang cocok</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5 max-w-sm">Coba sesuaikan kata kunci pencarian atau ubah kombinasi filter Anda.</p>
                                    <a href="{{ route('inventaris.index') }}" class="mt-2 text-xs font-semibold text-blue-600 hover:underline">
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
            <div class="px-4 py-3 border-t border-slate-200">
                {{ $inventaris->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================== -->
    <!-- MODALS                                         -->
    <!-- ============================================== -->

    <!-- Modal Import Excel (Bisa Diakses Semua Pengguna / Staf & Admin) -->
    <div x-show="showImportModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showImportModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="showImportModal = false"></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showImportModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                
                <!-- Modal Header -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-file-import"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800" id="modal-title">Import Data dari Excel</h3>
                            <p class="text-xs text-slate-500">Unggah file Excel Buku Inventaris Barang (.xlsx / .xls)</p>
                        </div>
                    </div>
                    <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('inventaris.import.excel') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    <!-- Petunjuk format -->
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-start space-x-2.5 text-xs text-blue-800">
                        <i class="fa-solid fa-circle-info text-base text-blue-600 mt-0.5 shrink-0"></i>
                        <p class="text-blue-700 leading-relaxed">
                            Sistem membaca file Excel resmi Buku Inventaris Barang Dana BOS / BOSP. Nilai perolehan dihitung otomatis.
                        </p>
                    </div>

                    <!-- Input File -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Pilih File Spreadsheet</label>
                        <div class="relative border-2 border-dashed border-slate-300 hover:border-violet-500 rounded-2xl p-5 text-center transition-all bg-slate-50/50 hover:bg-violet-50/30 group cursor-pointer"
                             @click="$refs.fileInput.click()">
                            <input type="file" 
                                   name="file_excel" 
                                   x-ref="fileInput" 
                                   accept=".xlsx,.xls,.csv" 
                                   required 
                                   class="hidden"
                                   @change="fileName = $refs.fileInput.files[0] ? $refs.fileInput.files[0].name : ''">
                            
                            <div class="flex flex-col items-center justify-center space-y-1.5">
                                <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="text-xs">
                                    <span class="font-bold text-violet-700 hover:underline">Klik untuk telusuri</span> atau seret file ke sini
                                </div>
                                <p class="text-[10px] text-slate-400">Mendukung .xlsx, .xls, .csv (Maks. 10 MB)</p>
                            </div>

                            <div x-show="fileName" x-cloak class="mt-2.5 pt-2.5 border-t border-slate-200/80 flex items-center justify-center space-x-2 text-xs font-semibold text-emerald-700">
                                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                                <span x-text="fileName" class="truncate max-w-[280px]"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi Timpa / Gantikan Data Lama -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="flex items-start space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="truncate_old" value="1" class="mt-0.5 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                            <div>
                                <span class="text-xs font-bold text-slate-700">Kosongkan data lama sebelum import</span>
                                <p class="text-[10px] text-slate-500">Centang jika ingin menghapus seluruh data inventaris lama sebelum isi file baru diimpor.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Unduh Template Link -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <span class="text-slate-500">Butuh template kosong?</span>
                        <a href="{{ route('inventaris.template.excel') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center">
                            <i class="fa-solid fa-download mr-1 text-xs"></i> Unduh Format Template (.xlsx)
                        </a>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
                        <button type="button" 
                                @click="showImportModal = false" 
                                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 text-xs font-bold text-white bg-violet-600 hover:bg-violet-700 rounded-xl shadow-xs transition-all inline-flex items-center">
                            <i class="fa-solid fa-arrow-up-from-bracket mr-1.5"></i> Proses Import
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal Cetak PDF (Pilih Bulan & Tahun - Semua Pengguna) -->
    <div x-show="showPdfModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-pdf-title" 
         role="dialog" 
         aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showPdfModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="showPdfModal = false"></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showPdfModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                
                <!-- Modal Header -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800" id="modal-pdf-title">Cetak Buku Inventaris (PDF)</h3>
                            <p class="text-xs text-slate-500">Pilih periode bulan dan tahun dokumen inventaris yang akan dicetak</p>
                        </div>
                    </div>
                    <button type="button" @click="showPdfModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form Cetak PDF -->
                <form action="{{ route('inventaris.export.pdf') }}" method="GET" target="_blank" @submit="setTimeout(() => { showPdfModal = false; }, 400)" class="p-6 space-y-4">
                    
                    <!-- Petunjuk format PDF -->
                    <div class="p-3 bg-orange-50 border border-orange-200 rounded-xl flex items-start space-x-2.5 text-xs text-orange-800">
                        <i class="fa-solid fa-circle-info text-base text-orange-600 mt-0.5 shrink-0"></i>
                        <p class="text-orange-800 leading-relaxed">
                            Dokumen akan dicetak dalam format landscape A4 lengkap dengan identitas sekolah dan <strong>Lembar Pengesahan resmi</strong> (Disahkan Oleh: Kepala Sekolah, Diketahui: Wakasek Sarpras).
                        </p>
                    </div>

                    <!-- Pilihan Tahun Anggaran -->
                    <div>
                        <label for="modal_pdf_tahun" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar mr-1 text-slate-400"></i> Tahun Anggaran
                        </label>
                        <select name="tahun_anggaran" id="modal_pdf_tahun" class="w-full py-2.5 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                            <option value="">Semua Tahun Anggaran</option>
                            @foreach($listTahunAnggaran as $tahun)
                                <option value="{{ $tahun }}" {{ (request('tahun_anggaran') == $tahun || (!request('tahun_anggaran') && $loop->first)) ? 'selected' : '' }}>
                                    Tahun Anggaran {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilihan Bulan Perolehan -->
                    <div>
                        <label for="modal_pdf_bulan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar-days mr-1 text-slate-400"></i> Bulan Perolehan
                        </label>
                        <select name="bulan" id="modal_pdf_bulan" class="w-full py-2.5 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                            <option value="">Semua Bulan (Laporan 1 Tahun Penuh)</option>
                            @foreach($listBulan as $num => $nama)
                                <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                    Bulan {{ $num }} - {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tambahan Opsional (Kondisi & Lokasi) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div>
                            <label for="modal_pdf_kondisi" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Filter Kondisi</label>
                            <select name="kondisi" id="modal_pdf_kondisi" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                                <option value="">Semua Kondisi</option>
                                <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label for="modal_pdf_lokasi" class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Filter Lokasi Ruang</label>
                            <select name="lokasi_ruang" id="modal_pdf_lokasi" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all">
                                <option value="">Semua Ruangan</option>
                                @foreach($listRuang as $ruang)
                                    <option value="{{ $ruang }}" {{ request('lokasi_ruang') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
                        <button type="button" 
                                @click="showPdfModal = false" 
                                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-xl shadow-xs transition-all inline-flex items-center">
                            <i class="fa-solid fa-file-pdf mr-1.5"></i> Buka &amp; Cetak PDF
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Modal Konfirmasi Hapus Data Terpilih (Bulk Delete - Admin Only) -->
    <div x-show="showBulkDeleteModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         aria-labelledby="modal-bulk-title" 
         role="dialog" 
         aria-modal="true">
        <div x-show="showBulkDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="showBulkDeleteModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showBulkDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">

                <div class="bg-rose-50 px-6 py-4 border-b border-rose-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800" id="modal-bulk-title">Hapus Data Terpilih</h3>
                            <p class="text-xs text-slate-500">Konfirmasi penghapusan data terpilih</p>
                        </div>
                    </div>
                    <button type="button" @click="showBulkDeleteModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
                        <p class="text-sm text-rose-800 leading-relaxed">
                            Anda akan menghapus <span class="font-bold text-rose-700" x-text="selectedIds.length"></span> data inventaris yang telah dicentang.
                        </p>
                        <p class="text-xs text-rose-700 mt-2 leading-relaxed">
                            Data yang dihapus tidak dapat dipulihkan kembali. Pastikan pilihan Anda sudah benar.
                        </p>
                    </div>

                    <form action="{{ route('inventaris.bulkDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="selected_ids[]" :value="id">
                        </template>

                        <div class="flex items-center justify-end space-x-2 pt-2">
                            <button type="button" 
                                    @click="showBulkDeleteModal = false" 
                                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs hover:shadow transition-all inline-flex items-center">
                                <i class="fa-solid fa-trash-can mr-2"></i> Ya, Hapus Terpilih
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data (Admin Only) -->
    <div id="modalResetAll"
         x-show="showResetModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         aria-labelledby="modal-reset-title" 
         role="dialog" 
         aria-modal="true">
        <div x-show="showResetModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="showResetModal = false"
             onclick="closeResetModalFallback()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showResetModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">

                <div class="bg-rose-50 px-6 py-4 border-b border-rose-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800" id="modal-reset-title">Hapus Semua Data Inventaris</h3>
                            <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="showResetModal = false" 
                            onclick="closeResetModalFallback()" 
                            class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
                        <p class="text-sm text-rose-800 leading-relaxed">
                            Seluruh <span class="font-bold">{{ number_format($seluruhDataInventaris ?? $inventaris->total(), 0, ',', '.') }} data inventaris</span> dalam tabel akan dihapus permanen.
                        </p>
                        <p class="text-xs text-rose-700 mt-2 leading-relaxed">
                            Tindakan ini mengosongkan seluruh Buku Inventaris Barang dan tidak bisa dibatalkan. Pastikan Anda sudah membuat cadangan (Unduh Excel) jika data masih dibutuhkan.
                        </p>
                    </div>

                    <form action="{{ route('inventaris.destroyAll') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex items-center justify-end space-x-2">
                            <button type="button" 
                                    @click="showResetModal = false" 
                                    onclick="closeResetModalFallback()"
                                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs hover:shadow transition-all inline-flex items-center">
                                <i class="fa-solid fa-trash-can mr-2"></i> Ya, Hapus Semua
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    function openResetModalFallback() {
        const modal = document.getElementById('modalResetAll');
        if (modal) {
            modal.style.display = 'block';
        }
    }
    function closeResetModalFallback() {
        const modal = document.getElementById('modalResetAll');
        if (modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
