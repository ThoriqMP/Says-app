<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentsMappingResource extends JsonResource
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
            'brain_dominance' => $this->brain_dominance,
            'social_dominance' => $this->social_dominance,
            'skill_dominance' => $this->skill_dominance,
            'strengths' => $this->strengths,
            'deficits' => $this->deficits,
            'cluster_strength' => $this->cluster_strength,
            'personal_branding' => $this->personal_branding,
        ];
    }
}
