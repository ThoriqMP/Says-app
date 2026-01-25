@extends('layouts.app')

@section('title', 'Detail Asesmen Psikologis')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ openPreview: false }">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Asesmen: {{ $assessment->subject->name }}
                    </h2>
                    <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                        <button type="button"
                                @click="openPreview = true"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto">
                            Preview Laporan
                        </button>
                        <a href="{{ route('psychological-assessments.pdf', $assessment) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Download PDF
                        </a>
                        <a href="{{ route('psychological-assessments.docx', $assessment) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Download DOCX
                        </a>
                        <a href="{{ route('psychological-assessments.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Data Subjek</h3>
                        <div class="space-y-1">
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">Nama:</span> {{ $assessment->subject->name }}</p>
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">Usia:</span> {{ $assessment->subject->precise_age }}</p>
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">Gender:</span> {{ $assessment->subject->gender === 'male' ? 'Laki-laki' : ($assessment->subject->gender === 'female' ? 'Perempuan' : '-') }}</p>
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">No. HP:</span> {{ $assessment->subject->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Info Asesmen</h3>
                        <div class="space-y-1">
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">Tanggal:</span> {{ $assessment->test_date->format('d/m/Y') }}</p>
                            <p class="text-sm"><span class="font-medium text-gray-500 w-24 inline-block">Psikolog:</span> {{ $assessment->psychologist_name }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $psychData = $assessment->psychologicalAssessment;
                @endphp

                <!-- New Psychological Data Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Aspek Kognitif -->
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-blue-600 text-white px-4 py-2 font-semibold text-sm uppercase">1. Aspek Kognitif</div>
                        <div class="p-4">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-2 py-2 text-left">Aspek</th>
                                        <th class="px-2 py-2 text-center">Score</th>
                                        <th class="px-2 py-2 text-center">Scale</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach(['Verbal', 'Numerical', 'Logical', 'Spatial'] as $aspect)
                                    <tr>
                                        <td class="px-2 py-2">Kemampuan {{ $aspect }}</td>
                                        <td class="px-2 py-2 text-center font-medium">{{ $psychData->{'cognitive_'.strtolower($aspect).'_score'} ?? '-' }}</td>
                                        <td class="px-2 py-2 text-center font-medium">{{ $psychData->{'cognitive_'.strtolower($aspect).'_scale'} ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Aspek Potensi & Lainnya -->
                    <div class="space-y-6">
                        <!-- Potensi -->
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-blue-600 text-white px-4 py-2 font-semibold text-sm uppercase">2. Aspek Potensi</div>
                            <div class="p-4">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 uppercase">
                                        <tr>
                                            <th class="px-2 py-2 text-left">Aspek</th>
                                            <th class="px-2 py-2 text-center">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach(['Intellectual', 'Social', 'Emotional'] as $aspect)
                                        <tr>
                                            <td class="px-2 py-2">Potensi {{ $aspect }}</td>
                                            <td class="px-2 py-2 text-center font-medium">{{ $psychData->{'potential_'.strtolower($aspect).'_score'} ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- IQ & Maturity -->
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-indigo-600 text-white px-4 py-2 font-semibold text-sm uppercase">Taraf Kecerdasan & Kematangan</div>
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-center border-b pb-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Full Scale IQ</span>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $psychData->iq_full_scale ?? '-' }}</span>
                                        <span class="text-xs text-gray-500 block">{{ $psychData->iq_category ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Rekomendasi</span>
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $psychData->maturity_recommendation ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Personality</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'personality') as $score)
                                    <tr>
                                        <td class="px-4 py-2 font-medium">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $score->label }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Love Language</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'love_language') as $score)
                                    <tr>
                                        <td class="px-4 py-2 font-medium">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 text-xs rounded bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
                                                {{ $score->label }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Multiple Intelligence</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aspek</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visual</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'multiple_intelligence') as $score)
                                    <tr>
                                        <td class="px-4 py-2 font-medium">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">{{ $score->score_value }}</td>
                                        <td class="px-4 py-2 w-1/3">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                @php $width = min(100, ($score->score_value * 2)); @endphp
                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $width }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($assessment->talentsMapping)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Talents Mapping</h3>
                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm">
                                    <span class="text-xs text-gray-500 block">Brain Dominance</span>
                                    <span class="font-medium">{{ $assessment->talentsMapping->brain_dominance }}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm">
                                    <span class="text-xs text-gray-500 block">Social Dominance</span>
                                    <span class="font-medium">{{ $assessment->talentsMapping->social_dominance }}</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm">
                                    <span class="text-xs text-gray-500 block">Skill Dominance</span>
                                    <span class="font-medium">{{ $assessment->talentsMapping->skill_dominance }}</span>
                                </div>
                            </div>
                            
                            <p class="text-sm mt-2 mb-4"><span class="font-medium">Cluster Strength:</span> {{ $assessment->talentsMapping->cluster_strength }}</p>
                            <p class="text-sm mb-4"><span class="font-medium">Personal Branding:</span> {{ $assessment->talentsMapping->personal_branding }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium mb-2 text-green-600">Strengths</h4>
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm text-sm whitespace-pre-line border-l-4 border-green-500">{{ $assessment->talentsMapping->strengths }}</div>
                                </div>
                                <div>
                                    <h4 class="font-medium mb-2 text-red-600">Deficits</h4>
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm text-sm whitespace-pre-line border-l-4 border-red-500">{{ $assessment->talentsMapping->deficits }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div x-cloak x-show="openPreview" 
                     @keydown.escape.window="openPreview = false"
                     @click.self="openPreview = false"
                     x-trap.noscroll="openPreview"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white w-full max-w-4xl mx-4 rounded-lg shadow-lg overflow-hidden h-[90vh] flex flex-col">
                        <div class="flex justify-between items-center px-4 py-2 border-b relative z-10 bg-white">
                            <h3 class="font-semibold">Preview Laporan</h3>
                            <button type="button" @click="openPreview = false" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-gray-500 text-white hover:bg-gray-600 transition">Tutup</button>
                        </div>
                        <div class="flex-1 bg-gray-100 overflow-hidden relative z-0">
                            <iframe 
                                src="{{ route('psychological-assessments.pdf', ['assessment' => $assessment, 'preview' => 1]) }}"
                                class="w-full h-full"
                                frameborder="0">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
