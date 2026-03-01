@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Raport Saya</h2>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Lihat dan unduh laporan hasil perkembangan belajar Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($reports as $report)
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/40 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="px-4 py-1.5 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $report->created_at->format('M Y') }}</span>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $report->category->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-sm mb-4">{{ $report->period }}</p>

                    <div class="flex items-center gap-2 mb-8 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-xs">
                            {{ substr($report->teacher->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Dibuat Oleh</p>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate">{{ $report->teacher->name ?? 'Admin Sekolah' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('student.reports.show', $report) }}" class="block w-full py-4 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black text-center hover:bg-blue-600 transition-colors duration-300 active:scale-95">
                            Lihat Detail
                        </a>
                        <a href="{{ route('student.reports.pdf', $report) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl font-black text-center hover:bg-blue-100 transition-colors duration-300 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                    <div class="h-24 w-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Belum Ada Raport</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Laporan hasil belajar Anda akan muncul di sini setelah diterbitkan oleh guru atau admin.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
