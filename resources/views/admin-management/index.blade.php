@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<div class="py-12" x-data="{ showRoleModal: false }">
    <!-- Role Selection Modal -->
    <div x-show="showRoleModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="showRoleModal = false"
         style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-[32px] p-8 max-w-md w-full shadow-2xl transform transition-all"
             x-show="showRoleModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 text-center">Tambah Pengguna Baru</h3>
            <p class="text-gray-500 dark:text-gray-400 text-center mb-8 font-medium">Pilih peran untuk akun baru yang akan dibuat.</p>
            
            <div class="grid grid-cols-1 gap-4">
                <a :href="`{{ route('admin-management.create') }}?role=admin`" 
                   class="group flex items-center gap-4 p-5 bg-blue-50 dark:bg-blue-900/20 border-2 border-transparent hover:border-blue-600 rounded-2xl transition-all">
                    <div class="h-12 w-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div class="text-left">
                        <p class="font-black text-gray-900 dark:text-white">Admin</p>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Akses Manajemen Penuh</p>
                    </div>
                </a>

                <a :href="`{{ route('admin-management.create') }}?role=guru`" 
                   class="group flex items-center gap-4 p-5 bg-indigo-50 dark:bg-indigo-900/20 border-2 border-transparent hover:border-indigo-600 rounded-2xl transition-all">
                    <div class="h-12 w-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div class="text-left">
                        <p class="font-black text-gray-900 dark:text-white">Guru</p>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Manajemen Siswa & Raport</p>
                    </div>
                </a>
            </div>

            <button @click="showRoleModal = false" class="mt-8 w-full py-4 text-gray-400 font-black text-sm uppercase tracking-widest hover:text-gray-600 transition">Batal</button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Manajemen Pengguna</h2>
                    
                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <form action="{{ route('admin-management.index') }}" method="GET" class="w-full md:w-64 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 placeholder:text-gray-400" 
                                   placeholder="Cari pengguna...">
                        </form>

                        <button @click="showRoleModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Pengguna
                        </button>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Berhasil</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Grid Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($admins as $admin)
                        <div class="bg-white dark:bg-gray-800 rounded-[20px] border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-1" title="{{ $admin->name }}">
                                            {{ $admin->name }}
                                        </h3>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1" title="{{ $admin->email }}">
                                            {{ $admin->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin-management.edit', $admin) }}" 
                                       class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-full transition">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin-management.destroy', $admin) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-2 mb-4">
                                <div class="flex items-center text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">
                                    Akses Modul
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @if(is_array($admin->permissions) && count($admin->permissions) > 0)
                                        @foreach(array_slice($admin->permissions, 0, 3) as $permission)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                                {{ str_replace(['.index', '.edit'], '', $permission) }}
                                            </span>
                                        @endforeach
                                        @if(count($admin->permissions) > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                +{{ count($admin->permissions) - 3 }} lainnya
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400 italic">Tidak ada permission khusus</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mt-auto">
                                <span>Dibuat {{ $admin->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-4 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada admin</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Tambahkan admin baru untuk mengelola sistem.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
