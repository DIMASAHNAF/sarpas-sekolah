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
        // 1. Kolom jurusan pada tabel users
        if (!Schema::hasColumn('users', 'jurusan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('jurusan', 100)->nullable()->after('role');
            });
        }

        // 2. Kolom jurusan dan user_id pada tabel inventaris_barangs
        Schema::table('inventaris_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('inventaris_barangs', 'jurusan')) {
                $table->string('jurusan', 100)->nullable()->after('kategori')->index();
            }
            if (!Schema::hasColumn('inventaris_barangs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('tautan_dokumen')->constrained('users')->nullOnDelete();
            }
        });

        // 3. Update data akun kaprodi lama (user id 3)
        DB::table('users')
            ->where('id', 3)
            ->update([
                'jurusan' => 'TJKT (TKJ)',
                'name'    => 'Kaprodi TJKT (TKJ)',
            ]);

        // 4. Update data inventaris_barangs lama agar terhubung ke jurusan masing-masing
        DB::table('inventaris_barangs')
            ->where('lokasi_ruang', 'LIKE', '%Perpustakaan%')
            ->orWhere('penanggung_jawab', 'LIKE', '%Perpustakaan%')
            ->orWhere('kategori', 'LIKE', '%Buku%')
            ->update(['jurusan' => 'Perpustakaan']);

        DB::table('inventaris_barangs')
            ->where('lokasi_ruang', 'LIKE', '%Kuliner%')
            ->orWhere('kategori', 'LIKE', '%Kuliner%')
            ->orWhere('penanggung_jawab', 'LIKE', '%Kuliner%')
            ->update(['jurusan' => 'Kuliner']);

        DB::table('inventaris_barangs')
            ->where('lokasi_ruang', 'LIKE', '%Busana%')
            ->orWhere('kategori', 'LIKE', '%Busana%')
            ->orWhere('penanggung_jawab', 'LIKE', '%Busana%')
            ->update(['jurusan' => 'Tata Busana']);

        DB::table('inventaris_barangs')
            ->where('lokasi_ruang', 'LIKE', '%TKJ%')
            ->orWhere('kategori', 'LIKE', '%TKJ%')
            ->orWhere('penanggung_jawab', 'LIKE', '%TKJ%')
            ->update(['jurusan' => 'TJKT (TKJ)']);

        DB::table('inventaris_barangs')
            ->where('lokasi_ruang', 'LIKE', '%UPW%')
            ->orWhere('kategori', 'LIKE', '%UPW%')
            ->orWhere('penanggung_jawab', 'LIKE', '%UPW%')
            ->update(['jurusan' => 'Pariwisata']);

        // 5. Normalisasi data pengajuan barangs lama
        DB::table('pengajuan_barangs')
            ->where('program_keahlian', 'TJKT')
            ->update(['program_keahlian' => 'TJKT (TKJ)']);

        DB::table('pengajuan_barangs')
            ->where('program_keahlian', 'PPLG')
            ->update(['program_keahlian' => 'PPLG (RPL)']);

        DB::table('pengajuan_barangs')
            ->where('program_keahlian', 'ULP')
            ->update(['program_keahlian' => 'Pariwisata']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('inventaris_barangs', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('inventaris_barangs', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
        });
    }
};
