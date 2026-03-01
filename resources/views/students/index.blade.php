@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Data Siswa</h2>
                    
                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <form action="{{ route('students.index') }}" method="GET" class="w-full md:w-64 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400" 
                       placeholder="Cari siswa...">
                        </form>

                        <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Siswa
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-green-800 dark:text-green-300 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-center">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-red-800 dark:text-red-300 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Grid Cards -->
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
                    @forelse($students as $student)
                        <div class="bg-white dark:bg-gray-800 rounded-[16px] md:rounded-[20px] border border-gray-200 dark:border-gray-700 p-3 md:p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                            <div class="flex justify-between items-start mb-2 md:mb-4">
                                <div>
                                    <h3 class="text-xs md:text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-1" title="{{ $student->nama_siswa }}">
                                        {{ $student->nama_siswa }}
                                    </h3>
                                    <div class="flex items-center text-[10px] md:text-sm text-gray-500 dark:text-gray-400 mt-0.5 md:mt-1">
                                        <svg class="h-3 w-3 md:h-4 md:w-4 mr-1 md:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="truncate">{{ $student->nama_orang_tua }}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-1 md:space-x-2">
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="p-1 md:p-2 text-gray-400 hover:text-blue-600 transition rounded-full hover:bg-blue-50">
                                        <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="space-y-1 md:space-y-3 mb-2 md:mb-6 flex-grow">
                                <div class="flex items-start text-[10px] md:text-sm">
                                    <svg class="h-3 w-3 md:h-5 md:w-5 text-gray-400 mr-1.5 md:mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300 line-clamp-1 md:line-clamp-2">
                                        {{ $student->sekolah ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 md:pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-800 text-[10px] md:text-sm font-bold flex items-center gap-1 group">
                                    Profil
                                    <svg class="h-3 w-3 md:h-4 md:w-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-4 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada data siswa</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Mulai dengan menambahkan data siswa baru untuk keperluan tagihan dan laporan.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
