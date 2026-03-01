@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8" x-data="{ showUploadModal: false }">
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
                <button @click="showUploadModal = true" class="flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                    Bayar Sekarang
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-3xl text-green-700 dark:text-green-400 font-bold flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-3xl text-red-700 dark:text-red-400 font-bold flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

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

            <!-- Payment Proof Display if uploaded -->
            @if($invoice->payment_proof)
                <div class="bg-white dark:bg-gray-800 rounded-[40px] border border-gray-100 dark:border-gray-700 shadow-sm p-10">
                    <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-6 uppercase tracking-tight">Bukti Pembayaran Terupload</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-100 dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <img src="{{ asset('storage/' . $invoice->payment_proof) }}" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-500" @click="window.open('{{ asset('storage/' . $invoice->payment_proof) }}', '_blank')">
                        </div>
                        <div class="space-y-4">
                            <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-3xl border border-blue-100 dark:border-blue-800">
                                <p class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Tanggal Upload</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $invoice->payment_date->format('d M Y H:i') }}</p>
                            </div>
                            <p class="text-gray-500 italic font-medium px-2">Bukti pembayaran telah kami terima dan sedang dalam proses verifikasi oleh admin. Terima kasih.</p>
                        </div>
                    </div>
                </div>
            @endif
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
                        @if($invoice->status === 'paid')
                            LUNAS
                        @elseif($invoice->payment_proof)
                            VERIFIKASI
                        @else
                            BELUM BAYAR
                        @endif
                    </div>
                    <p class="text-white/80 font-bold text-lg">Jatuh Tempo: {{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d M Y') : '-' }}</p>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Bank Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[40px] p-10 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h4 class="text-xl font-black text-gray-900 dark:text-white mb-6">Metode Pembayaran</h4>
                <div class="space-y-4">
                    @if($schoolProfile && $schoolProfile->rekening_nomor)
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-3xl border border-gray-100 dark:border-gray-700 flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-sm">
                                    <span class="font-black text-blue-600 uppercase text-xs">{{ $schoolProfile->rekening_bank ?? 'BANK' }}</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Rekening</p>
                                    <p class="text-xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $schoolProfile->rekening_nomor }}</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Atas Nama</p>
                                <p class="font-black text-gray-900 dark:text-white uppercase">{{ $schoolProfile->rekening_nama }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Informasi rekening pembayaran belum tersedia. Silakan hubungi admin.</p>
                    @endif
                </div>
                <p class="mt-8 text-sm text-gray-500 font-bold italic leading-relaxed">Silakan upload bukti transfer melalui tombol "Bayar Sekarang" di atas untuk konfirmasi pembayaran.</p>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-800 rounded-[40px] w-full max-w-xl shadow-2xl overflow-hidden transform transition-all"
             @click.away="showUploadModal = false">
            
            <div class="p-10">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Upload Bukti</h3>
                    <button @click="showUploadModal = false" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('student.invoices.upload-payment-proof', $invoice) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div x-data="{ preview: null }">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4 ml-1">Pilih File Bukti Transfer (JPG, PNG, max 2MB)</label>
                        <div class="relative group aspect-[4/3] rounded-[32px] overflow-hidden bg-gray-50 dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center transition-all hover:border-blue-500 hover:bg-blue-50/30">
                            <template x-if="!preview">
                                <div class="text-center p-6">
                                    <div class="h-20 w-20 bg-blue-100 dark:bg-blue-900/40 rounded-3xl flex items-center justify-center text-blue-600 dark:text-blue-400 mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Klik untuk memilih file</p>
                                </div>
                            </template>
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-full object-cover">
                            </template>
                            <input type="file" name="payment_proof" required accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if(file) { preview = URL.createObjectURL(file); }">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="showUploadModal = false" class="flex-1 px-8 py-5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-3xl font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-8 py-5 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                            Kirim Bukti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

