@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    categoryName: '{{ $selectedCategory->name ?? '' }}',
    isProbing: {{ ($selectedCategory->name ?? '') === 'Probing' ? 'true' : 'false' }},
    isAkademik: {{ ($selectedCategory->name ?? '') === 'Akademik' ? 'true' : 'false' }},
    isDiniyah: {{ ($selectedCategory->name ?? '') === 'Diniyah' ? 'true' : 'false' }},
    isIbadah: {{ ($selectedCategory->name ?? '') === 'Praktek Ibadah' ? 'true' : 'false' }},
    newSubjects: [],
    
    calculateAverage(row) {
        let values = [
            parseFloat(row.pts) || 0,
            parseFloat(row.pas) || 0,
            parseFloat(row.rem) || 0,
            parseFloat(row.harian) || 0
        ].filter(v => v > 0);
        
        if (values.length === 0) {
            row.score = '';
            row.pred = '-';
            return;
        }
        
        let avg = values.reduce((a, b) => a + b, 0) / values.length;
        row.score = avg.toFixed(2);
        row.pred = this.calculatePredicate(row.score);
    },
    calculatePredicate(score) {
        if (!score) return '-';
        score = parseFloat(score);
        if (score >= 93) return 'A';
        if (score >= 84) return 'B';
        if (score >= 75) return 'C';
        return 'D';
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[20px] border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Input Raport {{ $selectedCategory->name ?? '' }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Menginput laporan perkembangan siswa sesuai standar Sayyidah School.</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Kembali
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

            <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="siswa_id" value="{{ $selectedStudentId }}">
                <input type="hidden" name="report_category_id" value="{{ $selectedCategoryId }}">
                
                <!-- Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Siswa</label>
                        <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $students->find($selectedStudentId)->nama_siswa ?? 'Siswa tidak ditemukan' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Periode / Semester</label>
                        <input type="text" name="period" value="{{ old('period') }}" required placeholder="Contoh: Semester 1 2024/2025" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-semibold">
                    </div>
                </div>

                <!-- Academic Form -->
                <template x-if="isAkademik">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-l-4 border-blue-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Nilai Akademik (KKM: 75)</h3>
                            <button type="button" @click="newSubjects.push({ id: Date.now(), name: '' })" class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Mata Pelajaran
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Mata Pelajaran</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">PTS</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">PAS</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Remedial</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Harian</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Akhir</th>
                                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 text-center">Predikat</th>
                                    </tr>
                                </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        @foreach($selectedCategory->subjects as $subject)
                                            <tr x-data="{ score: '', pts: '', pas: '', rem: '', harian: '', pred: '-' }" 
                                                x-init="$watch('pts', () => calculateAverage($data)); $watch('pas', () => calculateAverage($data)); $watch('rem', () => calculateAverage($data)); $watch('harian', () => calculateAverage($data))"
                                                class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition">
                                                <td class="p-4 font-bold text-gray-700 dark:text-gray-300">{{ $subject->name }}</td>
                                                <td class="p-2"><input type="number" name="grades[{{ $subject->id }}][score_pts]" x-model="pts" step="0.01" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm font-bold"></td>
                                                <td class="p-2"><input type="number" name="grades[{{ $subject->id }}][score_pas]" x-model="pas" step="0.01" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm font-bold"></td>
                                                <td class="p-2"><input type="number" name="grades[{{ $subject->id }}][score_remedial]" x-model="rem" step="0.01" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm font-bold"></td>
                                                <td class="p-2"><input type="number" name="grades[{{ $subject->id }}][score_harian]" x-model="harian" step="0.01" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm font-bold"></td>
                                                <td class="p-2"><input type="number" name="grades[{{ $subject->id }}][score]" x-model="score" step="0.01" class="w-20 rounded-lg border-blue-200 dark:bg-gray-700 dark:border-gray-600 text-sm font-black text-blue-600" readonly></td>
                                                <td class="p-4 text-center">
                                                    <input type="hidden" name="grades[{{ $subject->id }}][predicate]" :value="pred">
                                                <span class="px-3 py-1 rounded-full text-xs font-black" :class="{
                                                    'bg-green-100 text-green-700': pred === 'A',
                                                    'bg-blue-100 text-blue-700': pred === 'B',
                                                    'bg-yellow-100 text-yellow-700': pred === 'C',
                                                    'bg-red-100 text-red-700': pred === 'D',
                                                    'bg-gray-100 text-gray-500': pred === '-'
                                                }" x-text="pred"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <template x-for="(sub, index) in newSubjects" :key="sub.id">
                                        <tr x-data="{ score: '', pts: '', pas: '', rem: '', harian: '', pred: '-' }" 
                                            x-init="$watch('pts', () => calculateAverage($data)); $watch('pas', () => calculateAverage($data)); $watch('rem', () => calculateAverage($data)); $watch('harian', () => calculateAverage($data))"
                                            class="bg-blue-50/30 dark:bg-blue-900/10">
                                            <td class="p-2"><input type="text" :name="`new_subjects[${index}][name]`" placeholder="Nama Mapel" class="w-full rounded-lg border-gray-200 dark:bg-gray-700 text-sm font-bold"></td>
                                            <td class="p-2"><input type="number" :name="`new_subjects[${index}][score_pts]`" x-model="pts" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 text-sm font-bold"></td>
                                            <td class="p-2"><input type="number" :name="`new_subjects[${index}][score_pas]`" x-model="pas" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 text-sm font-bold"></td>
                                            <td class="p-2"><input type="number" :name="`new_subjects[${index}][score_remedial]`" x-model="rem" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 text-sm font-bold"></td>
                                            <td class="p-2"><input type="number" :name="`new_subjects[${index}][score_harian]`" x-model="harian" class="w-20 rounded-lg border-gray-200 dark:bg-gray-700 text-sm font-bold"></td>
                                            <td class="p-2"><input type="number" :name="`new_subjects[${index}][score]`" x-model="score" class="w-20 rounded-lg border-blue-200 dark:bg-gray-700 text-sm font-black text-blue-600" readonly></td>
                                            <td class="p-4 text-center">
                                                <input type="hidden" :name="`new_subjects[${index}][predicate]`" :value="pred">
                                                <span class="px-3 py-1 rounded-full text-xs font-black" :class="{
                                                    'bg-green-100 text-green-700': pred === 'A',
                                                    'bg-blue-100 text-blue-700': pred === 'B',
                                                    'bg-yellow-100 text-yellow-700': pred === 'C',
                                                    'bg-red-100 text-red-700': pred === 'D',
                                                    'bg-gray-100 text-gray-500': pred === '-'
                                                }" x-text="pred"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>

                <!-- Diniyah Form -->
                <template x-if="isDiniyah">
                    <div class="space-y-6">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Tahfidz & Diniyah</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($selectedCategory->subjects as $subject)
                                <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                    <div class="font-bold text-gray-700 dark:text-gray-300">{{ $subject->name }}</div>
                                    <div class="md:col-span-1">
                                        <input type="text" name="grades[{{ $subject->id }}][ayat_range]" placeholder="Rentang Ayat (mis: 1-10)" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 text-sm font-bold">
                                    </div>
                                    <div class="md:col-span-1">
                                        <select name="grades[{{ $subject->id }}][predicate]" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 text-sm font-bold">
                                            <option value="">Pilih Mutu</option>
                                            <option value="Mumtaz">Mumtaz (A)</option>
                                            <option value="Jayyid Jiddan">Jayyid Jiddan (B)</option>
                                            <option value="Jayyid">Jayyid (C)</option>
                                            <option value="Maqbul">Maqbul (D)</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-1">
                                        <input type="text" name="grades[{{ $subject->id }}][description]" placeholder="Keterangan" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 text-sm">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </template>

                <!-- Ibadah Form -->
                <template x-if="isIbadah">
                    <div class="space-y-6">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Praktek Ibadah & Shalat</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($selectedCategory->subjects as $subject)
                                <div class="p-5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm flex items-center justify-between">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                    <select name="grades[{{ $subject->id }}][predicate]" class="rounded-xl border-gray-300 dark:bg-gray-700 text-sm font-black">
                                        <option value="Lancar">Lancar</option>
                                        <option value="Cukup Lancar">Cukup Lancar</option>
                                        <option value="Perlu Bimbingan">Perlu Bimbingan</option>
                                        <option value="Belum Hafal">Belum Hafal</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </template>

                <!-- Probing Form -->
                <template x-if="isProbing">
                    <div x-data="{ 
                        activities: [{ id: Date.now(), title: '', desc: '', preview: null }],
                        handleFile(index, event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.activities[index].preview = URL.createObjectURL(file);
                            }
                        }
                    }">
                        <div class="flex justify-between items-center border-l-4 border-amber-500 pl-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Aktivitas Probing (Dokumentasi)</h3>
                        </div>
                        
                        <div class="space-y-8">
                            <template x-for="(activity, index) in activities" :key="activity.id">
                                <div class="p-8 border border-gray-200 dark:border-gray-700 rounded-[32px] bg-gray-50 dark:bg-gray-900/30 relative shadow-sm transition hover:shadow-md">
                                    <button type="button" @click="activities = activities.filter(a => a.id !== activity.id)" class="absolute top-6 right-6 text-red-400 hover:text-red-600 transition p-2 hover:bg-red-50 rounded-full">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                                        <div class="md:col-span-4">
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Foto Aktivitas</label>
                                            <div class="relative group aspect-square rounded-3xl overflow-hidden bg-gray-200 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                                <template x-if="!activity.preview">
                                                    <div class="text-center p-4">
                                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Klik untuk upload</p>
                                                    </div>
                                                </template>
                                                <template x-if="activity.preview">
                                                    <img :src="activity.preview" class="w-full h-full object-cover">
                                                </template>
                                                <input type="file" :name="`probing[${index}][image]`" @change="handleFile(index, $event)" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                            </div>
                                        </div>
                                        <div class="md:col-span-8 space-y-6">
                                            <div>
                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Judul Aktivitas</label>
                                                <input type="text" :name="`probing[${index}][title]`" x-model="activity.title" required placeholder="Contoh: Praktek Memasak di Dapur" class="w-full rounded-2xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-bold text-lg p-4">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Narasi Deskripsi</label>
                                                <textarea :name="`probing[${index}][description]`" x-model="activity.desc" rows="6" class="w-full rounded-2xl border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:text-white p-4" placeholder="Ceritakan detail aktivitas siswa, apa yang dipelajari, dan bagaimana perkembangannya..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="activities.push({ id: Date.now(), title: '', desc: '', preview: null })" class="w-full py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-[32px] text-gray-400 hover:text-blue-600 hover:border-blue-400 hover:bg-blue-50 transition-all flex items-center justify-center gap-3 font-black text-xl active:scale-[0.98]">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                TAMBAH AKTIVITAS BARU
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Summary Notes -->
                <div class="mt-12 bg-gray-50 dark:bg-gray-900/50 p-8 rounded-[32px] border border-gray-100 dark:border-gray-800">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4 ml-1">Catatan Kesimpulan (Wali Kelas)</label>
                    <textarea name="summary_notes" rows="5" class="w-full rounded-2xl border-gray-200 focus:ring-blue-500 dark:bg-gray-700 dark:text-white p-6 text-lg" placeholder="Berikan gambaran umum perkembangan siswa selama periode ini..."></textarea>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-12 py-5 rounded-[24px] font-black shadow-2xl transition transform hover:-translate-y-1 active:scale-95 text-xl tracking-widest uppercase">
                        SIMPAN RAPORT {{ $selectedCategory->name ?? '' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
