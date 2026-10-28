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
        Schema::table('item_pengajuan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('item_pengajuan_barangs', 'gambar_scale')) {
                $table->integer('gambar_scale')->default(100)->after('gambar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_pengajuan_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('item_pengajuan_barangs', 'gambar_scale')) {
                $table->dropColumn('gambar_scale');
            }
        });
    }
};
