@extends('layouts.app')

@section('title', 'Detail Asesmen Psikologi')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                        <a href="{{ route('assessments.edit', $assessment) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Edit
                        </a>
                        <a href="{{ route('assessments.pdf', $assessment) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Download PDF
                        </a>
                        <a href="{{ route('assessments.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Data Subjek</h3>
                        <p class="text-sm">Nama: {{ $assessment->subject->name }}</p>
                        @if($assessment->subject->age)
                            <p class="text-sm">Usia: {{ $assessment->subject->age }}</p>
                        @endif
                        @if($assessment->subject->gender)
                            <p class="text-sm">Jenis Kelamin: {{ $assessment->subject->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        @endif
                        @if($assessment->subject->phone)
                            <p class="text-sm">No. HP: {{ $assessment->subject->phone }}</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Info Asesmen</h3>
                        <p class="text-sm">Tanggal Tes: {{ $assessment->test_date->format('d/m/Y') }}</p>
                        <p class="text-sm">Psikolog: {{ $assessment->psychologist_name }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Personality</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Tipe</th>
                                    <th class="px-4 py-2 text-left">Label</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'personality') as $score)
                                    <tr>
                                        <td class="px-4 py-2">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">{{ $score->label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Love Language</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Tipe</th>
                                    <th class="px-4 py-2 text-left">Label</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'love_language') as $score)
                                    <tr>
                                        <td class="px-4 py-2">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">{{ $score->label }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Multiple Intelligence</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Aspek</th>
                                    <th class="px-4 py-2 text-left">Skor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($assessment->scores->where('category', 'multiple_intelligence') as $score)
                                    <tr>
                                        <td class="px-4 py-2">{{ $score->aspect_name }}</td>
                                        <td class="px-4 py-2">{{ $score->score_value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($assessment->talentsMapping)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Talents Mapping</h3>
                        <p class="text-sm">Brain Dominance: {{ $assessment->talentsMapping->brain_dominance }}</p>
                        <p class="text-sm">Social Dominance: {{ $assessment->talentsMapping->social_dominance }}</p>
                        <p class="text-sm">Skill Dominance: {{ $assessment->talentsMapping->skill_dominance }}</p>
                        <p class="text-sm mt-2">Cluster Strength: {{ $assessment->talentsMapping->cluster_strength }}</p>
                        <p class="text-sm">Personal Branding: {{ $assessment->talentsMapping->personal_branding }}</p>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium mb-1">Strengths</h4>
                                <p class="text-sm whitespace-pre-line">{{ $assessment->talentsMapping->strengths }}</p>
                            </div>
                            <div>
                                <h4 class="font-medium mb-1">Deficits</h4>
                                <p class="text-sm whitespace-pre-line">{{ $assessment->talentsMapping->deficits }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div x-cloak x-show="openPreview" 
                     @keydown.escape.window="openPreview = false"
                     @click.self="openPreview = false"
                     x-trap.noscroll="openPreview"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white w-full max-w-4xl mx-4 rounded-lg shadow-lg overflow-hidden">
                        <div class="flex justify-between items-center px-4 py-2 border-b relative z-10">
                            <h3 class="font-semibold">Preview Laporan</h3>
                            <button type="button" @click="openPreview = false" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-gray-500 text-white hover:bg-gray-600 transition">Tutup</button>
                        </div>
                        <div class="p-4 max-h-[80vh] relative z-0">
                            <iframe 
                                src="{{ route('assessments.pdf.view', $assessment) }}"
                                class="w-full h-[70vh]"
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
