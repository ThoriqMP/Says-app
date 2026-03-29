<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PsychologicalAssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cognitive_verbal_score' => $this->cognitive_verbal_score,
            'cognitive_verbal_category' => $this->cognitive_verbal_category,
            'cognitive_performance_score' => $this->cognitive_performance_score,
            'cognitive_performance_category' => $this->cognitive_performance_category,
            'cognitive_total_score' => $this->cognitive_total_score,
            'cognitive_total_category' => $this->cognitive_total_category,
            'job_recommendation' => $this->job_recommendation,
        ];
    }
}
