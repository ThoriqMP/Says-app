<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProbingActivity extends Model
{
    protected $fillable = ['student_report_id', 'activity_name', 'description', 'image_path'];

    public function report()
    {
        return $this->belongsTo(StudentReport::class, 'student_report_id');
    }
}
