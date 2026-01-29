@extends('layouts.app')

@section('title', 'Edit Asesmen Psikologi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Asesmen Psikologi</h2>
                    <a href="{{ route('assessments.show', $assessment) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terjadi kesalahan</h3>
                                <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('assessments.update', $assessment) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="subject_id" value="{{ $assessment->subject_id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Data Subjek</h3>
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
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Info Asesmen</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tes</label>
                                    <input type="date" name="test_date" 
                                           value="{{ old('test_date', $assessment->test_date->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Psikolog</label>
                                    <input type="text" name="psychologist_name" 
                                           value="{{ old('psychologist_name', $assessment->psychologist_name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Personality</h3>
                        <div class="space-y-4">
                            @php
                                $personalityAspects = ['Sanguinis', 'Koleris', 'Melankolis', 'Plegmatis'];
                                $labels = ['TD', 'KD', 'AD', 'D', 'SD'];
                            @endphp
                            @foreach($personalityAspects as $aspect)
                                <div>
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $aspect }}</div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($labels as $label)
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="personality[{{ $aspect }}]"
                                                       value="{{ $label }}"
                                                       @checked(($personalityScores[$aspect] ?? null) === $label)
                                                       class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span class="ml-1 text-xs">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Love Language</h3>
                        <div class="space-y-4">
                            @php
                                $loveAspects = ['Words of Affirmation', 'Quality Time', 'Receiving Gifts', 'Acts of Service', 'Physical Touch'];
                            @endphp
                            @foreach($loveAspects as $aspect)
                                <div>
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $aspect }}</div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($labels as $label)
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="love_language[{{ $aspect }}]"
                                                       value="{{ $label }}"
                                                       @checked(($loveLanguageScores[$aspect] ?? null) === $label)
                                                       class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span class="ml-1 text-xs">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Multiple Intelligence</h3>
                        <div class="space-y-4">
                            @php
                                $miAspects = ['Linguistic', 'Logical-Mathematical', 'Visual-Spatial', 'Musical', 'Bodily-Kinesthetic', 'Interpersonal', 'Intrapersonal', 'Naturalist'];
                            @endphp
                            @foreach($miAspects as $aspect)
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                    <div class="w-full sm:w-48 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $aspect }}
                                    </div>
                                    <div class="w-full sm:w-24">
                                        <input type="number" min="0" max="50"
                                               name="multiple_intelligence[{{ $aspect }}]"
                                               value="{{ old('multiple_intelligence.'.$aspect, $multipleIntelligenceScores[$aspect] ?? null) }}"
                                               class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Talents Mapping</h3>
                        @php
                            $tm = $assessment->talentsMapping;
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Brain Dominance</label>
                                <input type="text" name="talents[brain_dominance]" 
                                       value="{{ old('talents.brain_dominance', $tm->brain_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Social Dominance</label>
                                <input type="text" name="talents[social_dominance]" 
                                       value="{{ old('talents.social_dominance', $tm->social_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Dominance</label>
                                <input type="text" name="talents[skill_dominance]" 
                                       value="{{ old('talents.skill_dominance', $tm->skill_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Strengths</label>
                                <textarea name="talents[strengths]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('talents.strengths', $tm->strengths ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deficits</label>
                                <textarea name="talents[deficits]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('talents.deficits', $tm->deficits ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cluster Strength</label>
                                <input type="text" name="talents[cluster_strength]"
                                       value="{{ old('talents.cluster_strength', $tm->cluster_strength ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personal Branding</label>
                                <input type="text" name="talents[personal_branding]"
                                       value="{{ old('talents.personal_branding', $tm->personal_branding ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            Update Asesmen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
