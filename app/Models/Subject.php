<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'age',
        'gender',
        'phone',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function getPreciseAge($testDate)
    {
        if (!$this->date_of_birth) {
            return $this->age ? "{$this->age} tahun" : '-';
        }

        $testDate = \Carbon\Carbon::parse($testDate);
        $diff = $this->date_of_birth->diff($testDate);

        return "{$diff->y} thn {$diff->m} bln {$diff->d} hari";
    }
}
