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
        Schema::table('pilihan_setelah_lulus', function (Blueprint $table) {
            $table->string('bidang_pekerjaan', 255)->nullable()->after('rencana');
            $table->text('keterangan_pekerjaan')->nullable()->after('bidang_pekerjaan');
            $table->string('bidang_usaha', 255)->nullable()->after('keterangan_pekerjaan');
            $table->text('keterangan_usaha')->nullable()->after('bidang_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilihan_setelah_lulus', function (Blueprint $table) {
            $table->dropColumn([
                'bidang_pekerjaan',
                'keterangan_pekerjaan',
                'bidang_usaha',
                'keterangan_usaha',
            ]);
        });
    }
};
