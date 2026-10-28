@extends('layouts.app')

@section('title', 'Ubah Pengajuan Barang - ' . ($pengajuan->nomor_surat ?: 'SMKN 1 Beringin'))

@push('styles')
<style>
    /* Format Kertas A4 (Screen & Print) */
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

        body, main, #pengajuan-wrapper {
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
<div id="pengajuan-wrapper" x-data="pengajuanEditForm()" class="flex flex-col xl:flex-row gap-6 pb-20 w-full max-w-[1920px] mx-auto">
    
    <!-- Bagian Form Input (Kiri) -->
    <aside class="w-full xl:w-[480px] shrink-0 bg-white rounded-2xl shadow-sm border border-slate-200 no-print flex flex-col h-fit">
        <form action="{{ route('pengajuan-barang.update', $pengajuan) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Header Form Info -->
            <div class="bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 rounded-2xl p-4 sm:p-5">
                <div class="flex items-center gap-2 text-indigo-700 text-xs font-bold uppercase mb-1">
                    <i class="fa-solid fa-pen-to-square"></i> Perbarui Data Usulan
                </div>
                <h2 class="text-base font-bold text-slate-900">Ubah Pengajuan Barang</h2>
                <p class="text-xs text-slate-500 mt-1">Ubah data atau rincian item, perubahan otomatis tersimpan ke database.</p>
            </div>

            <!-- 1. Identitas Pengajuan -->
            <div class="border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">
                    1. Identitas Usulan & Jurusan
                </h3>

                <!-- Nomor Surat & Status -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Surat</label>
                        <input type="text" name="nomor_surat" x-model="formData.nomorSurat" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pengajuan</label>
                        @if(auth()->user()->isAdmin())
                            <select name="status" x-model="formData.status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-emerald-700 outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Draft">Draft</option>
                                <option value="Diajukan">Diajukan</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        @else
                            <div class="px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                                <span>{{ $pengajuan->status }}</span>
                                <input type="hidden" name="status" value="{{ $pengajuan->status }}">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Jenis Pengajuan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Pengajuan</label>
                    <select name="jenis_pengajuan" x-model="formData.jenisPengajuan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-indigo-700 outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="ALAT PRAKTIKUM">PENGAJUAN BARANG / ALAT PRAKTIKUM</option>
                        <option value="BARANG HABIS PAKAI">PENGAJUAN BARANG HABIS PAKAI PRAKTIKUM</option>
                    </select>
                </div>

                <!-- Program Keahlian / Jurusan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Program Keahlian / Unit
                        @if(!auth()->user()->isAdmin())
                            <span class="text-indigo-600 font-bold">(Terkunci Sesuai Unit Anda)</span>
                        @endif
                    </label>
                    @if(auth()->user()->isAdmin())
                        <select name="program_keahlian" x-model="formData.programKeahlian" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:ring-2 focus:ring-indigo-500" required>
                            @foreach($jurusanList as $jurusan)
                                <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="flex items-center space-x-2 px-3 py-2 bg-indigo-50/80 border border-indigo-200 rounded-xl text-indigo-900 font-bold text-xs">
                            <i class="fa-solid fa-building-columns text-indigo-600"></i>
                            <span>{{ $pengajuan->program_keahlian }}</span>
                            <input type="hidden" name="program_keahlian" value="{{ $pengajuan->program_keahlian }}">
                        </div>
                    @endif
                </div>

                <!-- Laboratorium / Bengkel -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Laboratorium / Bengkel</label>
                    <input type="text" name="laboratorium_bengkel" x-model="formData.labBengkel" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Grid: Tahun Anggaran & Tanggal -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun Anggaran</label>
                        <input type="number" name="tahun_anggaran" x-model.number="formData.tahunAnggaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Pengajuan</label>
                        <input type="date" name="tanggal_pengajuan" x-model="formData.tanggalPengajuan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                </div>

                <!-- Penanggung Jawab & NIP -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penanggung Jawab (Nama Kaprodi)</label>
                    <input type="text" name="penanggung_jawab" x-model="formData.penanggungJawab" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP Penanggung Jawab</label>
                    <input type="text" name="nip_penanggung_jawab" x-model="formData.nipPenanggungJawab" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                </div>
            </div>

            <!-- 2. Rincian Barang / Alat -->
            <div class="border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        2. Daftar Kebutuhan Barang
                    </h3>
                    <button type="button" @click="addItem()" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Item
                    </button>
                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl relative group hover:border-indigo-300 transition">
                            
                            <!-- Header Item & Tombol Hapus -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 bg-white border border-slate-200 rounded text-[10px] font-bold text-slate-700 uppercase" x-text="`Barang #${index + 1}`"></span>
                                <button type="button" @click="removeItem(item.id)" class="text-rose-400 hover:text-rose-600 p-1" title="Hapus Item" x-show="items.length > 1">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>

                            <div class="space-y-2.5">
                                <!-- Nama Barang -->
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-600 mb-1">Nama Barang/Alat *</label>
                                    <input type="text" :name="`items[${index}][nama_barang]`" x-model="item.nama" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500" required>
                                </div>

                                <!-- Fungsi & Merk -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-600 mb-1">Merk / Brand</label>
                                        <input type="text" :name="`items[${index}][merk]`" x-model="item.merk" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-600 mb-1">Fungsi Alat</label>
                                        <input type="text" :name="`items[${index}][fungsi]`" x-model="item.fungsi" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none">
                                    </div>
                                </div>

                                <!-- Spesifikasi Lengkap -->
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-600 mb-1">Spesifikasi Lengkap</label>
                                    <textarea :name="`items[${index}][spesifikasi]`" x-model="item.spek" rows="2" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none"></textarea>
                                </div>

                                <!-- Jumlah, Satuan, Harga Satuan -->
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-600 mb-1">Jumlah *</label>
                                        <input type="number" :name="`items[${index}][jumlah]`" x-model.number="item.jumlah" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-600 mb-1">Satuan *</label>
                                        <input type="text" :name="`items[${index}][satuan]`" x-model="item.satuan" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-600 mb-1">Harga Satuan (Rp) *</label>
                                        <input type="number" :name="`items[${index}][harga_satuan]`" x-model.number="item.hargaSatuan" min="0" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs outline-none" required>
                                    </div>
                                </div>

                                <!-- Catatan / Keterangan -->
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-600 mb-1">Catatan / Alasan</label>
                                    <input type="text" :name="`items[${index}][catatan]`" x-model="item.catatan" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs outline-none">
                                </div>

                                <!-- Upload Foto / Gambar Barang & Sesuaikan Ukuran -->
                                <div class="pt-2 border-t border-slate-200/80 space-y-2">
                                    <label class="block text-[11px] font-semibold text-slate-700">
                                        <i class="fa-regular fa-image mr-1 text-indigo-600"></i> Lampiran Gambar Barang
                                    </label>

                                    <!-- Upload State: Belum ada gambar -->
                                    <div x-show="!item.gambar_preview" class="border border-dashed border-slate-300 hover:border-indigo-400 rounded-xl p-3 text-center bg-white hover:bg-indigo-50/20 transition relative cursor-pointer">
                                        <input type="file" @change="handleItemImage($event, item)" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="flex items-center justify-center space-x-2 text-slate-600">
                                            <i class="fa-solid fa-cloud-arrow-up text-indigo-600 text-sm"></i>
                                            <span class="text-xs font-bold">Pilih / Upload Gambar</span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP (Akan muncul di kolom tabel & lembar cetak)</p>
                                    </div>

                                    <!-- Preview State: Sudah ada gambar -->
                                    <div x-show="item.gambar_preview" class="space-y-2" style="display: none;">
                                        <div class="relative rounded-xl border border-slate-200 bg-white p-2 flex items-center space-x-3">
                                            <img :src="item.gambar_preview" class="w-14 h-12 object-contain rounded-lg border border-slate-100 bg-slate-50 p-1">
                                            <div class="flex-1 min-w-0 text-left">
                                                <span class="text-[11px] font-bold text-slate-800 block truncate" x-text="item.gambar_nama || 'Foto Barang'"></span>
                                                <span class="text-[10px] text-emerald-600 font-semibold flex items-center">
                                                    <i class="fa-solid fa-circle-check mr-1 text-[9px]"></i> Muncul pada cetakan
                                                </span>
                                            </div>
                                            <button @click="removeItemImage(item)" type="button" class="w-7 h-7 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 flex items-center justify-center transition" title="Hapus Gambar">
                                                <i class="fa-solid fa-xmark text-sm font-bold"></i>
                                            </button>
                                        </div>

                                        <!-- Pengatur Skala / Ukuran (Seperti di Berita Acara) -->
                                        <div class="bg-indigo-50/60 p-2.5 rounded-xl border border-indigo-100">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[11px] font-bold text-indigo-900 flex items-center">
                                                    <i class="fa-solid fa-up-right-and-down-left-from-center mr-1 text-[10px] text-indigo-600"></i> Sesuaikan Ukuran Gambar di Dokumen:
                                                </span>
                                                <span class="text-xs font-extrabold text-indigo-600 font-mono" x-text="(item.gambar_scale || 100) + '%'"></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[10px] text-slate-400 font-medium">Kecil</span>
                                                <input type="range" x-model.number="item.gambar_scale" min="40" max="250" step="5" class="w-full h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                                <span class="text-[10px] text-slate-400 font-medium">Besar</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden Inputs -->
                                    <input type="hidden" :name="`items[${index}][existing_gambar]`" :value="item.gambar || ''">
                                    <input type="hidden" :name="`items[${index}][gambar_base64]`" :value="item.gambar_base64 || ''">
                                    <input type="hidden" :name="`items[${index}][gambar_scale]`" :value="item.gambar_scale || 100">
                                </div>

                                <!-- Subtotal per item -->
                                <div class="flex justify-between items-center pt-1 text-[11px]">
                                    <span class="text-slate-500">Perkiraan Subtotal:</span>
                                    <span class="font-extrabold text-emerald-700" x-text="formatRupiah(item.jumlah * item.hargaSatuan)"></span>
                                </div>

                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="pt-2 flex items-center gap-3">
                <a href="{{ route('pengajuan-barang.show', $pengajuan) }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-base"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </aside>

    <!-- Bagian Live Print Preview (Kanan) -->
    <main class="flex-1 bg-slate-200/60 print:bg-transparent rounded-2xl border border-slate-200 p-4 lg:p-6 print:p-0 flex flex-col overflow-x-auto">
        
        <!-- Action Bar Atas Dokumen (Cetak) -->
        <div class="w-full max-w-[297mm] mx-auto flex justify-between items-center bg-white p-3.5 rounded-xl shadow-sm mb-5 shrink-0 no-print">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-700">Preview Pembaruan Lembar Dokumen</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i> Cetak / Print A4
                </button>
            </div>
        </div>

        <!-- AREA CETAK FORM A4 LANDSCAPE -->
        <div id="printArea" class="mx-auto bg-white rounded-xl shadow-md border border-slate-300 print:shadow-none print:border-none p-6 lg:p-8 a4-landscape text-black">
            
            <!-- 1. KOP SURAT RESMI SMKN 1 BERINGIN (HD BANNER) -->
            <div class="mb-4">
                <img src="{{ asset('assets/kop-surat-landscape.png') }}" class="w-full object-contain" alt="Kop Surat Resmi SMKN 1 Beringin">
            </div>

            <!-- 2. JUDUL PENGAJUAN -->
            <div class="text-center mb-4">
                <h2 class="text-[14px] font-bold uppercase tracking-wide underline" x-text="formData.jenisPengajuan"></h2>
                <p class="text-[11px] font-mono mt-0.5 text-slate-700" x-text="formData.nomorSurat ? 'Nomor : ' + formData.nomorSurat : ''"></p>
            </div>

            <!-- 3. IDENTITAS PENGAJUAN -->
            <div class="text-[11px] mb-4 space-y-1 font-sans">
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3 font-semibold">Program Keahlian</div>
                    <div class="col-span-9" x-text="': ' + formData.programKeahlian"></div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3 font-semibold">Laboratorium / Bengkel</div>
                    <div class="col-span-9" x-text="': ' + (formData.labBengkel || '-')"></div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3 font-semibold">Tahun Anggaran</div>
                    <div class="col-span-9" x-text="': ' + formData.tahunAnggaran"></div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3 font-semibold">Tanggal Pengajuan</div>
                    <div class="col-span-9" x-text="': ' + formatDateIndo(formData.tanggalPengajuan)"></div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3 font-semibold">Penanggung Jawab</div>
                    <div class="col-span-9" x-text="': ' + formData.penanggungJawab"></div>
                </div>
            </div>

            <!-- 4. TABEL BARANG (11 Kolom) -->
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
                    <template x-for="(item, idx) in items" :key="item.id">
                        <tr>
                            <td class="border border-black p-1 text-center" x-text="idx + 1"></td>
                            <td class="border border-black p-1 font-semibold" x-text="item.nama"></td>
                            <td class="border border-black p-1 text-center align-middle">
                                <template x-if="item.gambar_preview">
                                    <div class="flex items-center justify-center p-0.5 overflow-hidden">
                                        <img :src="item.gambar_preview" 
                                             :style="`max-height: ${(45 * (item.gambar_scale || 100) / 100).toFixed(0)}px; max-width: ${(80 * (item.gambar_scale || 100) / 100).toFixed(0)}px;`" 
                                             class="object-contain inline-block rounded border border-slate-200">
                                    </div>
                                </template>
                                <template x-if="!item.gambar_preview">
                                    <span class="text-[9px] text-slate-400 italic">(Gambar)</span>
                                </template>
                            </td>
                            <td class="border border-black p-1" x-text="item.fungsi || '-'"></td>
                            <td class="border border-black p-1 text-center" x-text="item.merk || '-'"></td>
                            <td class="border border-black p-1" x-text="item.spek || '-'"></td>
                            <td class="border border-black p-1 text-center font-bold" x-text="item.jumlah"></td>
                            <td class="border border-black p-1 text-center" x-text="item.satuan"></td>
                            <td class="border border-black p-1 text-right font-mono" x-text="formatRupiah(item.hargaSatuan)"></td>
                            <td class="border border-black p-1 text-right font-mono font-bold" x-text="formatRupiah(item.jumlah * item.hargaSatuan)"></td>
                            <td class="border border-black p-1" x-text="item.catatan || '-'"></td>
                        </tr>
                    </template>
                    <!-- Baris Total -->
                    <tr class="font-bold bg-slate-100">
                        <td colspan="9" class="border border-black p-1.5 text-right uppercase">Total Perkiraan Biaya Pengajuan</td>
                        <td class="border border-black p-1.5 text-right font-mono text-[11px] font-black" x-text="formatRupiah(totalBiaya)"></td>
                        <td class="border border-black p-1.5 bg-slate-200"></td>
                    </tr>
                </tbody>
            </table>

            <!-- 5. TERBILANG -->
            <div class="text-[11px] font-sans mb-6">
                <span class="font-bold italic">Terbilang : </span>
                <span class="italic font-semibold underline" x-text="hitungTerbilang(totalBiaya) + ' Rupiah'"></span>
            </div>

            <!-- 6. FORMAT TANDA TANGAN (3 PIHAK) -->
            <div class="text-[11px] font-sans mt-6">
                <div class="flex justify-end mb-2 pr-6">
                    <p>Beringin, <span x-text="formatDateIndo(formData.tanggalPengajuan)"></span></p>
                </div>

                <div class="grid grid-cols-3 gap-6 text-center">
                    <div>
                        <p class="font-semibold">Mengetahui,</p>
                        <p class="font-semibold mb-16">Kepala Sekolah</p>
                        <p class="font-bold underline">{{ $profil->nama_kepsek }}</p>
                        <p class="text-[10px]">NIP. {{ $profil->nip_kepsek }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Wakil Kepala Sekolah</p>
                        <p class="font-semibold mb-16">Bid. Sarana & Prasarana</p>
                        <p class="font-bold underline">{{ $profil->nama_waka_sarpras }}</p>
                        <p class="text-[10px]">NIP. {{ $profil->nip_waka_sarpras }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Yang Mengajukan,</p>
                        <p class="font-semibold mb-16">Kepala Program Keahlian</p>
                        <p class="font-bold underline" x-text="formData.penanggungJawab"></p>
                        <p class="text-[10px]" x-text="formData.nipPenanggungJawab ? 'NIP. ' + formData.nipPenanggungJawab : 'NIP. -'"></p>
                    </div>
                </div>
            </div>

        </div>

    </main>

</div>
@endsection

@push('scripts')
<script>
    function pengajuanEditForm() {
        return {
            formData: {
                nomorSurat: '{{ $pengajuan->nomor_surat }}',
                status: '{{ $pengajuan->status }}',
                jenisPengajuan: '{{ $pengajuan->jenis_pengajuan }}',
                programKeahlian: '{{ $pengajuan->program_keahlian }}',
                labBengkel: '{{ $pengajuan->laboratorium_bengkel }}',
                tahunAnggaran: {{ $pengajuan->tahun_anggaran }},
                tanggalPengajuan: '{{ $pengajuan->tanggal_pengajuan->format("Y-m-d") }}',
                penanggungJawab: '{{ $pengajuan->penanggung_jawab }}',
                nipPenanggungJawab: '{{ $pengajuan->nip_penanggung_jawab }}'
            },

            items: [
                @foreach($pengajuan->items as $item)
                {
                    id: {{ $item->id }},
                    nama: @json($item->nama_barang),
                    gambar: @json($item->gambar),
                    gambar_preview: @json($item->gambar_url),
                    gambar_scale: {{ $item->gambar_scale ?: 100 }},
                    gambar_base64: null,
                    gambar_nama: @json($item->gambar ? basename($item->gambar) : null),
                    fungsi: @json($item->fungsi),
                    merk: @json($item->merk),
                    spek: @json($item->spesifikasi),
                    jumlah: {{ $item->jumlah }},
                    satuan: @json($item->satuan),
                    hargaSatuan: {{ (float) $item->harga_satuan }},
                    catatan: @json($item->catatan)
                },
                @endforeach
            ],

            addItem() {
                this.items.push({
                    id: Date.now(),
                    nama: '',
                    gambar: null,
                    gambar_preview: null,
                    gambar_scale: 100,
                    gambar_base64: null,
                    gambar_nama: null,
                    fungsi: '',
                    merk: '',
                    spek: '',
                    jumlah: 1,
                    satuan: 'Unit',
                    hargaSatuan: 0,
                    catatan: ''
                });
            },

            removeItem(id) {
                if (this.items.length > 1) {
                    this.items = this.items.filter(item => item.id !== id);
                }
            },

            handleItemImage(event, item) {
                const file = event.target.files[0];
                if (!file) return;

                if (!file.type.match('image.*')) {
                    alert('File harus berupa gambar (JPG, PNG, WebP, dll).');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file foto maksimal 5 MB.');
                    return;
                }

                item.gambar_nama = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    item.gambar_preview = e.target.result;
                    item.gambar_base64 = e.target.result;
                    item.gambar = null;
                };
                reader.readAsDataURL(file);
            },

            removeItemImage(item) {
                item.gambar = null;
                item.gambar_preview = null;
                item.gambar_base64 = null;
                item.gambar_nama = null;
            },

            get totalBiaya() {
                return this.items.reduce((sum, item) => sum + (Number(item.jumlah || 0) * Number(item.hargaSatuan || 0)), 0);
            },

            formatRupiah(amount) {
                return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
            },

            formatDateIndo(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
            },

            hitungTerbilang(angka) {
                angka = Math.abs(Number(angka || 0));
                const bilangan = [
                    '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima',
                    'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'
                ];

                if (angka < 12) {
                    return ' ' + bilangan[Math.floor(angka)];
                } else if (angka < 20) {
                    return this.hitungTerbilang(angka - 10) + ' Belas';
                } else if (angka < 100) {
                    return this.hitungTerbilang(Math.floor(angka / 10)) + ' Puluh' + this.hitungTerbilang(angka % 10);
                } else if (angka < 200) {
                    return ' Seratus' + this.hitungTerbilang(angka - 100);
                } else if (angka < 1000) {
                    return this.hitungTerbilang(Math.floor(angka / 100)) + ' Ratus' + this.hitungTerbilang(angka % 100);
                } else if (angka < 2000) {
                    return ' Seribu' + this.hitungTerbilang(angka - 1000);
                } else if (angka < 1000000) {
                    return this.hitungTerbilang(Math.floor(angka / 1000)) + ' Ribu' + this.hitungTerbilang(angka % 1000);
                } else if (angka < 1000000000) {
                    return this.hitungTerbilang(Math.floor(angka / 1000000)) + ' Juta' + this.hitungTerbilang(angka % 1000000);
                } else if (angka < 1000000000000) {
                    return this.hitungTerbilang(Math.floor(angka / 1000000000)) + ' Miliar' + this.hitungTerbilang(angka % 1000000000);
                } else {
                    return this.hitungTerbilang(Math.floor(angka / 1000000000000)) + ' Triliun' + this.hitungTerbilang(angka % 1000000000000);
                }
            }
        };
    }
</script>
@endpush
