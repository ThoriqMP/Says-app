<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'age',
        'gender',
        'phone',
    ];

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
