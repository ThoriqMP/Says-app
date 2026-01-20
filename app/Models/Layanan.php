<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'harga_standar',
    ];

    /**
     * Relasi ke invoice detail
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_layanan');
    }
}
