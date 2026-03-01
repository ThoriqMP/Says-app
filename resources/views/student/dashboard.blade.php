@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Latest Invoice Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Tagihan Terakhir</h3>
                @if($latestInvoice)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No. Invoice: {{ $latestInvoice->no_invoice }}</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $latestInvoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ strtoupper($latestInvoice->status) }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('student.invoices') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Tagihan &rarr;</a>
                    </div>
                @else
                    <p class="text-gray-500">Tidak ada tagihan terbaru.</p>
                @endif
            </div>

            <!-- Latest Reports Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Raport Terbaru</h3>
                @if($latestReports->count() > 0)
                    <div class="space-y-3">
                        @foreach($latestReports as $report)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->category->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->period }}</p>
                                </div>
                                <a href="{{ route('student.reports.show', $report) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('student.reports') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Raport &rarr;</a>
                    </div>
                @else
                    <p class="text-gray-500">Belum ada raport tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
