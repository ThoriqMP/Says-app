<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class FamilyMappingController extends Controller
{
    public function index()
    {
        abort_if(! in_array(Auth::user()?->role, ['pimpinan', 'admin'], true), 403);

        $assessments = Assessment::with(['subject'])
            ->latest('test_date')
            ->get();

        return view('family-mapping.index', compact('assessments'));
    }

    public function pdf(Request $request)
    {
        abort_if(! in_array(Auth::user()?->role, ['pimpinan', 'admin'], true), 403);

        $data = $request->validate([
            'ayah_id' => ['required', 'integer', 'exists:assessments,id', 'different:ibu_id'],
            'ibu_id' => ['required', 'integer', 'exists:assessments,id'],
        ]);

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $ayah = Assessment::with(['subject', 'user', 'scores', 'talentsMapping'])->findOrFail($data['ayah_id']);
        $ibu = Assessment::with(['subject', 'user', 'scores', 'talentsMapping'])->findOrFail($data['ibu_id']);

        $logoProsekar = null;
        $signatureAnggia = null;

        $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        if (file_exists($logoPath)) {
            $logoProsekar = 'data:image/png;base64,'.base64_encode(File::get($logoPath));
        }

        $signaturePath = storage_path('app/public/logos/ttd_Ambu-removebg-preview.png');
        if (file_exists($signaturePath)) {
            $signatureAnggia = 'data:image/png;base64,'.base64_encode(File::get($signaturePath));
        }

        $labels = ['TD', 'KD', 'AD', 'D', 'SD'];
        $rank = ['TD' => 1, 'KD' => 2, 'AD' => 3, 'D' => 4, 'SD' => 5];

        $ayahPersonality = $this->dominantAspects($ayah->scores, 'personality', $rank);
        $ibuPersonality = $this->dominantAspects($ibu->scores, 'personality', $rank);
        $irisanPersonality = array_values(array_intersect($ayahPersonality, $ibuPersonality));

        $ayahLoveLanguage = $this->dominantAspects($ayah->scores, 'love_language', $rank);
        $ibuLoveLanguage = $this->dominantAspects($ibu->scores, 'love_language', $rank);
        $irisanLoveLanguage = array_values(array_intersect($ayahLoveLanguage, $ibuLoveLanguage));

        $ayahMiTop = $this->topMultipleIntelligence($ayah->scores, 5);
        $ibuMiTop = $this->topMultipleIntelligence($ibu->scores, 5);
        $irisanMiKeys = array_values(array_intersect(
            array_column($ayahMiTop, 'key'),
            array_column($ibuMiTop, 'key')
        ));
        $irisanMiLabels = array_values(array_map(fn (string $k) => $this->miLabel($k), $irisanMiKeys));

        $ayahTalents = $this->talentsPayload($ayah);
        $ibuTalents = $this->talentsPayload($ibu);
        $irisanTalents = $this->talentsIntersection($ayahTalents, $ibuTalents);

        $pdf = Pdf::loadView('family-mapping.pdf', [
            'ayah' => $ayah,
            'ibu' => $ibu,
            'labels' => $labels,
            'logoProsekar' => $logoProsekar,
            'signatureAnggia' => $signatureAnggia,
            'ayahPersonality' => $ayahPersonality,
            'ibuPersonality' => $ibuPersonality,
            'irisanPersonality' => $irisanPersonality,
            'ayahLoveLanguage' => $ayahLoveLanguage,
            'ibuLoveLanguage' => $ibuLoveLanguage,
            'irisanLoveLanguage' => $irisanLoveLanguage,
            'ayahMiTop' => $ayahMiTop,
            'ibuMiTop' => $ibuMiTop,
            'irisanMiLabels' => $irisanMiLabels,
            'ayahTalents' => $ayahTalents,
            'ibuTalents' => $ibuTalents,
            'irisanTalents' => $irisanTalents,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $ayahName = $this->safeFilename($ayah->subject?->name ?? 'Ayah');
        $date = $ayah->test_date?->format('dmY') ?? now()->format('dmY');
        $filename = 'FM_'.$ayahName.'_'.$date.'.pdf';

        if ($request->boolean('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    private function dominantAspects(Collection $scores, string $category, array $rank): array
    {
        $items = $scores->where('category', $category)->values();
        if ($items->isEmpty()) {
            return [];
        }

        $max = $items
            ->map(fn ($s) => $rank[$s->label] ?? 0)
            ->max();

        return $items
            ->filter(fn ($s) => ($rank[$s->label] ?? 0) === $max)
            ->pluck('aspect_name')
            ->filter()
            ->values()
            ->all();
    }

    private function topMultipleIntelligence(Collection $scores, int $limit): array
    {
        $items = $scores->where('category', 'multiple_intelligence');

        return $items
            ->sortByDesc(fn ($s) => (int) $s->score_value)
            ->take($limit)
            ->values()
            ->map(function ($s) {
                $key = $this->miKey((string) $s->aspect_name);
                $percent = ((int) $s->score_value) * 2;

                return [
                    'key' => $key,
                    'label' => $this->miLabel($key),
                    'score' => (int) $s->score_value,
                    'percent' => $percent,
                ];
            })
            ->all();
    }

    private function miKey(string $name): string
    {
        $raw = mb_strtolower($name);
        $norm = trim((string) preg_replace('/[^a-z0-9]+/u', ' ', $raw));

        $aliases = [
            'linguistik' => ['linguistik', 'linguistic'],
            'logika_matematika' => ['logika matematika', 'logical mathematical', 'logicalmathematical', 'logical-mathematical'],
            'visual_spasial' => ['visual spasial', 'visual spatial', 'visualspatial', 'visual-spatial', 'spatial'],
            'musikal' => ['musikal', 'musical'],
            'kinestetik' => ['kinestetik', 'bodily kinesthetic', 'bodilykinesthetic', 'bodily-kinesthetic', 'kinesthetic', 'kinaesthetic'],
            'interpersonal' => ['interpersonal'],
            'intrapersonal' => ['intrapersonal'],
            'naturalis' => ['naturalis', 'naturalist', 'naturalistic'],
        ];

        foreach ($aliases as $key => $terms) {
            foreach ($terms as $t) {
                $tNorm = trim((string) preg_replace('/[^a-z0-9]+/u', ' ', mb_strtolower($t)));
                if ($tNorm !== '' && (str_contains($norm, $tNorm) || str_contains(str_replace(' ', '', $norm), str_replace(' ', '', $tNorm)))) {
                    return $key;
                }
            }
        }

        return preg_replace('/[^a-z0-9]+/u', '_', $norm) ?: 'lainnya';
    }

    private function miLabel(string $key): string
    {
        return match ($key) {
            'linguistik' => 'Linguistik',
            'logika_matematika' => 'Logika Matematika',
            'visual_spasial' => 'Visual Spasial',
            'musikal' => 'Musikal',
            'kinestetik' => 'Kinestetik',
            'interpersonal' => 'Interpersonal',
            'intrapersonal' => 'Intrapersonal',
            'naturalis' => 'Naturalis',
            default => ucwords(str_replace('_', ' ', $key)),
        };
    }

    private function talentsPayload(Assessment $assessment): array
    {
        $tm = $assessment->talentsMapping;

        return [
            'brain_dominance' => (string) ($tm->brain_dominance ?? ''),
            'social_dominance' => (string) ($tm->social_dominance ?? ''),
            'skill_dominance' => (string) ($tm->skill_dominance ?? ''),
            'strengths' => (string) ($tm->strengths ?? ''),
            'deficits' => (string) ($tm->deficits ?? ''),
            'cluster_strength' => (string) ($tm->cluster_strength ?? ''),
            'personal_branding' => (string) ($tm->personal_branding ?? ''),
        ];
    }

    private function talentsIntersection(array $a, array $b): array
    {
        $out = [];

        foreach (['brain_dominance', 'social_dominance', 'skill_dominance', 'cluster_strength', 'personal_branding'] as $field) {
            $va = $this->normalizeText($a[$field] ?? '');
            $vb = $this->normalizeText($b[$field] ?? '');
            $out[$field] = ($va !== '' && $va === $vb) ? ($a[$field] ?: $b[$field]) : '';
        }

        foreach (['strengths', 'deficits'] as $field) {
            $tokensA = $this->tokenize($a[$field] ?? '');
            $tokensB = $this->tokenize($b[$field] ?? '');
            $lowerB = array_flip(array_map('mb_strtolower', $tokensB));

            $shared = [];
            foreach ($tokensA as $t) {
                if (isset($lowerB[mb_strtolower($t)])) {
                    $shared[] = $t;
                }
            }

            $out[$field] = implode(', ', array_values(array_unique($shared)));
        }

        return $out;
    }

    private function tokenize(string $text): array
    {
        $text = trim($text);
        if ($text === '') {
            return [];
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $parts = preg_split('/[,\n;\/]+/u', $text) ?: [];

        return array_values(array_filter(array_map(fn ($p) => trim((string) preg_replace('/\s+/u', ' ', (string) $p)), $parts)));
    }

    private function normalizeText(string $text): string
    {
        $text = trim((string) preg_replace('/\s+/u', ' ', $text));

        return mb_strtolower($text);
    }

    private function safeFilename(string $value): string
    {
        $value = preg_replace('/[^A-Za-z0-9]+/', '_', $value);
        $value = trim((string) $value, '_');

        return $value !== '' ? $value : 'TanpaNama';
    }
}
