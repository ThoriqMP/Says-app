<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    protected $fillable = ['siswa_id', 'report_category_id', 'period', 'summary_notes'];

    public function student()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

    public function grades()
    {
        return $this->hasMany(ReportGrade::class);
    }

    public function probingActivities()
    {
        return $this->hasMany(ProbingActivity::class);
    }
}
