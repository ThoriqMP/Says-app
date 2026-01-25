<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsychologicalAssessment extends Model
{
    protected $fillable = [
        'assessment_id',
        'cognitive_verbal_score', 'cognitive_verbal_scale',
        'cognitive_numerical_score', 'cognitive_numerical_scale',
        'cognitive_logical_score', 'cognitive_logical_scale',
        'cognitive_spatial_score', 'cognitive_spatial_scale',
        'potential_intellectual_score',
        'potential_social_score',
        'potential_emotional_score',
        'iq_full_scale',
        'iq_category',
        'maturity_recommendation',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
