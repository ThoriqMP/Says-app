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
        'user_id',
        'nis',
        'class',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke raport
     */
    public function reports()
    {
        return $this->hasMany(StudentReport::class, 'siswa_id');
    }

    /**
     * Relasi ke invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_siswa');
    }
}
