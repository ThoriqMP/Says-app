@extends('layouts.app')

@section('title', 'Detail Saluran Pengeluaran')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Saluran Info -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-1">{{ $expenseCategory->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat oleh {{ $expenseCategory->creator->name ?? '-' }}</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Batas Anggaran</p>
                        <p class="font-semibold text-lg">{{ $expenseCategory->budget_limit ? 'Rp ' . number_format($expenseCategory->budget_limit, 0, ',', '.') : 'Tanpa Batas' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                        <p class="font-semibold text-xl text-blue-600 dark:text-blue-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            @if($expenseCategory->budget_limit && $totalPengeluaran > $expenseCategory->budget_limit)
                <div class="bg-red-500 text-white p-4 font-bold text-center">
                    OUT OF BUDGET (Melebihi Anggaran: Rp {{ number_format($totalPengeluaran - $expenseCategory->budget_limit, 0, ',', '.') }})
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="bg-green-50 text-green-800 p-4 rounded-lg shadow-sm border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Tambah Pengeluaran -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Tambah Pengeluaran Baru</h3>
                    <form action="{{ route('expense-categories.expenses.store', $expenseCategory) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi / Nama Item</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal (Rp)</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">Simpan Pengeluaran</button>
                    </form>
                </div>
            </div>

            <!-- List Pengeluaran -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($expenseCategory->expenses as $expense)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        {{ $expense->description }}
                                        <div class="text-xs text-gray-500 mt-1">oleh {{ $expense->creator->name ?? 'System' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-300">
                                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <form action="{{ route('expense-categories.expenses.destroy', [$expenseCategory, $expense]) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada pengeluaran dicatat untuk saluran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
