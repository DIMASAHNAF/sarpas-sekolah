<?php $__env->startSection('title', 'Konfigurasi Profil Sekolah & Lembar Pengesahan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('inventaris.index')); ?>" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center mb-1">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Profil & Lembar Pengesahan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Konfigurasi data identitas sekolah dan pejabat penandatangan yang tercetak pada dokumen Buku Inventaris Barang (BIB).</p>
        </div>
    </div>

    <!-- Alert Info Box -->
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start space-x-3 text-amber-900">
        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 text-base"></i>
        <div class="text-xs leading-relaxed">
            <span class="font-bold">Informasi Lembar Pengesahan:</span> Data yang disimpan pada halaman ini akan secara otomatis ditampilkan pada bagian bawah (Lembar Pengesahan) saat Anda mencetak dokumen PDF Buku Inventaris Barang.
        </div>
    </div>

    <!-- Form Card -->
    <form action="<?php echo e(route('profil-sekolah.update')); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Bagian 1: Identitas Lembaga / Sekolah -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-school"></i>
                </span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Identitas Lembaga Pendidikan</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Sekolah -->
                <div class="sm:col-span-2">
                    <label for="nama_sekolah" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Satuan Pendidikan / Sekolah <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_sekolah" id="nama_sekolah" value="<?php echo e(old('nama_sekolah', $profil->nama_sekolah)); ?>" required
                           placeholder="Contoh: SMK Negeri 1 Pembangunan"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border <?php $__errorArgs = ['nama_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    <?php $__errorArgs = ['nama_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- NPSN -->
                <div>
                    <label for="npsn" class="block text-xs font-bold text-slate-700 uppercase mb-1">NPSN (Nomor Pokok Sekolah Nasional) <span class="text-rose-500">*</span></label>
                    <input type="text" name="npsn" id="npsn" value="<?php echo e(old('npsn', $profil->npsn)); ?>" required
                           placeholder="Contoh: 20108920"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-mono">
                    <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Tahun Anggaran Default -->
                <div>
                    <label for="tahun_anggaran" class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Anggaran Pelaporan <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_anggaran" id="tahun_anggaran" value="<?php echo e(old('tahun_anggaran', $profil->tahun_anggaran)); ?>" min="2000" max="2099" required
                           class="w-full px-3 py-2 text-sm bg-slate-50 border <?php $__errorArgs = ['tahun_anggaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    <?php $__errorArgs = ['tahun_anggaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Sumber Dana Default -->
                <div class="sm:col-span-2">
                    <label for="sumber_dana" class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan Sumber Anggaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="sumber_dana" id="sumber_dana" value="<?php echo e(old('sumber_dana', $profil->sumber_dana)); ?>" required
                           placeholder="Contoh: Dana BOS Reguler / Dana BOSP"
                           class="w-full px-3 py-2 text-sm bg-slate-50 border <?php $__errorArgs = ['sumber_dana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                    <?php $__errorArgs = ['sumber_dana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Bagian 2: Pejabat Penandatangan Lembar Pengesahan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-signature"></i>
                </span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pejabat Pengesahan (Penandatangan Dokumen)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Waka Sarana & Prasarana (Diketahui) -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <div class="flex items-center space-x-2 text-blue-700 font-bold text-xs uppercase">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Waka Sarana Prasarana (Sisi Kiri - Diketahui)</span>
                    </div>

                    <div>
                        <label for="nama_waka_sarpras" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_waka_sarpras" id="nama_waka_sarpras" value="<?php echo e(old('nama_waka_sarpras', $profil->nama_waka_sarpras)); ?>" required
                               placeholder="Contoh: Budi Santoso, S.T., M.Kom."
                               class="w-full px-3 py-2 text-sm bg-white border <?php $__errorArgs = ['nama_waka_sarpras'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nip_waka_sarpras" class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP Waka Sarpras <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip_waka_sarpras" id="nip_waka_sarpras" value="<?php echo e(old('nip_waka_sarpras', $profil->nip_waka_sarpras)); ?>" required
                               placeholder="19820315 200801 1 015"
                               class="w-full px-3 py-2 text-sm bg-white border <?php $__errorArgs = ['nip_waka_sarpras'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
                    </div>
                </div>

                <!-- Kolom Kanan: Kepala Sekolah (Disahkan Oleh) -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <div class="flex items-center space-x-2 text-blue-700 font-bold text-xs uppercase">
                        <i class="fa-solid fa-user-tie"></i>
                        <span>Kepala Sekolah (Sisi Kanan - Disahkan Oleh)</span>
                    </div>

                    <div>
                        <label for="nama_kepsek" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kepsek" id="nama_kepsek" value="<?php echo e(old('nama_kepsek', $profil->nama_kepsek)); ?>" required
                               placeholder="Contoh: Drs. H. Ahmad Sudrajat, M.Pd."
                               class="w-full px-3 py-2 text-sm bg-white border <?php $__errorArgs = ['nama_kepsek'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nip_kepsek" class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP Kepala Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip_kepsek" id="nip_kepsek" value="<?php echo e(old('nip_kepsek', $profil->nip_kepsek)); ?>" required
                               placeholder="19750812 200003 1 002"
                               class="w-full px-3 py-2 text-sm bg-white border <?php $__errorArgs = ['nip_kepsek'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="<?php echo e(route('inventaris.index')); ?>" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all duration-150">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Konfigurasi Profil
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /www/wwwroot/sarpras.tiksmkn1beringin.my.id/resources/views/profil-sekolah/edit.blade.php ENDPATH**/ ?>