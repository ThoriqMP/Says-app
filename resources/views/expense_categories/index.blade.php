@extends('layouts.app')

@section('title', 'Saluran Pengeluaran')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Saluran Pengeluaran</h2>
                    <a href="{{ route('expense-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        Tambah Saluran
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 text-green-800 p-4 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($categories as $category)
                        <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 p-6 shadow-sm hover:shadow-md transition">
                            <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Batas Anggaran</p>
                                <p class="font-semibold text-lg">
                                    {{ $category->budget_limit ? 'Rp ' . number_format($category->budget_limit, 0, ',', '.') : 'Tanpa Batas' }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                                <p class="font-semibold text-lg text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($category->expenses_sum_amount ?? 0, 0, ',', '.') }}
                                </p>
                            </div>

                            @if($category->budget_limit && ($category->expenses_sum_amount ?? 0) > $category->budget_limit)
                                <div class="mb-4 bg-red-100 text-red-800 p-2 rounded-md text-sm font-bold text-center">
                                    Out of Budget (Over: Rp {{ number_format(($category->expenses_sum_amount ?? 0) - $category->budget_limit, 0, ',', '.') }})
                                </div>
                            @endif

                            <div class="flex justify-end mt-4 pt-4 border-t border-gray-100 dark:border-gray-600 gap-2">
                                <a href="{{ route('expense-categories.show', $category) }}" class="text-blue-600 hover:text-blue-800 px-3 py-1 bg-blue-50 hover:bg-blue-100 rounded-md text-sm">Detail & List</a>
                                <a href="{{ route('expense-categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-800 px-3 py-1 bg-yellow-50 hover:bg-yellow-100 rounded-md text-sm">Edit</a>
                                <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus saluran ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 px-3 py-1 bg-red-50 hover:bg-red-100 rounded-md text-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-500">
                            Belum ada saluran pengeluaran.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
