@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ auth()->user()->role === 'student' ? route('student.invoices') : route('invoices.index') }}" class="h-10 w-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase tracking-widest">Detail Tagihan</span>
            </div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-2">Invoice #{{ $invoice->no_invoice }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg font-bold">Diterbitkan pada {{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d M Y') : '-' }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('student.invoices.pdf', $invoice) }}" class="flex items-center gap-2 px-8 py-4 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black shadow-lg shadow-black/10 hover:bg-blue-600 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh Invoice
            </a>
            @if($invoice->status !== 'paid')
                <button class="flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                    Bayar Sekarang
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-10">
            <!-- Items Table -->
            <div class="bg-white dark:bg-gray-800 rounded-[40px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Layanan / Item</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Harga Satuan</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Jumlah</th>
                                <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach($invoice->invoiceDetails as $item)
                                <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                    <td class="px-10 py-8">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 bg-blue-50 dark:bg-blue-900/40 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                                                {{ substr($item->layanan->nama_layanan ?? 'L', 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="text-lg font-black text-gray-900 dark:text-white block">{{ $item->layanan->nama_layanan ?? 'Layanan' }}</span>
                                                @if($item->deskripsi_tambahan)
                                                    <span class="text-xs text-gray-500">{{ $item->deskripsi_tambahan }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-8 text-right text-gray-600 dark:text-gray-400 font-bold">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-10 py-8 text-center text-gray-900 dark:text-white font-black">
                                        {{ $item->kuantitas }}
                                    </td>
                                    <td class="px-10 py-8 text-right text-gray-900 dark:text-white font-black text-lg">
                                        Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-900 dark:bg-gray-700 text-white">
                                <td colspan="3" class="px-10 py-8 text-right font-black uppercase tracking-widest text-gray-400">Total Keseluruhan</td>
                                <td class="px-10 py-8 text-right font-black text-2xl">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Status Card -->
            <div class="{{ $invoice->status === 'paid' ? 'bg-green-600' : 'bg-red-600' }} rounded-[40px] p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-black uppercase tracking-wider">Status Pembayaran</h4>
                    </div>
                    <div class="text-4xl font-black mb-2 uppercase tracking-tighter">
                        {{ $invoice->status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                    </div>
                    <p class="text-white/80 font-bold text-lg">Jatuh Tempo: {{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d M Y') : '-' }}</p>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Note Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[40px] p-10 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h4 class="text-xl font-black text-gray-900 dark:text-white mb-6">Metode Pembayaran</h4>
                <div class="space-y-4">
                    @if($schoolProfile && $schoolProfile->no_rekening)
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                            <div class="h-10 w-10 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center shadow-sm">
                                <span class="font-black text-blue-600">{{ $schoolProfile->bank_nama ?? 'BANK' }}</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">Transfer Bank</p>
                                <p class="font-black text-gray-900 dark:text-white">{{ $schoolProfile->no_rekening }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">a.n {{ $schoolProfile->atas_nama }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Informasi rekening pembayaran belum tersedia. Silakan hubungi admin.</p>
                    @endif
                </div>
                <p class="mt-8 text-sm text-gray-500 font-medium italic">Silakan hubungi admin setelah melakukan pembayaran untuk konfirmasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
