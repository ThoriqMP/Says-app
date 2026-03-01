@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Daftar Raport Saya</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($reports as $report)
                    <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $report->category->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->period }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('student.reports.show', $report) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Detail</a>
                            <a href="{{ route('student.reports.pdf', $report) }}" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Unduh PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">Belum ada data raport tersedia.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
