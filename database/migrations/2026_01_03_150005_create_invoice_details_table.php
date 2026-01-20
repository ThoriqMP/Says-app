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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('layanan');
            $table->text('deskripsi_tambahan')->nullable();
            $table->integer('kuantitas')->default(1);
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_biaya', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
