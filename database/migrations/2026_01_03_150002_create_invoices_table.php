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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->date('tanggal_invoice');
            $table->date('jatuh_tempo');
            $table->foreignId('id_siswa')->constrained('siswa');
            $table->foreignId('id_sekolah')->constrained('profil_sekolah');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');
            $table->decimal('grand_total', 12, 2);
            $table->timestamps();

            $table->index('no_invoice');
            $table->index('tanggal_invoice');
            $table->index('status');
            $table->index('id_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
