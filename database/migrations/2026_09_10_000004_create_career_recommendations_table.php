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
        Schema::create('career_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('riasec_code', 10)->index();
            $table->enum('category', ['jurusan', 'profesi', 'usaha'])->index();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_recommendations');
    }
};
