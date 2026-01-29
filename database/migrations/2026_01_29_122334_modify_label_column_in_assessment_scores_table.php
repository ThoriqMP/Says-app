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
        // Change label column from ENUM to VARCHAR to support potential new labels
        DB::statement("ALTER TABLE assessment_scores MODIFY COLUMN label VARCHAR(50) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to ENUM
        DB::statement("ALTER TABLE assessment_scores MODIFY COLUMN label ENUM('TD', 'KD', 'AD', 'D', 'SD') NULL");
    }
};
