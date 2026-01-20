<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentScore extends Model
{
    protected $fillable = [
        'assessment_id',
        'category',
        'aspect_name',
        'score_value',
        'label',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
