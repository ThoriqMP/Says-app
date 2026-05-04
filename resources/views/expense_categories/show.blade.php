@extends('layouts.app')

@section('title', 'Detail Saluran Pengeluaran')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Immersive Card -->
        <div class="relative bg-white dark:bg-gray-800 rounded-[24px] border border-gray-100 dark:border-gray-700 p-8 shadow-sm overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $expenseCategory->name }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dikelola oleh <span class="text-gray-700 dark:text-gray-300 font-bold">{{ $expenseCategory->creator->name ?? 'Sistem' }}</span></p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div class="text-center sm:text-left">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Batas Anggaran</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $expenseCategory->budget_limit ? 'Rp ' . number_format($expenseCategory->budget_limit, 0, ',', '.') : 'Tanpa Batas' }}</p>
                    </div>
                    <div class="hidden sm:block w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="text-center sm:text-right">
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Total Terpakai</p>
                        <p class="font-black text-xl text-blue-600 dark:text-blue-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            @if($expenseCategory->budget_limit)
                @php
                    $usagePercentage = min(100, (($totalPengeluaran ?? 0) / $expenseCategory->budget_limit) * 100);
                    $isOverBudget = ($totalPengeluaran ?? 0) > $expenseCategory->budget_limit;
                @endphp
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/50 relative z-10">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                        <span class="text-gray-500 dark:text-gray-400">Indikator Pemakaian</span>
                        <span class="{{ $isOverBudget ? 'text-red-500' : 'text-blue-500' }}">{{ number_format($usagePercentage, 1) }}%</span>
                    </div>
                    <div class="h-3 bg-gray-100 dark:bg-gray-700/50 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-1000 {{ $isOverBudget ? 'bg-red-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}" style="width: {{ $usagePercentage }}%"></div>
                    </div>
                    @if($isOverBudget)
                        <div class="mt-4 flex items-center justify-center gap-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-3 rounded-xl border border-red-100 dark:border-red-800/50 font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            OVERBUDGET (Melebihi: Rp {{ number_format($totalPengeluaran - $expenseCategory->budget_limit, 0, ',', '.') }})
                        </div>
                    @endif
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
            <!-- Form Tambah Pengeluaran -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-[24px] shadow-sm p-6 border border-gray-100 dark:border-gray-700 sticky top-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Catat Baru</h3>
                    </div>
                    <form action="{{ route('expense-categories.expenses.store', $expenseCategory) }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label for="description" class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 ml-1">Deskripsi Item</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required placeholder="Contoh: Beli Kertas">
                            @error('description') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="amount" class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 ml-1">Nominal (Rp)</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required placeholder="50000">
                            @error('amount') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="date" class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required>
                            @error('date') <span class="text-red-500 text-xs mt-1 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest py-3 px-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 active:scale-95 flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Data
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Pengeluaran -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-[24px] shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">Riwayat Pengeluaran</h3>
                            <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-full">{{ $expenses->total() }} Data</span>
                        </div>
                    </div>
                    
                    <!-- Search & Filters -->
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <form action="{{ route('expense-categories.show', $expenseCategory) }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                            <!-- Date Range -->
                            <div class="flex items-center gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full lg:w-auto rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500 text-xs dark:text-white" placeholder="Mulai">
                                <span class="text-gray-400">-</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full lg:w-auto rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500 text-xs dark:text-white" placeholder="Selesai">
                            </div>
                            
                            <!-- Search -->
                            <div class="flex-1 relative">
                                <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500 text-xs dark:text-white pl-8" placeholder="Cari deskripsi...">
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>

                            <!-- Sort -->
                            <div class="w-full lg:w-48">
                                <select name="sort" class="w-full rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500 text-xs dark:text-white">
                                    <option value="date_desc" {{ request('sort') == 'date_desc' || !request('sort') ? 'selected' : '' }}>Tanggal Terkini</option>
                                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
                                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Nominal Terbesar</option>
                                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Nominal Terkecil</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-xs font-bold transition">Filter</button>
                            <a href="{{ route('expense-categories.show', $expenseCategory) }}" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 px-4 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center">Reset</a>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/80">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Deskripsi</th>
                                    <th scope="col" class="px-6 py-4 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest whitespace-nowrap">Nominal</th>
                                    <th scope="col" class="px-6 py-4 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/50">
                                @forelse($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($expense->date)->format('d M') }}
                                            </div>
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($expense->date)->format('Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $expense->description }}
                                            </div>
                                            <div class="text-[10px] font-medium text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $expense->creator->name ?? 'Sistem' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-black text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <form action="{{ route('expense-categories.expenses.destroy', [$expenseCategory, $expense]) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?');" class="inline-block md:opacity-0 group-hover:opacity-100 transition-opacity">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            <div class="hidden md:block group-hover:hidden text-gray-300 dark:text-gray-600">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-400 mb-4 border border-gray-100 dark:border-gray-700">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <p class="text-sm font-bold text-gray-900 dark:text-white">Belum ada pencatatan</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs">Data pengeluaran untuk saluran ini belum tersedia. Tambahkan pengeluaran baru menggunakan form di samping.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($expenses->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $expenses->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
