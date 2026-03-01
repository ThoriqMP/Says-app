<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSubject extends Model
{
    protected $fillable = ['report_category_id', 'name'];

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

    public function grades()
    {
        return $this->hasMany(ReportGrade::class);
    }
}
