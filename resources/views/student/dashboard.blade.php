@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-[32px] p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-2">Selamat Datang, {{ $student->nama_siswa }}! 👋</h1>
                <p class="text-blue-100 text-lg opacity-90">NIS: {{ $student->nis }} | Kelas: {{ $student->class }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-2xl text-sm font-semibold border border-white/10">
                        Semester Genap 2024/2025
                    </div>
                    <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-2xl text-sm font-semibold border border-white/10">
                        Status: Aktif
                    </div>
                </div>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl"></div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[24px] border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Raport</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $latestReports->count() }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[24px] border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-green-50 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tagihan Terakhir</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($latestInvoice->grand_total ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[24px] border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kehadiran</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">98%</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[24px] border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4 text-gray-400">
            <div class="h-12 w-12 bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider">Poin Karakter</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">450</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Raport Section -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">Raport Terbaru</h3>
                    <a href="{{ route('student.reports') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua &rarr;</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($latestReports as $report)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-[28px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/40 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-400 bg-gray-50 dark:bg-gray-700 px-3 py-1 rounded-full uppercase tracking-widest">{{ $report->created_at->format('d M Y') }}</span>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $report->category->name }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">{{ $report->period }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('student.reports.show', $report) }}" class="flex-1 text-center py-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl font-bold hover:bg-blue-600 hover:text-white transition">Detail</a>
                                <a href="{{ route('student.reports.pdf', $report) }}" class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-gray-50 dark:bg-gray-900/50 rounded-[28px] border-2 border-dashed border-gray-200 dark:border-gray-700 text-gray-400">
                            Belum ada raport yang diterbitkan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Content (Right) -->
        <div class="space-y-8">
            <!-- Tagihan Widget -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6">Informasi Pembayaran</h3>
                @if($latestInvoice)
                    <div class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-[24px]">
                            <p class="text-sm font-bold text-gray-400 uppercase mb-1">Total Tagihan</p>
                            <p class="text-3xl font-black text-gray-900 dark:text-white">Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}</p>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $latestInvoice->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $latestInvoice->status }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">Jatuh Tempo: {{ $latestInvoice->jatuh_tempo ? $latestInvoice->jatuh_tempo->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('student.invoices.show', $latestInvoice) }}" class="block w-full py-4 bg-blue-600 text-white rounded-2xl font-bold text-center shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition active:scale-95">
                            Lihat Rincian Tagihan
                        </a>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <div class="h-16 w-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-bold">Semua tagihan sudah lunas!</p>
                    </div>
                @endif
            </div>

            <!-- Quick Links / Help -->
            <div class="bg-indigo-900 rounded-[32px] p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-2 text-white">Butuh Bantuan?</h3>
                    <p class="text-indigo-200 text-sm mb-6 leading-relaxed">Hubungi admin sekolah untuk informasi lebih lanjut mengenai akademik dan pembayaran.</p>
                    <button @click="$dispatch('toggle-admin-chat')" class="w-full py-4 bg-white text-indigo-900 rounded-2xl font-bold hover:bg-indigo-50 transition active:scale-95">
                        Hubungi Admin
                    </button>
                </div>
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
