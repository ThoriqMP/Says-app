@extends('layouts.app')

@section('title', 'Tambah ' . ucfirst($role))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 {{ $role === 'admin' ? 'bg-blue-600' : 'bg-indigo-600' }} rounded-xl flex items-center justify-center text-white">
                            @if($role === 'admin')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            @endif
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Tambah {{ ucfirst($role) }} Baru</h2>
                    </div>
                    <a href="{{ route('admin-management.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 flex items-center">
                        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('admin-management.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="example@email.com"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Password</label>
                            <input type="password" name="password" id="password" required
                                placeholder="Masukkan password"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                placeholder="Ulangi password"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Akses Menu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($permissions as $key => $label)
                                <div class="relative flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="permission_{{ $key }}" name="permissions[]" type="checkbox" value="{{ $key }}"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                            {{ in_array($key, old('permissions', $defaultPermissions ?? [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="permission_{{ $key }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition">
                            Simpan {{ ucfirst($role) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
