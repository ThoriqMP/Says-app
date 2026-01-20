<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentsMapping extends Model
{
    protected $table = 'talents_mapping';

    protected $fillable = [
        'assessment_id',
        'brain_dominance',
        'social_dominance',
        'skill_dominance',
        'strengths',
        'deficits',
        'cluster_strength',
        'personal_branding',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
