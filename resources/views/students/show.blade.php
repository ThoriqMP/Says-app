@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
<div class="py-12" x-data="{ showCategoryModal: false }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Student Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[20px] border border-gray-200 dark:border-gray-700 mb-8">
            <div class="p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div class="h-20 w-20 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 text-3xl font-bold">
                            {{ substr($student->nama_siswa, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">{{ $student->nama_siswa }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">NIS: {{ $student->nis ?? '-' }} | Kelas: {{ $student->class ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button @click="showCategoryModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-blue-600/20 transition flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Raport
                        </button>
                        <a href="{{ route('students.edit', $student) }}" class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Edit Profil
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10 pt-10 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Orang Tua</h4>
                        <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $student->nama_orang_tua }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Akun</h4>
                        <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $student->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Tagihan</h4>
                        <p class="text-gray-900 dark:text-gray-100 font-semibold line-clamp-1">{{ $student->alamat_tagihan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Raport</h3>
            </div>

            @if($student->reports->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($student->reports as $report)
                        <div class="bg-white dark:bg-gray-800 rounded-[20px] border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 dark:bg-blue-900/40 rounded-lg">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100">{{ $report->category->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->period }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.reports.edit', $report) }}" class="p-2 text-gray-400 hover:text-green-600 transition rounded-full hover:bg-green-50" title="Edit Raport">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('student.reports.show', $report) }}" class="p-2 text-gray-400 hover:text-blue-600 transition rounded-full hover:bg-blue-50" title="Lihat Detail">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span class="text-xs text-gray-500">Dibuat {{ $report->created_at->format('d M Y') }}</span>
                                <a href="{{ route('student.reports.pdf', $report) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Unduh PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[20px] p-12 text-center">
                    <div class="bg-white dark:bg-gray-800 rounded-full p-4 w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Belum ada raport</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Siswa ini belum memiliki data raport yang tersimpan di sistem.</p>
                    <button @click="showCategoryModal = true" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold transition">
                        Buat Raport Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Category Selection Modal -->
    <div x-show="showCategoryModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCategoryModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 transition-opacity" 
                 @click="showCategoryModal = false">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showCategoryModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[20px] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pilih Kategori Raport</h3>
                        <button @click="showCategoryModal = false" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($categories as $category)
                            <a href="{{ route('admin.reports.create', ['student_id' => $student->id, 'category_id' => $category->id]) }}" 
                               class="flex items-center p-4 bg-gray-50 dark:bg-gray-900/50 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-200 dark:border-gray-700 rounded-2xl transition group">
                                <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="h-6 w-6 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Input laporan untuk kategori {{ $category->name }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
