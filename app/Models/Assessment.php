<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'subject_id',
        'user_id',
        'test_date',
        'psychologist_name',
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(AssessmentScore::class);
    }

    public function talentsMapping()
    {
        return $this->hasOne(TalentsMapping::class);
    }

    public function psychologicalAssessment()
    {
        return $this->hasOne(PsychologicalAssessment::class);
    }
}
