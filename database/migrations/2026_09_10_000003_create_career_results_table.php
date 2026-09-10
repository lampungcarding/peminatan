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
        Schema::create('career_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->integer('realistic_score')->default(0);
            $table->integer('investigative_score')->default(0);
            $table->integer('artistic_score')->default(0);
            $table->integer('social_score')->default(0);
            $table->integer('enterprising_score')->default(0);
            $table->integer('conventional_score')->default(0);
            $table->string('dominant_type', 50)->comment('Realistic, Investigative, etc.');
            $table->string('holland_code', 20)->comment('Top 3 letters e.g. I-C-R');
            $table->string('secondary_types', 100)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_results');
    }
};
