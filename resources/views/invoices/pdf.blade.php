<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->no_invoice }}</title>
    <style>
        @page {
            margin: 40px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
        }
        
        /* Header Section */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-logo {
            width: 80px;
            vertical-align: top;
        }
        .header-logo img {
            max-width: 70px;
            max-height: 70px;
        }
        .header-school {
            vertical-align: top;
            padding-left: 10px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .school-address {
            font-size: 12px;
            width: 80%;
        }
        .header-invoice {
            vertical-align: bottom;
            text-align: right;
            width: 200px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }
        .color-bars {
            display: flex;
            justify-content: flex-end;
            gap: 3px;
        }
        .bar {
            width: 35px;
            height: 12px;
            border: 1px solid #333;
            border-radius: 3px;
            display: inline-block;
            margin-left: 2px;
        }
        .bar-1 { background-color: #7B346C; }
        .bar-2 { background-color: #ff00ff; }
        .bar-3 { background-color: #ff00ff; }
        
        .header-line {
            border-top: 2px solid #7B346C;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        /* Info Section */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-left {
            width: 60%;
            vertical-align: top;
        }
        .info-right {
            width: 40%;
            vertical-align: top;
        }
        .info-row {
            margin-bottom: 2px;
        }
        .info-label {
            display: inline-block;
            width: 90px;
        }
        
        /* Items Table - Modern Design (Reverted) */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }
        .items-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            background-color: #7B346C; /* Custom Purple */
            color: #ffffff;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #5a264b;
        }
        .items-table td {
            padding: 8px 10px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            border-right: 1px solid #f9f9f9;
        }
        .items-table td:last-child {
            border-right: none;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .col-qty { width: 8%; text-align: center; }
        .col-info { width: 52%; }
        .col-price { width: 20%; text-align: right; }
        .col-total { width: 20%; text-align: right; }
        
        /* Empty Row Styling */
        .empty-row td {
            height: 30px; /* Adjust height as needed */
            border-bottom: 1px solid #f0f0f0;
        }

        .grand-total-row td {
            background-color: #7B346C;
            color: #ffffff;
            font-weight: bold;
            padding: 10px;
            border: none;
            font-size: 12px;
        }
        .grand-total-label {
            text-align: right;
            padding-right: 15px;
        }

        /* Footer Section - Flow Naturally */
        .footer-wrapper {
            margin-top: 20px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
        }
        .terbilang-section {
            width: 65%;
            vertical-align: top;
            padding-right: 20px;
        }
        .terbilang-text {
            font-weight: bold;
            font-style: italic;
            color: #666; /* Faded black/gray */
        }
        .signature-section {
            width: 35%;
            text-align: center;
            vertical-align: top;
        }
        .pimpinan-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        /* Bottom Center Info */
        .bottom-info {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #333;
        }
        .bank-info {
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($invoice->profilSekolah->logo_path)
                    <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->logo_path) }}" alt="Logo">
                @else
                    <div style="width: 80px; height: 80px; border: 1px dashed #ccc; text-align: center; line-height: 80px;">Logo</div>
                @endif
            </td>
            <td class="header-school">
                <div class="school-name">{{ $invoice->profilSekolah->nama_sekolah }}</div>
                <div class="school-address">{{ $invoice->profilSekolah->alamat }}</div>
            </td>
            <td class="header-invoice">
                <div class="invoice-title">INVOICE</div>
                <div>
                    <span class="bar bar-1"></span>
                    <span class="bar bar-2"></span>
                    <span class="bar bar-3"></span>
                </div>
            </td>
        </tr>
    </table>
    
    <div class="header-line"></div>

    <!-- Info Section -->
    <table class="info-table">
        <tr>
            <td class="info-left">
                <div style="margin-bottom: 5px;">Kepada Yth,</div>
                <div style="font-weight: bold; margin-bottom: 2px;">Orang Tua Ananda {{ $invoice->siswa->nama_siswa }}</div>
                <div>Di Tempat.</div>
            </td>
            <td class="info-right">
                <div class="info-row">
                    <span class="info-label">No. Invoice</span>
                    <span>: {{ $invoice->no_invoice }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span>: {{ $invoice->tanggal_invoice->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jatuh Tempo</span>
                    <span>: {{ $invoice->jatuh_tempo->format('d F Y') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="col-qty">Kuantitas</th>
                <th class="col-info">Informasi</th>
                <th class="col-price">Biaya per Unit</th>
                <th class="col-total">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->invoiceDetails as $detail)
                <tr>
                    <td class="col-qty">{{ $detail->kuantitas }}</td>
                    <td class="col-info">
                        {{ $detail->deskripsi_tambahan ?? $detail->layanan->nama_layanan }}
                    </td>
                    <td class="col-price">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="col-total">Rp{{ number_format($detail->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Empty Rows to Fill Space -->
            @php
                $rowCount = count($invoice->invoiceDetails);
            @endphp
            @for($i = 0; $i < (5 - $rowCount); $i++)
                <tr class="empty-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor

            <!-- Grand Total -->
            <tr class="grand-total-row">
                <td colspan="3" class="grand-total-label">Grand Total Biaya</td>
                <td>Rp{{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Wrapper (Sticky Bottom) -->
    <div class="footer-wrapper">
        <table class="footer-table">
            <tr>
                <td class="terbilang-section">
                    <div>
                        <span style="display: inline-block; width: 60px;">Terbilang</span>
                        <span class="terbilang-text">: &nbsp; {{ $terbilang }}</span>
                    </div>
                </td>
                <td class="signature-section">
                    <div style="margin-bottom: 10px;">Yang mengajukan,</div>
                    
                    @if($invoice->profilSekolah->signature_path)
                        <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->signature_path) }}" 
                             alt="Signature" 
                             style="max-width: 100px; max-height: 80px; margin: 5px auto; display: block;">
                    @else
                        <div style="height: 60px;"></div> <!-- Space for signature if missing -->
                    @endif
                    
                    <div class="pimpinan-name">{{ $invoice->profilSekolah->pimpinan_nama ?? 'Pimpinan' }}</div>
                    <div>Pimpinan</div>
                </td>
            </tr>
        </table>

        <!-- Bottom Info -->
        <div class="bottom-info">
            <div>Pembayaran invoice ini dapat dilakukan via transfer</div>
            @if($invoice->profilSekolah->bank_nama)
                <div class="bank-info">
                    {{ $invoice->profilSekolah->bank_nama }} | {{ $invoice->profilSekolah->no_rekening }} | {{ strtoupper($invoice->profilSekolah->atas_nama) }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
