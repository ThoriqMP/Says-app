<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->enum('category', ['personality', 'love_language', 'multiple_intelligence']);
            $table->string('aspect_name');
            $table->unsignedTinyInteger('score_value')->nullable();
            $table->enum('label', ['TD', 'KD', 'AD', 'D', 'SD'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
