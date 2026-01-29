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
        // Change category column from ENUM to VARCHAR to support new categories like 'learning_style'
        DB::statement("ALTER TABLE assessment_scores MODIFY COLUMN category VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting to ENUM is risky if new data exists. 
        // We will attempt to revert to the original ENUM definition.
        // Warning: This will fail if the table contains categories not in the enum list.
        DB::statement("ALTER TABLE assessment_scores MODIFY COLUMN category ENUM('personality', 'love_language', 'multiple_intelligence') NOT NULL");
    }
};
