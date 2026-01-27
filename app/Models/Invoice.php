<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'no_invoice',
        'tanggal_invoice',
        'jatuh_tempo',
        'id_siswa',
        'id_sekolah',
        'status',
        'grand_total',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'jatuh_tempo' => 'date',
        'grand_total' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->no_invoice)) {
                $invoice->no_invoice = self::generateInvoiceNumber($invoice->tanggal_invoice);
            }
        });
    }

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    /**
     * Relasi ke profil sekolah
     */
    public function profilSekolah()
    {
        return $this->belongsTo(ProfilSekolah::class, 'id_sekolah');
    }

    /**
     * Relasi ke invoice detail
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_invoice');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate nomor invoice otomatis dengan format INV-SAYYIDAH-[BulanRomawi]-[NomorUrut]
     */
    public static function generateInvoiceNumber($tanggalInvoice = null)
    {
        // Gunakan tanggal invoice atau tanggal sekarang
        $date = $tanggalInvoice ? \Carbon\Carbon::parse($tanggalInvoice) : now();
        $bulanRomawi = \App\Helpers\TerbilangHelper::toRoman($date->month);

        // Cari invoice terakhir di bulan yang sama
        $lastInvoice = self::whereYear('tanggal_invoice', $date->year)
            ->whereMonth('tanggal_invoice', $date->month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            // Ambil nomor urut dari invoice terakhir
            $lastNumber = intval(substr($lastInvoice->no_invoice, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada invoice di bulan ini, mulai dari 0001
            $newNumber = '0001';
        }

        return 'INV-SAYYIDAH-'.$bulanRomawi.'-'.$newNumber;
    }
}
