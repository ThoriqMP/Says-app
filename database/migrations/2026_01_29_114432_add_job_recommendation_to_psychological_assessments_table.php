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
        Schema::table('psychological_assessments', function (Blueprint $table) {
            $table->text('job_recommendation')->nullable()->after('maturity_recommendation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psychological_assessments', function (Blueprint $table) {
            $table->dropColumn('job_recommendation');
        });
    }
};
