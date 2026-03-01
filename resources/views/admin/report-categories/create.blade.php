@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10 flex items-center gap-4">
            <a href="{{ route('admin.report-categories.index') }}" class="h-12 w-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-500 shadow-sm border border-gray-100 dark:border-gray-700 hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Tambah Kategori</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Buat kategori baru untuk pengelompokan raport.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[40px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <form action="{{ route('admin.report-categories.store') }}" method="POST" class="p-10">
                @csrf
                <div class="space-y-8">
                    <div>
                        <label for="name" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nama Kategori</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Misal: Akademik, Diniyah, dll" 
                               class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 dark:text-white font-bold placeholder-gray-400">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
