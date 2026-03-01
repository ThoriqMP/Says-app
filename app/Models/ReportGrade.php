<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportGrade extends Model
{
    protected $fillable = [
        'student_report_id', 
        'report_subject_id', 
        'score', 
        'score_pts', 
        'score_pas', 
        'score_remedial', 
        'score_harian', 
        'predicate', 
        'ayat_range', 
        'description'
    ];

    public function report()
    {
        return $this->belongsTo(StudentReport::class, 'student_report_id');
    }

    public function subject()
    {
        return $this->belongsTo(ReportSubject::class, 'report_subject_id');
    }
}
