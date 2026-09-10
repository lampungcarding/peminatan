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
        Schema::create('pilihan_setelah_lulus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->enum('rencana', ['kuliah', 'bekerja', 'berwirausaha']);
            $table->string('perguruan_tinggi_id_external')->nullable();
            $table->string('nama_perguruan_tinggi')->nullable();
            $table->string('program_studi_id_external')->nullable();
            $table->string('nama_program_studi')->nullable();
            $table->string('jenjang')->nullable();
            $table->string('akreditasi')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihan_setelah_lulus');
    }
};
