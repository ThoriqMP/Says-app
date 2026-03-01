<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    protected $fillable = ['name'];

    public function subjects()
    {
        return $this->hasMany(ReportSubject::class);
    }

    public function reports()
    {
        return $this->hasMany(StudentReport::class);
    }
}
