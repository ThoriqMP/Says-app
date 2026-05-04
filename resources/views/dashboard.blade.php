@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        
        <!-- Header & Global Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
            <!-- Total Students -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-4 sm:p-6 flex items-center">
                <div class="p-2 sm:p-3 rounded-xl sm:rounded-full bg-blue-100 dark:bg-blue-900/30 mr-3 sm:mr-4 flex-shrink-0">
                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Siswa</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $totalStudents }}</p>
                </div>
            </div>

            <!-- Total Services -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-4 sm:p-6 flex items-center">
                <div class="p-2 sm:p-3 rounded-xl sm:rounded-full bg-purple-100 dark:bg-purple-900/30 mr-3 sm:mr-4 flex-shrink-0">
                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Layanan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $totalServices }}</p>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-4 sm:p-6 flex items-center col-span-2 md:col-span-1">
                <div class="p-2 sm:p-3 rounded-xl sm:rounded-full bg-orange-100 dark:bg-orange-900/30 mr-3 sm:mr-4 flex-shrink-0">
                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Invoice</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $totalInvoices }}</p>
                </div>
            </div>
        </div>

        <!-- Financial & Analytics Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Laporan Keuangan & Statistik
                    </h3>

                    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <!-- Year Filter -->
                        <select name="year" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endforeach
                        </select>

                        <!-- Month Filter -->
                        <select name="month" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $m)
                                <option value="{{ $idx+1 }}" {{ $month == $idx+1 ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>

                        <!-- Export Button -->
                        <button type="submit" formaction="{{ route('dashboard.export') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center justify-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export CSV
                        </button>
                    </form>
                </div>

                <!-- Financial Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                    <!-- Realized Revenue -->
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-green-100 font-medium text-sm mb-1 uppercase tracking-wider">Pendapatan Diterima</p>
                        <h4 class="text-2xl lg:text-3xl font-bold truncate" title="Rp {{ number_format($revenue, 0, ',', '.') }}">Rp {{ number_format($revenue, 0, ',', '.') }}</h4>
                        <div class="mt-4 flex items-center text-xs text-green-100">
                            <span class="bg-white/20 px-2 py-1 rounded-md">{{ $paidInvoices }} Invoice Lunas</span>
                        </div>
                    </div>

                    <!-- Total Expenses -->
                    <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-100 font-medium text-sm mb-1 uppercase tracking-wider">Total Pengeluaran</p>
                        <h4 class="text-2xl lg:text-3xl font-bold truncate" title="Rp {{ number_format($totalExpenses, 0, ',', '.') }}">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                        <div class="mt-4 flex items-center text-xs text-red-100">
                            <span class="bg-white/20 px-2 py-1 rounded-md cursor-pointer hover:bg-white/30 transition-colors" onclick="window.location='{{ route('expense-categories.index') }}'">Lihat Saluran Dana →</span>
                        </div>
                    </div>

                    <!-- Net Cashflow -->
                    @php $isPositive = $netCashflow >= 0; @endphp
                    <div class="bg-gradient-to-br {{ $isPositive ? 'from-blue-500 to-indigo-600' : 'from-yellow-500 to-orange-600' }} rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-white/80 font-medium text-sm mb-1 uppercase tracking-wider">Cashflow Bersih</p>
                        <h4 class="text-2xl lg:text-3xl font-bold truncate" title="Rp {{ number_format($netCashflow, 0, ',', '.') }}">Rp {{ number_format($netCashflow, 0, ',', '.') }}</h4>
                        <div class="mt-4 flex items-center text-xs text-white/80">
                            <span class="bg-white/20 px-2 py-1 rounded-md">{{ $isPositive ? 'Surplus' : 'Defisit' }} Periode Ini</span>
                        </div>
                    </div>

                    <!-- Potential Revenue -->
                    <div class="bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 text-white shadow-lg relative overflow-hidden group">
                         <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-gray-200 font-medium text-sm mb-1 uppercase tracking-wider">Potensi Pendapatan</p>
                        <h4 class="text-2xl lg:text-3xl font-bold truncate" title="Rp {{ number_format($potentialRevenue, 0, ',', '.') }}">Rp {{ number_format($potentialRevenue, 0, ',', '.') }}</h4>
                        <div class="mt-4 flex items-center text-xs text-gray-300">
                            <span class="bg-white/10 px-2 py-1 rounded-md">{{ $pendingInvoices + $overdueInvoices }} Inv. Belum Lunas</span>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Line Chart: Cashflow Trend -->
                    <div class="lg:col-span-2 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Tren Cashflow Tahun {{ $year }}</h4>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Doughnut Chart: Status -->
                    <div class="lg:col-span-1 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Status Invoice</h4>
                        <div class="h-48 flex justify-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Paid: {{ $statusChart['paid'] }}</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> Sent: {{ $statusChart['sent'] }}</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> Overdue: {{ $statusChart['overdue'] }}</div>
                            <div class="flex items-center"><span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span> Draft: {{ $statusChart['draft'] }}</div>
                        </div>
                    </div>

                    <!-- Top Expenses List -->
                    <div class="lg:col-span-1 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-600 flex flex-col">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex justify-between items-center">
                            Top Pengeluaran
                            <a href="{{ route('expense-categories.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
                        </h4>
                        <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                            @forelse($expensesByCategory as $ec)
                                <div class="flex flex-col border-b border-gray-200 dark:border-gray-600/50 pb-2 last:border-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate pr-2">{{ $ec->category->name ?? 'Tanpa Kategori' }}</span>
                                        <span class="text-xs font-black text-rose-600 dark:text-rose-400 whitespace-nowrap">Rp {{ number_format($ec->total, 0, ',', '.') }}</span>
                                    </div>
                                    @if($ec->category && $ec->category->budget_limit)
                                        @php $pct = min(100, ($ec->total / $ec->category->budget_limit) * 100); @endphp
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mt-1">
                                            <div class="bg-{{ $pct >= 100 ? 'red' : 'rose' }}-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 mt-8">
                                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <span class="text-xs text-center">Belum ada pengeluaran periode ini</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('invoices.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition group">
                    <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full p-3 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Buat Invoice</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tagihan baru</p>
                    </div>
                </a>

                <a href="{{ route('students.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition group">
                    <div class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full p-3 mr-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Tambah Siswa</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Data siswa baru</p>
                    </div>
                </a>

                <a href="{{ route('services.create') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition group">
                    <div class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full p-3 mr-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Tambah Layanan</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Katalog layanan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Pemasukan (Rp)',
                        data: @json($revenueChart),
                        borderColor: '#10B981', // green-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran (Rp)',
                        data: @json($expenseChart),
                        borderColor: '#F43F5E', // rose-500
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(156, 163, 175, 0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Sent', 'Overdue', 'Draft'],
                datasets: [{
                    data: [
                        {{ $statusChart['paid'] }},
                        {{ $statusChart['sent'] }},
                        {{ $statusChart['overdue'] }},
                        {{ $statusChart['draft'] }}
                    ],
                    backgroundColor: [
                        '#10B981', // green
                        '#3B82F6', // blue
                        '#EF4444', // red
                        '#9CA3AF'  // gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection