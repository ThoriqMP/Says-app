<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_details';

    protected $fillable = [
        'id_invoice',
        'id_layanan',
        'deskripsi_tambahan',
        'kuantitas',
        'harga_satuan',
        'total_biaya',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Relasi ke invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    /**
     * Relasi ke layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}
