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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nipd', 50)->nullable()->after('nisn');
            $table->string('jk', 10)->nullable()->after('nipd'); // 'L' atau 'P'
            $table->string('nik', 30)->nullable()->after('jk');
            $table->text('alamat')->nullable()->after('nik');
            $table->string('rt', 10)->nullable()->after('alamat');
            $table->string('rw', 10)->nullable()->after('rt');
            $table->string('dusun', 100)->nullable()->after('rw');
            $table->string('kelurahan', 100)->nullable()->after('dusun');
            $table->string('kecamatan', 100)->nullable()->after('kelurahan');
            $table->string('kabupaten_kota', 100)->nullable()->default('Kota Bandar Lampung')->after('kecamatan');
            $table->string('kode_pos', 10)->nullable()->after('kabupaten_kota');
            $table->string('no_hp', 30)->nullable()->after('kode_pos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nipd',
                'jk',
                'nik',
                'alamat',
                'rt',
                'rw',
                'dusun',
                'kelurahan',
                'kecamatan',
                'kabupaten_kota',
                'kode_pos',
                'no_hp',
            ]);
        });
    }
};
