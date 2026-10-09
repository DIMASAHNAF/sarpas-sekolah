<?php $__env->startSection('title', 'Buat Berita Acara (BAST)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* A4 Page Formatting (Screen & Print) */
    .a4-page {
        width: 210mm;
        min-height: 297mm;
        padding: 2.2cm 2.2cm;
        background: white;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* Print Specific Styles */
    @media print {
        @page {
            margin: 0; /* Menghilangkan header/footer bawaan browser (Tanggal, URL) */
            size: A4 portrait;
        }

        /* Sembunyikan elemen bawaan dari app layout saat print */
        header, footer, .md\:hidden { display: none !important; }
        
        /* Pastikan elemen pembungkus menggunakan block untuk mencegah bug flex saat print (kertas kosong/terpotong) */
        body, main, #bast-wrapper { 
            display: block !important; 
            overflow: visible !important; 
            height: auto !important; 
            min-height: auto !important;
            padding: 0 !important; 
            margin: 0 !important; 
            max-width: none !important; 
            border: none !important; 
            background: white !important; 
        }
        
        #printArea {
            transform: none !important;
            display: block !important;
            width: 100% !important;
        }
        
        /* Background putih bersih */
        body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        /* Sembunyikan elemen no-print spesifik BAST */
        .no-print { display: none !important; }
        
        .a4-page {
            margin: 0 !important;
            box-shadow: none !important;
            page-break-after: always;
            padding: 2.2cm 2.2cm !important;
            height: 297mm !important;
            max-height: 297mm !important;
            overflow: hidden !important;
            display: block !important;
        }
        
        /* Hapus margin atas dari halaman pertama kalau ada */
        .a4-page:first-child {
            margin-top: 0 !important;
        }

        /* Jangan tambahkan halaman kosong setelah halaman terakhir */
        .a4-page:last-child {
            page-break-after: auto;
        }
    }
    
    /* Font untuk A4 */
    .font-times {
        font-family: 'Times New Roman', Times, serif;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="bast-wrapper" x-data="bastForm()" 
     x-init="setTimeout(() => { $el.classList.remove('opacity-0', 'translate-y-4') }, 50)"
     class="flex flex-col lg:flex-row gap-6 pb-20 w-full max-w-[1920px] mx-auto opacity-0 translate-y-4 transition-all duration-700 ease-out">
    
    <!-- Bagian Form (Kiri) -->
    <aside class="w-full lg:w-[480px] xl:w-[530px] shrink-0 bg-white rounded-2xl shadow-sm border border-slate-200 no-print">
        <div class="p-6 space-y-6">
            
            <div class="bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 rounded-2xl p-5">
                <div class="flex items-center gap-2 text-indigo-700 text-xs font-bold uppercase mb-1">
                    <i class="fa-solid fa-sparkles"></i> Pengisian Data Dokumen
                </div>
                <h2 class="text-base font-bold text-slate-900">Data Berita Acara</h2>
                <p class="text-xs text-slate-500 mt-1">Kop surat otomatis menggunakan logo sekolah.</p>
            </div>

            <!-- 1. Data Surat -->
            <div class="border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">1. Data Surat & Waktu</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Surat</label>
                    <input type="text" x-model="formData.nomor" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Surat (Untuk Lampiran)</label>
                    <input type="text" x-model="formData.tanggalSurat" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hari</label>
                        <input type="text" x-model="formData.hari" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal (Terbilang)</label>
                        <input type="text" x-model="formData.tanggal" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bulan</label>
                        <input type="text" x-model="formData.bulan" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun (Terbilang)</label>
                        <input type="text" x-model="formData.tahun" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                    </div>
                </div>
            </div>

            <!-- 2. Pihak Pertama -->
            <div class="border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">2. Pihak Pertama (Yang Menyerahkan)</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama & Gelar</label>
                    <input type="text" x-model="formData.nama1" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP</label>
                    <input type="text" x-model="formData.nip1" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jabatan</label>
                    <input type="text" x-model="formData.jab1" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
            </div>

            <!-- 3. Pihak Kedua -->
            <div class="border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">3. Pihak Kedua (Yang Menerima)</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama & Gelar</label>
                    <input type="text" x-model="formData.nama2" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP</label>
                    <input type="text" x-model="formData.nip2" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jabatan</label>
                    <input type="text" x-model="formData.jab2" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
            </div>

            <!-- 4. Barang -->
            <div class="border border-slate-200 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-800">4. Daftar Barang</h3>
                    <button @click="addBarang" type="button" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1.5 rounded-lg hover:bg-indigo-100">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(barang, index) in barangList" :key="barang.id">
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl relative transition-all duration-300 hover:shadow-md hover:border-indigo-200 group">
                            <button @click="removeBarang(barang.id)" type="button" class="absolute top-3 right-3 text-rose-400 hover:text-rose-600 p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <span class="text-[10px] font-bold uppercase bg-white px-2 py-0.5 rounded border border-slate-200 mb-3 inline-block shadow-sm" x-text="`Item #${index + 1}`"></span>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-500 mb-1">Nama Barang</label>
                                    <input type="text" x-model="barang.nama" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-500 mb-1">Spesifikasi Lengkap</label>
                                    <input type="text" x-model="barang.spek" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Jumlah</label>
                                        <input type="text" x-model="barang.jumlah" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Satuan</label>
                                        <input type="text" x-model="barang.satuan" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-slate-500 mb-1">Keterangan / Sumber Dana</label>
                                    <input type="text" x-model="barang.ket" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5"><i class="fa-solid fa-map-pin text-indigo-500 mr-1"></i> Lokasi Penempatan / Inventaris</label>
                    <input type="text" x-model="formData.lokasi" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
            </div>

            <!-- 5. Kepsek -->
            <div class="border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">5. Mengetahui (Kepsek)</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama & Gelar</label>
                    <input type="text" x-model="formData.namaKepsek" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP</label>
                    <input type="text" x-model="formData.nipKepsek" class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 rounded-xl px-3.5 py-2.5 text-sm outline-none font-mono">
                </div>
            </div>

            <!-- 6. Lampiran Foto -->
            <div class="border border-slate-200 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">6. Lampiran Dokumentasi</h3>
                
                <div x-show="!foto" class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:bg-slate-50 transition relative">
                    <input type="file" @change="handleFoto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <i class="fa-regular fa-image text-3xl text-slate-400 mb-2"></i>
                    <p class="text-sm font-bold text-slate-700">Upload Foto Serah Terima</p>
                    <p class="text-[10px] text-slate-400 mt-1">Akan dicetak di Halaman 3</p>
                </div>

                <div x-show="foto" class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 p-2" style="display: none;">
                    <img :src="foto" class="w-full h-auto max-h-48 object-contain rounded-xl">
                    <button @click="removeFoto" type="button" class="absolute top-4 right-4 bg-white/90 text-rose-600 w-8 h-8 rounded-full flex items-center justify-center shadow hover:bg-rose-50">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div x-show="foto" class="mt-4 pt-4 border-t border-slate-100" style="display: none;">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-700">Sesuaikan Ukuran (Skala)</span>
                        <span class="text-xs font-bold text-indigo-600" x-text="fotoScale + '%'"></span>
                    </div>
                    <input type="range" x-model="fotoScale" min="10" max="200" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                </div>
            </div>

        </div>
    </aside>

    <!-- Bagian Preview (Kanan) -->
    <main class="flex-1 bg-slate-200/50 print:bg-transparent print:border-none print:rounded-none rounded-2xl border border-slate-200 p-4 lg:p-8 print:p-0 flex flex-col overflow-x-auto overflow-y-visible">
        
        <div class="w-full max-w-[210mm] mx-auto flex justify-between items-center bg-white p-3 rounded-xl shadow-sm mb-6 shrink-0 no-print hover:shadow-md transition-shadow">
            <span class="text-xs font-bold text-slate-700"><i class="fa-solid fa-file-pdf text-rose-500 mr-1.5"></i> Preview Dokumen (3 Halaman)</span>
            <button onclick="window.print()" type="button" class="bg-indigo-600 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-indigo-700 shadow-sm transition flex items-center">
                <i class="fa-solid fa-print mr-2"></i> Cetak PDF
            </button>
        </div>

        <!-- Render Komponen Cetak yang Sebenarnya -->
        <div id="printArea" class="mx-auto flex flex-col items-center space-y-8 print:space-y-0 pb-10 print:pb-0 origin-top shrink-0" style="transform: scale(0.95);">
            <!-- HALAMAN 1 -->
            <div class="a4-page bg-white font-times text-[13px] text-justify shadow-lg relative">
                <!-- KOP SURAT -->
                <div class="flex items-center border-b-4 border-black pb-2 mb-1">
                    <div class="w-[90px] shrink-0 flex items-center justify-center pr-3">
                        <img src="<?php echo e(asset('assets/sumut-logo.webp')); ?>" class="w-20 h-20 object-contain">
                    </div>
                    <div class="flex-1 text-center pr-[20px]">
                        <h2 class="text-[14.5px] font-bold tracking-wide uppercase leading-tight">PEMERINTAH PROVINSI SUMATERA UTARA</h2>
                        <h2 class="text-[14.5px] font-bold tracking-wide uppercase leading-tight">DINAS PENDIDIKAN</h2>
                        <h1 class="text-[16.5px] font-extrabold tracking-wide uppercase leading-tight mt-0.5">SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 1 BERINGIN</h1>
                        <p class="text-[10px] leading-tight mt-1 text-slate-900">Jalan Pendidikan No. 3 Emplasmen Kuala Namu, Kecamatan Beringin, Kabupaten Deli Serdang - 20552</p>
                        <p class="text-[10px] leading-tight mt-0.5 text-slate-900">Email : smknsatuberingin@gmail.com | Website : www.smknegeri1beringin.sch.id | NPSN : 10261468 | NSS : 531070117025</p>
                    </div>
                </div>
                <div class="border-b-[1px] border-black w-full mb-5"></div>

                <!-- JUDUL -->
                <div class="text-center mb-5">
                    <h3 class="text-[14px] font-bold underline tracking-wide uppercase">BERITA ACARA SERAH TERIMA</h3>
                    <p class="text-[12.5px] font-medium mt-0.5">Nomor : <span class="font-semibold" x-text="formData.nomor"></span></p>
                </div>

                <!-- ISI PARAGRAF 1 -->
                <div class="space-y-3">
                    <p class="indent-8 leading-normal">
                        Pada hari ini <span class="font-medium underline" x-text="formData.hari"></span> tanggal <span class="font-medium underline" x-text="formData.tanggal"></span> bulan <span class="font-medium underline" x-text="formData.bulan"></span> tahun <span class="font-medium underline" x-text="formData.tahun"></span>, kami yang bertanda tangan dibawah ini masing - masing :
                    </p>

                    <table class="w-full">
                        <tr><td class="w-6 align-top">1.</td><td class="w-20 align-top">Nama</td><td class="w-4 align-top">:</td><td class="font-bold" x-text="formData.nama1"></td></tr>
                        <tr><td></td><td class="align-top">NIP</td><td class="align-top">:</td><td x-text="formData.nip1"></td></tr>
                        <tr><td></td><td class="align-top">Jabatan</td><td class="align-top">:</td><td x-text="formData.jab1"></td></tr>
                    </table>
                    <p class="pl-6 text-[12.5px]">Selanjutnya disebut pihak pertama.</p>

                    <table class="w-full pt-1">
                        <tr><td class="w-6 align-top">2.</td><td class="w-20 align-top">Nama</td><td class="w-4 align-top">:</td><td class="font-bold" x-text="formData.nama2"></td></tr>
                        <tr><td></td><td class="align-top">NIP</td><td class="align-top">:</td><td x-text="formData.nip2"></td></tr>
                        <tr><td></td><td class="align-top">Jabatan</td><td class="align-top">:</td><td x-text="formData.jab2"></td></tr>
                    </table>
                    <p class="pl-6 text-[12.5px]">Selanjutnya disebut pihak kedua.</p>

                    <p class="indent-8 leading-normal pt-1">
                        Pihak pertama telah menyerahkan barang inventaris kepada pihak kedua, dan pihak kedua telah menerimanya, adapun barang-barang dimaksud antara lain berupa :
                    </p>

                    <!-- TABEL BARANG -->
                    <table class="w-full border-collapse border border-black my-2 text-[12px]">
                        <thead>
                            <tr class="font-bold text-center bg-slate-50 print:bg-transparent">
                                <th class="border border-black py-1.5 px-1.5 w-10">No.</th>
                                <th class="border border-black py-1.5 px-2 w-[28%]">Nama Barang (Alat &amp; Bahan) / Keluaran Jasa</th>
                                <th class="border border-black py-1.5 px-2 w-[38%]">Spesifikasi</th>
                                <th class="border border-black py-1.5 px-1.5 w-14">Jumlah</th>
                                <th class="border border-black py-1.5 px-1.5 w-16">Satuan</th>
                                <th class="border border-black py-1.5 px-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(brg, idx) in barangList" :key="brg.id">
                                <tr>
                                    <td class="border border-black py-1.5 px-1 text-center align-top" x-text="idx + 1 + '.'"></td>
                                    <td class="border border-black py-1.5 px-2 align-top font-medium" x-text="brg.nama"></td>
                                    <td class="border border-black py-1.5 px-2 align-top leading-tight" x-text="brg.spek"></td>
                                    <td class="border border-black py-1.5 px-1.5 text-center align-top font-medium" x-text="brg.jumlah"></td>
                                    <td class="border border-black py-1.5 px-1.5 text-center align-top" x-text="brg.satuan"></td>
                                    <td class="border border-black py-1.5 px-2 text-center align-top leading-tight" x-text="brg.ket"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <p class="indent-8 leading-normal">
                        Selanjutnya barang diinventariskan di <span class="font-semibold underline" x-text="formData.lokasi"></span>. Dengan ketentuan:
                    </p>
                    <ol class="list-decimal pl-7 space-y-1 text-[12px] pt-1">
                        <li>Pihak kedua menggunakan barang untuk kepentingan sekolah.</li>
                        <li>Pihak kedua bertanggungjawab apabila terjadi kehilangan/kerusakan barang yang diterima, sesuai dengan kondisi barang pada saat serah terima.</li>
                        <li>Pihak kedua tidak memindah tangankan barang yang diterima tanpa sepengetahuan Pihak pertama;</li>
                    </ol>
                </div>
            </div>

            <!-- HALAMAN 2 -->
            <div class="a4-page bg-white font-times text-[13px] text-justify shadow-lg relative flex flex-col justify-between">
                <div>
                    <ol start="4" class="list-decimal pl-7 space-y-1 text-[12px] mb-6">
                        <li>Pihak kedua melaporkan barang yang telah diterima kepada Pihak Pertama apabila terjadi alih tugas pada penerima/pihak kedua.</li>
                    </ol>
                    <p class="indent-8 leading-normal mb-10">
                        Demikian, berita acara ini buat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.
                    </p>

                    <!-- TTD Pihak 1 & 2 -->
                    <div class="grid grid-cols-2 text-center text-[12.5px] pb-6">
                        <div>
                            <p class="font-medium">Pihak Pertama</p>
                            <p class="font-medium mb-24">Yang Menyerahkan</p>
                            <p class="font-bold underline" x-text="formData.nama1"></p>
                            <p class="font-medium">NIP. <span x-text="formData.nip1"></span></p>
                        </div>
                        <div>
                            <p class="font-medium">Pihak Kedua</p>
                            <p class="font-medium mb-24">Yang Menerima</p>
                            <p class="font-bold underline" x-text="formData.nama2"></p>
                            <p class="font-medium">NIP. <span x-text="formData.nip2"></span></p>
                        </div>
                    </div>

                    <!-- TTD Kepsek -->
                    <div class="text-center text-[12.5px] pt-6 mt-6">
                        <p class="font-medium">Mengetahui,</p>
                        <p class="font-medium mb-24">Kepala SMK Negeri 1 Beringin</p>
                        <p class="font-bold underline" x-text="formData.namaKepsek"></p>
                        <p class="font-medium">NIP. <span x-text="formData.nipKepsek"></span></p>
                    </div>
                </div>
                <div class="text-right text-[11px] text-slate-400 no-print">Halaman 2</div>
            </div>

            <!-- HALAMAN 3 (Lampiran) -->
            <div class="a4-page bg-white font-times text-[13px] shadow-lg relative flex flex-col justify-between">
                <div>
                    <div class="text-left mb-8">
                        <p class="text-[13px]">Lampiran Berita Acara Serah Terima</p>
                        <p class="text-[13px]">Nomor: <span x-text="formData.nomor"></span></p>
                        <p class="text-[13px]">Tanggal: <span x-text="formData.tanggalSurat"></span></p>
                        <h3 class="text-[14px] font-bold uppercase text-center mt-6 mb-2">DOKUMENTASI FOTO SERAH TERIMA BARANG</h3>
                    </div>
                    
                    <div class="w-full flex items-center justify-center p-4 print:block print:text-center">
                        <template x-if="foto">
                            <div class="flex justify-center items-center w-full overflow-hidden print:block print:text-center print:mt-4">
                                <img :src="foto" :style="`transform: scale(${fotoScale / 100}); transform-origin: top center;`" class="max-w-full max-h-[700px] print:max-h-[600px] object-contain border-4 border-slate-100 p-2 shadow-sm inline-block">
                            </div>
                        </template>
                        <template x-if="!foto">
                            <div class="text-center py-20 text-slate-300 no-print border-2 border-dashed border-slate-200 rounded-2xl w-full">
                                <i class="fa-regular fa-image text-5xl mb-3"></i>
                                <p>Foto Dokumentasi Belum Diunggah</p>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="text-right text-[11px] text-slate-400 no-print">Halaman 3</div>
            </div>

        </div>
    </main>

    <!-- Floating Print Button (Mobile) -->
    <div class="fixed bottom-16 right-4 lg:hidden z-40 no-print">
        <button onclick="window.print()" class="bg-indigo-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg active:scale-95 transition-transform">
            <i class="fa-solid fa-print text-xl"></i>
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bastForm', () => ({
            formData: {
                nomor: '400.3.13.2/035/SMKN.01/IX/2026',
                tanggalSurat: '29 September 2026',
                hari: 'Selasa',
                tanggal: 'Dua Puluh Sembilan',
                bulan: 'September',
                tahun: 'Dua Ribu Dua Puluh Enam',
                
                nama1: 'Guntoro Pramadarno, S.Pd.',
                nip1: '198511132011011004',
                jab1: 'Wakasek Bidang Sarana dan Prasarana',
                
                nama2: 'Sudung Haposan Sinabutar, S.Kom.',
                nip2: '198504042014031001',
                jab2: 'Wakasek Bidang Kurikulum',
                
                lokasi: 'Ruang Wakasek Kurikulum',
                namaKepsek: 'Asron Batubara, S.Pd., M.Si.',
                nipKepsek: '197312162005021003'
            },
            
            barangList: [
                {
                    id: Date.now(),
                    nama: 'Laptop',
                    spek: 'ASUS EXPERTBOOK PM 1403CDA-S67151WS',
                    jumlah: '1',
                    satuan: 'Unit',
                    ket: 'BOSP Reguler 2026'
                }
            ],
            
            foto: null,
            fotoScale: 100,

            addBarang() {
                this.barangList.push({
                    id: Date.now(),
                    nama: '',
                    spek: '',
                    jumlah: '1',
                    satuan: 'Unit',
                    ket: ''
                });
            },

            removeBarang(id) {
                if (this.barangList.length > 1) {
                    this.barangList = this.barangList.filter(b => b.id !== id);
                }
            },

            handleFoto(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.foto = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            removeFoto() {
                this.foto = null;
            }
        }));
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/sarpras.tiksmkn1beringin.my.id/resources/views/berita-acara/index.blade.php ENDPATH**/ ?>