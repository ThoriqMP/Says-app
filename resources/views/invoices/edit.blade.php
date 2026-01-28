@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Invoice</h2>
                    <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    $existingItems = $invoice->invoiceDetails->map(function($detail) {
                        return [
                            'id_layanan' => $detail->id_layanan,
                            'deskripsi_tambahan' => $detail->deskripsi_tambahan,
                            'kuantitas' => $detail->kuantitas,
                            'harga_satuan' => $detail->harga_satuan,
                            'total' => $detail->total_biaya
                        ];
                    })->toArray();

                    $currentStudentName = $invoice->siswa ? $invoice->siswa->nama_siswa . ' - ' . $invoice->siswa->nama_orang_tua : '';
                @endphp

                <form method="POST" action="{{ route('invoices.update', $invoice) }}" 
                      x-data="invoiceEditForm({{ json_encode($services) }}, {{ json_encode($existingItems) }}, '{{ $currentStudentName }}')" 
                      @submit.prevent="submitForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informasi Utama -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="id_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Siswa</label>
                            
                            <div class="relative" @click.outside="showResults = false">
                                <input type="text" 
                                       x-model.debounce.300ms="searchQuery"
                                       @focus="if(searchQuery.length >= 2) showResults = true"
                                       placeholder="Cari nama siswa atau orang tua..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                       autocomplete="off">
                                <input type="hidden" name="id_siswa" x-model="form.id_siswa">
                                
                                <!-- Loading Indicator -->
                                <div x-show="isSearching" class="absolute right-3 top-2.5" style="display: none;">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                <!-- Results Dropdown -->
                                <div x-show="showResults && searchResults.length > 0" 
                                     class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                     style="display: none;">
                                    <ul>
                                        <template x-for="student in searchResults" :key="student.id">
                                            <li @click="selectStudent(student)"
                                                class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-600 last:border-0">
                                                <div class="font-medium" x-text="student.nama_siswa"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="student.nama_orang_tua"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                
                                <div x-show="showResults && searchQuery.length >= 2 && searchResults.length === 0 && !isSearching" 
                                     class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg px-4 py-2 text-gray-500 dark:text-gray-400"
                                     style="display: none;">
                                    Tidak ada data ditemukan
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="tanggal_invoice" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Invoice</label>
                            <input type="date" id="tanggal_invoice" name="tanggal_invoice" x-model="form.tanggal_invoice" required
                                   value="{{ old('tanggal_invoice', $invoice->tanggal_invoice->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        
                        <div>
                            <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jatuh Tempo</label>
                            <input type="date" id="jatuh_tempo" name="jatuh_tempo" x-model="form.jatuh_tempo" required
                                   value="{{ old('jatuh_tempo', $invoice->jatuh_tempo->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>

                    <!-- Status Invoice -->
                    <div class="mb-8">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Invoice</label>
                        <select id="status" name="status" x-model="form.status" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100">Draft</option>
                            <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100">Terkirim</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100">Lunas</option>
                            <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100">Jatuh Tempo</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Status invoice: 
                            <span class="font-medium">
                                <span x-text="getStatusLabel(form.status)"></span>
                            </span>
                        </p>
                    </div>

                    <!-- Tabel Item -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Item Invoice</h3>
                            <button type="button" @click="addItem" 
                                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Item
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuantitas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-6 py-4">
                                                <select :name="`items[${index}][id_layanan]`" x-model="item.id_layanan" required
                                                        @change="updateHarga(index)"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                                    <option value="" class="text-gray-900 dark:text-gray-100">Pilih Layanan</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" data-harga="{{ $service->harga_standar }}"
                                                                class="text-gray-900 dark:text-gray-100">
                                                            {{ $service->nama_layanan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" :name="`items[${index}][deskripsi_tambahan]`" x-model="item.deskripsi_tambahan"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                                       placeholder="Deskripsi tambahan (opsional)">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" :name="`items[${index}][kuantitas]`" x-model="item.kuantitas" required min="1"
                                                       @input="calculateTotal(index)"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" :name="`items[${index}][harga_satuan]`" x-model="item.harga_satuan" required min="0" step="0.01"
                                                       @input="calculateTotal(index)"
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" x-model="formatRupiah(item.total)" readonly
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-600 text-gray-900 dark:text-gray-100">
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button" @click="removeItem(index)" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-red-600 text-white hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                        :disabled="items.length <= 1">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Grand Total -->
                        <div class="mt-6 flex justify-end">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Grand Total:</span>
                                    <span class="text-2xl font-bold text-blue-600" x-text="formatRupiah(grandTotal)"></span>
                                </div>
                                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400" x-text="terbilang"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function invoiceEditForm(services, existingItems = [], currentStudentName = '') {
    return {
        services: services,
        form: {
            id_siswa: '{{ old("id_siswa", $invoice->id_siswa) }}',
            tanggal_invoice: '{{ old("tanggal_invoice", $invoice->tanggal_invoice->format("Y-m-d")) }}',
            jatuh_tempo: '{{ old("jatuh_tempo", $invoice->jatuh_tempo->format("Y-m-d")) }}',
            status: '{{ old("status", $invoice->status) }}',
        },
        items: existingItems.length > 0 ? existingItems : [],
        grandTotal: 0,
        terbilang: '',

        // Search functionality
        searchQuery: currentStudentName,
        searchResults: [],
        isSearching: false,
        showResults: false,
        
        init() {
            if (this.items.length === 0) {
                this.addItem();
            }

            this.calculateGrandTotal();

            // Watch for search query changes
            this.$watch('searchQuery', (value) => {
                if (value && value.length >= 2) {
                    this.performSearch();
                } else {
                    this.searchResults = [];
                    this.showResults = false;
                }
            });

            this.$watch('form.tanggal_invoice', (value) => {
                if (value) {
                    this.form.jatuh_tempo = this.calculateDueDate(value);
                }
            });

            this.$watch('form.jatuh_tempo', (value) => {
                this.items.forEach((item, index) => {
                    const service = this.services.find(s => s.id == item.id_layanan);
                    if (service && service.nama_layanan.toUpperCase().includes('SPP')) {
                        this.updateItemSPPDescription(index);
                    }
                });
            });
        },

        performSearch() {
            this.isSearching = true;
            fetch(`{{ route('students.search') }}?query=${encodeURIComponent(this.searchQuery)}`)
                .then(res => res.json())
                .then(data => {
                    this.searchResults = data;
                    this.showResults = true;
                })
                .catch(err => {
                    console.error('Search error:', err);
                })
                .finally(() => {
                    this.isSearching = false;
                });
        },

        selectStudent(student) {
            this.form.id_siswa = student.id;
            this.searchQuery = `${student.nama_siswa} - ${student.nama_orang_tua}`;
            this.showResults = false;
        },

        calculateDueDate(dateStr) {
            const date = new Date(dateStr);
            if (isNaN(date)) {
                return this.form.jatuh_tempo;
            }
            let year = date.getFullYear();
            let month = date.getMonth() + 1;
            month += 1; // Next month
            if (month > 12) {
                month = 1;
                year += 1;
            }
            const day = 10;
            const mm = String(month).padStart(2, '0');
            const dd = String(day).padStart(2, '0');
            return `${year}-${mm}-${dd}`;
        },
        
        createEmptyItem() {
            return {
                id_layanan: '',
                deskripsi_tambahan: '',
                kuantitas: 1,
                harga_satuan: 0,
                total: 0
            };
        },
        
        addItem() {
            this.items.push(this.createEmptyItem());
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
                this.calculateGrandTotal();
            }
        },
        
        updateHarga(index) {
            const select = event.target;
            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            
            if (harga) {
                this.items[index].harga_satuan = parseFloat(harga);
                this.calculateTotal(index);
            }

            // Auto Description for SPP
            const serviceName = selectedOption.text.trim();
            if (serviceName.toUpperCase().includes('SPP')) {
                this.updateItemSPPDescription(index);
            }
        },

        updateItemSPPDescription(index) {
            const jatuhTempo = new Date(this.form.jatuh_tempo);
            if (!isNaN(jatuhTempo.getTime())) {
                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                const monthName = months[jatuhTempo.getMonth()];
                const year = jatuhTempo.getFullYear();
                this.items[index].deskripsi_tambahan = `SPP ${monthName} ${year}`;
            }
        },
        
        calculateTotal(index) {
            const item = this.items[index];
            item.total = (parseFloat(item.kuantitas) || 0) * (parseFloat(item.harga_satuan) || 0);
            this.calculateGrandTotal();
        },
        
        calculateGrandTotal() {
            this.grandTotal = this.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0);
            this.terbilang = this.generateTerbilang(this.grandTotal);
        },
        
        formatRupiah(angka) {
            return 'Rp ' + (parseFloat(angka) || 0).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        },
        
        generateTerbilang(angka) {
            return 'Terbilang: Rupiah ' + (parseFloat(angka) || 0).toLocaleString('id-ID') + ',-';
        },
        
        getStatusLabel(status) {
            const labels = {
                'draft': 'Draft - Invoice belum dikirim',
                'sent': 'Terkirim - Invoice sudah dikirim ke siswa',
                'paid': 'Lunas - Invoice sudah dibayar',
                'overdue': 'Jatuh Tempo - Invoice melewati batas pembayaran'
            };
            return labels[status] || status;
        },
        
        submitForm(event) {
            // Validasi minimal satu item
            if (this.items.length === 0) {
                alert('Minimal harus ada satu item.');
                return;
            }
            
            // Validasi semua item harus lengkap
            for (let i = 0; i < this.items.length; i++) {
                const item = this.items[i];
                if (!item.id_layanan || !item.kuantitas || !item.harga_satuan) {
                    alert(`Item ke-${i + 1} belum lengkap.`);
                    return;
                }
            }
            
            event.target.submit();
        }
    };
}
</script>
@endpush
@endsection
