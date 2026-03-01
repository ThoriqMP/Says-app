@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Tagihan & Pembayaran</h2>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Pantau status pembayaran uang sekolah dan layanan lainnya.</p>
    </div>

    <div class="space-y-6">
        @forelse($invoices as $invoice)
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-start gap-5">
                            <div class="h-16 w-16 bg-blue-50 dark:bg-blue-900/40 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Invoice #{{ $invoice->no_invoice }}</h3>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $invoice->status === 'paid' ? 'bg-green-100/80 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100/80 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                                        {{ $invoice->status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Diterbitkan pada {{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-8 items-center">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                                <p class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</p>
                            </div>
                            <div class="hidden lg:block">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jatuh Tempo</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-span-2 lg:col-span-1 flex gap-2">
                                <a href="{{ route('student.invoices.show', $invoice) }}" class="flex-1 lg:flex-none px-6 py-3 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black text-sm text-center hover:bg-blue-600 transition-colors active:scale-95">
                                    Detail
                                </a>
                                @if($invoice->status !== 'paid')
                                    <button class="flex-1 lg:flex-none px-6 py-3 bg-blue-600 text-white rounded-2xl font-black text-sm text-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20 active:scale-95">
                                        Bayar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                <div class="h-24 w-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Tidak Ada Tagihan</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Semua tagihan Anda sudah terbayar atau belum ada tagihan baru untuk periode ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
