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
        // 1. Update career_questions table
        Schema::table('career_questions', function (Blueprint $table) {
            $table->string('section', 30)->default('riasec')->after('id')->comment('riasec atau career_anchor');
            $table->string('type_anchor', 20)->nullable()->after('type_riasec')->comment('TF, GM, AU, SE, EC, SV, CH, LS');
        });

        // Ubah enum type_riasec agar bisa nullable jika soal adalah career_anchor
        DB::statement("ALTER TABLE career_questions MODIFY COLUMN type_riasec ENUM('R', 'I', 'A', 'S', 'E', 'C') NULL;");

        // 2. Update career_results table
        Schema::table('career_results', function (Blueprint $table) {
            $table->integer('tf_score')->default(0)->after('conventional_score');
            $table->integer('gm_score')->default(0)->after('tf_score');
            $table->integer('au_score')->default(0)->after('gm_score');
            $table->integer('se_score')->default(0)->after('au_score');
            $table->integer('ec_score')->default(0)->after('se_score');
            $table->integer('sv_score')->default(0)->after('ec_score');
            $table->integer('ch_score')->default(0)->after('sv_score');
            $table->integer('ls_score')->default(0)->after('ch_score');

            $table->string('dominant_anchor', 20)->nullable()->after('secondary_types')->comment('TF, GM, AU, SE, EC, SV, CH, LS');
            $table->string('dominant_anchor_name', 100)->nullable()->after('dominant_anchor');
            $table->string('recommended_execution_path', 50)->nullable()->after('dominant_anchor_name')->comment('kuliah, bekerja, berwirausaha');
            $table->string('anchor_recommendation_title', 150)->nullable()->after('recommended_execution_path');
            $table->text('collaboration_narrative')->nullable()->after('anchor_recommendation_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_results', function (Blueprint $table) {
            $table->dropColumn([
                'tf_score', 'gm_score', 'au_score', 'se_score', 'ec_score', 'sv_score', 'ch_score', 'ls_score',
                'dominant_anchor', 'dominant_anchor_name', 'recommended_execution_path',
                'anchor_recommendation_title', 'collaboration_narrative'
            ]);
        });

        Schema::table('career_questions', function (Blueprint $table) {
            $table->dropColumn(['section', 'type_anchor']);
        });

        DB::statement("ALTER TABLE career_questions MODIFY COLUMN type_riasec ENUM('R', 'I', 'A', 'S', 'E', 'C') NOT NULL;");
    }
};
