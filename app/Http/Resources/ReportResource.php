<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'student' => new StudentResource($this->whenLoaded('student')),
            'category' => new ReportCategoryResource($this->whenLoaded('category')),
            'semester' => $this->semester,
            'academic_year' => $this->academic_year,
            'summary_notes' => $this->summary_notes,
            'probing_data' => $this->probing_data,
            'grades' => GradeResource::collection($this->whenLoaded('grades')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
