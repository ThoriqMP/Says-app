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
        Schema::table('probing_activities', function (Blueprint $table) {
            $table->string('activity_title')->nullable()->after('activity_name');
            // 'description' and 'image_path' already exist in 115121_create_probing_activities_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('probing_activities', function (Blueprint $table) {
            $table->dropColumn('activity_title');
        });
    }
};
