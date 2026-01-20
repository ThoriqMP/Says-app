<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'nullable|exists:subjects,id',
            'new_subject_name' => 'nullable|string|max:255',
            'new_subject_age' => 'nullable|integer|min:0|max:120',
            'new_subject_gender' => 'nullable|in:male,female',
            'new_subject_phone' => 'nullable|string|max:50',
            'test_date' => 'required|date',
            'psychologist_name' => 'required|string|max:255',
            'personality' => 'array',
            'personality.*' => 'nullable|in:TD,KD,AD,D,SD',
            'love_language' => 'array',
            'love_language.*' => 'nullable|in:TD,KD,AD,D,SD',
            'multiple_intelligence' => 'array',
            'multiple_intelligence.*' => 'nullable|integer|min:0|max:50',
            'talents.brain_dominance' => 'nullable|string|max:255',
            'talents.social_dominance' => 'nullable|string|max:255',
            'talents.skill_dominance' => 'nullable|string|max:255',
            'talents.strengths' => 'nullable|string',
            'talents.deficits' => 'nullable|string',
            'talents.cluster_strength' => 'nullable|string|max:255',
            'talents.personal_branding' => 'nullable|string|max:255',
        ];
    }
}
