<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'subject_name' => $this->subject->name, // Asumsi relasi subject ada
            'score' => $this->score,
            'score_pts' => $this->score_pts,
            'score_pas' => $this->score_pas,
            'score_remedial' => $this->score_remedial,
            'score_harian' => $this->score_harian,
            'predicate' => $this->predicate,
            'description' => $this->description,
            'ayat_range' => $this->ayat_range,
        ];
    }
}
