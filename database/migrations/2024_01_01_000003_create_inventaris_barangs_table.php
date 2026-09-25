<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventaris_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique()->index();
            $table->date('tanggal_perolehan');
            $table->date('tanggal_pencatatan');
            $table->string('nama_barang')->index();
            $table->text('merk_spesifikasi');
            $table->string('kategori')->index();
            $table->integer('jumlah');
            $table->string('satuan', 50);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai_perolehan', 15, 2); // jumlah * harga_satuan
            $table->string('no_bast');
            $table->string('sumber_dana')->default('Dana BOS');
            $table->year('tahun_anggaran')->index();
            $table->string('lokasi_ruang')->index();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik')->index();
            $table->string('penanggung_jawab');
            $table->string('nomor_register');
            $table->text('keterangan')->nullable();
            $table->string('tautan_dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_barangs');
    }
};
