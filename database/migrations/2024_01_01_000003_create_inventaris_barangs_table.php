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
            $table->string('kode_barang')->index();
            $table->date('tanggal_perolehan')->nullable();
            $table->date('tanggal_pencatatan')->nullable();
            $table->string('nama_barang')->index();
            $table->text('merk_spesifikasi')->nullable();
            $table->string('kategori')->nullable()->index();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('Buah');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('nilai_perolehan', 15, 2)->default(0); // jumlah * harga_satuan
            $table->string('no_bast')->nullable();
            $table->string('sumber_dana')->default('BOSP Reguler');
            $table->year('tahun_anggaran')->nullable()->index();
            $table->string('lokasi_ruang')->nullable()->index();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik')->index();
            $table->string('penanggung_jawab')->nullable();
            $table->string('nomor_register')->nullable();
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
