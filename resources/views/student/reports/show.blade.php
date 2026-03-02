@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ auth()->user()->role === 'student' ? route('student.reports') : route('admin.reports.index') }}" class="h-10 w-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase tracking-widest">Detail Laporan</span>
            </div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-2">{{ $report->category->name }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg font-bold">{{ $report->period }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reports.pdf', $report) }}" class="flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-10">
            @if($report->category->name === 'Probing')
                <div class="space-y-8">
                    @foreach($report->probingActivities as $activity)
                        <div class="bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-500 group">
                            @if($activity->image_path)
                                <div class="relative h-64 sm:h-96 overflow-hidden">
                                    <img src="{{ asset('storage/' . $activity->image_path) }}" alt="{{ $activity->activity_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                </div>
                            @endif
                            <div class="p-6 sm:p-10">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="h-2 w-10 bg-blue-600 rounded-full"></div>
                                    <h4 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">{{ $activity->activity_name }}</h4>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg leading-relaxed whitespace-pre-line font-medium">{{ $activity->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-[40px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 sm:px-10 py-4 sm:py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mata Pelajaran / Subjek</th>
                                    <th class="px-4 sm:px-10 py-4 sm:py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Nilai Akhir</th>
                                    <th class="px-4 sm:px-10 py-4 sm:py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @foreach($report->grades as $grade)
                                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                        <td class="px-4 sm:px-10 py-6 sm:py-8">
                                            <div class="flex items-center gap-3 sm:gap-4">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 bg-blue-50 dark:bg-blue-900/40 rounded-lg sm:rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm sm:text-base">
                                                    {{ substr($grade->subject->name, 0, 1) }}
                                                </div>
                                                <span class="text-base sm:text-lg font-black text-gray-900 dark:text-white">{{ $grade->subject->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-10 py-6 sm:py-8 text-center">
                                            <div class="inline-flex items-center justify-center h-10 w-10 sm:h-14 sm:w-14 rounded-xl sm:rounded-2xl bg-gray-900 dark:bg-gray-700 text-white font-black text-base sm:text-xl shadow-lg">
                                                {{ $grade->score }}
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-10 py-6 sm:py-8">
                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium italic line-clamp-2 sm:line-clamp-none">{{ $grade->description ?: '-' }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Summary Card -->
            <div class="bg-indigo-900 rounded-[40px] p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-12 w-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h4 class="text-xl font-black uppercase tracking-wider">Kesimpulan Guru</h4>
                    </div>
                    <div class="text-indigo-100 text-lg leading-relaxed font-medium italic">
                        "{{ $report->summary_notes ?? 'Teruslah semangat dalam belajar dan kembangkan potensimu!' }}"
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-white/10">
                        <div class="flex flex-col gap-4">
                            @if($report->teacher && $report->teacher->signature_path)
                                <img src="{{ Storage::url($report->teacher->signature_path) }}" alt="Tanda Tangan Guru" class="h-16 w-auto object-contain bg-white/10 rounded-lg p-1">
                            @endif
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 bg-white rounded-2xl flex items-center justify-center text-indigo-900 font-black">
                                    {{ substr($report->teacher->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black">{{ $report->teacher->name ?? 'Admin Sekolah' }}</p>
                                    <p class="text-xs text-indigo-300 font-bold uppercase tracking-widest">{{ $report->teacher->role ?? 'OFFICER' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Quick Action -->
            <div class="bg-white dark:bg-gray-800 rounded-[40px] p-10 border border-gray-100 dark:border-gray-700 shadow-sm text-center">
                <h4 class="text-xl font-black text-gray-900 dark:text-white mb-4">Ada Pertanyaan?</h4>
                <p class="text-gray-500 dark:text-gray-400 mb-8 font-medium">Jika ada ketidaksesuaian data pada raport, silakan hubungi admin sekolah.</p>
                <button @click="$dispatch('toggle-admin-chat')" class="w-full py-4 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl font-black hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                    Chat Admin
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
