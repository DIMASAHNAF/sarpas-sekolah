@extends('layouts.app')

@section('title', 'Pengajuan Barang & Alat Praktikum')

@section('content')
<div class="space-y-6">

    <!-- Header Section with Breadcrumb & Action Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <span>SIM-SARPRAS</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-indigo-600 font-bold">Pengajuan Barang Kaprodi</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2 flex-wrap">
                <span>Pengajuan Barang & Alat Praktikum</span>
                @if(!auth()->user()->isAdmin() && auth()->user()->jurusan)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        <i class="fa-solid fa-building-columns mr-1.5 text-indigo-600"></i> Unit: {{ auth()->user()->jurusan }}
                    </span>
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                @if(auth()->user()->isAdmin())
                    Formulir usulan pengadaan sarana, alat praktik, dan bahan habis pakai laboratorium/bengkel seluruh jurusan & unit sekolah.
                @else
                    Formulir usulan pengadaan sarana & alat praktik khusus unit <strong>{{ auth()->user()->jurusan }}</strong>.
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('pengajuan-barang.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <i class="fa-solid fa-plus-circle mr-2 text-indigo-200"></i> Buat Pengajuan Baru
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards (4 Columns) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Pengajuan -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold shrink-0">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Pengajuan</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800">{{ $totalPengajuan }} <span class="text-xs font-normal text-slate-400">Berkas</span></span>
            </div>
        </div>

        <!-- Card 2: Total Estimasi Biaya -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold shrink-0">
                <i class="fa-solid fa-rupiah-sign"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Estimasi</span>
                <span class="text-sm sm:text-base font-extrabold text-slate-800 break-all">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Card 3: Alat Praktikum -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold shrink-0">
                <i class="fa-solid fa-toolbox"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Alat Praktikum</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800">{{ $totalAlatPraktikum }} <span class="text-xs font-normal text-slate-400">Usulan</span></span>
            </div>
        </div>

        <!-- Card 4: Bahan Habis Pakai -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold shrink-0">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Habis Pakai (BHP)</span>
                <span class="text-lg sm:text-xl font-extrabold text-slate-800">{{ $totalBHP }} <span class="text-xs font-normal text-slate-400">Usulan</span></span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('pengajuan-barang.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            
            <!-- Pencarian -->
            <div class="sm:col-span-2 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nomor surat, jurusan, kaprodi..." 
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <!-- Filter Jurusan / Program Keahlian -->
            <div>
                <select name="program_keahlian" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Semua Jurusan & Unit</option>
                    @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan }}" {{ request('program_keahlian') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Filter Jenis -->
            <div>
                <select name="jenis_pengajuan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Semua Jenis Pengajuan</option>
                    <option value="ALAT PRAKTIKUM" {{ request('jenis_pengajuan') == 'ALAT PRAKTIKUM' ? 'selected' : '' }}>Alat Praktikum</option>
                    <option value="BARANG HABIS PAKAI" {{ request('jenis_pengajuan') == 'BARANG HABIS PAKAI' ? 'selected' : '' }}>Barang Habis Pakai</option>
                </select>
            </div>

            <!-- Filter Tahun Anggaran -->
            <div>
                <select name="tahun_anggaran" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Semua Tahun Anggaran</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ request('tahun_anggaran') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'program_keahlian', 'jenis_pengajuan', 'tahun_anggaran', 'status']))
                    <a href="{{ route('pengajuan-barang.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card Container with Mobile Card/Table Switcher -->
    <div x-data="{ mobileViewMode: 'card' }" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="px-3.5 sm:px-4 py-2.5 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2 bg-slate-50/70">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    {{ $pengajuanList->total() }} Data Pengajuan
                </span>
            </div>

            <!-- Mobile View Mode Switcher (Kartu vs Tabel) -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <div class="md:hidden flex items-center bg-slate-200/80 p-0.5 rounded-lg text-[10px] font-semibold">
                    <button type="button" 
                            @click="mobileViewMode = 'card'"
                            :class="mobileViewMode === 'card' ? 'bg-white text-indigo-600 shadow-xs font-bold' : 'text-slate-600'"
                            class="px-2 py-1 rounded-md transition-all flex items-center gap-1">
                        <i class="fa-solid fa-table-cells-large"></i> Kartu
                    </button>
                    <button type="button" 
                            @click="mobileViewMode = 'table'"
                            :class="mobileViewMode === 'table' ? 'bg-white text-indigo-600 shadow-xs font-bold' : 'text-slate-600'"
                            class="px-2 py-1 rounded-md transition-all flex items-center gap-1">
                        <i class="fa-solid fa-table-list"></i> Tabel
                    </button>
                </div>
                <span class="text-[11px] text-slate-400">Hal. {{ $pengajuanList->currentPage() }}/{{ $pengajuanList->lastPage() }}</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 1. TAMPILAN MOBILE: KARTU (CARD VIEW)      -->
        <!-- ========================================== -->
        <div class="p-3 space-y-2.5 md:hidden" x-show="mobileViewMode === 'card'">
            @forelse($pengajuanList as $index => $pengajuan)
                <div class="p-3.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:border-indigo-300 transition-all space-y-2.5">
                    
                    <!-- Card Top Row: Jurusan & Jenis Usulan Badge -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('pengajuan-barang.show', $pengajuan) }}" class="font-bold text-slate-800 text-xs sm:text-sm hover:text-indigo-600 line-clamp-2 block leading-snug">
                                {{ $pengajuan->program_keahlian }}
                            </a>
                            <p class="text-[10px] text-slate-500 line-clamp-1 mt-0.5">
                                {{ $pengajuan->laboratorium_bengkel ?: 'Laboratorium / Bengkel Umum' }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            @if($pengajuan->jenis_pengajuan === 'ALAT PRAKTIKUM')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1"></span> Alat Praktik
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span> Habis Pakai
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Meta Grid: Nomor Surat, Tanggal, Kaprodi, Status, Jml Item -->
                    <div class="grid grid-cols-2 gap-2 p-2.5 bg-slate-50 rounded-lg text-[11px]">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Nomor Surat</span>
                            <span class="font-mono text-indigo-700 font-bold break-all text-[10px]">{{ $pengajuan->nomor_surat ?: '-' }}</span>
                            <span class="text-[9px] text-slate-400 block mt-0.5"><i class="fa-regular fa-calendar mr-1"></i>{{ $pengajuan->tanggal_pengajuan->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Status Berkas</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span> {{ $pengajuan->status }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Penanggung Jawab</span>
                            <span class="text-slate-700 font-medium text-[10px] flex items-center truncate">
                                <i class="fa-solid fa-user-tie text-slate-400 mr-1 text-[9px]"></i> {{ $pengajuan->penanggung_jawab }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Jumlah Item</span>
                            <span class="font-bold text-slate-800 text-[10px]">{{ $pengajuan->items->count() }} Item Barang</span>
                        </div>
                    </div>

                    <!-- Card Bottom: Total Anggaran & Action Buttons -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase block font-semibold">Total Anggaran</span>
                            <span class="text-xs font-extrabold text-emerald-700">{{ $pengajuan->formatted_total_biaya }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('pengajuan-barang.show', $pengajuan) }}" 
                               title="Lihat Detail & Cetak" 
                               class="px-2.5 py-1 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-eye text-[10px]"></i> Detail
                            </a>

                            <a href="{{ route('pengajuan-barang.export-excel', $pengajuan) }}" 
                               title="Unduh Excel" 
                               class="p-1.5 rounded-lg text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                <i class="fa-solid fa-file-excel text-xs"></i>
                            </a>

                            <a href="{{ route('pengajuan-barang.edit', $pengajuan) }}" 
                               title="Ubah" 
                               class="p-1.5 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-100 transition-colors">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>

                            <form action="{{ route('pengajuan-barang.destroy', $pengajuan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Hapus" 
                                        class="p-1.5 rounded-lg text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="py-10 text-center text-slate-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                    <p class="text-xs font-bold text-slate-700">Belum ada data pengajuan barang</p>
                </div>
            @endforelse
        </div>

        <!-- ========================================== -->
        <!-- 2. TAMPILAN TABEL: DESKTOP & MOBILE TABLE   -->
        <!-- ========================================== -->
        <div class="overflow-x-auto" :class="mobileViewMode === 'table' ? 'block' : 'hidden md:block'">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-3 text-center w-12">No</th>
                        <th class="py-3 px-3">Nomor & Tanggal</th>
                        <th class="py-3 px-3">Jurusan / Lab</th>
                        <th class="py-3 px-3 text-center">Jenis Usulan</th>
                        <th class="py-3 px-3">Penanggung Jawab</th>
                        <th class="py-3 px-3 text-center">Jml Item</th>
                        <th class="py-3 px-3 text-right">Total Anggaran</th>
                        <th class="py-3 px-3 text-center">Status</th>
                        <th class="py-3 px-3 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pengajuanList as $index => $pengajuan)
                        <tr class="hover:bg-indigo-50/20 transition-colors">
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">
                                {{ $pengajuanList->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800 text-xs">
                                    {{ $pengajuan->nomor_surat ?: '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-regular fa-calendar mr-1"></i>{{ $pengajuan->tanggal_pengajuan->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="py-3 px-3 max-w-[200px]">
                                <div class="font-semibold text-slate-800 truncate" title="{{ $pengajuan->program_keahlian }}">
                                    {{ $pengajuan->program_keahlian }}
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">
                                    {{ $pengajuan->laboratorium_bengkel ?: 'Laboratorium Umum' }}
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                @if($pengajuan->jenis_pengajuan === 'ALAT PRAKTIKUM')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-toolbox mr-1"></i> Alat Praktikum
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-box-archive mr-1"></i> Habis Pakai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-medium text-slate-700">{{ $pengajuan->penanggung_jawab }}</div>
                                @if($pengajuan->nip_penanggung_jawab)
                                    <div class="text-[10px] font-mono text-slate-400">NIP: {{ $pengajuan->nip_penanggung_jawab }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center font-bold text-slate-700">
                                {{ $pengajuan->items->count() }} <span class="text-[10px] font-normal text-slate-400">item</span>
                            </td>
                            <td class="py-3 px-3 text-right font-extrabold text-emerald-700 whitespace-nowrap">
                                {{ $pengajuan->formatted_total_biaya }}
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $pengajuan->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Lihat & Cetak Surat -->
                                    <a href="{{ route('pengajuan-barang.show', $pengajuan) }}" 
                                       title="Lihat & Cetak Lembar Dokumen" 
                                       class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <!-- Unduh Excel Asli -->
                                    <a href="{{ route('pengajuan-barang.export-excel', $pengajuan) }}" 
                                       title="Unduh Format Excel Asli" 
                                       class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-file-excel text-xs"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('pengajuan-barang.edit', $pengajuan) }}" 
                                       title="Ubah Data Pengajuan" 
                                       class="p-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Hapus -->
                                    <form action="{{ route('pengajuan-barang.destroy', $pengajuan) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan {{ $pengajuan->nomor_surat }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Pengajuan" 
                                                class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 text-2xl mb-3">
                                        <i class="fa-solid fa-clipboard-question"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Belum Ada Riwayat Pengajuan Barang</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm">Mulai buat pengajuan alat praktikum atau barang habis pakai dengan menekan tombol di bawah.</p>
                                    <a href="{{ route('pengajuan-barang.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">
                                        <i class="fa-solid fa-plus-circle mr-1.5"></i> Buat Pengajuan Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuanList->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $pengajuanList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
