@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Kategori Raport</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Kelola kategori untuk pengelompokan nilai raport.</p>
            </div>
            <a href="{{ route('admin.report-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 p-6 rounded-[24px] flex items-center gap-4 text-green-700 dark:text-green-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 p-6 rounded-[24px] flex items-center gap-4 text-red-700 dark:text-red-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 p-8 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/40 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.report-categories.edit', $category) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.report-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-sm">
                        {{ $category->subjects_count }} Subjek Nilai
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
