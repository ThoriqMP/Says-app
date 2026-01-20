<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama_siswa',
        'nama_orang_tua',
        'alamat_tagihan',
    ];

    /**
     * Relasi ke invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_siswa');
    }
}
