@extends('layouts.app')

@section('title', 'Edit Asesmen Psikologi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100" x-data="assessmentForm()">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Asesmen Psikologi</h2>
                    <a href="{{ route('psychological-assessments.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('psychological-assessments.update', $assessment->id) }}" @submit.prevent="submitForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Data Subjek</h3>
                            
                            <!-- Note: We are editing the assessment, but we display subject info. 
                                 For now, we assume subject info is managed via Subject module, 
                                 but user might want to verify DOB here. 
                                 If we want to allow editing Subject details here, we'd need to handle that in Controller.
                                 The current controller update method doesn't update Subject, only Assessment. 
                                 Wait, the user said "tambahkan untuk edit data juga".
                                 Usually this implies editing the *assessment data*. 
                                 However, since age depends on DOB, let's display DOB read-only or allow editing if I update controller.
                                 
                                 My controller update method currently DOES NOT update Subject. 
                                 I'll stick to editing Assessment fields. Subject info will be read-only for now to avoid complexity, 
                                 OR I can add subject update logic if needed. 
                                 Let's keep it simple: Read-only Name, but allow changing DOB logic if it affects age? 
                                 Actually, the Age is calculated dynamically. 
                                 Let's just show the subject info as read-only for safety, 
                                 but user might want to change the "Test Date" which affects "Age at Test".
                            -->

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                                    <input type="text" value="{{ $assessment->subject->name }}" disabled
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 mt-1">Data subjek tidak dapat diubah di sini.</p>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                                        <input type="date" value="{{ $assessment->subject->date_of_birth ? $assessment->subject->date_of_birth->format('Y-m-d') : '' }}" disabled
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usia Saat Tes</label>
                                        <input type="text" :value="calculated_age" readonly
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Info Asesmen</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tes</label>
                                    <input type="date" name="test_date" x-model="form.test_date" @change="calculateAge()"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Psikolog</label>
                                    <input type="text" name="psychologist_name" x-model="form.psychologist_name"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Asesmen Psikologis Inputs -->
                    <div class="mb-10 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Data Asesmen Psikologis</h3>
                        
                        <!-- Aspek Kognitif -->
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">1. Aspek Kognitif</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            @foreach(['Verbal', 'Numerical', 'Logical', 'Spatial'] as $aspect)
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <div class="text-sm font-medium mb-3">{{ $aspect }}</div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-xs text-gray-500">Score</label>
                                            <input type="number" name="psychological[cognitive_{{ strtolower($aspect) }}_score]" 
                                                   value="{{ old('psychological.cognitive_'.strtolower($aspect).'_score', $assessment->psychologicalAssessment->{'cognitive_'.strtolower($aspect).'_score'} ?? '') }}"
                                                   class="w-full px-2 py-1 text-sm border rounded">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Scale</label>
                                            <input type="number" name="psychological[cognitive_{{ strtolower($aspect) }}_scale]" 
                                                   value="{{ old('psychological.cognitive_'.strtolower($aspect).'_scale', $assessment->psychologicalAssessment->{'cognitive_'.strtolower($aspect).'_scale'} ?? '') }}"
                                                   class="w-full px-2 py-1 text-sm border rounded">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Aspek Potensi -->
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">2. Aspek Potensi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                             @foreach(['Intellectual', 'Social', 'Emotional'] as $aspect)
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <div class="text-sm font-medium mb-3">{{ $aspect }}</div>
                                    <div>
                                        <label class="text-xs text-gray-500">Score</label>
                                        <input type="number" name="psychological[potential_{{ strtolower($aspect) }}_score]" 
                                               value="{{ old('psychological.potential_'.strtolower($aspect).'_score', $assessment->psychologicalAssessment->{'potential_'.strtolower($aspect).'_score'} ?? '') }}"
                                               class="w-full px-2 py-1 text-sm border rounded">
                                    </div>
                                </div>
                             @endforeach
                        </div>

                        <!-- IQ & Maturity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">Taraf Kecerdasan (Full Scale)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">IQ Score</label>
                                        <input type="text" name="psychological[iq_full_scale]" placeholder="e.g. 110"
                                               value="{{ old('psychological.iq_full_scale', $assessment->psychologicalAssessment->iq_full_scale ?? '') }}"
                                               class="w-full px-3 py-2 border rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Category</label>
                                        <select name="psychological[iq_category]" class="w-full px-3 py-2 border rounded-lg">
                                            <option value="">Select Category</option>
                                            @foreach(['Very Superior', 'Superior', 'High Average', 'Average', 'Low Average', 'Borderline', 'Intellectually Deficient'] as $cat)
                                                <option value="{{ $cat }}" {{ (old('psychological.iq_category', $assessment->psychologicalAssessment->iq_category ?? '') == $cat) ? 'selected' : '' }}>
                                                    {{ $cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">Taraf Kematangan</h4>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Recommendation</label>
                                    <select name="psychological[maturity_recommendation]" class="w-full px-3 py-2 border rounded-lg">
                                        <option value="">Select Recommendation</option>
                                        @foreach(['Disarankan', 'Dipertimbangkan', 'Tidak Disarankan'] as $rec)
                                            <option value="{{ $rec }}" {{ (old('psychological.maturity_recommendation', $assessment->psychologicalAssessment->maturity_recommendation ?? '') == $rec) ? 'selected' : '' }}>
                                                {{ $rec }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                                       {{ (old('personality.'.$aspect, $scores['personality'][$aspect] ?? '') == $label) ? 'checked' : '' }}
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
                                                       {{ (old('love_language.'.$aspect, $scores['love_language'][$aspect] ?? '') == $label) ? 'checked' : '' }}
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
                                               x-model.number="mi['{{ $aspect }}']"
                                               class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                    </div>
                                    <div class="w-full sm:flex-1">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-purple-500 h-2 rounded-full" 
                                                 :style="`width: ${Math.min(100, (mi['{{ $aspect }}'] || 0) * 2)}%`">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Talents Mapping</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Brain Dominance</label>
                                <input type="text" name="talents[brain_dominance]" 
                                       value="{{ old('talents.brain_dominance', $assessment->talentsMapping->brain_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Social Dominance</label>
                                <input type="text" name="talents[social_dominance]" 
                                       value="{{ old('talents.social_dominance', $assessment->talentsMapping->social_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Dominance</label>
                                <input type="text" name="talents[skill_dominance]" 
                                       value="{{ old('talents.skill_dominance', $assessment->talentsMapping->skill_dominance ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Strengths</label>
                                <textarea name="talents[strengths]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('talents.strengths', $assessment->talentsMapping->strengths ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deficits</label>
                                <textarea name="talents[deficits]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('talents.deficits', $assessment->talentsMapping->deficits ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cluster Strength</label>
                                <input type="text" name="talents[cluster_strength]"
                                       value="{{ old('talents.cluster_strength', $assessment->talentsMapping->cluster_strength ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personal Branding</label>
                                <input type="text" name="talents[personal_branding]"
                                       value="{{ old('talents.personal_branding', $assessment->talentsMapping->personal_branding ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@php
    $miMap = [
        'Linguistic' => ['Linguistic', 'Linguistik'],
        'Logical-Mathematical' => ['Logical-Mathematical', 'Logika Matematika'],
        'Visual-Spatial' => ['Visual-Spatial', 'Visual Spasial'],
        'Musical' => ['Musical', 'Musikal'],
        'Bodily-Kinesthetic' => ['Bodily-Kinesthetic', 'Kinestetik'],
        'Interpersonal' => ['Interpersonal'],
        'Intrapersonal' => ['Intrapersonal'],
        'Naturalist' => ['Naturalist', 'Naturalis']
    ];
    $miScoresData = $scores['multiple_intelligence'] ?? [];
    
    $preparedMiScores = [];
    foreach($miMap as $key => $aliases) {
        $oldValue = old('multiple_intelligence.'.$key);
        if ($oldValue !== null) {
            $preparedMiScores[$key] = (int)$oldValue;
            continue;
        }

        $val = 0;
        foreach ($aliases as $alias) {
            if (isset($miScoresData[$alias])) {
                $val = $miScoresData[$alias];
                break;
            }
        }
        $preparedMiScores[$key] = (int)$val;
    }
@endphp
<script>
function assessmentForm() {
    return {
        form: {
            test_date: {{ Illuminate\Support\Js::from(old('test_date', $assessment->test_date->format('Y-m-d'))) }},
            psychologist_name: {{ Illuminate\Support\Js::from(old('psychologist_name', $assessment->psychologist_name)) }},
            subject_dob: {{ Illuminate\Support\Js::from($assessment->subject->date_of_birth ? $assessment->subject->date_of_birth->format('Y-m-d') : null) }},
        },
        calculated_age: '',
        mi: {{ Illuminate\Support\Js::from($preparedMiScores) }},
        init() {
            this.calculateAge();
        },
        calculateAge() {
            if (!this.form.subject_dob || !this.form.test_date) {
                this.calculated_age = '{{ $assessment->subject->age ? $assessment->subject->age . " Tahun (Manual)" : "-" }}';
                return;
            }
            
            const dob = new Date(this.form.subject_dob);
            const testDate = new Date(this.form.test_date);
            
            let years = testDate.getFullYear() - dob.getFullYear();
            let months = testDate.getMonth() - dob.getMonth();
            let days = testDate.getDate() - dob.getDate();

            if (days < 0) {
                months--;
                days += new Date(testDate.getFullYear(), testDate.getMonth(), 0).getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }
            
            this.calculated_age = `${years} thn ${months} bln ${days} hari`;
        },
        submitForm(event) {
            event.target.submit();
        }
    }
}
</script>
@endpush
@endsection
