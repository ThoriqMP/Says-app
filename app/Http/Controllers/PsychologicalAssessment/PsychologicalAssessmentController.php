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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

class PsychologicalAssessmentController extends Controller
{
    public function index()
    {
        // Menampilkan semua asesmen (Personal Mapping)
        // User ingin bisa mengambil data dari asesmen personal mapping yang sudah ada
        $assessments = Assessment::with(['subject', 'psychologicalAssessment'])
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
            'psychological.potential_intellectual_score' => 'nullable|integer',
            'psychological.potential_social_score' => 'nullable|integer',
            'psychological.potential_emotional_score' => 'nullable|integer',
            'psychological.iq_full_scale' => 'nullable|string',
            'psychological.iq_category' => 'nullable|string',
            'psychological.maturity_recommendation' => 'nullable|string',

            // Existing Assessment Data (Personality, MI, TM, etc.)
            'personality' => 'nullable|array',
            'love_language' => 'nullable|array',
            'multiple_intelligence' => 'nullable|array',
            'talents' => 'nullable|array',
        ]);

        // Update Assessment
        $assessment->update([
            'test_date' => $data['test_date'],
            'psychologist_name' => $data['psychologist_name'],
        ]);

        // Update/Create Psychological Data
        if (!empty($data['psychological'])) {
            $assessment->psychologicalAssessment()->updateOrCreate(
                ['assessment_id' => $assessment->id],
                $data['psychological']
            );
        }

        // Sync Scores (Delete old and re-insert for simplicity or update)
        // Only delete scores for categories we are updating to avoid deleting other potential data
        $assessment->scores()
            ->whereIn('category', ['personality', 'love_language', 'multiple_intelligence'])
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
            'psychological.potential_intellectual_score' => 'nullable|integer',
            'psychological.potential_social_score' => 'nullable|integer',
            'psychological.potential_emotional_score' => 'nullable|integer',
            'psychological.iq_full_scale' => 'nullable|string',
            'psychological.iq_category' => 'nullable|string',
            'psychological.maturity_recommendation' => 'nullable|string',

            // Existing Assessment Data (Personality, MI, TM, etc.)
            'personality' => 'nullable|array',
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
            $psychData['assessment_id'] = $assessment->id;
            PsychologicalAssessment::create($psychData);
        }

        // Save Scores (Personality, Love Language, MI)
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

    public function docx(Assessment $assessment)
    {
        $assessment->load(['subject', 'user', 'scores', 'talentsMapping', 'psychologicalAssessment']);
        $psychData = $assessment->psychologicalAssessment;

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Title
        $section->addText('LAPORAN ASESMEN PSIKOLOGIS', ['bold' => true, 'size' => 16, 'color' => '4338CA'], ['alignment' => 'center']);
        $section->addText('DOKUMEN RAHASIA', ['size' => 10, 'color' => '555555'], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Subject Info
        $styleTable = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80];
        $phpWord->addTableStyle('Subject Table', $styleTable);
        $table = $section->addTable('Subject Table');
        
        $table->addRow();
        $table->addCell(3000)->addText('Nama Lengkap', ['bold' => true]);
        $table->addCell(6000)->addText($assessment->subject->name);
        
        $table->addRow();
        $table->addCell(3000)->addText('Usia', ['bold' => true]);
        $table->addCell(6000)->addText($assessment->subject->precise_age);
        
        $table->addRow();
        $table->addCell(3000)->addText('Tanggal Pemeriksaan', ['bold' => true]);
        $table->addCell(6000)->addText($assessment->test_date->format('d F Y'));

        $section->addTextBreak(1);
        $section->addText('HASIL PEMERIKSAAN', ['bold' => true, 'size' => 14, 'color' => '4338CA', 'underline' => 'single']);
        $section->addTextBreak(1);

        // 1. Aspek Kognitif
        $section->addText('ASPEK KOGNITIF & POTENSI', ['bold' => true, 'size' => 11, 'color' => '5B21B6']);
        $section->addText('ASPEK KOGNITIF', ['bold' => true, 'size' => 10, 'color' => '5B21B6']);
        
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Aspek', ['bold' => true]);
        $table->addCell(4000, ['bgColor' => 'F3F4F6'])->addText('Keterangan', ['bold' => true]);
        $table->addCell(2000, ['bgColor' => 'F3F4F6'])->addText('Skala', ['bold' => true]);

        $cogDescriptions = [
            'Verbal' => 'Kemampuan berkomunikasi dan memahami bahasa',
            'Numerical' => 'Kemampuan berpikir praktis matematis dan berhitung',
            'Logical' => 'Kemampuan untuk memahami masalah secara hubungan sebab akibat dan menemukan solusi',
            'Spatial' => 'Kemampuan untuk berlaku cermat dalam menyelesaikan suatu pekerjaan atau tugas'
        ];
        $cogMap = [
            'Verbal' => 'Kemampuan Verbal',
            'Numerical' => 'Kemampuan Numerikal',
            'Logical' => 'Kemampuan Berpikir Logis',
            'Spatial' => 'Kemampuan Visual Spasial'
        ];

        foreach(['Verbal', 'Numerical', 'Logical', 'Spatial'] as $aspect) {
            $table->addRow();
            $table->addCell(3000)->addText($cogMap[$aspect]);
            $table->addCell(4000)->addText($cogDescriptions[$aspect]);
            $scoreVal = $psychData->{'cognitive_'.strtolower($aspect).'_score'} ?? '-';
            $scaleVal = $psychData->{'cognitive_'.strtolower($aspect).'_scale'} ?? '';
            $table->addCell(2000)->addText($scoreVal . ' ' . $scaleVal, ['bold' => true], ['alignment' => 'center']);
        }
        $section->addTextBreak(1);

        // 2. Aspek Potensi
        $section->addText('ASPEK POTENSI', ['bold' => true, 'size' => 10, 'color' => '5B21B6']);
        
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(4000, ['bgColor' => 'F3F4F6'])->addText('Aspek Potensi', ['bold' => true]);
        $table->addCell(2000, ['bgColor' => 'F3F4F6'])->addText('Skor', ['bold' => true]);
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Keterangan Grafis', ['bold' => true]);

        $potensiMap = [
            'Intellectual' => 'Intelektual (Original Scale)',
            'Social' => 'Sosial',
            'Emotional' => 'Emosional'
        ];

        $legend = "3 = Berkembang Baik / Optimal\n2 = Cukup Berkembang\n1 = Kurang Berkembang\n(-) = Berkembang namun Ada Hambatan";

        foreach(['Intellectual', 'Social', 'Emotional'] as $index => $aspect) {
            $table->addRow();
            $table->addCell(4000)->addText($potensiMap[$aspect]);
            
            $val = $psychData->{'potential_'.strtolower($aspect).'_score'} ?? '-';
            if ($aspect !== 'Intellectual' && $val !== '-') {
                $val = '(-) ' . $val;
            }
            $table->addCell(2000)->addText($val, ['bold' => true], ['alignment' => 'center']);
            
            if ($index === 0) {
                 $table->addCell(3000, ['vMerge' => 'restart'])->addText($legend, ['size' => 8]);
            } else {
                 $table->addCell(3000, ['vMerge' => 'continue']);
            }
        }
        $section->addTextBreak(1);

        // IQ & Maturity
        $section->addText('KESIMPULAN', ['bold' => true, 'size' => 10, 'color' => '5B21B6']);
        
        // IQ Table
        $section->addText('Taraf Kecerdasan (Full Scale)', ['bold' => true, 'size' => 9]);
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Kategori');
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Range');
        $table->addCell(1000, ['bgColor' => 'F3F4F6'])->addText('Cek');

        $iqRanges = [
            'Very Superior' => '119 - Ke atas',
            'Tinggi' => '105 - 118',
            'Cukup' => '100 - 104',
            'Sedang' => '95 - 99',
            'Rendah' => '81 - 94'
        ];
        $currentIqCat = trim($psychData->iq_category ?? '');

        foreach($iqRanges as $cat => $range) {
            $table->addRow();
            $table->addCell(3000)->addText($cat);
            $table->addCell(3000)->addText($range);
            $check = (strcasecmp($currentIqCat, $cat) === 0) ? 'V' : '';
            $table->addCell(1000)->addText($check, ['bold' => true], ['alignment' => 'center']);
        }
        $section->addTextBreak(1);

        // Maturity Table
        $section->addText('Taraf Kematangan Perkembangan', ['bold' => true, 'size' => 9]);
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(6000, ['bgColor' => 'F3F4F6'])->addText('Rekomendasi');
        $table->addCell(1000, ['bgColor' => 'F3F4F6'])->addText('Cek');

        $maturityLevels = ['Disarankan', 'Dipertimbangkan', 'Tidak Disarankan'];
        $currentMaturity = trim($psychData->maturity_recommendation ?? '');

        foreach($maturityLevels as $level) {
            $table->addRow();
            $table->addCell(6000)->addText($level);
            $check = (strcasecmp($currentMaturity, $level) === 0) ? 'V' : '';
            $table->addCell(1000)->addText($check, ['bold' => true], ['alignment' => 'center']);
        }
        
        // Personal Mapping Section
        // $section->addPageBreak(); // Removed to keep in one page if possible
        $section->addText('(PERSONAL MAPPING)', ['bold' => true, 'size' => 14, 'color' => '4338CA', 'underline' => 'single']);
        $section->addTextBreak(1);

        // Personality
        $section->addText('PERSONALITY', ['bold' => true, 'size' => 11]);
        $personalityScores = $assessment->scores->where('category', 'personality')->values();
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Aspek', ['bold' => true]);
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Label', ['bold' => true]);
        
        foreach($personalityScores as $s) {
            $table->addRow();
            $table->addCell(3000)->addText($s->aspect_name);
            $table->addCell(3000)->addText($s->label);
        }
        $section->addTextBreak(1);

        // Love Language
        $section->addText('LOVE LANGUAGE', ['bold' => true, 'size' => 11]);
        $llScores = $assessment->scores->where('category', 'love_language')->values();
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Aspek', ['bold' => true]);
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Label', ['bold' => true]);
        
        foreach($llScores as $s) {
            $table->addRow();
            $table->addCell(3000)->addText($s->aspect_name);
            $table->addCell(3000)->addText($s->label);
        }
        $section->addTextBreak(1);

        // MI
        $section->addText('MULTIPLE INTELLIGENCE', ['bold' => true, 'size' => 11]);
        $miScores = $assessment->scores->where('category', 'multiple_intelligence')->values();
        $table = $section->addTable('Subject Table');
        $table->addRow();
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Aspek', ['bold' => true]);
        $table->addCell(3000, ['bgColor' => 'F3F4F6'])->addText('Score', ['bold' => true]);
        
        foreach($miScores as $s) {
            $table->addRow();
            $table->addCell(3000)->addText($s->aspect_name);
            $table->addCell(3000)->addText($s->score_value);
        }

        $section->addTextBreak(1);

        // Talents Mapping
        if ($assessment->talentsMapping) {
            $section->addPageBreak();
            $section->addText('TALENTS MAPPING', ['bold' => true, 'size' => 11]);
            $tm = $assessment->talentsMapping;
            
            $table = $section->addTable('Subject Table');
            
            $table->addRow();
            $table->addCell(3000)->addText('Brain Dominance', ['color' => '666666', 'size' => 9]);
            $table->addCell(3000)->addText('Social Dominance', ['color' => '666666', 'size' => 9]);
            $table->addCell(3000)->addText('Skill Dominance', ['color' => '666666', 'size' => 9]);
            
            $table->addRow();
            $table->addCell(3000)->addText($tm->brain_dominance, ['bold' => true]);
            $table->addCell(3000)->addText($tm->social_dominance, ['bold' => true]);
            $table->addCell(3000)->addText($tm->skill_dominance, ['bold' => true]);
            
            $section->addTextBreak(1);
            
            $section->addText('Cluster Strength: ' . $tm->cluster_strength);
            $section->addText('Personal Branding: ' . $tm->personal_branding);
            
            $section->addTextBreak(1);
            
            $section->addText('Strengths:', ['bold' => true, 'color' => '059669']);
            $section->addText($tm->strengths);
            
            $section->addTextBreak(1);
            
            $section->addText('Deficits:', ['bold' => true, 'color' => 'DC2626']);
            $section->addText($tm->deficits);
        }

        $section->addTextBreak(2);

        // Signature
        $signaturePath = storage_path('app/public/logos/ttd_Ambu-removebg-preview.png');
        if (file_exists($signaturePath)) {
            $table = $section->addTable(['borderSize' => 0]);
            $table->addRow();
            $table->addCell(5000); // Spacer
            $cell = $table->addCell(4000);
            $cell->addText('Bogor, ' . $assessment->test_date->format('d F Y'), ['alignment' => 'center']);
            $cell->addText('Psikolog Pemeriksa,', ['alignment' => 'center']);
            try {
                $cell->addImage($signaturePath, ['height' => 60, 'align' => 'center']);
            } catch (\Exception $e) {
                // Ignore image error
            }
            $cell->addText($assessment->psychologist_name, ['bold' => true, 'underline' => 'single', 'alignment' => 'center']);
            $cell->addText('SIPP: ' . ($assessment->psychologist_sipp ?? '0514-22-2-1'), ['size' => 9, 'alignment' => 'center']);
        }

        $subjectName = $this->safeFilename($assessment->subject?->name ?? 'TanpaNama');
        $testDate = $assessment->test_date?->format('dmY') ?? now()->format('dmY');
        $filename = 'PSIKOLOGI_'.$subjectName.'_'.$testDate.'.docx';

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        
        // Clean buffer to prevent file corruption
        if (ob_get_length()) {
            ob_end_clean();
        }

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        
        $writer->save("php://output");
        exit;
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
