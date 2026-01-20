@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Invoice</h2>
                    <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
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

                <form method="POST" action="{{ route('invoices.update', $invoice) }}" x-data="invoiceEditForm()" @submit.prevent="submitForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informasi Utama -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="id_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Siswa</label>
                            <select id="id_siswa" name="id_siswa" x-model="form.id_siswa" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="" class="text-gray-900 dark:text-gray-100">Pilih Siswa</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $invoice->id_siswa == $student->id ? 'selected' : '' }} class="text-gray-900 dark:text-gray-100">
                                        {{ $student->nama_siswa }} - {{ $student->nama_orang_tua }}
                                    </option>
                                @endforeach
                            </select>
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
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                                x-bind:selected="item.id_layanan == {{ $service->id }}" class="text-gray-900 dark:text-gray-100">
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
                                                        class="text-red-600 hover:text-red-800 transition"
                                                        :disabled="items.length <= 1">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
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
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
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
function invoiceEditForm() {
    return {
        form: {
            id_siswa: '{{ old("id_siswa", $invoice->id_siswa) }}',
            tanggal_invoice: '{{ old("tanggal_invoice", $invoice->tanggal_invoice->format("Y-m-d")) }}',
            jatuh_tempo: '{{ old("jatuh_tempo", $invoice->jatuh_tempo->format("Y-m-d")) }}',
            status: '{{ old("status", $invoice->status) }}',
        },
        items: [],
        grandTotal: 0,
        terbilang: '',
        
        init() {
            // Load existing items
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
            @endphp
            const existingItems = @json($existingItems);
            
            this.items = existingItems.length > 0 ? existingItems : [this.createEmptyItem()];
            this.calculateGrandTotal();
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
        },
        
        calculateTotal(index) {
            const item = this.items[index];
            item.total = (item.kuantitas || 0) * (item.harga_satuan || 0);
            this.calculateGrandTotal();
        },
        
        calculateGrandTotal() {
            this.grandTotal = this.items.reduce((sum, item) => sum + item.total, 0);
            this.terbilang = this.generateTerbilang(this.grandTotal);
        },
        
        formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        
        generateTerbilang(angka) {
            return 'Terbilang: Rupiah ' + angka.toLocaleString('id-ID') + ',-';
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