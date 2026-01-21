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
                                            {{ $ayah->subject->age !== null ? $ayah->subject->age.' Tahun' : '-' }}
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

            <div class="kamus-section-title">
                Strength Typology (ST-30)
            </div>

            <table class="kamus-table">
                <tr>
                    <td>
                        <p><strong>ADMINISTRATOR (ADM)</strong>: Anda suka dengan keteraturan, terencana dan rapih dalam pengelolaan administrasi.</p>
                        <p><strong>AMBASSADOR (AMB)</strong>: Anda senang membina hubungan persahabatan, berkomunikasi, dan menjadi perwakilan organisasi.</p>
                        <p><strong>ANALYST (ANA)</strong>: Anda berpikiran analisis, senang data, dan suka menguraikan sesuatu ke bagian kecil.</p>
                        <p><strong>ARRANGER (ARR)</strong>: Anda senang mengatur sumber daya manusia untuk hasil optimum.</p>
                        <p><strong>CARETAKER (CAR)</strong>: Anda bisa merasakan perasaan orang lain sehingga senang merawat atau membantu orang lain.</p>
                        <p><strong>COMMANDER (CMD)</strong>: Anda keras, berani menghadapi konfrontasi dan mengambil alih tanggung jawab.</p>
                        <p><strong>COMMUNICATOR (COM)</strong>: Anda senang menjelaskan sesuatu baik lisan maupun tertulis, dan suka tampil di depan.</p>
                        <p><strong>CREATOR (CRE)</strong>: Anda punya banyak ide, berpikiran jauh kedepan dan strategis.</p>
                        <p><strong>DESIGNER (DES)</strong>: Anda punya banyak ide, kemampuan analisis, dan menyatakannya ke dalam gambar.</p>
                        <p><strong>DISTRIBUTOR (DIS)</strong>: Anda senang mengatur sumber daya, bertanggung jawab, dan pekerja keras.</p>
                        <p><strong>EDUCATOR (EDU)</strong>: Anda suka memajukan orang lain dengan mengajar, melatih, atau memberi nasehat.</p>
                        <p><strong>EVALUATOR (EVA)</strong>: Anda teliti sesuai aturan dan suka tugas analisis untuk membuktikan sesuatu.</p>
                        <p><strong>EXPLORER (EXP)</strong>: Anda senang mengumpulkan informasi dan mempelajari sesuatu melalui penelitian.</p>
                        <p><strong>INTERPRETER (INT)</strong>: Anda senang menjelaskan sesuatu dan memiliki daya analisis untuk mengartikan sesuatu.</p>
                        <p><strong>JOURNALIST (JOU)</strong>: Anda mudah menyesuaikan diri, senang menulis, dan menjelaskan sesuatu secara strategis.</p>
                    </td>
                    <td>
                        <p><strong>MARKETER (MAR)</strong>: Anda senang menonjolkan kelebihan, mengkomunikasikannya, dan menggali peluang pasar.</p>
                        <p><strong>MEDIATOR (MED)</strong>: Anda berani menghadapi konfrontasi untuk mengatasi dan menyelesaikan konflik.</p>
                        <p><strong>MOTIVATOR (MOT)</strong>: Anda suka memajukan orang lain dengan memberi panduan, semangat, atau inspirasi.</p>
                        <p><strong>OPERATOR (OPE)</strong>: Pekerja keras yang senang keteraturan dan melayani melalui perangkat kerja.</p>
                        <p><strong>PRODUCER (PRO)</strong>: Pekerja keras yang tidak sabar bertindak dan senang membuat ide menjadi produk nyata.</p>
                        <p><strong>QUALITY CONTROLLER (QCA)</strong>: Memegang teguh aturan, teliti, dan senang dengan tugas pengontrolan mutu.</p>
                        <p><strong>RESTORER (RES)</strong>: Berpikiran analitis, senang mendiagnosa, dan mengembalikan sesuatu ke fungsi semula.</p>
                        <p><strong>SAFEKEEPER (SAF)</strong>: Teliti, waspada, bertanggung jawab terkait keselamatan dan keamanan.</p>
                        <p><strong>SELECTOR (SLC)</strong>: Mengerti keunikan orang dan berani menentukan pilihan orang tepat untuk tugas tertentu.</p>
                        <p><strong>SELLER (SEL)</strong>: Senang meyakinkan orang lain dengan memelihara hubungan atau menonjolkan kehebatan produk.</p>
                        <p><strong>SERVER (SER)</strong>: Anda orang yang senang melayani dan mendahulukan orang lain.</p>
                        <p><strong>STRATEGIST (STR)</strong>: Memilih jalan terbaik mencapai tujuan melalui kemampuan analisis atau intuisi.</p>
                        <p><strong>SYTHESIZER (SYN)</strong>: Senang mengatur sumber daya dan mampu merangkum berbagai hal menjadi sesuatu yang baru.</p>
                        <p><strong>TREASURY (TRE)</strong>: Berpikiran analitis, teliti, teratur, dan senang dengan tugas pengelolaan keuangan.</p>
                        <p><strong>VISIONARY (VIS)</strong>: Dapat melihat jauh kedepan melampaui cakrawala secara intuisi atau perasaan.</p>
                    </td>
                </tr>
            </table>

            <div class="page-break"></div>

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
