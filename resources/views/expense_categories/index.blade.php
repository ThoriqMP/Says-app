@extends('layouts.app')

@section('title', 'Saluran Pengeluaran')

@section('content')
<div class="py-12" x-data="{ showExpenseModal: false, selectedCategory: null, categoryName: '' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Saluran Dana</h2>
                        <p class="text-sm font-medium text-gray-500 mt-1">Kelola kategori pengeluaran dan pantau anggaran.</p>
                    </div>
                    <a href="{{ route('expense-categories.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Saluran
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 text-green-800 p-4 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($categories as $category)
                        <div @click="window.location.href='{{ route('expense-categories.show', $category) }}'" class="cursor-pointer bg-white dark:bg-gray-800 rounded-[24px] border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group overflow-hidden">
                            <!-- Premium Background Elements -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="relative z-10 flex flex-col h-full">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-black text-gray-900 dark:text-white truncate">{{ $category->name }}</h3>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6 mt-auto">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Anggaran</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white truncate">
                                            {{ $category->budget_limit ? 'Rp ' . number_format($category->budget_limit, 0, ',', '.') : 'Tanpa Batas' }}
                                        </p>
                                    </div>
                                    <div class="p-3 bg-blue-50/50 dark:bg-blue-900/20 rounded-2xl border border-blue-100/50 dark:border-blue-800/50">
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Terpakai</p>
                                        <p class="text-sm font-black text-blue-600 dark:text-blue-400 truncate">
                                            Rp {{ number_format($category->expenses_sum_amount ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                @if($category->budget_limit)
                                    @php
                                        $usagePercentage = min(100, (($category->expenses_sum_amount ?? 0) / $category->budget_limit) * 100);
                                        $isOverBudget = ($category->expenses_sum_amount ?? 0) > $category->budget_limit;
                                    @endphp
                                    <div class="mb-6">
                                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                                            <span class="text-gray-500">Pemakaian</span>
                                            <span class="{{ $isOverBudget ? 'text-red-500' : 'text-blue-500' }}">{{ number_format($usagePercentage, 1) }}%</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-1000 {{ $isOverBudget ? 'bg-red-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}" style="width: {{ $usagePercentage }}%"></div>
                                        </div>
                                        @if($isOverBudget)
                                            <p class="mt-2 text-xs font-bold text-red-500 text-center bg-red-50 dark:bg-red-900/20 py-1.5 rounded-lg border border-red-100 dark:border-red-800/50">
                                                Over: Rp {{ number_format(($category->expenses_sum_amount ?? 0) - $category->budget_limit, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-700/50 gap-2 mt-auto">
                                    <button @click.stop="selectedCategory = {{ $category->id }}; categoryName = '{{ addslashes($category->name) }}'; showExpenseModal = true;" class="flex-1 text-center py-2.5 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30 active:scale-95">
                                        Catat Pengeluaran
                                    </button>
                                    <a href="{{ route('expense-categories.show', $category) }}" @click.stop class="p-2.5 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 dark:bg-gray-900/50 dark:hover:bg-blue-900/20 rounded-xl transition-all active:scale-95 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/50" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('expense-categories.edit', $category) }}" @click.stop class="p-2.5 text-gray-400 hover:text-yellow-600 bg-gray-50 hover:bg-yellow-50 dark:bg-gray-900/50 dark:hover:bg-yellow-900/20 rounded-xl transition-all active:scale-95 border border-transparent hover:border-yellow-200 dark:hover:border-yellow-800/50" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" @click.stop onsubmit="return confirm('Yakin ingin menghapus saluran ini? Seluruh data pengeluaran di dalamnya akan ikut terhapus!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 dark:bg-gray-900/50 dark:hover:bg-red-900/20 rounded-xl transition-all active:scale-95 border border-transparent hover:border-red-200 dark:hover:border-red-800/50" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
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

    <!-- Expense Record Modal -->
    <div x-show="showExpenseModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/75 backdrop-blur-sm" @click="showExpenseModal = false"></div>

            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-3xl"
                 x-show="showExpenseModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Catat Pengeluaran</h3>
                    <button @click="showExpenseModal = false" class="text-gray-400 hover:text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 p-2 rounded-full transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/50 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-black uppercase tracking-widest">Saluran Terpilih</p>
                        <p class="text-sm font-black text-gray-900 dark:text-white mt-0.5 truncate" x-text="categoryName"></p>
                    </div>
                </div>

                <form :action="'{{ url('expense-categories') }}/' + selectedCategory + '/expenses'" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2 ml-1">Deskripsi / Item</label>
                        <input type="text" name="description" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required placeholder="Contoh: Pembelian alat tulis...">
                    </div>

                    <div class="mb-4">
                        <label class="block text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2 ml-1">Nominal (Rp)</label>
                        <input type="number" step="0.01" name="amount" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required placeholder="50000">
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" required>
                    </div>

                    <div class="flex gap-3 pt-2 border-t border-gray-100 dark:border-gray-700 mt-6">
                        <button type="button" @click="showExpenseModal = false" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition active:scale-95">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
