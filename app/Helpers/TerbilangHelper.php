<?php

namespace App\Helpers;

class TerbilangHelper
{
    private static $angka = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas',
    ];

    public static function terbilang($number)
    {
        if ($number < 12) {
            return self::$angka[$number];
        } elseif ($number < 20) {
            return self::$angka[$number - 10].' belas';
        } elseif ($number < 100) {
            return self::$angka[floor($number / 10)].' puluh '.self::$angka[$number % 10];
        } elseif ($number < 200) {
            return 'seratus '.self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return self::$angka[floor($number / 100)].' ratus '.self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return 'seribu '.self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang(floor($number / 1000)).' ribu '.self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang(floor($number / 1000000)).' juta '.self::terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::terbilang(floor($number / 1000000000)).' milyar '.self::terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            return self::terbilang(floor($number / 1000000000000)).' triliun '.self::terbilang($number % 1000000000000);
        }

        return '';
    }

    public static function rupiah($number)
    {
        $terbilang = self::terbilang($number);

        return ucwords($terbilang).' Rupiah';
    }

    public static function formatRupiah($number)
    {
        return 'Rp '.number_format($number, 0, ',', '.');
    }

    public static function toRoman($number)
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1,
        ];
        $result = '';
        foreach ($map as $roman => $value) {
            $matches = intval($number / $value);
            $result .= str_repeat($roman, $matches);
            $number = $number % $value;
        }

        return $result;
    }
}
