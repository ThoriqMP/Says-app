@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    activeTab: 'akademik', 
    categories: {{ $categories->toJson() }},
    selectedAcademicCategory: '',
    probingCategoryId: '{{ $categories->where('name', 'Probing')->first()->id ?? '' }}',
    
    get currentSubjects() {
        if (!this.selectedAcademicCategory) return [];
        const cat = this.categories.find(c => c.id == this.selectedAcademicCategory);
        return cat ? cat.subjects : [];
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buat Raport Baru</h2>
                <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition text-sm">Kembali</a>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="flex space-x-4 mb-8 border-b border-gray-200 dark:border-gray-700">
                <button @click="activeTab = 'akademik'" :class="activeTab === 'akademik' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
                    Akademik/Diniyah/Ibadah
                </button>
                <button @click="activeTab = 'probing'" :class="activeTab === 'probing' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
                    Probing (Aktivitas)
                </button>
            </div>

            <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Siswa</label>
                        <select name="siswa_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (old('siswa_id') ?? $selectedStudentId) == $student->id ? 'selected' : '' }}>{{ $student->nama_siswa }} ({{ $student->nis ?? 'No NIS' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Periode / Semester</label>
                        <input type="text" name="period" value="{{ old('period') }}" required placeholder="Contoh: Semester 1 2024/2025" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- Akademik Tab Content -->
                <div x-show="activeTab === 'akademik'" class="space-y-8">
                    <input type="hidden" name="report_category_id" :value="selectedAcademicCategory" :disabled="activeTab !== 'akademik'">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kategori Raport</label>
                        <select x-model="selectedAcademicCategory" :required="activeTab === 'akademik'" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                @if($category->name !== 'Probing')
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div x-show="selectedAcademicCategory" class="space-y-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-l-4 border-blue-500 pl-3">Input Nilai Subjek</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="subject in currentSubjects" :key="subject.id">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                                    <div class="font-medium text-gray-700 dark:text-gray-300" x-text="subject.name"></div>
                                    <div>
                                        <input type="number" :name="`grades[${subject.id}][score]`" step="0.01" min="0" max="100" placeholder="Nilai (0-100)" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                    <div>
                                        <input type="text" :name="`grades[${subject.id}][description]`" placeholder="Keterangan / Catatan" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="!selectedAcademicCategory" class="p-8 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-gray-400">
                        Silakan pilih kategori raport terlebih dahulu untuk memunculkan daftar mata pelajaran.
                    </div>
                </div>

                <!-- Probing Tab Content -->
                <div x-show="activeTab === 'probing'" x-data="{ activities: [{ id: Date.now(), name: '', desc: '' }] }">
                    <!-- Hidden field for category Probing -->
                    <input type="hidden" name="report_category_id" :value="probingCategoryId" :disabled="activeTab !== 'probing'">
                    
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-l-4 border-green-500 pl-3">Aktivitas Probing</h3>
                        
                        <template x-for="(activity, index) in activities" :key="activity.id">
                            <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900/30 relative shadow-sm">
                                <button type="button" @click="activities = activities.filter(a => a.id !== activity.id)" class="absolute top-4 right-4 text-red-400 hover:text-red-600 transition" title="Hapus Baris">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Aktivitas</label>
                                        <input type="text" :name="`probing[${index}][name]`" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Upload Foto Aktivitas</label>
                                        <input type="file" :name="`probing[${index}][image]`" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi Detail</label>
                                        <textarea :name="`probing[${index}][description]`" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Ceritakan detail aktivitas siswa di sini..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="activities.push({ id: Date.now(), name: '', desc: '' })" class="w-full py-4 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl text-gray-500 hover:text-blue-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tambah Baris Aktivitas
                        </button>
                    </div>
                </div>

                <!-- Summary Notes (Common for all) -->
                <div class="mt-10">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Catatan Perkembangan Umum (Kesimpulan)</label>
                    <textarea name="summary_notes" rows="4" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Berikan catatan kesimpulan perkembangan siswa selama periode ini..."></textarea>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-600/30 transition transform active:scale-95">
                        Simpan Raport Seluruhnya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
