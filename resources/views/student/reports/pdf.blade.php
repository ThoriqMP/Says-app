<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Raport - {{ $report->student->nama_siswa }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; color: #1a1a1a; line-height: 1.4; font-size: 11pt; }
        
        /* Header Sayyidah School */
        .header { border-bottom: 3px double #2563eb; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .header table { width: 100%; border: none; }
        .logo { width: 80px; height: 80px; }
        .school-name { font-size: 20pt; font-weight: bold; color: #1e40af; text-transform: uppercase; margin: 0; }
        .school-info { font-size: 9pt; color: #4b5563; margin-top: 2px; }
        
        .report-title { text-align: center; margin: 20px 0; }
        .report-title h2 { margin: 0; font-size: 16pt; text-transform: uppercase; border-bottom: 1px solid #333; display: inline-block; padding: 0 20px; }
        
        /* Student Info Table */
        .student-info { margin-bottom: 20px; }
        .student-info table { width: 100%; font-size: 10pt; }
        .student-info td { padding: 3px 0; vertical-align: top; }
        
        /* Main Grades Table */
        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 8px; font-size: 10pt; }
        table.main-table th { background-color: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Probing Layout */
        .probing-container { width: 100%; }
        .probing-item { margin-bottom: 30px; page-break-inside: avoid; border: 1px solid #e5e7eb; padding: 15px; border-radius: 10px; }
        .probing-item h3 { margin: 0 0 10px 0; font-size: 12pt; color: #1e40af; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .probing-image-wrapper { text-align: center; margin-bottom: 15px; }
        .probing-image { max-width: 100%; max-height: 400px; border-radius: 5px; border: 1px solid #ddd; }
        .probing-description { font-size: 10pt; text-align: justify; color: #374151; line-height: 1.6; }
        
        /* Academic Intervals Table */
        .interval-table { margin-top: 20px; width: 60%; border-collapse: collapse; font-size: 9pt; }
        .interval-table th, .interval-table td { border: 1px solid #999; padding: 4px 8px; }
        .interval-header { font-weight: bold; background: #f9fafb; }
        
        /* Summary & Signatures */
        .summary-section { margin-top: 20px; border: 1px solid #000; padding: 10px; min-height: 80px; }
        .summary-title { font-weight: bold; text-decoration: underline; margin-bottom: 5px; font-size: 10pt; }
        
        .signature-container { margin-top: 30px; width: 100%; }
        .signature-box { width: 33%; text-align: center; vertical-align: top; font-size: 10pt; }
        .signature-space { height: 70px; position: relative; }
        .signature-img { max-height: 70px; max-width: 120px; }
        
        .footer-note { position: fixed; bottom: -0.5cm; left: 0; right: 0; font-size: 8pt; color: #999; text-align: center; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <table>
            <tr>
                <td width="15%">
                    @if($school && $school->logo_path)
                        <img src="{{ public_path('storage/' . $school->logo_path) }}" class="logo">
                    @else
                        <div class="logo" style="background: #eee; border-radius: 50%;"></div>
                    @endif
                </td>
                <td width="85%" style="padding-left: 15px;">
                    <div class="school-name">{{ $school->nama_sekolah ?? 'SAYYIDAH SCHOOL' }}</div>
                    <div class="school-info">
                        {{ $school->alamat ?? 'Alamat Sekolah Belum Diatur' }}<br>
                        Email: info@sayyidah.sch.id | Website: www.sayyidah.sch.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Student Information -->
    <div class="student-info">
        <table width="100%">
            <tr>
                <td width="18%">Nama Siswa</td>
                <td width="2%">:</td>
                <td width="35%" class="font-bold">{{ strtoupper($report->student->nama_siswa) }}</td>
                <td width="18%">Kelas</td>
                <td width="2%">:</td>
                <td width="25%">{{ $report->student->class ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nomor Induk (NIS)</td>
                <td>:</td>
                <td>{{ $report->student->nis ?? '-' }}</td>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $report->period }}</td>
            </tr>
            <tr>
                <td>Kategori Raport</td>
                <td>:</td>
                <td class="font-bold">{{ strtoupper($report->category->name) }}</td>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        <h2>LAPORAN HASIL PERKEMBANGAN SISWA</h2>
    </div>

    <!-- Content Based on Category -->
    @if($report->category->name === 'Akademik')
        <table class="main-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Mata Pelajaran</th>
                    <th width="10%">PTS</th>
                    <th width="10%">PAS</th>
                    <th width="10%">Remedial</th>
                    <th width="10%">Harian</th>
                    <th width="10%">Akhir</th>
                    <th width="10%">Predikat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->grades as $index => $grade)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $grade->subject->name }}</td>
                        <td class="text-center">{{ $grade->score_pts ?? '-' }}</td>
                        <td class="text-center">{{ $grade->score_pas ?? '-' }}</td>
                        <td class="text-center">{{ $grade->score_remedial ?? '-' }}</td>
                        <td class="text-center">{{ $grade->score_harian ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $grade->score ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $grade->predicate ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Interval Predikat -->
        <div style="margin-top: 20px;">
            <div class="font-bold" style="font-size: 10pt; margin-bottom: 5px;">Tabel Interval Predikat Berdasarkan KKM (75):</div>
            <table class="interval-table">
                <tr class="interval-header">
                    <td class="text-center">Interval Nilai</td>
                    <td class="text-center">Predikat</td>
                    <td class="text-center">Keterangan</td>
                </tr>
                <tr>
                    <td class="text-center">93 - 100</td>
                    <td class="text-center">A</td>
                    <td>Sangat Baik</td>
                </tr>
                <tr>
                    <td class="text-center">84 - 92</td>
                    <td class="text-center">B</td>
                    <td>Baik</td>
                </tr>
                <tr>
                    <td class="text-center">75 - 83</td>
                    <td class="text-center">C</td>
                    <td>Cukup</td>
                </tr>
                <tr>
                    <td class="text-center">< 75</td>
                    <td class="text-center">D</td>
                    <td>Perlu Bimbingan</td>
                </tr>
            </table>
        </div>

    @elseif($report->category->name === 'Diniyah')
        <table class="main-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Materi / Surat</th>
                    <th width="20%">Rentang Ayat</th>
                    <th width="15%">Mutu (Predikat)</th>
                    <th width="25%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->grades as $index => $grade)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $grade->subject->name }}</td>
                        <td class="text-center">{{ $grade->ayat_range ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $grade->predicate ?? '-' }}</td>
                        <td>{{ $grade->description ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($report->category->name === 'Praktek Ibadah')
        <table class="main-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="55%">Gerakan / Bacaan Ibadah</th>
                    <th width="40%">Kriteria Penilaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->grades as $index => $grade)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $grade->subject->name }}</td>
                        <td class="text-center font-bold">{{ $grade->predicate ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($report->category->name === 'Probing')
        <div class="probing-container">
            @foreach($report->probingActivities as $activity)
                <div class="probing-item">
                    <h3>{{ $activity->activity_title ?? $activity->activity_name }}</h3>
                    @if($activity->image_path)
                        <div class="probing-image-wrapper">
                            <img src="{{ public_path('storage/' . $activity->image_path) }}" class="probing-image">
                        </div>
                    @endif
                    <div class="probing-description">
                        {!! nl2br(e($activity->description)) !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Catatan Wali Kelas -->
    <div class="summary-section">
        <div class="summary-title">Catatan Wali Kelas:</div>
        <div style="font-size: 10pt; font-style: italic; color: #333;">
            "{{ $report->summary_notes ?? 'Alhamdulillah, ananda menunjukkan perkembangan yang baik selama periode ini.' }}"
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-container">
        <table width="100%">
            <tr>
                <td class="signature-box">
                    <p>Mengetahui,</p>
                    <p>Orang Tua/Wali</p>
                    <div class="signature-space"></div>
                    <p><strong>( .................................... )</strong></p>
                </td>
                <td class="signature-box">
                    <p>&nbsp;</p>
                    <p>Wali Kelas</p>
                    <div class="signature-space">
                        @if($report->teacher && $report->teacher->signature_path)
                            <img src="{{ public_path('storage/' . $report->teacher->signature_path) }}" class="signature-img">
                        @endif
                    </div>
                    <p><strong>( {{ $report->teacher->name ?? '....................................' }} )</strong></p>
                </td>
                <td class="signature-box">
                    <p>Depok, {{ now()->translatedFormat('d F Y') }}</p>
                    <p>Kepala Sekolah</p>
                    <div class="signature-space">
                        @if($school && $school->signature_path)
                            <img src="{{ public_path('storage/' . $school->signature_path) }}" class="signature-img">
                        @endif
                    </div>
                    <p><strong>( {{ $school->pimpinan_nama ?? '....................................' }} )</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dokumen ini diterbitkan secara elektronik oleh {{ $school->nama_sekolah ?? 'Sayyidah School' }} melalui Sayyidah Apps.
    </div>
</body>
</html>
