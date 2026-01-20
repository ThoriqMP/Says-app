<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreAssessmentRequest;
use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\Subject;
use App\Models\TalentsMapping;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with(['subject'])
            ->latest('test_date')
            ->paginate(10);

        return view('assessments.index', compact('assessments'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('assessments.create', compact('subjects'));
    }

    public function store(StoreAssessmentRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['new_subject_name'])) {
            $subject = Subject::create([
                'name' => $data['new_subject_name'],
                'age' => $data['new_subject_age'] ?? null,
                'gender' => $data['new_subject_gender'] ?? null,
                'phone' => $data['new_subject_phone'] ?? null,
            ]);
        } else {
            $subject = Subject::findOrFail($data['subject_id']);
        }

        $assessment = Assessment::create([
            'subject_id' => $subject->id,
            'user_id' => Auth::id(),
            'test_date' => $data['test_date'],
            'psychologist_name' => $data['psychologist_name'],
        ]);

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

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Asesmen berhasil dibuat.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['subject', 'user', 'scores', 'talentsMapping']);

        $logoProsekar = null;
        $signatureAmbu = null;

        $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        if (file_exists($logoPath)) {
            $logoProsekar = 'data:image/png;base64,'.base64_encode(File::get($logoPath));
        }

        $signaturePath = storage_path('app/public/logos/ttd_Ambu-removebg-preview.png');
        if (file_exists($signaturePath)) {
            $signatureAmbu = 'data:image/png;base64,'.base64_encode(File::get($signaturePath));
        }

        return view('assessments.show', compact('assessment', 'logoProsekar', 'signatureAmbu'));
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load(['subject', 'scores', 'talentsMapping']);

        $personalityScores = $assessment->scores
            ->where('category', 'personality')
            ->pluck('label', 'aspect_name');

        $loveLanguageScores = $assessment->scores
            ->where('category', 'love_language')
            ->pluck('label', 'aspect_name');

        $multipleIntelligenceScores = $assessment->scores
            ->where('category', 'multiple_intelligence')
            ->pluck('score_value', 'aspect_name');

        return view('assessments.edit', compact(
            'assessment',
            'personalityScores',
            'loveLanguageScores',
            'multipleIntelligenceScores'
        ));
    }

    public function update(StoreAssessmentRequest $request, Assessment $assessment)
    {
        $data = $request->validated();

        $assessment->update([
            'test_date' => $data['test_date'],
            'psychologist_name' => $data['psychologist_name'],
        ]);

        AssessmentScore::where('assessment_id', $assessment->id)->delete();
        TalentsMapping::where('assessment_id', $assessment->id)->delete();

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

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Asesmen berhasil diperbarui.');
    }

    public function pdf(Assessment $assessment)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $assessment->load(['subject', 'user', 'scores', 'talentsMapping']);

        $logoProsekar = null;
        $signatureAmbu = null;
        $kamusContent = '';
        $kamusPages = [];

        $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        if (file_exists($logoPath)) {
            $logoProsekar = 'data:image/png;base64,'.base64_encode(File::get($logoPath));
        }

        $signaturePath = storage_path('app/public/logos/ttd_Ambu-removebg-preview.png');
        if (file_exists($signaturePath)) {
            $signatureAmbu = 'data:image/png;base64,'.base64_encode(File::get($signaturePath));
        }

        $kamusPath = storage_path('app/public/kamus-laporan.txt');
        if (File::exists($kamusPath)) {
            $kamusContent = trim(File::get($kamusPath));
            if ($kamusContent !== '') {
                $kamusPages = $this->buildKamusPages($kamusContent);
            }
        }

        $pdf = Pdf::loadView('assessments.pdf', [
            'assessment' => $assessment,
            'logoProsekar' => $logoProsekar,
            'signatureAmbu' => $signatureAmbu,
            'kamusContent' => $kamusContent,
            'kamusPages' => $kamusPages,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $subjectName = $assessment->subject?->name ?? 'TanpaNama';
        $subjectName = preg_replace('/[^A-Za-z0-9]+/', '_', $subjectName);
        $subjectName = trim($subjectName, '_');
        if ($subjectName === '') {
            $subjectName = 'TanpaNama';
        }

        $testDate = $assessment->test_date?->format('dmY') ?? now()->format('dmY');
        $filename = 'PMA_'.$subjectName.'_'.$testDate.'.pdf';

        if (request()->boolean('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('assessments.index')
            ->with('success', 'Asesmen berhasil dihapus.');
    }

    public function pdfView(Assessment $assessment)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $assessment->load(['subject', 'user', 'scores', 'talentsMapping']);

        $logoProsekar = null;
        $signatureAmbu = null;
        $kamusContent = '';
        $kamusPages = [];

        $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        if (file_exists($logoPath)) {
            $logoProsekar = 'data:image/png;base64,'.base64_encode(File::get($logoPath));
        }

        $signaturePath = storage_path('app/public/logos/ttd_Ambu-removebg-preview.png');
        if (file_exists($signaturePath)) {
            $signatureAmbu = 'data:image/png;base64,'.base64_encode(File::get($signaturePath));
        }

        $kamusPath = storage_path('app/public/kamus-laporan.txt');
        if (File::exists($kamusPath)) {
            $kamusContent = trim(File::get($kamusPath));
            if ($kamusContent !== '') {
                $kamusPages = $this->buildKamusPages($kamusContent);
            }
        }

        $pdf = Pdf::loadView('assessments.pdf', [
            'assessment' => $assessment,
            'logoProsekar' => $logoProsekar,
            'signatureAmbu' => $signatureAmbu,
            'kamusContent' => $kamusContent,
            'kamusPages' => $kamusPages,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('assessment-'.$assessment->id.'.pdf');
    }

    private function buildKamusPages(string $kamusContent): array
    {
        $sections = $this->splitKamusSections($kamusContent);

        if (empty($sections)) {
            $sections = [trim($kamusContent)];
        }

        $pages = [];

        foreach ($sections as $sectionText) {
            $sectionText = trim($sectionText);

            if ($sectionText === '') {
                continue;
            }

            $lines = preg_split("/\R/", $sectionText);
            $firstNonEmptyLine = null;

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $firstNonEmptyLine = $line;
                    break;
                }
            }

            $title = null;
            $bodyText = $sectionText;

            if ($firstNonEmptyLine !== null && ! preg_match('/^(•|\d+\.)/u', $firstNonEmptyLine)) {
                $title = $firstNonEmptyLine;
                $bodyText = preg_replace('/^\s*'.preg_quote($firstNonEmptyLine, '/').'\s*\R?/', '', $sectionText, 1);
                $bodyText = trim((string) $bodyText);
            }

            $paragraphs = preg_split('/\n\s*\n/', $bodyText);
            $paragraphs = array_values(array_filter(array_map('trim', $paragraphs), fn ($p) => $p !== ''));

            $totalLength = strlen(implode("\n\n", $paragraphs));
            $midPoint = $totalLength / 2;

            $currentLength = 0;
            $splitIndex = count($paragraphs);

            foreach ($paragraphs as $index => $para) {
                $currentLength += strlen($para);
                if ($currentLength >= $midPoint) {
                    $splitIndex = $index + 1;
                    break;
                }
            }

            $pages[] = [
                'title' => $title,
                'left' => array_slice($paragraphs, 0, $splitIndex),
                'right' => array_slice($paragraphs, $splitIndex),
            ];
        }

        return $pages;
    }

    private function splitKamusSections(string $kamusContent): array
    {
        $kamusContent = str_replace("\r\n", "\n", $kamusContent);
        $lines = explode("\n", $kamusContent);

        $sections = [];
        $buffer = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed !== '' && preg_match('/^\[batas\b.*\]$/i', $trimmed)) {
                $sectionText = trim(implode("\n", $buffer));
                if ($sectionText !== '') {
                    $sections[] = $sectionText;
                }
                $buffer = [];

                continue;
            }

            $buffer[] = $line;
        }

        $tail = trim(implode("\n", $buffer));
        if ($tail !== '') {
            $sections[] = $tail;
        }

        return $sections;
    }
}
