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
        Schema::create('career_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('type_riasec', ['R', 'I', 'A', 'S', 'E', 'C']);
            $table->unsignedInteger('order_num')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_questions');
    }
};
