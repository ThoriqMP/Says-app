@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->no_invoice)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Invoice #{{ $invoice->no_invoice }}
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('invoices.preview', $invoice) }}" 
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview PDF
                        </a>
                        <a href="{{ route('invoices.pdf', $invoice) }}" 
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                        <a href="{{ route('invoices.edit', $invoice) }}" 
                           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('invoices.index') }}" 
                           class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            Kembali
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Header Invoice -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Info Sekolah -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ $invoice->profilSekolah->nama_sekolah }}</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <p>{{ $invoice->profilSekolah->alamat }}</p>
                            @if($invoice->profilSekolah->bank_nama)
                                <p class="mt-2">
                                    <strong>Bank:</strong> {{ $invoice->profilSekolah->bank_nama }}<br>
                                    <strong>No. Rek:</strong> {{ $invoice->profilSekolah->no_rekening }}<br>
                                    <strong>Atas Nama:</strong> {{ $invoice->profilSekolah->atas_nama }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Info Invoice -->
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <div>
                                <strong>No. Invoice:</strong> {{ $invoice->no_invoice }}
                            </div>
                            <div>
                                <strong>Tanggal:</strong> {{ $invoice->tanggal_invoice->format('d/m/Y') }}
                            </div>
                            <div>
                                <strong>Jatuh Tempo:</strong> {{ $invoice->jatuh_tempo->format('d/m/Y') }}
                            </div>
                            <div>
                                <strong>Status:</strong> 
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($invoice->status == 'paid') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @elseif($invoice->status == 'sent') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                    @elseif($invoice->status == 'overdue') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Siswa -->
                <div class="mb-8">
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">Kepada:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p><strong>Siswa:</strong> {{ $invoice->siswa->nama_siswa }}</p>
                        <p><strong>Orang Tua:</strong> {{ $invoice->siswa->nama_orang_tua }}</p>
                        <p><strong>Alamat:</strong> {{ $invoice->siswa->alamat_tagihan }}</p>
                    </div>
                </div>

                <!-- Tabel Item -->
                <div class="mb-8">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuantitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($invoice->invoiceDetails as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->layanan->nama_layanan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->deskripsi_tambahan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->kuantitas }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">Rp {{ number_format($detail->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100">Grand Total:</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Terbilang -->
                <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Terbilang:</strong> {{ $terbilang }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <strong>Catatan:</strong><br>
                            Pembayaran dapat dilakukan melalui transfer bank ke rekening yang tercantum di atas.
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="mb-8">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $invoice->profilSekolah->nama_sekolah }}
                            </p>
                            @if($invoice->profilSekolah->pimpinan_nama)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-12">
                                    <strong>{{ $invoice->profilSekolah->pimpinan_nama }}</strong><br>
                                    Pimpinan
                                </p>
                                @if($invoice->profilSekolah->signature_path)
                                    <div class="mt-4">
                                        <img src="{{ Storage::url($invoice->profilSekolah->signature_path) }}" alt="Tanda Tangan" class="h-16 object-contain inline-block">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
