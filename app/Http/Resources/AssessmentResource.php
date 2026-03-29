<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'test_date' => $this->test_date->format('Y-m-d'),
            'psychologist_name' => $this->psychologist_name,
            'psychological_assessment' => new PsychologicalAssessmentResource($this->whenLoaded('psychologicalAssessment')),
            'talents_mapping' => new TalentsMappingResource($this->whenLoaded('talentsMapping')),
            'scores' => AssessmentScoreResource::collection($this->whenLoaded('scores')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
