@extends('layouts.app')

@section('title', 'Daftar Invoice')

@section('content')
<div class="py-12" x-data="{ showPreviewModal: false, previewUrl: '' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Invoice</h2>
                        <a href="{{ route('invoices.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Invoice Baru
                        </a>
                    </div>

                    <!-- Filter & Search Section -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                            <!-- Search -->
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                       placeholder="Cari invoice (No, Siswa, Ortu)...">
                            </div>

                            <!-- Month Filter -->
                            <div class="w-full lg:w-48">
                                <select name="month" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-8 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Semua Bulan</option>
                                    @php
                                        $bulanIndo = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                    @endphp
                                    @foreach($bulanIndo as $num => $name)
                                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="w-full lg:w-48">
                                <select name="status" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-8 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('invoices.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-500 transition flex items-center justify-center" title="Reset Filter">
                                   Reset
                                </a>
                                
                                <button type="submit" formaction="{{ route('invoices.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition flex items-center justify-center whitespace-nowrap">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Export CSV
                                </button>
                            </div>
                        </form>
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

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Grid Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($invoices as $invoice)
                        <div class="bg-white dark:bg-gray-800 rounded-[20px] border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col relative overflow-hidden">
                            <!-- Status Stripe -->
                            <div class="absolute top-0 left-0 w-1.5 h-full 
                                @if($invoice->status == 'paid') bg-green-500
                                @elseif($invoice->status == 'sent') bg-blue-500
                                @elseif($invoice->status == 'overdue') bg-red-500
                                @else bg-gray-300
                                @endif">
                            </div>

                            <div class="pl-3">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                            {{ $invoice->no_invoice }}
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-1" title="{{ $invoice->siswa->nama_siswa }}">
                                            {{ $invoice->siswa->nama_siswa }}
                                        </h3>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $invoice->siswa->nama_orang_tua }}
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                        @if($invoice->status == 'paid') bg-green-50 text-green-700 border border-green-200
                                        @elseif($invoice->status == 'sent') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($invoice->status == 'overdue') bg-red-50 text-red-700 border border-red-200
                                        @else bg-gray-50 text-gray-700 border border-gray-200
                                        @endif">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Tanggal</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->tanggal_invoice->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Jatuh Tempo</span>
                                        <span class="font-medium 
                                            @if($invoice->status != 'paid' && $invoice->jatuh_tempo < now()) text-red-600 font-bold
                                            @else text-gray-900 dark:text-gray-100
                                            @endif">
                                            {{ $invoice->jatuh_tempo->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="pt-3 border-t border-dashed border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                        <span class="text-gray-500 font-medium">Total</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-2 pt-2">
                                    <div class="flex gap-1">
                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-full transition" title="Lihat Detail">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button type="button" 
                                           @click="showPreviewModal = true; previewUrl = '{{ route('invoices.preview', $invoice) }}'"
                                           class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-full transition" title="Preview PDF">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('invoices.pdf', $invoice) }}" 
                                           class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-full transition" title="Download PDF">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                    
                                    <div class="flex gap-1 border-l border-gray-200 dark:border-gray-700 pl-2 ml-auto">
                                        <a href="{{ route('invoices.edit', $invoice) }}" 
                                           class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-full transition" title="Edit">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-full transition" title="Hapus">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-4 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada invoice</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Buat invoice baru untuk menagih pembayaran kepada siswa.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreviewModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showPreviewModal = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Preview Invoice</h3>
                    <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Body (Iframe) -->
                <div class="flex-1 bg-gray-100 dark:bg-gray-900 p-0 overflow-hidden relative">
                    <div x-show="!previewUrl" class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <iframe :src="previewUrl" class="w-full h-full border-0" x-show="previewUrl"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
