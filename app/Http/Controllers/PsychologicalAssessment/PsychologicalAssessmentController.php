<?php

namespace App\Http\Controllers\PsychologicalAssessment;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\PsychologicalAssessment;
use App\Models\Subject;
use App\Models\TalentsMapping;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PsychologicalAssessmentController extends Controller
{
    public function index()
    {
        // Menampilkan semua asesmen (Personal Mapping)
        // User ingin bisa mengambil data dari asesmen personal mapping yang sudah ada
        $assessments = Assessment::with(['subject', 'psychologicalAssessment', 'scores', 'talentsMapping'])
            ->latest('test_date')
            ->paginate(10);

        return view('psychological-assessments.index', compact('assessments'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('psychological-assessments.create', compact('subjects'));
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load(['subject', 'scores', 'talentsMapping', 'psychologicalAssessment']);
        
        // Transform scores for easier use in Blade
        $scores = $assessment->scores->groupBy('category')->map(function ($items, $category) {
            if ($category === 'multiple_intelligence') {
                return $items->pluck('score_value', 'aspect_name')->all();
            }
            return $items->pluck('label', 'aspect_name')->all();
        });

        return view('psychological-assessments.edit', compact('assessment', 'scores'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $data = $request->validate([
            'test_date' => 'required|date',
            'psychologist_name' => 'required|string|max:255',
            
            // Psychological Data
            'psychological.cognitive_verbal_score' => 'nullable|integer',
            'psychological.cognitive_verbal_scale' => 'nullable|integer',
            'psychological.cognitive_numerical_score' => 'nullable|integer',
            'psychological.cognitive_numerical_scale' => 'nullable|integer',
            'psychological.cognitive_logical_score' => 'nullable|integer',
            'psychological.cognitive_logical_scale' => 'nullable|integer',
            'psychological.cognitive_spatial_score' => 'nullable|integer',
            'psychological.cognitive_spatial_scale' => 'nullable|integer',
            'psychological.potential_intellectual_score' => 'nullable|string',
            'psychological.potential_social_score' => 'nullable|string',
            'psychological.potential_emotional_score' => 'nullable|string',
            // 'psychological.iq_full_scale' => 'nullable|string', // Removed manual input
            'psychological.iq_category' => 'nullable|string',
            'psychological.maturity_recommendation' => 'nullable|string',
            'psychological.job_recommendation' => 'nullable|string',

            // Existing Assessment Data (Personality, MI, TM, etc.)
            'personality' => 'nullable|array',
            'learning_style' => 'nullable|array',
            'love_language' => 'nullable|array',
            'multiple_intelligence' => 'nullable|array',
            'talents' => 'nullable|array',
        ]);

        // Update Assessment
        $assessment->update([
            'test_date' => $data['test_date'],
            'psychologist_name' => $data['psychologist_name'],
        ]);

        // Update Subject DOB if provided
        if (!empty($data['subject_dob'])) {
            $assessment->subject->update(['date_of_birth' => $data['subject_dob']]);
        }

        // Update/Create Psychological Data
        if (!empty($data['psychological'])) {
            $psychological = $data['psychological'];
            $psychological['potential_intellectual_score'] = $this->parsePotentialScore($psychological['potential_intellectual_score'] ?? null);
            $psychological['potential_social_score'] = $this->parsePotentialScore($psychological['potential_social_score'] ?? null);
            $psychological['potential_emotional_score'] = $this->parsePotentialScore($psychological['potential_emotional_score'] ?? null);
            // Derive IQ Range/Scale from Category logic if needed (or just leave null)
            // $psychological['iq_full_scale'] = null; 

            $assessment->psychologicalAssessment()->updateOrCreate(
                ['assessment_id' => $assessment->id],
                $psychological
            );
        }

        // Sync Scores (Delete old and re-insert for simplicity or update)
        // Only delete scores for categories we are updating to avoid deleting other potential data
        $assessment->scores()
            ->whereIn('category', ['personality', 'learning_style', 'love_language', 'multiple_intelligence'])
            ->delete();

        $scoresPayload = [];
        foreach ($data['personality'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'personality',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['learning_style'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'learning_style',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['love_language'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'love_language',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['multiple_intelligence'] ?? [] as $aspect => $score) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'multiple_intelligence',
                'aspect_name' => $aspect,
                'score_value' => (int) $score,
                'label' => null,
            ];
        }

        if (!empty($scoresPayload)) {
            AssessmentScore::insert($scoresPayload);
        }

        // Update Talents Mapping
        $assessment->talentsMapping()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            [
                'brain_dominance' => $data['talents']['brain_dominance'] ?? null,
                'social_dominance' => $data['talents']['social_dominance'] ?? null,
                'skill_dominance' => $data['talents']['skill_dominance'] ?? null,
                'strengths' => $data['talents']['strengths'] ?? null,
                'deficits' => $data['talents']['deficits'] ?? null,
                'cluster_strength' => $data['talents']['cluster_strength'] ?? null,
                'personal_branding' => $data['talents']['personal_branding'] ?? null,
            ]
        );

        return redirect()->route('psychological-assessments.show', $assessment)
            ->with('success', 'Asesmen Psikologis berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        // Validation
        $data = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'new_subject_name' => 'nullable|required_without:subject_id|string|max:255',
            'new_subject_dob' => 'nullable|required_without:subject_id|date',
            'new_subject_gender' => 'nullable|in:male,female',
            'new_subject_phone' => 'nullable|string|max:50',
            'test_date' => 'required|date',
            'psychologist_name' => 'required|string|max:255',
            
            // Psychological Data
            'psychological.cognitive_verbal_score' => 'nullable|integer',
            'psychological.cognitive_verbal_scale' => 'nullable|integer',
            'psychological.cognitive_numerical_score' => 'nullable|integer',
            'psychological.cognitive_numerical_scale' => 'nullable|integer',
            'psychological.cognitive_logical_score' => 'nullable|integer',
            'psychological.cognitive_logical_scale' => 'nullable|integer',
            'psychological.cognitive_spatial_score' => 'nullable|integer',
            'psychological.cognitive_spatial_scale' => 'nullable|integer',
            'psychological.potential_intellectual_score' => 'nullable|string',
            'psychological.potential_social_score' => 'nullable|string',
            'psychological.potential_emotional_score' => 'nullable|string',
            // 'psychological.iq_full_scale' => 'nullable|string', // Removed
            'psychological.iq_category' => 'nullable|string',
            'psychological.maturity_recommendation' => 'nullable|string',
            'psychological.job_recommendation' => 'nullable|string',

            // Existing Assessment Data (Personality, MI, TM, etc.)
            'personality' => 'nullable|array',
            'learning_style' => 'nullable|array',
            'love_language' => 'nullable|array',
            'multiple_intelligence' => 'nullable|array',
            'talents' => 'nullable|array',
        ]);

        // Handle Subject
        if (! empty($data['new_subject_name'])) {
            $subject = Subject::create([
                'name' => $data['new_subject_name'],
                'date_of_birth' => $data['new_subject_dob'] ?? null,
                'gender' => $data['new_subject_gender'] ?? null,
                'phone' => $data['new_subject_phone'] ?? null,
            ]);
        } else {
            $subject = Subject::findOrFail($data['subject_id']);
        }

        // Create Assessment
        $assessment = Assessment::create([
            'subject_id' => $subject->id,
            'user_id' => Auth::id(),
            'test_date' => $data['test_date'],
            'psychologist_name' => $data['psychologist_name'],
        ]);

        // Save Psychological Data
        if (!empty($data['psychological'])) {
            $psychData = $data['psychological'];
            $psychData['potential_intellectual_score'] = $this->parsePotentialScore($psychData['potential_intellectual_score'] ?? null);
            $psychData['potential_social_score'] = $this->parsePotentialScore($psychData['potential_social_score'] ?? null);
            $psychData['potential_emotional_score'] = $this->parsePotentialScore($psychData['potential_emotional_score'] ?? null);
            $psychData['assessment_id'] = $assessment->id;
            PsychologicalAssessment::create($psychData);
        }

        // Save Scores (Personality, Learning Style, Love Language, MI)
        $scoresPayload = [];

        foreach ($data['personality'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'personality',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['learning_style'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'learning_style',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['love_language'] ?? [] as $aspect => $label) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'love_language',
                'aspect_name' => $aspect,
                'score_value' => null,
                'label' => $label,
            ];
        }

        foreach ($data['multiple_intelligence'] ?? [] as $aspect => $score) {
            $scoresPayload[] = [
                'assessment_id' => $assessment->id,
                'category' => 'multiple_intelligence',
                'aspect_name' => $aspect,
                'score_value' => (int) $score,
                'label' => null,
            ];
        }

        if (! empty($scoresPayload)) {
            AssessmentScore::insert($scoresPayload);
        }

        // Save Talents Mapping
        TalentsMapping::create([
            'assessment_id' => $assessment->id,
            'brain_dominance' => $data['talents']['brain_dominance'] ?? null,
            'social_dominance' => $data['talents']['social_dominance'] ?? null,
            'skill_dominance' => $data['talents']['skill_dominance'] ?? null,
            'strengths' => $data['talents']['strengths'] ?? null,
            'deficits' => $data['talents']['deficits'] ?? null,
            'cluster_strength' => $data['talents']['cluster_strength'] ?? null,
            'personal_branding' => $data['talents']['personal_branding'] ?? null,
        ]);

        return redirect()->route('psychological-assessments.show', $assessment)
            ->with('success', 'Asesmen Psikologis berhasil dibuat.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['subject', 'user', 'scores', 'talentsMapping', 'psychologicalAssessment']);

        // Base64 images for view if needed
        $logoProsekar = $this->getBase64Image('logos/LogoProsekarWIthText-removebg-preview.png');
        $signatureAmbu = $this->getBase64Image('logos/ttd_Ambu-removebg-preview.png');

        return view('psychological-assessments.show', compact('assessment', 'logoProsekar', 'signatureAmbu'));
    }

    public function pdf(Assessment $assessment)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $assessment->load(['subject', 'user', 'scores', 'talentsMapping', 'psychologicalAssessment']);

        $logoProsekar = $this->getBase64Image('logos/LogoProsekarWIthText-removebg-preview.png');
        $signatureAmbu = $this->getBase64Image('logos/ttd_Ambu-removebg-preview.png');

        $pdf = Pdf::loadView('psychological-assessments.pdf', [
            'assessment' => $assessment,
            'logoProsekar' => $logoProsekar,
            'signature' => $signatureAmbu,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $subjectName = $this->safeFilename($assessment->subject?->name ?? 'TanpaNama');
        $testDate = $assessment->test_date?->format('dmY') ?? now()->format('dmY');
        $filename = 'PSIKOLOGI_'.$subjectName.'_'.$testDate.'.pdf';

        if (request()->boolean('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }




    private function parsePotentialScore($value): ?int
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);
        if ($string === '') {
            return null;
        }

        if (preg_match('/-?\d+/', $string, $matches) !== 1) {
            return null;
        }

        $number = (int) $matches[0];

        if (str_starts_with($string, '(-')) {
            return -abs($number);
        }

        return $number;
    }

    private function getBase64Image($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $data = File::get($fullPath);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return null;
    }

    private function safeFilename($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string));
    }
}
