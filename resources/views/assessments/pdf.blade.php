@php
    $labels = ['TD', 'KD', 'AD', 'D', 'SD'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Asesmen - {{ $assessment->subject->name }}</title>
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
            background-color: rgba(255, 255, 255, 0.95);
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
            background: #fff;
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
    text-align: left;          /* 🔥 FIX UTAMA */
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
                            <div class="cover-headline">LAPORAN<br>HASIL ASESMEN<br>PERSONAL MAPPING</div>
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
                            <div class="subject-value" style="font-size: 12pt;">{{ $assessment->subject->age }} Tahun</div>
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

            <table class="table" style="margin-bottom: 8px;">
                <tr>
                    <!-- LEFT COLUMN: Personality & Love Language -->
                    <td class="align-top" style="width: 55%; padding-right: 10px;">
                        
                        <!-- Personality Card -->
                        <div class="card">
                            <div class="card-header">PERSONALITY</div>
                            <div class="card-body">
                                @php
                                    $personalityScores = $assessment->scores->where('category', 'personality')->values();
                                @endphp
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach($personalityScores as $s)
                                    <tr>
                                        <td>{{ $s?->aspect_name ?? '' }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box {{ $s && $s->label === $l ? 'active' : '' }}">
                                                {{ $s && $s->label === $l ? 'X' : '' }}
                                            </div>
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <!-- Love Language Card -->
                        <div class="card">
                            <div class="card-header">LOVE LANGUAGE</div>
                            <div class="card-body">
                                @php
                                    $loveLanguageScores = $assessment->scores->where('category', 'love_language')->values();
                                @endphp
                                <table class="score-table">
                                    <tr>
                                        <th style="text-align: left;">ASPEK</th>
                                        @foreach($labels as $l) <th width="18">{{ $l }}</th> @endforeach
                                    </tr>
                                    @foreach($loveLanguageScores as $s)
                                    <tr>
                                        <td>{{ $s?->aspect_name ?? '' }}</td>
                                        @foreach($labels as $l)
                                        <td align="center">
                                            <div class="score-box {{ $s && $s->label === $l ? 'active' : '' }}">
                                                {{ $s && $s->label === $l ? 'X' : '' }}
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
                                    $miOrder = [
                                        'linguistik',
                                        'logika matematika',
                                        'visual spasial',
                                        'musikal',
                                        'kinestetik',
                                        'interpersonal',
                                        'intrapersonal',
                                        'naturalis',
                                    ];

                                    $miIndex = function (?string $name) use ($miOrder): int {
                                        $raw = mb_strtolower((string) $name);
                                        $norm = preg_replace('/[^a-z0-9]+/u', ' ', $raw);
                                        $norm = trim((string) $norm);

                                        $aliases = [
                                            'linguistik' => ['linguistik', 'linguistic'],
                                            'logika matematika' => ['logika matematika', 'logical mathematical', 'logicalmathematical', 'logical mathematical', 'logico mathematical'],
                                            'visual spasial' => ['visual spasial', 'visual spatial', 'visualspatial', 'spatial'],
                                            'musikal' => ['musikal', 'musical'],
                                            'kinestetik' => ['kinestetik', 'bodily kinesthetic', 'bodilykinesthetic', 'kinaesthetic', 'kinesthetic'],
                                            'interpersonal' => ['interpersonal'],
                                            'intrapersonal' => ['intrapersonal'],
                                            'naturalis' => ['naturalis', 'naturalist', 'naturalistic'],
                                        ];

                                        $key = null;
                                        foreach ($aliases as $candidate => $terms) {
                                            foreach ($terms as $t) {
                                                $tNorm = trim((string) preg_replace('/[^a-z0-9]+/u', ' ', mb_strtolower($t)));
                                                if ($tNorm !== '' && (str_contains($norm, $tNorm) || str_contains(str_replace(' ', '', $norm), str_replace(' ', '', $tNorm)))) {
                                                    $key = $candidate;
                                                    break 2;
                                                }
                                            }
                                        }

                                        $idx = $key !== null ? array_search($key, $miOrder, true) : false;
                                        return $idx === false ? 999 : $idx;
                                    };

                                    $miScores = $assessment->scores
                                        ->where('category', 'multiple_intelligence')
                                        ->sortBy(fn ($s) => $miIndex($s->aspect_name))
                                        ->values();
                                @endphp

                                @foreach($miScores as $s)
                                @php $p = $s->score_value * 2; @endphp
                                <div style="margin-bottom: 6px;">
                                    <div style="margin-bottom: 2px; font-size: 7.5pt;">
                                        <span style="font-weight: bold;">{{ $s->aspect_name }}</span>
                                        <span style="float: right;">{{ $p }}%</span>
                                    </div>
                                    <table class="progress-table">
                                        <tr>
                                            <td class="progress-fill" width="{{ $p }}%"></td>
                                            <td class="progress-empty"></td>
                                        </tr>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Legend Card -->
                        <div class="card" style="background-color: #f9fafb; border-style: dashed;">
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
            <div class="card">
                <div class="card-header">TALENTS MAPPING ANALYSIS</div>
                <div class="card-body">
                    <table class="table tm-table" style="font-size: 8.5pt;">
                        <tr>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Brain Dominance</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->brain_dominance ?? '-' }}</div>
                            </td>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Social Dominance</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->social_dominance ?? '-' }}</div>
                            </td>
                            <td width="33%">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase;">Skill Dominance</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->skill_dominance ?? '-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase; margin-top: 4px;">Strengths</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->strengths ?? '-' }}</div>
                            </td>
                            <td>
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase; margin-top: 4px;">Defisit</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->deficits ?? '-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase; margin-top: 4px;">Cluster Strength</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->cluster_strength ?? '-' }}</div>
                            </td>
                            <td colspan="2">
                                <div style="color: #6b7280; font-size: 6.5pt; text-transform: uppercase; margin-top: 4px;">Personal Branding</div>
                                <div style="font-weight: bold;">{{ $assessment->talentsMapping->personal_branding ?? '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Signature -->
            <div class="signature-box">
                <div style="font-size: 8.5pt; margin-bottom: 8px;">Bogor, {{ $assessment->test_date->format('d F Y') }}</div>
                @if(!empty($signatureAmbu))
                    <img src="{{ $signatureAmbu }}" style="height: 55px; margin-bottom: 4px;">
                @else
                    <div style="height: 55px;"></div>
                @endif
                <div style="font-weight: bold; font-size: 9.5pt; text-decoration: underline;">Anggia Chrisanti, S.Psi, M.Psi, Psikolog</div>
                <div style="font-size: 7.5pt;">No.SIAP HIMPSI 20250719</div>
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
                        Memiliki stamina tinggi dan selalu bekerja keras. Kepuasan hidup berasal dari kesibukan dan keberhasilan.
                        Cocok untuk Sales, Teknisi Lapangan, Relawan, Petugas SAR.
                    </p>
                </td>
                <td>
                    <p><strong>FOKUS</strong><br>
                        Membutuhkan tujuan jelas sebagai arah prioritas.
                        Cocok untuk Team Leader.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ACTIVATOR</strong><br>
                        Mampu mengubah ide menjadi tindakan nyata.
                        Cocok untuk Entrepreneur dan Sales.
                    </p>
                </td>
                <td>
                    <p><strong>FUTURISTIC</strong><br>
                        Memiliki visi masa depan yang inspiratif.
                        Cocok untuk Visioner, Entrepreneur.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ADAPTABILITY</strong><br>
                        Fleksibel dan bekerja sesuai situasi saat ini.
                        Cocok untuk Wartawan, Produksi Live TV, Perawat IGD.
                    </p>
                </td>
                <td>
                    <p><strong>HARMONY</strong><br>
                        Menghindari konflik dan mencari titik temu.
                        Cocok untuk Mediator.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ANALYTICAL</strong><br>
                        Mencari sebab dan pola secara logis.
                        Cocok untuk Analis, Periset, Accounting, Programmer.
                    </p>
                </td>
                <td>
                    <p><strong>IDEATION</strong><br>
                        Senang brainstorming dan eksplorasi ide.
                        Cocok untuk Marketing, Product Developer.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>ARRANGER</strong><br>
                        Pandai mengorganisir sumber daya secara fleksibel.
                        Cocok untuk Manajer, Supervisor, Event Organizer.
                    </p>
                </td>
                <td>
                    <p><strong>INCLUDER</strong><br>
                        Mengajak semua orang agar merasa diterima.
                        Cocok untuk Mentor.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>BELIEF</strong><br>
                        Memiliki nilai luhur dan dorongan melayani.
                        Cocok untuk Pekerja Sosial, Perawat.
                    </p>
                </td>
                <td>
                    <p><strong>INDIVIDUALIZATION</strong><br>
                        Melihat keunikan setiap individu.
                        Cocok untuk HRD, Recruiter.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMMAND</strong><br>
                        Berani mengambil kendali dan menghadapi tantangan.
                        Cocok untuk Leader, HRD, Negosiator.
                    </p>
                </td>
                <td>
                    <p><strong>INPUT</strong><br>
                        Haus akan informasi dan pengetahuan.
                        Cocok untuk Periset.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMMUNICATION</strong><br>
                        Mampu menyampaikan ide secara jelas dan menarik.
                        Cocok untuk Pengajar, Presenter, Marketing.
                    </p>
                </td>
                <td>
                    <p><strong>INTELLECTION</strong><br>
                        Menyukai pemikiran mendalam.
                        Cocok untuk Peneliti.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>COMPETITION</strong><br>
                        Termotivasi untuk menjadi yang terbaik.
                        Cocok untuk Sales, Pelatih Olahraga.
                    </p>
                </td>
                <td>
                    <p><strong>LEARNER</strong><br>
                        Menikmati proses belajar.
                        Cocok untuk Guru, Programmer.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONNECTEDNESS</strong><br>
                        Percaya semua kejadian saling terhubung.
                        Cocok untuk Counselor, Team Leader.
                    </p>
                </td>
                <td>
                    <p><strong>MAXIMIZER</strong><br>
                        Mengembangkan kekuatan menjadi keunggulan.
                        Cocok untuk Manajer.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONSISTENCY / FAIRNESS</strong><br>
                        Menjunjung keadilan dan kesetaraan.
                        Cocok untuk Hakim, Quality Control.
                    </p>
                </td>
                <td>
                    <p><strong>POSITIVITY</strong><br>
                        Optimis dan menularkan semangat.
                        Cocok untuk Motivator.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>CONTEXT</strong><br>
                        Belajar melalui pemahaman masa lalu.
                        Cocok untuk Guru Sejarah, Arkeolog.
                    </p>
                </td>
                <td>
                    <p><strong>RELATOR</strong><br>
                        Mengutamakan hubungan yang erat.
                        Cocok untuk Account Manager.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DELIBERATIVE</strong><br>
                        Hati-hati dan penuh pertimbangan.
                        Cocok untuk Advisor, Legal.
                    </p>
                </td>
                <td>
                    <p><strong>RESPONSIBILITY</strong><br>
                        Bertanggung jawab atas komitmen.
                        Cocok untuk Quality Control.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DEVELOPER</strong><br>
                        Senang mengembangkan potensi orang lain.
                        Cocok untuk Guru, Pelatih, Manajer.
                    </p>
                </td>
                <td>
                    <p><strong>RESTORATIVE</strong><br>
                        Senang memecahkan masalah.
                        Cocok untuk Teknisi.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>DISCIPLINE</strong><br>
                        Menyukai keteraturan dan struktur.
                        Cocok untuk Administrasi, Accounting.
                    </p>
                </td>
                <td>
                    <p><strong>SELF-ASSURANCE</strong><br>
                        Percaya diri dan mandiri dalam keputusan.
                        Cocok untuk Leader.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>EMPATHY</strong><br>
                        Mampu merasakan emosi orang lain.
                        Cocok untuk HRD, Psikolog.
                    </p>
                </td>
                <td>
                    <p><strong>SIGNIFICANCE</strong><br>
                        Ingin diakui dan memberi dampak.
                        Cocok untuk Presenter.
                    </p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <p><strong>STRATEGIC</strong><br>
                        Cepat melihat solusi terbaik.
                        Cocok untuk Manajer.
                    </p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <p><strong>WOO</strong><br>
                        Mudah membangun relasi baru.
                        Cocok untuk Sales.
                    </p>
                </td>
            </tr>
        </table>

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
                    <p><strong>1. SANGUINIS</strong><br>Optimis, ringan, riang, menyukai petualangan dan risiko. Kreatif, cocok untuk marketing, travel, fashion, atau kuliner.</p>
                    <p><strong>2. PHLEGMATIS</strong><br>Cinta damai, mencari keharmonisan, setia, dan menghindari konflik. Ideal sebagai perawat, guru, psikolog, atau layanan sosial.</p>
                </td>
                <td>
                    <p><strong>3. KHOLERIS</strong><br>Berorientasi tujuan, cerdas, analitis, logis, praktis, dan menyukai percakapan mendalam. Cocok untuk industri pengelolaan, teknologi, statistik, atau teknik.</p>
                    <p><strong>4. MELANKHOLIS</strong><br>Menyukai tradisi, mencintai keluarga, teliti, dan akurat. Karier sempurna di bidang manajemen, akuntansi, atau administrasi.</p>
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
                    <p><strong>1. WORD OF AFFIRMATION</strong><br>Perlu mendengar kata-kata penegasan, apresiasi tulus, dan ungkapan kasih sayang secara langsung atau tulisan.</p>
                    <p><strong>2. QUALITY TIME</strong><br>Fokus pada perhatian penuh tanpa gangguan. Menghabiskan waktu bersama melalui komunikasi dan kebersamaan bermakna.</p>
                    <p><strong>3. RECEIVING GIFTS</strong><br>Merasa dicintai melalui hadiah sebagai bukti nyata kasih sayang dan perhatian khusus.</p>
                </td>
                <td>
                    <p><strong>4. ACTS OF SERVICE</strong><br>Tindakan nyata untuk meringankan beban tanggung jawab pasangan, seperti membantu pekerjaan rumah atau tugas.</p>
                    <p><strong>5. PHYSICAL TOUCH</strong><br>Senang dengan sentuhan fisik seperti pelukan, berpegangan tangan, atau tepukan sebagai cara komunikasi cinta langsung.</p>
                </td>
            </tr>
        </table>

        {{-- ======================================================
            MULTIPLE INTELLIGENCE
        ====================================================== --}}
        <div class="kamus-section-title">
            Multiple Intelligence
        </div>

        <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>Linguistik</strong> — Kemampuan menggunakan kata secara efektif.</p>
                    <p><strong>Numerikal</strong> — Kemampuan berpikir logis dan matematis.</p>
                    <p><strong>Visual-Spasial</strong> — Kepekaan terhadap ruang dan bentuk.</p>
                    <p><strong>Musikal</strong> — Kepekaan terhadap ritme dan melodi.</p>
                </td>
                <td>
                    <p><strong>Interpersonal</strong> — Memahami dan berinteraksi dengan orang lain.</p>
                    <p><strong>Intrapersonal</strong> — Kesadaran dan pengendalian diri.</p>
                    <p><strong>Bodily Kinestetik</strong> — Menggunakan tubuh secara terampil.</p>
                    <p><strong>Naturalis</strong> — Mengenali dan mengklasifikasi alam.</p>
                </td>
            </tr>
        </table>

    </div>
</div>


</body>
</html>
