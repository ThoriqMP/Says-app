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
        Schema::create('report_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_report_id')->constrained('student_reports')->onDelete('cascade');
            $table->foreignId('report_subject_id')->constrained('report_subjects')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_grades');
    }
};
