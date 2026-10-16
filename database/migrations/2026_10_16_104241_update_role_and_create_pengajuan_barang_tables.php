<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi kolom role pada tabel users agar mendukung 'kaprodi'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('kaprodi')->change();
        });

        // Update akun user lama menjadi kaprodi
        DB::table('users')
            ->where('role', 'user')
            ->update([
                'role' => 'kaprodi',
                'name' => 'Kepala Program Keahlian (Kaprodi)'
            ]);

        // 2. Tabel Pengajuan Barang
        Schema::create('pengajuan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_surat')->nullable();
            $table->string('jenis_pengajuan', 100)->default('ALAT PRAKTIKUM'); // ALAT PRAKTIKUM / BARANG HABIS PAKAI
            $table->string('program_keahlian');
            $table->string('laboratorium_bengkel')->nullable();
            $table->unsignedSmallInteger('tahun_anggaran')->default(2026);
            $table->date('tanggal_pengajuan');
            $table->string('penanggung_jawab');
            $table->string('nip_penanggung_jawab')->nullable();
            $table->string('status', 50)->default('Diajukan'); // Draft, Diajukan, Disetujui, Ditolak
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->text('terbilang')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Item Barang Pengajuan
        Schema::create('item_pengajuan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_barang_id')->constrained('pengajuan_barangs')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('gambar')->nullable();
            $table->text('fungsi')->nullable();
            $table->string('merk')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('Unit');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pengajuan_barangs');
        Schema::dropIfExists('pengajuan_barangs');
    }
};
