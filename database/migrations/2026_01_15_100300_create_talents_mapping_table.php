<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talents_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->string('brain_dominance')->nullable();
            $table->string('social_dominance')->nullable();
            $table->string('skill_dominance')->nullable();
            $table->text('strengths')->nullable();
            $table->text('deficits')->nullable();
            $table->string('cluster_strength')->nullable();
            $table->string('personal_branding')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talents_mapping');
    }
};
