@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8" x-data="{ 
    subjects: [
        @foreach($reportCategory->subjects as $subject)
            { id: '{{ $subject->id }}', name: '{{ $subject->name }}' },
        @endforeach
    ]
}">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10 flex items-center gap-4">
            <a href="{{ route('admin.report-categories.index') }}" class="h-12 w-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Edit Kategori</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Ubah nama kategori dan daftar subjek nilai.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[40px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <form action="{{ route('admin.report-categories.update', $reportCategory) }}" method="POST" class="p-10">
                @csrf
                @method('PUT')
                <div class="space-y-10">
                    <div>
                        <label for="name" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nama Kategori</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $reportCategory->name) }}" required 
                               placeholder="Misal: Akademik, Diniyah, dll"
                               class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-transparent focus:border-blue-500 rounded-2xl focus:ring-4 focus:ring-blue-500/20 dark:text-white font-bold placeholder:text-gray-400 text-base transition-all">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Daftar Subjek Nilai</label>
                            <button type="button" @click="subjects.push({ id: '', name: '' })" class="text-blue-600 hover:text-blue-800 text-xs font-black uppercase tracking-widest flex items-center gap-1.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Subjek
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(subject, index) in subjects" :key="index">
                                <div class="flex gap-3">
                                    <input type="hidden" :name="`subjects[${index}][id]`" x-model="subject.id">
                                    <input type="text" :name="`subjects[${index}][name]`" x-model="subject.name" placeholder="Nama Subjek" required
                                           class="flex-1 px-6 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-transparent focus:border-blue-500 rounded-2xl focus:ring-4 focus:ring-blue-500/20 dark:text-white font-bold placeholder:text-gray-400 text-base transition-all">
                                    <button type="button" @click="subjects.splice(index, 1)" x-show="subjects.length > 1"
                                            class="p-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl hover:bg-red-100 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
