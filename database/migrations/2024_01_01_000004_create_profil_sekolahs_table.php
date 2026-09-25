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
        Schema::create('profil_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->string('npsn', 20);
            $table->year('tahun_anggaran');
            $table->string('sumber_dana')->default('Dana BOS');
            $table->string('nama_kepsek');
            $table->string('nip_kepsek', 30);
            $table->string('nama_waka_sarpras');
            $table->string('nip_waka_sarpras', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_sekolahs');
    }
};
