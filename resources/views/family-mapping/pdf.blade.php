<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Family Mapping - {{ $ayah->subject->name }}</title>
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

        * { box-sizing: border-box; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 9pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-purple { color: #5b21b6; }
        .table { display: table; width: 100%; border-collapse: collapse; table-layout: fixed; }
        .table-cell { display: table-cell; }
        .align-top { vertical-align: top; }

        .page { width: 100%; position: relative; clear: both; }
        .page-break { page-break-before: always; }
        .content-wrapper { padding-bottom: 18mm; position: relative; width: 100%; }
        .dashboard-wrapper { padding-bottom: 45mm; }

        .cover-page { padding: 0; height: 297mm; background-color: white; z-index: 50; position: relative; }
        
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
        .cover-header-table { width: 100%; margin-bottom: 2cm; }
        .cover-headline { font-size: 22pt; font-weight: bold; color: #111; line-height: 1.1; }
        .cover-subheadline { font-size: 12pt; margin-top: 10px; color: #555; letter-spacing: 1px; }

        .cover-content { position: relative; overflow: hidden; padding: 0; min-height: 273mm; height: 297mm; }
        .cover-safe-area { position: relative; padding: 10mm; z-index: 1; }

        .cover-decor { position: absolute; left: 0; right: 0; bottom: 0; height: 65mm; background-color: #4c1d95; z-index: 0; }
        .cover-decor-accent { position: absolute; left: 0; right: 0; bottom: 40mm; height: 35mm; background-color: #7c3aed; opacity: 0.35; z-index: 0; }
        .cover-shape-left { position: absolute; left: -35mm; bottom: 20mm; width: 95mm; height: 55mm; background-color: #5b21b6; border-radius: 55mm 55mm 0 0; opacity: 0.22; z-index: 0; }
        .cover-shape-right { position: absolute; right: -40mm; bottom: 10mm; width: 105mm; height: 70mm; background-color: #a78bfa; border-radius: 70mm 70mm 0 0; opacity: 0.22; z-index: 0; }

        .pair-card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 18px 18px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-left: 5px solid #5b21b6;
        }

        .pair-title {
            font-size: 9pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .kv-label { font-size: 7.5pt; color: #6b7280; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 2px; }
        .kv-value { font-size: 11.5pt; font-weight: bold; color: #1f2937; margin-bottom: 10px; }

        .card {
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            overflow: hidden;
            page-break-inside: avoid;
        }

        .card-header {
            background-color: #5b21b6;
            color: white;
            padding: 5px 10px;
            font-size: 8.2pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body { padding: 8px; }

        .mini-label {
            font-size: 6.5pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .mini-value { font-size: 8.8pt; font-weight: bold; margin-bottom: 6px; }

        .badge-wrap { margin: 0 0 6px 0; }
        .badge {
            display: inline-block;
            background-color: #f3e8ff;
            color: #4c1d95;
            border: 1px solid #ddd6fe;
            padding: 2px 6px;
            border-radius: 999px;
            font-size: 7.5pt;
            font-weight: bold;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .muted { color: #6b7280; font-size: 8pt; }

        .progress-table { width: 100%; border-collapse: collapse; height: 6px; }
        .progress-fill { background-color: #7c3aed; }
        .progress-empty { background-color: #f3e8ff; }

        .legend-grid { width: 100%; font-size: 7pt; }
        .legend-item { display: inline-block; margin-right: 8px; padding: 2px 5px; background: #f9fafb; border-radius: 3px; border: 1px solid #e5e7eb; }

        .signature-box-fixed { position: absolute; right: 0; bottom: 0; width: 40%; text-align: center; }

        .kamus-section-title {
            margin-top: 18px;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 10pt;
            color: #4c1d95;
            border-bottom: 1px solid #c4b5fd;
            padding-bottom: 2px;
        }

        .kamus-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .kamus-table td { width: 50%; vertical-align: top; padding: 0 10px; font-size: 8.5pt; line-height: 1.4; text-align: left; }
        .kamus-table tr { page-break-inside: avoid; }
        .kamus-table p { margin: 0 0 7px 0; text-align: left; }
    </style>
</head>
<body>
    @if(!empty($logoProsekar))
    <div class="watermark">
        <img src="{{ $logoProsekar }}" alt="Watermark">
    </div>
    @endif
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
                            <div class="cover-headline">LAPORAN<br>HASIL ASESMEN<br>FAMILY MAPPING</div>
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
                                Instagram : @prosekar_psikologibogor
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="table" style="width: 86%; margin: 3.2cm auto 0;">
                    <tr>
                        <td class="table-cell align-top" style="width: 100%; padding-right: 0;">
                            <div class="pair-card">
                                <div class="pair-title">Atas Nama Keluarga</div>
                                <div class="kv-label">Kepala Keluarga</div>
                                <div class="kv-value">{{ $ayah->subject->name }}</div>
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-cell; width: 50%;">
                                        <div class="kv-label">Usia</div>
                                        <div class="kv-value" style="font-size: 10.5pt;">
                                            {{ $ayah->subject->getPreciseAge($ayah->test_date) ?? '-' }}
                                        </div>
                                    </div>
                                    <div style="display: table-cell; width: 50%;">
                                        <div class="kv-label">Jenis Kelamin</div>
                                        <div class="kv-value" style="font-size: 10.5pt;">
                                            {{ ($ayah->subject->gender ?? '') === 'male' ? 'Laki-Laki' : ((($ayah->subject->gender ?? '') === 'female') ? 'Perempuan' : '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 8px; border-top: 1px solid #eee; padding-top: 8px;">
                                    <div class="kv-label">Tanggal Asesmen</div>
                                    <div style="font-size: 10pt; font-weight: bold; color: #4b5563;">
                                        {{ $ayah->test_date?->format('d F Y') ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="page page-break">
        <div class="content-wrapper dashboard-wrapper">
            <h2 style="color: #4c1d95; margin-bottom: 10px; font-size: 14pt; border-bottom: 2px solid #4c1d95; padding-bottom: 4px; display: inline-block;">HASIL PEMERIKSAAN FAMILY MAPPING</h2>

            <table class="table" style="margin-bottom: 8px;">
                <tr>
                    <td class="align-top" style="width: 33.33%; padding-right: 8px;">
                        <div class="card">
                            <div class="card-header">Data Ayah</div>
                            <div class="card-body">
                                <div class="mini-label">Personality (Dominan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($ayahPersonality))
                                        @foreach($ayahPersonality as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Learning Style (Dominan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($ayahLearningStyle))
                                        @foreach($ayahLearningStyle as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Love Language (Dominan)</div>
                                <div class="badge-wrap" style="margin-bottom: 0;">
                                    @if(!empty($ayahLoveLanguage))
                                        @foreach($ayahLoveLanguage as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 4px; padding-right: 4px;">
                        <div class="card">
                            <div class="card-header">Family Mapping</div>
                            <div class="card-body">
                                <div class="mini-label">Personality (Kesamaan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($irisanPersonality))
                                        @foreach($irisanPersonality as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Learning Style (Kesamaan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($irisanLearningStyle))
                                        @foreach($irisanLearningStyle as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Love Language (Kesamaan)</div>
                                <div class="badge-wrap" style="margin-bottom: 0;">
                                    @if(!empty($irisanLoveLanguage))
                                        @foreach($irisanLoveLanguage as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 8px;">
                        <div class="card">
                            <div class="card-header">Data Ibu</div>
                            <div class="card-body">
                                <div class="mini-label">Personality (Dominan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($ibuPersonality))
                                        @foreach($ibuPersonality as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Learning Style (Dominan)</div>
                                <div class="badge-wrap">
                                    @if(!empty($ibuLearningStyle))
                                        @foreach($ibuLearningStyle as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>

                                <div class="mini-label">Love Language (Dominan)</div>
                                <div class="badge-wrap" style="margin-bottom: 0;">
                                    @if(!empty($ibuLoveLanguage))
                                        @foreach($ibuLoveLanguage as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="align-top" style="width: 33.33%; padding-right: 8px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Multiple Intelligence (Top)</div>
                            <div class="card-body">
                                @if(!empty($ayahMiTop))
                                    @foreach($ayahMiTop as $row)
                                        <div style="margin-bottom: 6px;">
                                            <div style="margin-bottom: 2px; font-size: 7.5pt;">
                                                <span style="font-weight: bold;">{{ $row['label'] }}</span>
                                                <span style="float: right;">{{ $row['percent'] }}%</span>
                                            </div>
                                            <table class="progress-table">
                                                <tr>
                                                    <td class="progress-fill" width="{{ $row['percent'] }}%"></td>
                                                    <td class="progress-empty"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="muted">-</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 4px; padding-right: 4px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Kesamaan MI (Top)</div>
                            <div class="card-body">
                                <div class="badge-wrap" style="margin-bottom: 0;">
                                    @if(!empty($irisanMiLabels))
                                        @foreach($irisanMiLabels as $item)
                                            <span class="badge">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 8px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Multiple Intelligence (Top)</div>
                            <div class="card-body">
                                @if(!empty($ibuMiTop))
                                    @foreach($ibuMiTop as $row)
                                        <div style="margin-bottom: 6px;">
                                            <div style="margin-bottom: 2px; font-size: 7.5pt;">
                                                <span style="font-weight: bold;">{{ $row['label'] }}</span>
                                                <span style="float: right;">{{ $row['percent'] }}%</span>
                                            </div>
                                            <table class="progress-table">
                                                <tr>
                                                    <td class="progress-fill" width="{{ $row['percent'] }}%"></td>
                                                    <td class="progress-empty"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="muted">-</div>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="align-top" style="width: 33.33%; padding-right: 8px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Talents Mapping</div>
                            <div class="card-body">
                                <div class="mini-label">Brain Dominance</div>
                                <div class="mini-value">{{ $ayahTalents['brain_dominance'] !== '' ? $ayahTalents['brain_dominance'] : '-' }}</div>
                                <div class="mini-label">Social Dominance</div>
                                <div class="mini-value">{{ $ayahTalents['social_dominance'] !== '' ? $ayahTalents['social_dominance'] : '-' }}</div>
                                <div class="mini-label">Skill Dominance</div>
                                <div class="mini-value">{{ $ayahTalents['skill_dominance'] !== '' ? $ayahTalents['skill_dominance'] : '-' }}</div>
                                <div class="mini-label">Strength Approach</div>
                                <div class="mini-value">{{ $ayahTalents['strengths'] !== '' ? $ayahTalents['strengths'] : '-' }}</div>
                                <div class="mini-label">Deficit Approach</div>
                                <div class="mini-value">{{ $ayahTalents['deficits'] !== '' ? $ayahTalents['deficits'] : '-' }}</div>
                                <div class="mini-label">Cluster Strength</div>
                                <div class="mini-value">{{ $ayahTalents['cluster_strength'] !== '' ? $ayahTalents['cluster_strength'] : '-' }}</div>
                                <div class="mini-label">Personal Branding</div>
                                <div class="mini-value" style="margin-bottom: 0;">{{ $ayahTalents['personal_branding'] !== '' ? $ayahTalents['personal_branding'] : '-' }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 4px; padding-right: 4px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Kesamaan Talents Mapping</div>
                            <div class="card-body">
                                <div class="mini-label">Brain Dominance</div>
                                <div class="mini-value">{{ ($irisanTalents['brain_dominance'] ?? '') !== '' ? $irisanTalents['brain_dominance'] : '-' }}</div>
                                <div class="mini-label">Social Dominance</div>
                                <div class="mini-value">{{ ($irisanTalents['social_dominance'] ?? '') !== '' ? $irisanTalents['social_dominance'] : '-' }}</div>
                                <div class="mini-label">Skill Dominance</div>
                                <div class="mini-value">{{ ($irisanTalents['skill_dominance'] ?? '') !== '' ? $irisanTalents['skill_dominance'] : '-' }}</div>
                                <div class="mini-label">Strength Approach</div>
                                <div class="mini-value">{{ ($irisanTalents['strengths'] ?? '') !== '' ? $irisanTalents['strengths'] : '-' }}</div>
                                <div class="mini-label">Deficit Approach</div>
                                <div class="mini-value">{{ ($irisanTalents['deficits'] ?? '') !== '' ? $irisanTalents['deficits'] : '-' }}</div>
                                <div class="mini-label">Cluster Strength</div>
                                <div class="mini-value">{{ ($irisanTalents['cluster_strength'] ?? '') !== '' ? $irisanTalents['cluster_strength'] : '-' }}</div>
                                <div class="mini-label">Personal Branding</div>
                                <div class="mini-value" style="margin-bottom: 0;">{{ ($irisanTalents['personal_branding'] ?? '') !== '' ? $irisanTalents['personal_branding'] : '-' }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="align-top" style="width: 33.33%; padding-left: 8px; padding-top: 8px;">
                        <div class="card">
                            <div class="card-header">Talents Mapping</div>
                            <div class="card-body">
                                <div class="mini-label">Brain Dominance</div>
                                <div class="mini-value">{{ $ibuTalents['brain_dominance'] !== '' ? $ibuTalents['brain_dominance'] : '-' }}</div>
                                <div class="mini-label">Social Dominance</div>
                                <div class="mini-value">{{ $ibuTalents['social_dominance'] !== '' ? $ibuTalents['social_dominance'] : '-' }}</div>
                                <div class="mini-label">Skill Dominance</div>
                                <div class="mini-value">{{ $ibuTalents['skill_dominance'] !== '' ? $ibuTalents['skill_dominance'] : '-' }}</div>
                                <div class="mini-label">Strength Approach</div>
                                <div class="mini-value">{{ $ibuTalents['strengths'] !== '' ? $ibuTalents['strengths'] : '-' }}</div>
                                <div class="mini-label">Deficit Approach</div>
                                <div class="mini-value">{{ $ibuTalents['deficits'] !== '' ? $ibuTalents['deficits'] : '-' }}</div>
                                <div class="mini-label">Cluster Strength</div>
                                <div class="mini-value">{{ $ibuTalents['cluster_strength'] !== '' ? $ibuTalents['cluster_strength'] : '-' }}</div>
                                <div class="mini-label">Personal Branding</div>
                                <div class="mini-value" style="margin-bottom: 0;">{{ $ibuTalents['personal_branding'] !== '' ? $ibuTalents['personal_branding'] : '-' }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>



            <div class="signature-box-fixed">
                <div style="font-size: 8.5pt; margin-bottom: 8px;">Bogor, {{ $ayah->test_date?->format('d F Y') ?? '-' }}</div>
                @if(!empty($signatureAnggia))
                    <img src="{{ $signatureAnggia }}" style="height: 55px; margin-bottom: 4px;">
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
            <h2 style="color:#4c1d95; margin-bottom:12px; font-size:14pt; border-bottom:2px solid #4c1d95; padding-bottom:4px; display:inline-block;">KAMUS LAPORAN</h2>

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
                        <p><strong>FOKUS</strong><br>
                            membutuhkan tujuan yang jelas sebagai kompas prioritas. Tema bakat ini banyak terdapat pada peran: Project Officer, Team Leader.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>ACTIVATOR</strong><br>
                            dapat membuat sesuatunya terjadi dengan mengubah pikiran menjadi tindakan. Tema bakat ini sering terdapat pada peran: usaha-usaha baru atau yang memerlukan perubahan besar, Entrepreneur, Sales.
                        </p>
                    </td>
                    <td>
                        <p><strong>FUTURISTIC</strong><br>
                            senang membayangkan masa depan dan memberikan inspirasi visi. Tema bakat ini banyak terdapat pada peran: Entrepreneur, Perencana jangka panjang, Visioner, Pengembang produk baru.
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
                        <p><strong>HARMONY</strong><br>
                            dapat bekerja sama secara baik dengan orang lain dan mencari titik temu. Tema bakat ini banyak terdapat pada peran: Juru Damai, Penasehat, Pembangun jaringan.
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
                        <p><strong>IDEATION</strong><br>
                            menyukai diskusi bebas, brainstorming, dan menemukan benang merah antar fenomena. Tema bakat ini banyak terdapat pada peran: Marketing, Advertising, Wartawan, Pengembang produk.
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
                        <p><strong>INCLUDER</strong><br>
                            kecenderungan untuk menerima semua orang agar merasa memiliki dalam kelompok. Tema bakat ini banyak terdapat pada peran: Motivator kelompok, Mentor, Pemimpin beragam budaya.
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
                        <p><strong>INDIVIDUALIZATION</strong><br>
                            mampu melihat keunikan masing-masing orang secara individual. Tema bakat ini banyak terdapat pada peran: Manajer, Penasihat, Rekrutmen, Pengajar, Penulis, HRD.
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
                        <p><strong>INPUT</strong><br>
                            memiliki hasrat untuk mengetahui lebih jauh dan senang mengumpulkan informasi. Tema bakat ini banyak terdapat pada peran: Pengajar, Periset, Wartawan, Petugas Arsip.
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
                        <p><strong>INTELLECTION</strong><br>
                            senang berpikir, mawas diri dan menyukai diskusi intelektual. Tema bakat ini banyak terdapat pada peran: Filusuf, Peneliti, Psikolog.
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
                        <p><strong>LEARNER</strong><br>
                            senang mempelajari sesuatu dan tertarik pada proses pembelajaran. Tema bakat ini sering terdapat pada peran: Konsultan, Teknisi TI, Programmer, Guru.
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
                        <p><strong>MAXIMIZER</strong><br>
                            fokus pada kekuatan untuk merangsang keunggulan pribadi dan kelompok. Tema bakat ini banyak terdapat pada peran: Pelatih, Manajer, Mentor, Transformational leader.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>CONSISTENCY / FAIRNESS</strong><br>
                            memiliki bakat untuk melihat “kesamaan” orang dan memperlakukan semua orang secara sama. Tema bakat ini banyak terdapat pada peran: Hakim, Quantity Surveyor, Petugas Kontrol standar.
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
                        <p><strong>CONTEXT</strong><br>
                            menikmati mempelajari sesuatu melalui riset dan studi tentang masa lalu. Tema bakat ini banyak terdapat pada peran: Guru Sejarah, Arkeolog, Penyusun budaya perusahaan, Hakim.
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
                        <p><strong>DELIBERATIVE</strong><br>
                            berhati-hati dan memiliki karakter “melihat sebelum melompat”. Tema bakat ini sering terdapat pada peran: Pilot, Advisor, Urusan Legal, Membuat Kontrak Bisnis.
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
                        <p><strong>DEVELOPER</strong><br>
                            senang mengenali dan menggali potensi orang lain. Tema bakat ini banyak terdapat pada peran: Manajer, Guru, Pelatih, Pembimbing, Petugas Sosial.
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
                        <p><strong>DISCIPLINE</strong><br>
                            senang berada dalam kondisi yang teratur, terstruktur, dan terencana. Tema bakat ini banyak terdapat pada peran: Keuangan, Sekretaris, Administrasi, Petugas ISO, Accounting, Programmer.
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
                        <p><strong>EMPATHY</strong><br>
                            mampu merasakan perasaan orang lain seakan-akan mengalaminya sendiri. Tema bakat ini banyak terdapat pada peran: Sales, HRD, Perawat, Psikiater, Layanan Pelanggan.
                        </p>
                    </td>
                    <td>
                        <p><strong>SIGNIFICANCE</strong><br>
                            senang menjadi pusat perhatian, dikenal, dan dihargai atas keunikannya. Tema bakat ini banyak terdapat pada peran: Marketing, Presenter, MC, Sales.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <p><strong>STRATEGIC</strong><br>
                            mampu memilah masalah dan menemukan jalan terbaik untuk solusinya. Tema bakat ini banyak terdapat pada peran: Perencana Strategi, Manajer, Leader.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <p><strong>WOO (Winning Others Over)</strong><br>
                            senang tantangan untuk bertemu orang baru dan menjadi akrab. Tema bakat ini banyak terdapat pada peran: Duta Organisasi, Sales, Entertainer, Resepsionis.
                        </p>
                    </td>
                </tr>
            </table>

            <div class="page-break"></div>

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
            
            <div class="page-break"></div>
            <div class="kamus-section-title">
                Multiple Intelligence
            </div>

            <table class="kamus-table">
            <tr>
                <td>
                    <p><strong>Linguistic Intelligence</strong><br>Kecerdasan linguistik adalah kemampuan seseorang dalam menggunakan dan memahami bahasa secara efektif, baik secara lisan maupun tulisan. Individu dengan kecerdasan ini biasanya peka terhadap makna kata, susunan kalimat, serta mampu menyampaikan dan menerima informasi dengan jelas. Kelebihannya terlihat pada kemampuan berbicara, menulis, mengingat informasi verbal, dan mempelajari bahasa. Namun, kecerdasan linguistik tidak selalu diikuti oleh kemampuan yang sama baiknya pada bidang lain seperti logika, visual, atau gerak tubuh.</p>
                    <p><strong>Logical-Mathematical Intelligence</strong><br>Kecerdasan numerikal merupakan kemampuan berpikir logis dan teratur dalam memecahkan masalah yang berkaitan dengan angka, pola, dan hubungan sebab akibat. Individu dengan kecerdasan ini cenderung mudah mengelompokkan masalah, menyusun langkah penyelesaian, dan melihat inti persoalan secara rasional. Kelebihannya terletak pada ketelitian dan kejelasan berpikir, sedangkan keterbatasannya dapat muncul pada situasi yang menuntut pendekatan emosional atau intuisi.</p>
                    <p><strong>Visual-Spatial Intelligence</strong><br>Kecerdasan visual-spasial adalah kemampuan memahami dan mengolah informasi yang berkaitan dengan gambar, bentuk, warna, dan ruang. Individu dengan kecerdasan ini mampu membayangkan objek, memahami posisi dan arah, serta melihat hubungan antar unsur visual. Kelebihannya tampak pada daya imajinasi dan orientasi ruang, sementara keterbatasannya dapat terlihat pada tugas yang sangat bergantung pada bahasa atau simbol abstrak.</p>
                    <p><strong>Musical Intelligence</strong><br>Kecerdasan musik adalah kemampuan mengenali, membedakan, dan mengekspresikan unsur-unsur musik seperti ritme, melodi, dan nada. Individu dengan kecerdasan ini peka terhadap bunyi dan dapat menggunakan musik untuk membantu konsentrasi, suasana belajar, dan daya ingat. Kelebihannya terletak pada kepekaan terhadap suara dan pola bunyi, sedangkan keterbatasannya adalah kemampuan ini tidak selalu berkaitan langsung dengan bidang akademik lain.</p>
                </td>
                <td>
                    <p><strong>Interpersonal Intelligence</strong><br>Kecerdasan interpersonal adalah kemampuan memahami perasaan, niat, dan perilaku orang lain melalui komunikasi verbal dan nonverbal. Individu dengan kecerdasan ini biasanya mudah berinteraksi, bekerja sama, dan menyesuaikan diri dalam lingkungan sosial. Kelebihannya adalah kemampuan membangun hubungan dan bekerja dalam kelompok, sementara keterbatasannya dapat berupa ketergantungan pada respons sosial dalam mengambil keputusan.</p>
                    <p><strong>Intrapersonal Intelligence</strong><br>Kecerdasan intrapersonal adalah kemampuan memahami diri sendiri, termasuk emosi, motivasi, kekuatan, dan kelemahan pribadi. Individu dengan kecerdasan ini mampu mengelola diri, menetapkan tujuan, dan bertindak sesuai nilai yang diyakini. Kelebihannya terletak pada kesadaran diri dan pengendalian emosi, sedangkan keterbatasannya dapat berupa kecenderungan menarik diri atau terlalu fokus pada pemikiran internal.</p>
                    <p><strong>Bodily-Kinesthetic Intelligence</strong><br>Kecerdasan bodily-kinestetik adalah kemampuan menggunakan tubuh secara terampil untuk melakukan aktivitas fisik dan mengekspresikan ide atau perasaan. Individu dengan kecerdasan ini memiliki koordinasi gerak, keseimbangan, dan kontrol tubuh yang baik. Kelebihannya tampak pada keterampilan fisik dan aktivitas yang melibatkan gerakan, sementara keterbatasannya dapat muncul pada tugas yang menuntut pemrosesan verbal atau simbolik tinggi.</p>
                    <p><strong>Naturalist Intelligence</strong><br>Kecerdasan naturalis adalah kemampuan mengenali, membedakan, dan mengelompokkan unsur-unsur alam dan lingkungan sekitar. Individu dengan kecerdasan ini peka terhadap tanaman, hewan, dan fenomena alam serta menunjukkan perhatian terhadap lingkungan. Kelebihannya adalah kemampuan observasi dan klasifikasi alam, sedangkan keterbatasannya terletak pada penerapan yang lebih terbatas di luar konteks lingkungan hidup.</p>
                </td>
            </tr>
        </table>
        </div>
    </div>
</body>
</html>
