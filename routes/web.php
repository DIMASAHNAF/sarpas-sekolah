<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\ProfilSekolahController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// Redirect homepage ke halaman inventaris (akan diarahkan ke /login jika belum login)
Route::redirect('/', '/inventaris');

// Rute Autentikasi Publik
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Seluruh Rute Internal Dilindungi Autentikasi (Harus Login)
Route::middleware('auth')->group(function () {

    // Akses Lihat & Ekspor Dokumen (Bisa diakses Admin & User / Staf)
    Route::get('inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    // Tambah Data Barang (Bisa diakses Admin & User / Staf)
    Route::get('inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
    Route::post('inventaris', [InventarisController::class, 'store'])->name('inventaris.store');

    // Import & Unduh Template Excel (Bisa diakses Admin & User / Staf)
    Route::post('inventaris/import/excel', [InventarisController::class, 'importExcel'])->name('inventaris.import.excel');
    Route::get('inventaris/template/excel', [InventarisController::class, 'downloadTemplate'])->name('inventaris.template.excel');

    // Rute Khusus Administrator (Akses Penuh / Tindakan Sensitif)
    Route::middleware(EnsureUserIsAdmin::class)->group(function () {
        // Ekspor Dokumen (Hanya Admin)
        Route::get('inventaris/export/excel', [InventarisController::class, 'exportExcel'])->name('inventaris.export.excel');
        Route::get('inventaris/export/pdf', [InventarisController::class, 'exportPdf'])->name('inventaris.export.pdf');

        // Edit Data Barang
        Route::get('inventaris/{inventaris}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('inventaris/{inventaris}', [InventarisController::class, 'update'])->name('inventaris.update');
        Route::patch('inventaris/{inventaris}', [InventarisController::class, 'update']);

        // Hapus Data Barang (Satuan, Terpilih / Bulk, & Hapus Seluruh Database)
        Route::delete('inventaris/destroy-all', [InventarisController::class, 'destroyAll'])->name('inventaris.destroyAll');
        Route::delete('inventaris/bulk-delete', [InventarisController::class, 'bulkDelete'])->name('inventaris.bulkDelete');
        Route::delete('inventaris/{inventaris}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

        // Pengaturan Profil Sekolah & Lembar Pengesahan
        Route::get('profil-sekolah', [ProfilSekolahController::class, 'edit'])->name('profil-sekolah.edit');
        Route::put('profil-sekolah', [ProfilSekolahController::class, 'update'])->name('profil-sekolah.update');

        // Berita Acara Serah Terima
        Route::get('berita-acara', [BeritaAcaraController::class, 'index'])->name('berita-acara.index');
    });

    // Detail Barang (ditaruh setelah rute inventaris/create agar tidak bentrok param)
    Route::get('inventaris/{inventaris}', [InventarisController::class, 'show'])->name('inventaris.show');
});
