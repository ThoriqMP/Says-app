<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'logo_path',
        'bank_nama',
        'no_rekening',
        'atas_nama',
        'pimpinan_nama',
        'signature_path',
    ];

    /**
     * Relasi ke invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_sekolah');
    }
}
