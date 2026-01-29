@extends('layouts.app')

@section('title', 'Asesmen Psikologis')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <!-- Search -->
            <form action="{{ route('psychological-assessments.index') }}" method="GET" class="w-full md:max-w-xl relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition shadow-sm" 
                       placeholder="Pencarian nama">
            </form>

            <!-- Create Button -->
            <a href="{{ route('psychological-assessments.create') }}" 
               class="whitespace-nowrap px-6 py-2.5 bg-[#2563eb] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-sm font-medium rounded-full transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Asesmen Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($assessments as $assessment)
                <div class="bg-white dark:bg-gray-800 rounded-[20px] border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                    
                    <!-- Row 1: Identitas & Usia -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $assessment->subject->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $assessment->test_date->format('d F Y') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-0.5">Usia</div>
                            <div class="flex items-baseline justify-end gap-1">
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $assessment->subject->age ?? '-' }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tahun</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Asesor & Status -->
                    <div class="flex justify-between items-start mb-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <div>
                            <div class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Asesor</div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $assessment->psychologist_name }}</span>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <div class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Status Kelengkapan</div>
                            <div class="flex flex-wrap justify-end gap-1.5 max-w-[200px]">
                                @php
                                    $p = $assessment->psychologicalAssessment;
                                    $scores = $assessment->scores->groupBy('category');
                                    $tm = $assessment->talentsMapping;

                                    $hasPsych = $p && $p->cognitive_verbal_score;
                                    $hasPers = isset($scores['personality']);
                                    $hasTM = $tm && $tm->brain_dominance;
                                    
                                    $sections = [];
                                    if ($hasPsych) $sections[] = 'Psikologis';
                                    if ($hasPers) $sections[] = 'Personal';
                                    if ($hasTM) $sections[] = 'Talent';
                                @endphp

                                @if(count($sections) > 0)
                                    @foreach($sections as $section)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">
                                            {{ $section }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400">
                                        Mohon lengkapi laporan ini
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Action Buttons -->
                    <div class="mt-auto grid grid-cols-4 gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('psychological-assessments.show', $assessment) }}" 
                           class="h-9 flex items-center justify-center gap-1.5 rounded-lg bg-slate-800 dark:bg-slate-700 text-white hover:bg-slate-900 dark:hover:bg-slate-600 transition text-xs font-medium"
                           title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="hidden xl:inline">Lihat</span>
                        </a>
                        
                        <a href="{{ route('psychological-assessments.pdf', $assessment) }}" target="_blank"
                           class="h-9 flex items-center justify-center gap-1.5 rounded-lg bg-blue-500 dark:bg-blue-600 text-white hover:bg-blue-600 dark:hover:bg-blue-700 transition text-xs font-medium"
                           title="Unduh PDF">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="hidden xl:inline">Unduh</span>
                        </a>

                        <a href="{{ route('psychological-assessments.edit', $assessment) }}" 
                           class="h-9 flex items-center justify-center gap-1.5 rounded-lg bg-amber-500 dark:bg-amber-600 text-white hover:bg-amber-600 dark:hover:bg-amber-700 transition text-xs font-medium"
                           title="Edit Data">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="hidden xl:inline">Edit</span>
                        </a>

                        <form action="{{ route('psychological-assessments.destroy', $assessment) }}" method="POST" class="m-0 block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus asesmen ini? Data yang dihapus tidak dapat dikembalikan.')"
                                    class="h-9 w-full flex items-center justify-center gap-1.5 rounded-lg bg-red-600 dark:bg-red-700 text-white hover:bg-red-700 dark:hover:bg-red-800 transition text-xs font-medium"
                                    title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span class="hidden xl:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center py-12 px-4 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-[20px] bg-white dark:bg-gray-800 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-gray-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada asesmen</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-sm">Mulai dengan membuat asesmen psikologis baru untuk subjek.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection