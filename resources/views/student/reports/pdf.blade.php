<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Raport - {{ $report->student->nama_siswa }}</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; font-size: 12px; }
        .student-info { margin-bottom: 30px; }
        .student-info table { width: 100%; font-size: 14px; }
        .student-info td { padding: 5px 0; }
        .report-title { background: #f3f4f6; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .report-title h2 { margin: 0; font-size: 18px; color: #1f2937; }
        table.grades { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.grades th, table.grades td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; font-size: 13px; }
        table.grades th { background: #f9fafb; color: #4b5563; font-weight: bold; }
        .probing-item { margin-bottom: 40px; page-break-inside: avoid; }
        .probing-item h3 { margin: 0 0 10px 0; font-size: 16px; color: #111827; }
        .probing-item p { margin: 0 0 15px 0; font-size: 13px; color: #4b5563; line-height: 1.6; }
        .probing-item img { max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #e5e7eb; }
        .summary { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
        .summary h3 { font-size: 15px; color: #2563eb; margin-bottom: 10px; }
        .summary p { font-size: 13px; color: #374151; line-height: 1.6; font-style: italic; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SAYYIDAH APPS - RAPORT PERKEMBANGAN</h1>
        <p>Jl. Contoh No. 123, Kota, Provinsi | Telp: (021) 1234567 | Email: info@sayyidah.sch.id</p>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td width="15%"><strong>Nama Siswa</strong></td>
                <td width="2%">:</td>
                <td width="33%">{{ $report->student->nama_siswa }}</td>
                <td width="15%"><strong>Periode</strong></td>
                <td width="2%">:</td>
                <td width="33%">{{ $report->period }}</td>
            </tr>
            <tr>
                <td><strong>NIS</strong></td>
                <td>:</td>
                <td>{{ $report->student->nis ?? '-' }}</td>
                <td><strong>Kategori</strong></td>
                <td>:</td>
                <td>{{ $report->category->name }}</td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>:</td>
                <td>{{ $report->student->class ?? '-' }}</td>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td>{{ now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        <h2>Laporan Hasil {{ $report->category->name }}</h2>
    </div>

    @if($report->category->name === 'Probing')
        @foreach($report->probingActivities as $activity)
            <div class="probing-item">
                <h3>{{ $activity->activity_name }}</h3>
                <p>{{ $activity->description }}</p>
                @if($activity->image_path)
                    <img src="{{ public_path('storage/' . $activity->image_path) }}" alt="{{ $activity->activity_name }}">
                @endif
            </div>
        @endforeach
    @else
        <table class="grades">
            <thead>
                <tr>
                    <th width="40%">Mata Pelajaran / Bidang Pengembangan</th>
                    <th width="10%" style="text-align: center;">Nilai</th>
                    <th width="50%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->grades as $grade)
                    <tr>
                        <td>{{ $grade->subject->name }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $grade->score }}</td>
                        <td>{{ $grade->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="summary">
        <h3>Catatan Guru / Pembimbing:</h3>
        <p>{{ $report->summary_notes ?? 'Tidak ada catatan tambahan.' }}</p>
    </div>

    <div style="margin-top: 50px; page-break-inside: avoid;">
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    <p style="margin-bottom: 5px;">Mengetahui,</p>
                    <div style="height: 80px; margin-bottom: 5px; position: relative; display: flex; align-items: center; justify-content: center;">
                        @if($report->teacher && $report->teacher->signature_path)
                            <img src="{{ public_path('storage/' . $report->teacher->signature_path) }}" style="max-height: 80px; max-width: 150px;">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    </div>
                    <p style="margin-top: 0; margin-bottom: 2px;"><strong>({{ $report->teacher->name ?? '....................................' }})</strong></p>
                    <p style="font-size: 11px; margin-top: 0;">{{ $report->teacher->role ?? 'Kepala Sekolah / Wali Kelas' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak secara otomatis melalui Sayyidah Apps pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
