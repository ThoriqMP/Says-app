@extends('layouts.app')

@section('title', 'Edit Saluran Pengeluaran')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Saluran Pengeluaran</h2>
                    <a href="{{ route('expense-categories.index') }}" class="text-gray-500 hover:text-gray-700">Kembali</a>
                </div>

                <form action="{{ route('expense-categories.update', $expenseCategory) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Saluran</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $expenseCategory->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="budget_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Anggaran (Opsional)</label>
                        <input type="number" step="0.01" name="budget_limit" id="budget_limit" value="{{ old('budget_limit', $expenseCategory->budget_limit) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('budget_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
