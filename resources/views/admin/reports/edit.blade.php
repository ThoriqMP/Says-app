@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    activeTab: '{{ $report->category->name === 'Probing' ? 'probing' : 'akademik' }}', 
    categories: {{ $categories->toJson() }},
    selectedAcademicCategory: '{{ $report->report_category_id }}',
    probingCategoryId: '{{ $categories->where('name', 'Probing')->first()->id ?? '' }}',
    newSubjects: [],
    
    get currentSubjects() {
        if (!this.selectedAcademicCategory) return [];
        const cat = this.categories.find(c => c.id == this.selectedAcademicCategory);
        return cat ? cat.subjects : [];
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[20px] border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Edit Raport {{ $report->category->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Memperbarui laporan periode {{ $report->period }}.</p>
                </div>
                <a href="{{ route('students.show', $report->siswa_id) }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Kembali ke Profil
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4 rounded-xl">
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Siswa</label>
                        <input type="hidden" name="siswa_id" value="{{ $report->siswa_id }}">
                        <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $report->student->nama_siswa }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Periode / Semester</label>
                        <input type="text" name="period" value="{{ old('period', $report->period) }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-semibold">
                    </div>
                </div>

                <!-- Akademik Tab Content -->
                <div x-show="activeTab === 'akademik'" class="space-y-8">
                    <input type="hidden" name="report_category_id" value="{{ $report->report_category_id }}">
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-l-4 border-blue-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Daftar Nilai Subjek</h3>
                            <button type="button" @click="newSubjects.push({ id: Date.now(), name: '' })" class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Subjek Baru
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            @foreach($report->category->subjects as $subject)
                                @php $grade = $report->grades->where('report_subject_id', $subject->id)->first(); @endphp
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:border-blue-200 transition">
                                    <div class="font-bold text-gray-700 dark:text-gray-300">{{ $subject->name }}</div>
                                    <div>
                                        <input type="number" name="grades[{{ $subject->id }}][score]" value="{{ $grade->score ?? '' }}" step="0.01" min="0" max="100" placeholder="Nilai (0-100)" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white text-sm font-bold">
                                    </div>
                                    <div>
                                        <input type="text" name="grades[{{ $subject->id }}][description]" value="{{ $grade->description ?? '' }}" placeholder="Keterangan / Catatan" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                </div>
                            @endforeach

                            <!-- Dynamic New Subjects -->
                            <template x-for="(sub, index) in newSubjects" :key="sub.id">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center p-5 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl shadow-sm">
                                    <div class="md:col-span-1">
                                        <input type="text" :name="`new_subjects[${index}][name]`" placeholder="Nama Subjek Baru" required class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white text-sm font-bold">
                                    </div>
                                    <div class="md:col-span-1">
                                        <input type="number" :name="`new_subjects[${index}][score]`" step="0.01" min="0" max="100" placeholder="Nilai" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white text-sm font-bold">
                                    </div>
                                    <div class="md:col-span-1">
                                        <input type="text" :name="`new_subjects[${index}][description]`" placeholder="Keterangan" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div class="md:col-span-1 flex justify-end">
                                        <button type="button" @click="newSubjects = newSubjects.filter(s => s.id !== sub.id)" class="text-red-500 hover:text-red-700 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Probing Tab Content -->
                <div x-show="activeTab === 'probing'" x-data="{ activities: {{ $report->probingActivities->map(function($a) { return ['id' => $a->id, 'name' => $a->activity_name, 'desc' => $a->description, 'img' => $a->image_path]; })->toJson() }} }">
                    <input type="hidden" name="report_category_id" value="{{ $report->report_category_id }}">
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-l-4 border-green-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Aktivitas Probing</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <template x-for="(activity, index) in activities" :key="activity.id">
                                <div class="p-8 border border-gray-200 dark:border-gray-700 rounded-[24px] bg-gray-50 dark:bg-gray-900/30 relative shadow-sm">
                                    <button type="button" @click="activities = activities.filter(a => a.id !== activity.id)" class="absolute top-6 right-6 text-red-400 hover:text-red-600 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Aktivitas</label>
                                            <input type="text" :name="`probing[${index}][name]`" :value="activity.name" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Ganti Foto (Kosongkan jika tidak ingin ganti)</label>
                                            <input type="file" :name="`probing[${index}][image]`" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                            <template x-if="activity.img">
                                                <p class="mt-2 text-xs text-gray-500 italic">Sudah ada foto terunggah.</p>
                                            </template>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Detail</label>
                                            <textarea :name="`probing[${index}][description]`" x-text="activity.desc" rows="4" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white p-5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="activities.push({ id: Date.now(), name: '', desc: '', img: '' })" class="w-full py-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-[24px] text-gray-500 hover:text-blue-600 flex items-center justify-center gap-2 font-bold text-lg transition hover:border-blue-400 hover:bg-blue-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tambah Aktivitas Lainnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Notes -->
                <div class="mt-12">
                    <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Catatan Perkembangan Umum (Kesimpulan)</label>
                    <textarea name="summary_notes" rows="4" class="w-full rounded-2xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white p-5">{{ old('summary_notes', $report->summary_notes) }}</textarea>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-700 flex justify-between">
                    <button type="button" onclick="if(confirm('Hapus raport ini?')) document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-extrabold transition">
                        Hapus Raport
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-extrabold shadow-xl shadow-blue-600/30 transition transform active:scale-95 text-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
            
            <form id="delete-form" action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection
