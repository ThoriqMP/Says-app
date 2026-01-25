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
        Schema::create('psychological_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            
            // Aspek Kognitif
            $table->integer('cognitive_verbal_score')->nullable();
            $table->integer('cognitive_verbal_scale')->nullable();
            $table->integer('cognitive_numerical_score')->nullable();
            $table->integer('cognitive_numerical_scale')->nullable();
            $table->integer('cognitive_logical_score')->nullable();
            $table->integer('cognitive_logical_scale')->nullable();
            $table->integer('cognitive_spatial_score')->nullable();
            $table->integer('cognitive_spatial_scale')->nullable();
            
            // Aspek Potensi
            $table->integer('potential_intellectual_score')->nullable();
            $table->integer('potential_social_score')->nullable();
            $table->integer('potential_emotional_score')->nullable();
            
            // Taraf Kecerdasan
            $table->string('iq_full_scale')->nullable(); // Score or Category? "Tabel kategori IQ". Let's store text or score.
            $table->string('iq_category')->nullable();
            
            // Taraf Kematangan
            $table->string('maturity_recommendation')->nullable(); // Disarankan, Dipertimbangkan, Tidak Disarankan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychological_assessments');
    }
};
