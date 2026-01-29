@extends('layouts.app')

@section('title', 'Buat Asesmen Psikologi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100" x-data="assessmentForm()">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Buat Asesmen Psikologi</h2>
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

                <form method="POST" action="{{ route('psychological-assessments.store') }}" @submit.prevent="submitForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Data Subjek</h3>

                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Subjek (Searchable)</label>
                            <div x-data="{ q: '' }" class="mb-4">
                                <input type="text" x-model="q" placeholder="Ketik untuk mencari subjek..."
                                       class="w-full px-3 py-2 mb-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <select name="subject_id" x-model="form.subject_id"
                                        x-init="$watch('q', value => {
                                            const sel = $el;
                                            Array.from(sel.options).forEach(opt => {
                                                if (opt.value === '') return;
                                                const show = opt.text.toLowerCase().includes(value.toLowerCase());
                                                opt.style.display = show ? '' : 'none';
                                            });
                                        })"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">-- Subjek Baru --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                                    <input type="text" name="new_subject_name" x-model="form.new_subject_name"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                                        <input type="date" name="new_subject_dob" x-model="form.new_subject_dob" @change="calculateAge()"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <p class="text-sm text-gray-500 mt-1" x-show="form.calculated_age" x-text="form.calculated_age"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
                                        <select name="new_subject_gender" x-model="form.new_subject_gender"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            <option value="">Pilih</option>
                                            <option value="male">Laki-laki</option>
                                            <option value="female">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. HP</label>
                                    <input type="text" name="new_subject_phone" x-model="form.new_subject_phone"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Info Asesmen</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tes</label>
                                    <input type="date" name="test_date" x-model="form.test_date"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asesor</label>
                                    <input type="text" name="psychologist_name" x-model="form.psychologist_name"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Asesmen Psikologis Inputs -->
                    @php
                        $cognitiveScaleOptions = [
                            5 => 'A',
                            4 => 'B',
                            3 => 'C',
                            2 => 'D',
                            1 => 'E',
                        ];
                    @endphp

                    <div class="mb-10 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Data Asesmen Psikologis</h3>
                        
                        <!-- Aspek Kognitif -->
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">1. Aspek Kognitif</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            @foreach(['Verbal', 'Numerical', 'Logical', 'Spatial'] as $aspect)
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <div class="text-[13px] font-medium mb-3 text-gray-900 dark:text-gray-100">{{ $aspect }}</div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-[11px] text-gray-700 dark:text-gray-300">Score</label>
                                            <input type="number" name="psychological[cognitive_{{ strtolower($aspect) }}_score]" 
                                                   class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        </div>
                                        <div>
                                            <label class="text-[11px] text-gray-700 dark:text-gray-300">Scale</label>
                                            <select name="psychological[cognitive_{{ strtolower($aspect) }}_scale]" 
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                                <option value="">Pilih Skala</option>
                                                @foreach($cognitiveScaleOptions as $val => $label)
                                                    <option value="{{ $val }}" {{ old('psychological.cognitive_'.strtolower($aspect).'_scale') == $val ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                    <div class="text-[13px] font-medium mb-3 text-gray-900 dark:text-gray-100">{{ $aspect }}</div>
                                    <div>
                                        <label class="text-[11px] text-gray-700 dark:text-gray-300">Score</label>
                                        <input type="text" name="psychological[potential_{{ strtolower($aspect) }}_score]" 
                                               value="{{ old('psychological.potential_'.strtolower($aspect).'_score') }}"
                                               placeholder="contoh: 2 atau (-) 3"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
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
                                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                        <select name="psychological[iq_category]" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            <option value="">Pilih Kategori IQ</option>
                                            <option value="Very Superior" {{ old('psychological.iq_category') === 'Very Superior' ? 'selected' : '' }}>
                                                Very Superior (119 ke atas)
                                            </option>
                                            <option value="Tinggi" {{ old('psychological.iq_category') === 'Tinggi' ? 'selected' : '' }}>
                                                Tinggi (105 - 118)
                                            </option>
                                            <option value="Cukup" {{ old('psychological.iq_category') === 'Cukup' ? 'selected' : '' }}>
                                                Cukup (100 - 104)
                                            </option>
                                            <option value="Sedang" {{ old('psychological.iq_category') === 'Sedang' ? 'selected' : '' }}>
                                                Sedang (95 - 99)
                                            </option>
                                            <option value="Rendah" {{ old('psychological.iq_category') === 'Rendah' ? 'selected' : '' }}>
                                                Rendah (81 - 94)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">Taraf Kematangan</h4>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Recommendation</label>
                                    <select name="psychological[maturity_recommendation]" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Recommendation</option>
                                        <option value="Disarankan">Disarankan</option>
                                        <option value="Dipertimbangkan">Dipertimbangkan</option>
                                        <option value="Tidak Disarankan">Tidak Disarankan</option>
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
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Learning Style</h3>
                        <div class="space-y-4">
                            @php
                                $learningAspects = ['Visual', 'Auditory', 'Kinestetik'];
                            @endphp
                            @foreach($learningAspects as $aspect)
                                <div>
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $aspect }}</div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($labels as $label)
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="learning_style[{{ $aspect }}]"
                                                       value="{{ $label }}"
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
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Social Dominance</label>
                                <input type="text" name="talents[social_dominance]" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Dominance</label>
                                <input type="text" name="talents[skill_dominance]" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Strengths</label>
                                <textarea name="talents[strengths]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deficits</label>
                                <textarea name="talents[deficits]" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cluster Strength</label>
                                <input type="text" name="talents[cluster_strength]"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Personal Branding</label>
                                <input type="text" name="talents[personal_branding]"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Kesimpulan & Rekomendasi</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rekomendasi Pekerjaan</label>
                            <textarea name="psychological[job_recommendation]" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                      placeholder="Tuliskan maksimal 10 rekomendasi pekerjaan di sini..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Simpan Asesmen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function assessmentForm() {
    return {
        form: {
            subject_id: @json(old('subject_id')),
            new_subject_name: @json(old('new_subject_name')),
            new_subject_dob: @json(old('new_subject_dob')),
            calculated_age: '',
            new_subject_gender: @json(old('new_subject_gender')),
            new_subject_phone: @json(old('new_subject_phone')),
            test_date: @json(old('test_date', date('Y-m-d'))),
            psychologist_name: @json(old('psychologist_name')),
        },
        mi: {},
        calculateAge() {
            if (!this.form.new_subject_dob) return;
            const birthDate = new Date(this.form.new_subject_dob);
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            let days = today.getDate() - birthDate.getDate();

            if (days < 0) {
                months--;
                days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }
            this.form.calculated_age = `${years} thn ${months} bln ${days} hari`;
        },
        submitForm(event) {
            event.target.submit();
        }
    }
}
</script>
@endpush
@endsection
