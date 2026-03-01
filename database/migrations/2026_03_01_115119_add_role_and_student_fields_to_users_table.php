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
        Schema::table('users', function (Blueprint $table) {
            // role is already in the table based on User model
            // but we need to ensure 'student' is a possible value
            // and we might need additional fields if we want to store them in users instead of a separate table
            // however the requirement asks for a 'students' structure
            // Let's just add 'student' role if needed, but it's likely already a string
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
