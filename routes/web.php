<?php

use App\Http\Controllers\InventarisController;
use App\Http\Controllers\ProfilSekolahController;
use Illuminate\Support\Facades\Route;

// Redirect homepage ke halaman inventaris
Route::redirect('/', '/inventaris');

// Rute Ekspor Dokumen (diletakkan sebelum resource agar tidak tertabrak oleh wildcard {inventari})
Route::get('inventaris/export/excel', [InventarisController::class, 'exportExcel'])->name('inventaris.export.excel');
Route::get('inventaris/export/pdf', [InventarisController::class, 'exportPdf'])->name('inventaris.export.pdf');

// Resource CRUD Inventaris Barang
Route::resource('inventaris', InventarisController::class)->parameters([
    'inventaris' => 'inventaris',
]);

// Pengaturan Profil Sekolah (Single-Row Config)
Route::get('profil-sekolah', [ProfilSekolahController::class, 'edit'])->name('profil-sekolah.edit');
Route::put('profil-sekolah', [ProfilSekolahController::class, 'update'])->name('profil-sekolah.update');
