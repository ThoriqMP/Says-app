@php
    $labels = ['TD', 'KD', 'AD', 'D', 'SD'];
    $psych = $assessment->psychologicalAssessment;
    $tm = $assessment->talentsMapping;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Asesmen Psikologis - {{ $assessment->subject->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        @page :first {
            margin: 0;
        }

        html, body {
            margin: 12mm;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937; /* Gray-800 */
            font-size: 9pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-white { color: white; }
        .text-purple { color: #5b21b6; } /* Purple-800 */
        .bg-purple { background-color: #5b21b6; }
        .bg-purple-light { background-color: #f3e8ff; } /* Purple-100 */
        .bg-white { background-color: white; }
        .w-full { width: 100%; }
        .table { display: table; width: 100%; border-collapse: collapse; table-layout: fixed; }
        .table-cell { display: table-cell; }
        .align-top { vertical-align: top; }
        .align-middle { vertical-align: middle; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .p-2 { padding: 8px; }
        .p-4 { padding: 16px; }
        
        /* Page Containers */
        .page {
            width: 100%;
            position: relative;
            clear: both;
        }

        .page-break {
            page-break-before: always;
        }

        .content-wrapper {
            padding-bottom: 20mm;
            position: relative;
            width: 100%;
        }

        /* Cover Page */
        .cover-page {
            padding: 0;
            height: 297mm;
            background-color: white;
            z-index: 50;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 450px;
            height: 450px;
            margin-left: -225px;
            margin-top: -225px;
            opacity: 0.1;
            z-index: -10;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .cover-header-table {
            width: 100%;
            margin-bottom: 2cm;
        }

        .cover-headline {
            font-size: 24pt;
            font-weight: bold;
            color: #111;
            line-height: 1.1;
        }

        .cover-subheadline {
            font-size: 12pt;
            margin-top: 10px;
            color: #555;
            letter-spacing: 1px;
        }

        .cover-content {
            position: relative;
            overflow: hidden;
            padding: 0;
            min-height: 273mm;
            height: 297mm;
        }

        .cover-safe-area {
            position: relative;
            padding: 10mm;
            z-index: 1;
        }

        .cover-decor {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 65mm;
            background-color: #4c1d95;
            z-index: 0;
        }

        .cover-decor-accent {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 40mm;
            height: 35mm;
            background-color: #7c3aed;
            opacity: 0.35;
            z-index: 0;
        }

        .cover-shape-left {
            position: absolute;
            left: -35mm;
            bottom: 20mm;
            width: 95mm;
            height: 55mm;
            background-color: #5b21b6;
            border-radius: 55mm 55mm 0 0;
            opacity: 0.22;
            z-index: 0;
        }

        .cover-shape-right {
            position: absolute;
            right: -40mm;
            bottom: 10mm;
            width: 105mm;
            height: 70mm;
            background-color: #a78bfa;
            border-radius: 70mm 70mm 0 0;
            opacity: 0.22;
            z-index: 0;
        }

        .subject-card {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 60%;
            margin: 4cm auto 0;
            text-align: center;
            border-left: 5px solid #5b21b6;
            position: relative;
            z-index: 1;
        }

        .subject-label {
            font-size: 9pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .subject-value {
            font-size: 16pt;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }

        /* Dashboard Styles */
        .card {
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            overflow: hidden;
            page-break-inside: avoid; /* Prevent card from splitting */
        }

        .card-header {
            background-color: #5b21b6;
            color: white;
            padding: 5px 10px;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 8px;
        }

        .score-table {
            width: 100%;
            font-size: 7.5pt;
        }

        .score-table th {
            text-align: center;
            padding: 3px;
            color: #5b21b6;
            border-bottom: 1px solid #e5e7eb;
        }

        .score-table td {
            padding: 3px;
            border-bottom: 1px solid #f3f4f6;
        }

        .score-box {
            display: inline-block;
            width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            border-radius: 3px;
            border: 1px solid #d1d5db;
            color: #d1d5db;
            font-size: 6.5pt;
        }

        .score-box.active {
            background-color: #5b21b6;
            border-color: #5b21b6;
            color: white;
            font-weight: bold;
        }

        .progress-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 2px;
        }

        .progress-table td {
            padding: 0;
            height: 6px;
        }

        .progress-fill {
            background-color: #7c3aed;
        }

        .progress-empty {
            background-color: #f3e8ff;
        }

        .legend-grid {
            width: 100%;
            font-size: 7pt;
        }
        .legend-item {
            display: inline-block;
            margin-right: 8px;
            padding: 2px 5px;
            background: #f9fafb;
            border-radius: 3px;
            border: 1px solid #e5e7eb;
        }

        .tm-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .signature-box {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 5px;
            page-break-inside: avoid;
        }

        /* Kamus */
      
        .kamus-section-title {
            margin-top: 18px;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 10pt;
            color: #4c1d95;
            border-bottom: 1px solid #c4b5fd;
            padding-bottom: 2px;
        }

        .kamus-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .kamus-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
            font-size: 8.5pt;
            line-height: 1.4;
            text-align: left;
        }

        .kamus-table tr {
            page-break-inside: avoid;
        }

        .kamus-table p {
            margin: 0 0 7px 0;
        }

        .kamus-table strong {
            font-size: 8.8pt;
            color: #111827;
        }

        /* Untuk section non-table */
        .kamus-paragraph p {
            font-size: 8.5pt;
            line-height: 1.45;
            margin: 0 0 6px 0;
            text-align: left;
        }

    </style>
</head>
<body>
    @if(!empty($logoProsekar))
    <div class="watermark">
        <img src="{{ $logoProsekar }}" alt="Watermark">
    </div>
    @endif

    <!-- COVER PAGE -->
    <div class="page cover-page">
        <div class="cover-content">
            <div class="cover-decor"></div>
            <div class="cover-decor-accent"></div>
            <div class="cover-shape-left"></div>
            <div class="cover-shape-right"></div>
            <div class="cover-safe-area">
                <table class="cover-header-table">
                    <tr>
                        <td class="align-top" style="width: 60%">
                            <div class="cover-headline">LAPORAN<br>ASESMEN PSIKOLOGIS</div>
                            <div class="cover-subheadline">DOKUMEN RAHASIA</div>
                        </td>
                        <td class="align-top text-right" style="width: 40%">
                            @if(!empty($logoProsekar))
                                <img src="{{ $logoProsekar }}" style="height: 60px; margin-bottom: 5px;">
                            @endif
                            <div style="font-weight: bold; font-size: 10pt; color: #333;">PRO SEKAR PSYCHOLOGICAL SERVICES</div>
                            <div style="font-size: 8pt; color: #666;">
                                Windelrio Townhouse Blok A No 9 <br>
                                Jl. Brigjen Saptadji Hadiprawira<br>
                                Semplak – Bogor Barat 16112 <br>
                                Phone : 0851-1765-8225<br>
                                Instagram: @prosekar_psikologibogor
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="subject-card">
                    <div class="subject-label">Nama Lengkap</div>
                    <div class="subject-value">{{ $assessment->subject->name }}</div>
                    
                    <div style="display: table; width: 100%;">
                        <div style="display: table-cell; width: 50%;">
                            <div class="subject-label">Usia</div>
                            <div class="subject-value" style="font-size: 12pt;">{{ $assessment->subject->precise_age }}</div>
                        </div>
                        <div style="display: table-cell; width: 50%;">
                            <div class="subject-label">Jenis Kelamin</div>
                            <div class="subject-value" style="font-size: 12pt;">{{ $assessment->subject->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                        <div class="subject-label">Tanggal Asesmen</div>
                        <div style="font-size: 11pt; font-weight: bold; color: #4b5563;">{{ $assessment->test_date->format('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD PAGE -->
    <div class="page page-break">
        <div class="content-wrapper">
            <h2 style="color: #4c1d95; margin-bottom: 12px; font-size: 14pt; border-bottom: 2px solid #4c1d95; padding-bottom: 4px; display: inline-block;">HASIL PEMERIKSAAN</h2>

            @if($psych)
            <!-- Aspek Kognitif Card -->
            <div class="card">
                <div class="card-header">ASPEK KOGNITIF</div>
                <div class="card-body">
                    <table class="score-table">
                        <tr>
                            <th align="left">ASPEK KOGNITIF</th>
                            <th align="left">KETERANGAN ASPEK</th>
                            <th width="50">SKALA</th>
                        </tr>
                        @php
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
                        @endphp
                        @foreach(['Verbal', 'Numerical', 'Logical', 'Spatial'] as $aspect)
                        <tr>
                            <td style="font-weight: bold;">{{ $cogMap[$aspect] }}</td>
                            <td>{{ $cogDescriptions[$aspect] }}</td>
                            <td align="center" style="font-weight: bold;">
                                {{ $psych->{'cognitive_'.strtolower($aspect).'_score'} ?? '-' }}
                                <span style="margin-left: 5px; color: #6b7280; font-size: 7pt;">{{ $psych->{'cognitive_'.strtolower($aspect).'_scale'} ?? '' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Aspek Potensi & Kecerdasan Row -->
            <div style="width: 100%; margin-bottom: 5px;">
                <div style="float: left; width: 49%; margin-right: 1%;">
                    <div class="card" style="height: 100%;">
                        <div class="card-header">ASPEK POTENSI</div>
                        <div class="card-body">
                            <div style="font-size: 7.5pt;">
                                <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 4px; font-weight: bold; color: #5b21b6;">
                                    <div style="float: left; width: 70%;">ASPEK</div>
                                    <div style="float: right; width: 30%; text-align: center;">SKOR</div>
                                    <div style="clear: both;"></div>
                                </div>
                                
                                <div style="border-bottom: 1px solid #f3f4f6; padding: 4px 0;">
                                    <div style="float: left; width: 70%;">Intelektual (Original Scale)</div>
                                    <div style="float: right; width: 30%; text-align: center; font-weight: bold;">{{ $psych->potential_intellectual_score ?? '-' }}</div>
                                    <div style="clear: both;"></div>
                                </div>
                                <div style="border-bottom: 1px solid #f3f4f6; padding: 4px 0;">
                                    <div style="float: left; width: 70%;">Sosial</div>
                                    <div style="float: right; width: 30%; text-align: center; font-weight: bold;">
                                        {{ $psych->potential_social_score ? '(-) ' . $psych->potential_social_score : '-' }}
                                    </div>
                                    <div style="clear: both;"></div>
                                </div>
                                <div style="border-bottom: 1px solid #f3f4f6; padding: 4px 0; margin-bottom: 10px;">
                                    <div style="float: left; width: 70%;">Emosional</div>
                                    <div style="float: right; width: 30%; text-align: center; font-weight: bold;">
                                        {{ $psych->potential_emotional_score ? '(-) ' . $psych->potential_emotional_score : '-' }}
                                    </div>
                                    <div style="clear: both;"></div>
                                </div>
                            </div>

                            <div style="font-size: 7pt; background: #f9fafb; padding: 5px; border-radius: 4px; border: 1px solid #e5e7eb;">
                                <div style="font-weight: bold; margin-bottom: 2px; color: #4c1d95;">SKALA ASSESSMENT GRAFIS:</div>
                                <div><strong>3 =</strong> Berkembang Baik / Optimal</div>
                                <div><strong>2 =</strong> Cukup Berkembang</div>
                                <div><strong>1 =</strong> Kurang Berkembang</div>
                                <div><strong>(-) =</strong> Berkembang namun Ada Hambatan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="float: left; width: 49%;">
                    <div class="card" style="height: 100%;">
                        <div class="card-header">SKALA ASSESSMENT GRAFIS</div>
                         <div class="card-body">
                            <div style="font-size: 7.5pt;">
                                <div style="margin-bottom: 4px; padding-bottom: 2px; border-bottom: 1px solid #f3f4f6;">
                                    <strong>3</strong> = Berkembang Baik / Optimal
                                </div>
                                <div style="margin-bottom: 4px; padding-bottom: 2px; border-bottom: 1px solid #f3f4f6;">
                                    <strong>2</strong> = Cukup Berkembang
                                </div>
                                <div style="margin-bottom: 4px; padding-bottom: 2px; border-bottom: 1px solid #f3f4f6;">
                                    <strong>1</strong> = Kurang Berkembang
                                </div>
                                <div>
                                    <strong>(-)</strong> = Berkembang namun Ada Hambatan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <!-- Taraf Kecerdasan & Kematangan Row -->
            <div style="width: 100%; margin-bottom: 5px;">
                <div style="float: left; width: 49%; margin-right: 1%;">
                    <div class="card" style="height: 100%;">
                        <div class="card-header">TARAF KECERDASAN (FULL SCALE)</div>
                            <div class="card-body">
                                <div style="font-size: 7.5pt;">
                                    @php
                                        $iqRanges = [
                                            'Very Superior' => '119 - Ke atas',
                                            'Tinggi' => '105 - 118',
                                            'Cukup' => '100 - 104',
                                            'Sedang' => '95 - 99',
                                            'Rendah' => '81 - 94'
                                        ];
                                        $currentIqCat = trim($psych->iq_category ?? '');
                                    @endphp
                                    @foreach($iqRanges as $cat => $range)
                                    <div style="border-bottom: 1px solid #f3f4f6; padding: 4px 0;">
                                        <div style="float: left; width: 40%;">{{ $cat }}</div>
                                        <div style="float: left; width: 40%; text-align: center;">{{ $range }}</div>
                                        <div style="float: right; width: 10%; text-align: center;">
                                            @if(strcasecmp($currentIqCat, $cat) === 0)
                                            <span style="font-weight: bold; color: #5b21b6;">V</span>
                                            @endif
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                <div style="float: left; width: 49%;">
                    <div class="card" style="height: 100%;">
                        <div class="card-header">TARAF KEMATANGAN PERKEMBANGAN</div>
                            <div class="card-body">
                                <div style="font-size: 7.5pt;">
                                    @php
                                        $maturityLevels = ['Disarankan', 'Dipertimbangkan', 'Tidak Disarankan'];
                                        $currentMaturity = trim($psych->maturity_recommendation ?? '');
                                    @endphp
                                    @foreach($maturityLevels as $level)
                                    <div style="border-bottom: 1px solid #f3f4f6; padding: 4px 0;">
                                        <div style="float: left; width: 80%;">{{ $level }}</div>
                                        <div style="float: right; width: 10%; text-align: center;">
                                            @if(strcasecmp($currentMaturity, $level) === 0)
                                            <span style="font-weight: bold; color: #5b21b6;">V</span>
                                            @endif
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                <div style="clear: both;"></div>
            </div>
            @endif

            <h2 style="color: #4c1d95; margin-top: 10px; margin-bottom: 8px; font-size: 14pt; border-bottom: 2px solid #4c1d95; padding-bottom: 4px; display: inline-block;">PERSONAL MAPPING</h2>

            <table class="table" style="margin-bottom: 8px;">
                <tr>
                    <!-- LEFT COLUMN: Personality & Love Language & Learning Style -->
                    <td class="align-top" style="width: 55%; padding-right: 10px;">
                        
                        <!-- Personality Card -->
                        <div class="card">
                            <div class="card-header">PERSONALITY</div>
                            <div class="card-body">
                                @php
                                    $personalityScores = $assessment->scores->where('category', 'personality')->values();
                                    $persRows = ['Sanguinis', 'Koleris', 'Melankolis', 'Phlegmatis'];
                                @endphp
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach($persRows as $rowName)
                                    @php
                                        $score = $personalityScores->filter(function($s) use ($rowName) {
                                            return stripos($s->aspect_name, $rowName) !== false;
                                        })->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $rowName }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box {{ $score && $score->label === $l ? 'active' : '' }}">
                                                {{ $score && $score->label === $l ? 'X' : '' }}
                                            </div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <!-- Learning Style Card -->
                        <div class="card">
                            <div class="card-header">LEARNING STYLE</div>
                            <div class="card-body">
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach(['Visual', 'Auditory', 'Kinestetik'] as $ls)
                                    <tr>
                                        <td>{{ $ls }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box"></div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <!-- Love Language Card -->
                        <div class="card">
                            <div class="card-header">BAHASA CINTA</div>
                            <div class="card-body">
                                @php
                                    $loveLanguageScores = $assessment->scores->where('category', 'love_language')->values();
                                    $llRows = [
                                        'Kata Pendukung' => ['Words of Affirmation', 'Kata Pendukung'],
                                        'Waktu Berkualitas' => ['Quality Time', 'Waktu Berkualitas'],
                                        'Hadiah' => ['Receiving Gifts', 'Hadiah'],
                                        'Service' => ['Acts of Service', 'Pelayanan', 'Service'],
                                        'Sentuhan' => ['Physical Touch', 'Sentuhan Fisik', 'Sentuhan']
                                    ];
                                @endphp
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach($llRows as $displayName => $aliases)
                                    @php
                                        $score = $loveLanguageScores->filter(function($s) use ($aliases) {
                                            foreach($aliases as $alias) {
                                                if (stripos($s->aspect_name, $alias) !== false) return true;
                                            }
                                            return false;
                                        })->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $displayName }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box {{ $score && $score->label === $l ? 'active' : '' }}">
                                                {{ $score && $score->label === $l ? 'X' : '' }}
                                            </div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                    </td>

                    <!-- RIGHT COLUMN: Multiple Intelligence & Legend -->
                    <td class="align-top" style="width: 45%;">
                        
                        <!-- MI Card -->
                        <div class="card">
                            <div class="card-header">MULTIPLE INTELLIGENCE</div>
                            <div class="card-body">
                                @php
                                    $miScores = $assessment->scores->where('category', 'multiple_intelligence')->values();
                                    $miRows = [
                                        'Linguistic' => ['Linguistik', 'Linguistic'],
                                        'Numeric' => ['Logika Matematika', 'Numeric', 'Logical'],
                                        'Visual – Spatial' => ['Visual Spasial', 'Visual'],
                                        'Bodily Kinesthetic' => ['Kinestetik', 'Bodily'],
                                        'Musical' => ['Musikal', 'Musical'],
                                        'Inter-personal' => ['Interpersonal'],
                                        'Intra-personal' => ['Intrapersonal'],
                                        'Naturalist' => ['Naturalis', 'Naturalist']
                                    ];
                                @endphp
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach($miRows as $displayName => $aliases)
                                    @php
                                        $score = $miScores->filter(function($s) use ($aliases) {
                                            foreach($aliases as $alias) {
                                                if (stripos($s->aspect_name, $alias) !== false) return true;
                                            }
                                            return false;
                                        })->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $displayName }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box {{ $score && $score->label === $l ? 'active' : '' }}">
                                                {{ $score && $score->label === $l ? 'X' : '' }}
                                            </div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <!-- Legend Card -->
                        <div class="card">
                            <div class="card-body">
                                <div style="font-weight: bold; font-size: 7.5pt; margin-bottom: 4px; color: #4c1d95;">KETERANGAN SKOR:</div>
                                <div class="legend-grid">
                                    <div class="legend-item">TD = Tidak Dominan</div>
                                    <div class="legend-item">KD = Kurang Dominan</div>
                                    <div class="legend-item">AD = Agak Dominan</div>
                                    <div class="legend-item">D = Dominan</div>
                                    <div class="legend-item">SD = Sangat Dominan</div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
            </table>

            <!-- Talents Mapping Card -->
            <div class="page-break"></div>
            
            <h2 style="color: #4c1d95; margin-bottom: 12px; font-size: 14pt; border-bottom: 2px solid #4c1d95; padding-bottom: 4px; display: inline-block;">TALENTS MAPPING</h2>

            <div class="card">
                <div class="card-header">TALENTS MAPPING ANALYSIS</div>
                <div class="card-body">
                    <table class="table tm-table" style="font-size: 8.5pt;">
                        <tr>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Brain Dominance</div>
                                <div style="font-weight: bold;">{{ $tm->brain_dominance ?? '-' }}</div>
                            </td>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Social Dominance</div>
                                <div style="font-weight: bold;">{{ $tm->social_dominance ?? '-' }}</div>
                            </td>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Skill Dominance</div>
                                <div style="font-weight: bold;">{{ $tm->skill_dominance ?? '-' }}</div>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="table tm-table" style="font-size: 8.5pt; margin-top: 10px;">
                        <tr>
                            <td style="width: 25%; vertical-align: top;">
                                <div style="color: #4c1d95; font-weight: bold; margin-bottom: 5px;">Strength Approach</div>
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($tm->strengths ? array_map('trim', explode(',', $tm->strengths)) : [] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <div style="color: #4c1d95; font-weight: bold; margin-bottom: 5px;">Deficit Approach</div>
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($tm->deficits ? array_map('trim', explode(',', $tm->deficits)) : [] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <div style="color: #4c1d95; font-weight: bold; margin-bottom: 5px;">Cluster Strength</div>
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($tm->cluster_strength ? array_map('trim', explode(',', $tm->cluster_strength)) : [] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <div style="color: #4c1d95; font-weight: bold; margin-bottom: 5px;">Personal Branding</div>
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($tm->personal_branding ? array_map('trim', explode(',', $tm->personal_branding)) : [] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Conclusion Card -->
            <div class="card">
                <div class="card-header">KESIMPULAN</div>
                <div class="card-body">
                    <table style="border: none; width: 100%; font-size: 9pt;">
                        <tr>
                            <td style="border: none; width: 20px; vertical-align: top;">1.</td>
                            <td style="border: none; vertical-align: top;">
                                Taraf Kemampuan Intelektual Umum <strong>{{ strtoupper($assessment->subject->name) }}</strong> : <strong>{{ $psych->iq_category ?? '...' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 20px; vertical-align: top;">2.</td>
                            <td style="border: none; vertical-align: top;">
                                Rekomendasi atas penelusuran potensi bakat dan minat :
                                <div style="font-weight: bold; margin-top: 5px;">
                                    {{ $psych->recommendations ?? '[Belum ada data rekomendasi]' }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 20px; vertical-align: top;">3.</td>
                            <td style="border: none; vertical-align: top;">
                                Saran yang membutuhkan penanganan terapeutik (asesemen kasuistik, konseling & terapi) :
                                <div style="margin-top: 5px;">
                                     {{ $psych->suggestions ?? '[Belum ada data saran]' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Signature -->
            <div class="signature-box">
                <div style="font-size: 8.5pt; margin-bottom: 8px;">Bogor, {{ $assessment->test_date->format('d F Y') }}</div>
                @if(!empty($signature))
                    <img src="{{ $signature }}" style="height: 60px; margin-bottom: 5px;">
                @elseif(!empty($signatureAmbu))
                    <img src="{{ $signatureAmbu }}" style="height: 55px; margin-bottom: 4px;">
                @else
                    <div style="height: 55px;"></div>
                @endif
                <div style="font-weight: bold; font-size: 9.5pt; text-decoration: underline;">{{ $assessment->psychologist_name ?? 'Anggia Chrisanti, S.Psi, M.Psi, Psikolog' }}</div>
                <div style="font-size: 7.5pt;">SIPP: {{ $assessment->psychologist_sipp ?? '0514-22-2-1' }}</div>
            </div>
        </div>
    </div>

   
    <div class="page page-break">
    <div class="content-wrapper">

        <h2 style="color:#4c1d95; margin-bottom:12px; font-size:14pt;
            border-bottom:2px solid #4c1d95; padding-bottom:4px; display:inline-block;">
            KAMUS LAPORAN
        </h2>

        {{-- ======================================================
            34 TALENT THEMES
        ====================================================== --}}
        <div class="kamus-section-title">
            34 Tema Bakat (Clifton’s Talents Theme)
        </div>

        <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>ACHIEVER</strong><br>
                        memiliki stamina yang tinggi dan selalu bekerja keras, kepuasan hidupnya berasal dari kesibukan dan keberhasilan yang diperoleh. Tema bakat ini banyak terdapat pada peran: Tenaga Penjual/Sales, Teknisi Proyek, Teknisi Lapangan, Pekerja Lapangan, Relawan, Petugas SAR.
                    </p>
                </td>
                <td>
                    <p><strong>FUTURISTIC</strong><br>
                        senang membayangkan masa depan dan memberikan inspirasi visi. Tema bakat ini banyak terdapat pada peran: Entrepreneur, Perencana jangka panjang, Visioner, Pengembang produk baru.
                    </p>
                </td>
            </tr>
            <!-- ... (Keeping the rest of Kamus Laporan as is) ... -->
            <tr>
                <td>
                    <p><strong>ACTIVATOR</strong><br>
                        dapat membuat sesuatunya terjadi dengan mengubah pikiran menjadi tindakan. Tema bakat ini sering terdapat pada peran: usaha-usaha baru atau yang memerlukan perubahan besar, Entrepreneur, Sales.
                    </p>
                </td>
                <td>
                    <p><strong>HARMONY</strong><br>
                        dapat bekerja sama secara baik dengan orang lain dan mencari titik temu. Tema bakat ini banyak terdapat pada peran: Juru Damai, Penasehat, Pembangun jaringan.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ADAPTABILITY</strong><br>
                        melakukan tugas sesuai dengan apa yang diterimnya saat itu. Peran yang mungkin sesuai: Wartawan, Produksi live TV, Perawat Gawat Darurat, Pelayanan Pelanggan (Customer Service), Pemadam Kebakaran, Dispatcher.
                    </p>
                </td>
                <td>
                    <p><strong>IDEATION</strong><br>
                        menyukai diskusi bebas, brainstorming, dan menemukan benang merah antar fenomena. Tema bakat ini banyak terdapat pada peran: Marketing, Advertising, Wartawan, Pengembang produk.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ANALYTICAL</strong><br>
                        mencari alasan dan sebab-musabab. Memiliki kemampuan untuk memikirkan semua faktor yang dapat mempengaruhi situasi atau kondisi. Tema bakat ini banyak terdapat pada peran: Analis, Periset, Manajemen Database, Editor, Manajemen Risiko, Accounting, Programmer.
                    </p>
                </td>
                <td>
                    <p><strong>INCLUDER</strong><br>
                        kecenderungan untuk menerima semua orang agar merasa memiliki dalam kelompok. Tema bakat ini banyak terdapat pada peran: Motivator kelompok, Mentor, Pemimpin beragam budaya.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ARRANGER</strong><br>
                        dapat mengorganisir dan memiliki fleksibilitas yang membantunya untuk mengatur sesuatu. Tema bakat ini banyak terdapat pada peran: Supervisor, Manajer, Event Organizer, Programmer.
                    </p>
                </td>
                <td>
                    <p><strong>INDIVIDUALIZATION</strong><br>
                        mampu melihat keunikan masing-masing orang secara individual. Tema bakat ini banyak terdapat pada peran: Manajer, Penasihat, Rekrutmen, Pengajar, Penulis, HRD.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>BELIEF</strong><br>
                        senang melayani orang lain dengan tulus karena menganggapnya sebagai perbuatan mulia. Memiliki nilai-nilai luhur yang tidak pernah berubah. Tema bakat ini banyak terdapat pada peran: Pelayanan Pelanggan, CRM, Maintenance, Perawat, Pekerja Sosial Relawan.
                    </p>
                </td>
                <td>
                    <p><strong>INPUT</strong><br>
                        memiliki hasrat untuk mengetahui lebih jauh dan senang mengumpulkan informasi. Tema bakat ini banyak terdapat pada peran: Pengajar, Periset, Wartawan, Petugas Arsip.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMMAND</strong><br>
                        senang menjadi penanggung jawab dan berani menghadapi masalah secara langsung. Tema bakat ini banyak terdapat pada peran: Sales, Negosiator, Wartawan, Pengacara, Komandan, HRD, Pembelian.
                    </p>
                </td>
                <td>
                    <p><strong>INTELLECTION</strong><br>
                        senang berpikir, mawas diri dan menyukai diskusi intelektual. Tema bakat ini banyak terdapat pada peran: Filusuf, Peneliti, Psikolog.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMMUNICATION</strong><br>
                        mudah mengungkapkan apa yang dipikirkannya melalui kata-kata atau tulisan yang mudah dimengerti. Tema bakat ini banyak terdapat pada peran: Pengajar, Marketing, Humas, Juru Bicara, Presenter, MC, Penulis.
                    </p>
                </td>
                <td>
                    <p><strong>LEARNER</strong><br>
                        senang mempelajari sesuatu dan tertarik pada proses pembelajaran. Tema bakat ini sering terdapat pada peran: Konsultan, Teknisi TI, Programmer, Guru.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMPETITION</strong><br>
                        senang membandingkan kemajuannya dengan orang lain dan selalu berusaha menjadi nomor satu. Tema bakat ini banyak terdapat pada peran: Sales, Pelatih Olahraga.
                    </p>
                </td>
                <td>
                    <p><strong>MAXIMIZER</strong><br>
                        fokus pada kekuatan untuk merangsang keunggulan pribadi dan kelompok. Tema bakat ini banyak terdapat pada peran: Pelatih, Manajer, Mentor, Transformational leader.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONNECTEDNESS</strong><br>
                        senang mengaitkan peristiwa dan percaya setiap kejadian memiliki alasan/sebab. Tema bakat ini banyak terdapat pada peran: Counselor, Leader dalam membangun team.
                    </p>
                </td>
                <td>
                    <p><strong>POSITIVITY</strong><br>
                        memiliki antusiasme tinggi dan optimisme yang menular. Tema bakat ini banyak terdapat pada peran: Pengajar, Entertainer, Motivator, Sales, Entrepreneur.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONSISTENCY/FAIRNESS</strong><br>
                        memiliki bakat untuk melihat “kesamaan” orang dan memperlakukan semua orang secara sama. Tema bakat ini banyak terdapat pada peran: Hakim, Quantity Surveyor, Petugas Kontrol standar.
                    </p>
                </td>
                <td>
                    <p><strong>RELATOR</strong><br>
                        menikmati hubungan dekat dan bekerja keras dengan teman untuk mencapai tujuan. Tema bakat ini banyak terdapat pada peran: Account Sales, Katalisator hubungan kepercayaan.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONTEXT</strong><br>
                        menikmati mempelajari sesuatu melalui riset dan studi tentang masa lalu. Tema bakat ini banyak terdapat pada peran: Guru Sejarah, Arkeolog, Penyusun budaya perusahaan, Hakim.
                    </p>
                </td>
                <td>
                    <p><strong>RESPONSIBILITY</strong><br>
                        memiliki rasa tanggung jawab tinggi atas komitmen yang telah dibuat. Tema bakat ini banyak terdapat pada peran: HSE, Manajer, Keuangan, Quality Control, Keamanan.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DELIBERATIVE</strong><br>
                        berhati-hati dan memiliki karakter “melihat sebelum melompat”. Tema bakat ini sering terdapat pada peran: Pilot, Advisor, Urusan Legal, Membuat Kontrak Bisnis.
                    </p>
                </td>
                <td>
                    <p><strong>RESTORATIVE</strong><br>
                        senang memecahkan masalah dan mengembalikan fungsi segala sesuatu. Tema bakat ini banyak terdapat pada peran: Pengobatan, Konsultan, Teknisi Perbaikan, Terapist.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DEVELOPER</strong><br>
                        senang mengenali dan menggali potensi orang lain. Tema bakat ini banyak terdapat pada peran: Manajer, Guru, Pelatih, Pembimbing, Petugas Sosial.
                    </p>
                </td>
                <td>
                    <p><strong>SELF-ASSURANCE</strong><br>
                        memiliki kepercayaan diri tinggi dan keyakinan pada keputusan sendiri. Tema bakat ini banyak terdapat pada peran: Leader, Legal, Entrepreneur.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DISCIPLINE</strong><br>
                        senang berada dalam kondisi yang teratur, terstruktur, dan terencana. Tema bakat ini banyak terdapat pada peran: Keuangan, Sekretaris, Administrasi, Petugas ISO, Accounting, Programmer.
                    </p>
                </td>
                <td>
                    <p><strong>SIGNIFICANCE</strong><br>
                        senang menjadi pusat perhatian, dikenal, dan dihargai atas keunikannya. Tema bakat ini banyak terdapat pada peran: Marketing, Presenter, MC, Sales.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>EMPATHY</strong><br>
                        mampu merasakan perasaan orang lain seakan-akan mengalaminya sendiri. Tema bakat ini banyak terdapat pada peran: Sales, HRD, Perawat, Psikiater, Layanan Pelanggan.
                    </p>
                </td>
                <td>
                    <p><strong>STRATEGIC</strong><br>
                        mampu memilah masalah dan menemukan jalan terbaik untuk solusinya. Tema bakat ini banyak terdapat pada peran: Perencana Strategi, Manajer, Leader.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>FOKUS</strong><br>
                        membutuhkan tujuan yang jelas sebagai kompas prioritas. Tema bakat ini banyak terdapat pada peran: Project Officer, Team Leader.
                    </p>
                </td>
                <td>
                    <p><strong>WOO (Winning Others Over)</strong><br>
                        senang tantangan untuk bertemu orang baru dan menjadi akrab. Tema bakat ini banyak terdapat pada peran: Duta Organisasi, Sales, Entertainer, Resepsionis.
                    </p>
                </td>
            </tr>
        </table>

        <div class="page-break"></div>

        {{-- ======================================================
            STRENGTH TYPOLOGY (ST-30)
        ====================================================== --}}
        <div class="kamus-section-title">
            Strength Typology (ST-30)
        </div>

        <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>ADMINISTRATOR</strong>: Anda suka dengan keteraturan, terencana dan rapih dalam pengelolaan administrasi.</p>
                    <p><strong>AMBASSADOR</strong>: Anda senang membina hubungan persahabatan, berkomunikasi, dan menjadi perwakilan organisasi.</p>
                    <p><strong>ANALYST</strong>: Anda berpikiran analisis, senang data, dan suka menguraikan sesuatu ke bagian kecil.</p>
                    <p><strong>ARRANGER</strong>: Anda senang mengatur sumber daya manusia untuk hasil optimum.</p>
                    <p><strong>CARETAKER</strong>: Anda bisa merasakan perasaan orang lain sehingga senang merawat atau membantu orang lain.</p>
                    <p><strong>COMMANDER</strong>: Anda keras, berani menghadapi konfrontasi dan mengambil alih tanggung jawab.</p>
                    <p><strong>COMMUNICATOR</strong>: Anda senang menjelaskan sesuatu baik lisan maupun tertulis, dan suka tampil di depan.</p>
                    <p><strong>CREATOR</strong>: Anda punya banyak ide, berpikiran jauh kedepan dan strategis.</p>
                    <p><strong>DESIGNER</strong>: Anda punya banyak ide, kemampuan analisis, dan menyatakannya ke dalam gambar.</p>
                    <p><strong>DISTRIBUTOR</strong>: Anda senang mengatur sumber daya, bertanggung jawab, dan pekerja keras.</p>
                    <p><strong>EDUCATOR</strong>: Anda suka memajukan orang lain dengan mengajar, melatih, atau memberi nasehat.</p>
                    <p><strong>EVALUATOR</strong>: Anda teliti sesuai aturan dan suka tugas analisis untuk membuktikan sesuatu.</p>
                    <p><strong>EXPLORER</strong>: Anda senang mengumpulkan informasi dan mempelajari sesuatu melalui penelitian.</p>
                    <p><strong>INTERPRETER</strong>: Anda senang menjelaskan sesuatu dan memiliki daya analisis untuk mengartikan sesuatu.</p>
                    <p><strong>JOURNALIST</strong>: Anda mudah menyesuaikan diri, senang menulis, dan menjelaskan sesuatu secara strategis.</p>
                </td>
                <td>
                    <p><strong>MARKETER</strong>: Anda senang menonjolkan kelebihan, mengkomunikasikannya, dan menggali peluang pasar.</p>
                    <p><strong>MEDIATOR</strong>: Anda berani menghadapi konfrontasi untuk mengatasi dan menyelesaikan konflik.</p>
                    <p><strong>MOTIVATOR</strong>: Anda suka memajukan orang lain dengan memberi panduan, semangat, atau inspirasi.</p>
                    <p><strong>OPERATOR</strong>: Pekerja keras yang senang keteraturan dan melayani melalui perangkat kerja.</p>
                    <p><strong>PRODUCER</strong>: Pekerja keras yang tidak sabar bertindak dan senang membuat ide menjadi produk nyata.</p>
                    <p><strong>QUALITY CONTROLLER</strong>: Memegang teguh aturan, teliti, dan senang dengan tugas pengontrolan mutu.</p>
                    <p><strong>RESTORER</strong>: Berpikiran analitis, senang mendiagnosa, dan mengembalikan sesuatu ke fungsi semula.</p>
                    <p><strong>SAFEKEEPER</strong>: Teliti, waspada, bertanggung jawab terkait keselamatan dan keamanan.</p>
                    <p><strong>SELECTOR</strong>: Mengerti keunikan orang dan berani menentukan pilihan orang tepat untuk tugas tertentu.</p>
                    <p><strong>SELLER</strong>: Senang meyakinkan orang lain dengan memelihara hubungan atau menonjolkan kehebatan produk.</p>
                    <p><strong>SERVER</strong>: Anda orang yang senang melayani dan mendahulukan orang lain.</p>
                    <p><strong>STRATEGIST</strong>: Memilih jalan terbaik mencapai tujuan melalui kemampuan analisis atau intuisi.</p>
                    <p><strong>SYTHESIZER</strong>: Senang mengatur sumber daya dan mampu merangkum berbagai hal menjadi sesuatu yang baru.</p>
                    <p><strong>TREASURY</strong>: Berpikiran analitis, teliti, teratur, dan senang dengan tugas pengelolaan keuangan.</p>
                    <p><strong>VISIONARY</strong>: Dapat melihat jauh kedepan melampaui cakrawala secara intuisi atau perasaan.</p>
                </td>
            </tr>
        </table>

        <div class="card" style="margin-top: 10px;">
            <div class="card-header">Cluster Strength Typology</div>
            <div class="card-body">
                <table style="width: 100%; font-size: 8pt;">
                    <tr>
                        <td style="width: 50%; vertical-align: top;">
                            <div style="margin-bottom: 4px;"><strong>H</strong> : Headman (Mempengaruhi orang lain)</div>
                            <div style="margin-bottom: 4px;"><strong>S</strong> : Servicing (Melayani orang lain)</div>
                            <div style="margin-bottom: 4px;"><strong>Gi</strong> : Generating Ideal (Individual otak kanan)</div>
                            <div style="margin-bottom: 4px;"><strong>Te</strong> : Technical (Individual Teknik)</div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            <div style="margin-bottom: 4px;"><strong>E</strong> : Elementary (Admin Bahasa)</div>
                            <div style="margin-bottom: 4px;"><strong>R</strong> : Reasoning (Otak kiri bawah)</div>
                            <div style="margin-bottom: 4px;"><strong>T</strong> : Thinking (Otak kiri atas)</div>
                            <div style="margin-bottom: 4px;"><strong>N</strong> : Networking (Bekerjasama dengan orang lain)</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="page-break"></div>

        {{-- ======================================================
            PERSONALITY
        ====================================================== --}}
        <div class="kamus-section-title">
            Personality (Tipologi Kepribadian)
        </div>

        <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>1. SANGUINIS</strong><br>
                        Individu sanguinis cenderung responsif terhadap rangsangan baru, menyukai variasi, dan memiliki toleransi risiko yang tinggi. Mereka kurang tahan terhadap rutinitas dan kebosanan, sehingga terdorong mencari pengalaman yang bersifat menyenangkan. Pola ini dapat memengaruhi stabilitas relasi dan kontrol impuls. Sanguinis umumnya memiliki kapasitas kreativitas tinggi dan sesuai pada bidang kerja dinamis seperti marketing, pariwisata, hiburan, kuliner, fashion, dan olahraga.
                    </p>
                    <p><strong>2. PLEGMATIS</strong><br>
                        Individu plegmatis berorientasi pada kestabilan hubungan dan keharmonisan sosial. Mereka cenderung menghindari konflik, berperan sebagai penengah, serta menunjukkan empati dan kepedulian terhadap orang lain. Plegmatis konsisten dalam relasi jangka panjang dan cocok pada peran yang menuntut kesabaran serta pelayanan, seperti pendidikan, kesehatan, konseling, dan layanan sosial.
                    </p>
                </td>
                <td>
                    <p><strong>3. KOLERIS</strong><br>
                        Individu koleris berfokus pada tujuan, efisiensi, dan hasil. Mereka berpikir logis, analitis, serta cenderung langsung pada inti persoalan. Koleris kurang menyukai interaksi sosial yang bersifat dangkal dan lebih nyaman bekerja secara mandiri atau dengan individu yang setara secara intelektual. Bidang yang sesuai meliputi manajemen, teknologi, teknik, statistik, dan pemrograman.
                    </p>
                    <p><strong>4. MELANKOLIS</strong><br>
                        Individu melankolis menilai stabilitas, struktur, dan ketertiban sebagai hal penting. Mereka cenderung berhati-hati, teliti, dan konsisten, serta kurang tertarik pada perubahan drastis atau risiko tinggi. Melankolis memiliki orientasi sosial yang kuat dalam konteks tanggung jawab dan kontribusi. Tipe ini sesuai untuk peran manajerial, administrasi, akuntansi, dan pekerjaan yang menuntut ketepatan serta perencanaan.
                    </p>
                </td>
            </tr>
        </table>

        {{-- ======================================================
            LOVE LANGUAGE
        ====================================================== --}}
        <div class="kamus-section-title">
            Love Language
        </div>

         <table class="kamus-table">
                <tr>
                    <td>
                        <p><strong>1. WORD OF AFFIRMATION</strong><br>Individu dengan bahasa cinta ini merasakan afeksi melalui ungkapan verbal yang jelas dan tulus. Pernyataan penghargaan, pengakuan, dan apresiasi berperan penting dalam membangun rasa diterima dan dihargai.</p>
                        <p><strong>2. QUALITY TIME</strong><br>Bahasa cinta ini ditandai oleh kebutuhan akan kehadiran penuh dan perhatian tanpa distraksi. Interaksi bermakna melalui kebersamaan, percakapan, dan aktivitas bersama menjadi indikator utama rasa peduli.</p>
                        <p><strong>3. RECEIVING GIFTS</strong><br>Individu dengan bahasa cinta ini menilai perhatian melalui pemberian simbolis yang bermakna. Hadiah dipahami sebagai bentuk usaha, pemikiran, dan kepedulian, bukan semata nilai materi.</p>
                    </td>
                    <td>
                        <p><strong>4. ACTS OF SERVICE</strong><br>Bahasa cinta ini diwujudkan melalui tindakan nyata yang membantu atau meringankan beban. Dukungan praktis dipersepsikan sebagai bentuk tanggung jawab dan kepedulian emosional.</p>
                        <p><strong>5. PHYSICAL TOUCH</strong><br>Individu dengan bahasa cinta ini merasakan kedekatan melalui kontak fisik yang wajar dan aman, seperti sentuhan ringan atau pelukan. Sentuhan berfungsi sebagai sarana komunikasi afeksi secara langsung.</p>
                    </td>
                </tr>
            </table>

        {{-- ======================================================
            MULTIPLE INTELLIGENCE
        ====================================================== --}}
        <div class="page-break"></div>
        <div class="kamus-section-title">
            Multiple Intelligence
        </div>

        <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>1. LINGUISTIK</strong><br>
                        Kecerdasan dalam mengolah kata-kata, baik secara lisan maupun tertulis. Orang dengan kecerdasan ini umumnya gemar membaca, menulis, berbicara, dan mendengarkan cerita. Mereka mampu menggunakan bahasa untuk meyakinkan, menghibur, atau menyampaikan informasi secara efektif.
                    </p>
                    <p><strong>2. LOGIKA MATEMATIKA</strong><br>
                        Kecerdasan dalam menganalisis masalah secara logis, menemukan pola, dan melakukan operasi matematis. Orang dengan kecerdasan ini menyukai angka, teka-teki, eksperimen, dan berpikir kritis. Mereka cenderung sistematis dalam menyelesaikan masalah.
                    </p>
                    <p><strong>3. VISUAL SPASIAL</strong><br>
                        Kecerdasan dalam memvisualisasikan objek dan ruang. Orang dengan kecerdasan ini memiliki kemampuan membayangkan bentuk, warna, dan dimensi. Mereka umumnya menyukai menggambar, merancang, membaca peta, dan memiliki kepekaan estetika yang baik.
                    </p>
                    <p><strong>4. MUSIKAL</strong><br>
                        Kecerdasan dalam mengenali, membedakan, dan mengekspresikan bentuk-bentuk musik. Orang dengan kecerdasan ini peka terhadap nada, irama, dan ritme. Mereka seringkali gemar menyanyi, bermain alat musik, atau sekadar mendengarkan musik.
                    </p>
                </td>
                <td>
                    <p><strong>5. KINESTETIK</strong><br>
                        Kecerdasan dalam menggunakan seluruh tubuh atau bagian tubuh untuk mengekspresikan ide dan perasaan, serta keterampilan tangan untuk menciptakan atau mengubah sesuatu. Orang dengan kecerdasan ini menyukai aktivitas fisik, olahraga, menari, atau membuat kerajinan tangan.
                    </p>
                    <p><strong>6. INTERPERSONAL</strong><br>
                        Kecerdasan dalam memahami dan berinteraksi dengan orang lain secara efektif. Orang dengan kecerdasan ini peka terhadap perasaan, motivasi, dan watak orang lain. Mereka umumnya pandai bergaul, bekerja sama dalam tim, dan memiliki empati yang tinggi.
                    </p>
                    <p><strong>7. INTRAPERSONAL</strong><br>
                        Kecerdasan dalam memahami diri sendiri, termasuk kekuatan, kelemahan, keinginan, dan perasaan. Orang dengan kecerdasan ini memiliki kesadaran diri yang tinggi, mampu merefleksikan pengalaman, dan memiliki tujuan hidup yang jelas.
                    </p>
                    <p><strong>8. NATURALIS</strong><br>
                        Kecerdasan dalam mengenali, mengkategorikan, dan memahami flora, fauna, serta fenomena alam lainnya. Orang dengan kecerdasan ini menyukai kegiatan di alam bebas, berkebun, memelihara hewan, atau mengamati lingkungan sekitar.
                    </p>
                </td>
            </tr>
        </table>

    </div>
    </div>
</body>
</html>