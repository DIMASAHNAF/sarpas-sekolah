@extends('layouts.app')

@section('title', 'Tambah Inventaris Barang Baru - Dana BOS')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('inventaris.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center mb-1">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pencatatan Inventaris Barang Baru</h1>
            <p class="text-xs text-slate-500 mt-0.5">Input data inventaris barang hasil pengadaan Dana BOS / BOSP Reguler.</p>
        </div>
    </div>

    <!-- Form Card -->
    <form action="{{ route('inventaris.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Bagian 1: Informasi Utama Barang -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">1</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Identitas & Spesifikasi Barang/Aset</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kode Barang -->
                <div>
                    <label for="kode_barang" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Barang / Rekening Aset <span class="text-rose-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: 1.3.05.01.01.0001.00005-1-520501010001" required
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('kode_barang') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-mono">
                    @error('kode_barang')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Barang -->
                <div>
                    <label for="nama_barang" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Barang / Aset <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: BUKU PERPUSTAKAAN, Laptop Asus..." required
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('nama_barang') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    @error('nama_barang')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori (Opsional)</label>
                    <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Buku, Peralatan, Media Pembelajaran, Elektronik..."
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('kategori') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    @error('kategori')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Register / Label -->
                <div>
                    <label for="nomor_register" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Register / Label (Opsional)</label>
                    <input type="text" name="nomor_register" id="nomor_register" value="{{ old('nomor_register') }}" placeholder="Contoh: REG-001 s/d REG-010"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('nomor_register') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    @error('nomor_register')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merk & Spesifikasi -->
                <div class="md:col-span-2">
                    <label for="merk_spesifikasi" class="block text-xs font-bold text-slate-700 uppercase mb-1">Merk / Spesifikasi Lengkap (Opsional)</label>
                    <textarea name="merk_spesifikasi" id="merk_spesifikasi" rows="3" placeholder="Tuliskan judul buku / spesifikasi detail, merk, tipe, nomor seri, dll..."
                              class="w-full px-3 py-2 text-sm bg-slate-50 border @error('merk_spesifikasi') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">{{ old('merk_spesifikasi') }}</textarea>
                    @error('merk_spesifikasi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Bagian 2: Anggaran, Kalkulasi Nilai & Pengadaan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Perolehan, Volume & Kalkulasi Anggaran</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Tanggal Perolehan -->
                <div>
                    <label for="tanggal_perolehan" class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" value="{{ old('tanggal_perolehan', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('tanggal_perolehan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Tanggal Pencatatan -->
                <div>
                    <label for="tanggal_pencatatan" class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Pencatatan (Buku)</label>
                    <input type="date" name="tanggal_pencatatan" id="tanggal_pencatatan" value="{{ old('tanggal_pencatatan', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('tanggal_pencatatan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Tahun Anggaran -->
                <div>
                    <label for="tahun_anggaran" class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Anggaran</label>
                    <input type="number" name="tahun_anggaran" id="tahun_anggaran" value="{{ old('tahun_anggaran', date('Y')) }}" min="2000" max="2099"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('tahun_anggaran') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Sumber Dana -->
                <div>
                    <label for="sumber_dana" class="block text-xs font-bold text-slate-700 uppercase mb-1">Sumber Dana</label>
                    <input type="text" name="sumber_dana" id="sumber_dana" value="{{ old('sumber_dana', 'BOSP Reguler') }}" placeholder="BOSP Reguler / Dana BOS"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('sumber_dana') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- No BAST -->
                <div class="sm:col-span-2">
                    <label for="no_bast" class="block text-xs font-bold text-slate-700 uppercase mb-1">No. BAST (Berita Acara Serah Terima)</label>
                    <input type="text" name="no_bast" id="no_bast" value="{{ old('no_bast') }}" placeholder="Contoh: 400.3.13.2/002/SMKN.01/III/2026"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('no_bast') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-xs font-bold text-slate-700 uppercase mb-1">Jumlah <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('jumlah') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-bold">
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan</label>
                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', 'Buah') }}" placeholder="Contoh: Buah, Unit, Set, Pcs"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('satuan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Harga Satuan -->
                <div>
                    <label for="harga_satuan" class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" step="0.01" name="harga_satuan" id="harga_satuan" value="{{ old('harga_satuan', 0) }}" min="0" required
                               class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border @error('harga_satuan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-bold">
                    </div>
                </div>

                <!-- Nilai Perolehan Preview (Kalkulasi Otomatis) -->
                <div class="sm:col-span-2 lg:col-span-3 bg-blue-50/70 p-4 rounded-xl border border-blue-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <span class="text-xs font-bold text-blue-900 uppercase">Kalkulasi Otomatis Nilai Perolehan:</span>
                        <p class="text-xs text-blue-700">(Jumlah × Harga Satuan) tersimpan otomatis ke database sesuai rumus Excel.</p>
                    </div>
                    <div class="text-right">
                        <span id="preview_nilai_perolehan" class="text-xl font-black text-blue-900 font-mono">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Lokasi, Kondisi & Penanggung Jawab -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">3</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Lokasi Penempatan & Keadaan Barang</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Lokasi Ruang -->
                <div>
                    <label for="lokasi_ruang" class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi / Ruang (Opsional)</label>
                    <input type="text" name="lokasi_ruang" id="lokasi_ruang" value="{{ old('lokasi_ruang') }}" placeholder="Contoh: Perpustakaan, Lab TKJ, Ruang Guru"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('lokasi_ruang') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Kondisi -->
                <div>
                    <label for="kondisi" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kondisi Barang</label>
                    <select name="kondisi" id="kondisi"
                            class="w-full px-3 py-2 text-sm bg-slate-50 border @error('kondisi') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-medium">
                        <option value="Baik" {{ old('kondisi', 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <!-- Penanggung Jawab -->
                <div>
                    <label for="penanggung_jawab" class="block text-xs font-bold text-slate-700 uppercase mb-1">Penanggung Jawab (Opsional)</label>
                    <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ old('penanggung_jawab') }}" placeholder="Contoh: Kepala Perpustakaan, Kaprog"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('penanggung_jawab') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- Bagian 4: Keterangan & Tautan Dokumen -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold">4</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keterangan Tambahan & Tautan / Referensi</h2>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <!-- Tautan / Referensi Dokumen -->
                <div>
                    <label for="tautan_dokumen" class="block text-xs font-bold text-slate-700 uppercase mb-1">Tautan / Referensi Dokumen (Opsional)</label>
                    <input type="text" name="tautan_dokumen" id="tautan_dokumen" value="{{ old('tautan_dokumen') }}" placeholder="https://drive.google.com/... atau nomor arsip"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border @error('tautan_dokumen') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan / Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" placeholder="Catatan perlakuan barang atau informasi pendukung lainnya..."
                              class="w-full px-3 py-2 text-sm bg-slate-50 border @error('keterangan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('inventaris.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all duration-150">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Data Inventaris
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function hitungNilaiPerolehan() {
        const jumlahInput = document.getElementById('jumlah');
        const hargaInput = document.getElementById('harga_satuan');
        const previewElement = document.getElementById('preview_nilai_perolehan');

        const jumlah = parseFloat(jumlahInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = jumlah * harga;

        previewElement.textContent = 'Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.getElementById('jumlah').addEventListener('input', hitungNilaiPerolehan);
    document.getElementById('harga_satuan').addEventListener('input', hitungNilaiPerolehan);
    window.addEventListener('DOMContentLoaded', hitungNilaiPerolehan);
</script>
@endpush
