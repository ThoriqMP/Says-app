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
        Schema::table('report_grades', function (Blueprint $table) {
            $table->decimal('score_pts', 5, 2)->nullable()->after('score');
            $table->decimal('score_remedial', 5, 2)->nullable()->after('score_pts');
            $table->decimal('score_harian', 5, 2)->nullable()->after('score_remedial');
            $table->string('predicate', 10)->nullable()->after('score_harian');
            $table->string('ayat_range')->nullable()->after('predicate'); // For Diniyah
            // 'description' already exists in the table from zz_create_report_grades_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_grades', function (Blueprint $table) {
            $table->dropColumn(['score_pts', 'score_remedial', 'score_harian', 'predicate', 'ayat_range']);
        });
    }
};
