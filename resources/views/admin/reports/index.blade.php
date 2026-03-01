@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Manajemen Raport</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Kelola, lihat, dan unduh laporan perkembangan siswa.</p>
            </div>
            <a href="{{ route('students.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Raport Baru
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm mb-10">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Nama Siswa</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama siswa..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 dark:text-white font-bold placeholder-gray-400">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kategori Raport</label>
                    <select name="category_id" onchange="this.form.submit()" 
                            class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 dark:text-white font-bold appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-gray-900 dark:bg-gray-700 text-white py-3.5 rounded-2xl font-black hover:bg-blue-600 transition-colors active:scale-95">
                        Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category_id']))
                        <a href="{{ route('admin.reports.index') }}" class="px-4 py-3.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl font-black hover:bg-red-100 transition-colors" title="Reset Filter">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 p-6 rounded-[24px] flex items-center gap-4 text-green-700 dark:text-green-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Reports Grid (Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($reports as $report)
                <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col">
                    <div class="p-8 flex-1">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/40 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="px-4 py-1.5 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white line-clamp-1 mb-1">{{ $report->student->nama_siswa }}</h3>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">NIS: {{ $report->student->nis ?? '-' }}</p>
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $report->category->name }}
                                </span>
                            </div>
                            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 italic">"{{ $report->period }}"</p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 space-y-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.reports.show', $report) }}" class="flex-1 py-3.5 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black text-sm text-center hover:bg-blue-600 transition-colors active:scale-95">
                                Detail
                            </a>
                            <a href="{{ route('admin.reports.edit', $report) }}" class="p-3.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-2xl hover:bg-amber-100 transition-colors active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                        <a href="{{ route('admin.reports.pdf', $report) }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl font-black text-sm hover:bg-blue-100 transition-colors active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                        <div class="h-24 w-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Tidak Ada Raport</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Data raport tidak ditemukan untuk kriteria pencarian ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
