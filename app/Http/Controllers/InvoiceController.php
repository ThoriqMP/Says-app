<?php

namespace App\Http\Controllers;

use App\Helpers\TerbilangHelper;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['siswa', 'profilSekolah'])
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Search students for autocomplete
     */
    public function searchStudents(Request $request)
    {
        $query = $request->get('query');
        
        $students = Siswa::where('nama_siswa', 'like', "%{$query}%")
            ->orWhere('nama_orang_tua', 'like', "%{$query}%")
            ->limit(20)
            ->get(['id', 'nama_siswa', 'nama_orang_tua']);

        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Siswa::all();
        $services = Layanan::all();
        $schoolProfile = ProfilSekolah::first();

        return view('invoices.create', compact('students', 'services', 'schoolProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal_invoice' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:layanan,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $schoolProfile = ProfilSekolah::first();

            $invoiceDate = Carbon::parse($request->tanggal_invoice);
            $dueDate = $invoiceDate->copy()->addMonth();
            $dueDate->day = 10;

            $dueMonthName = $this->getIndonesianMonthName($dueDate->month);
            $dueYear = $dueDate->year;

            $itemsData = $request->items;
            $serviceIds = collect($itemsData)->pluck('id_layanan')->all();
            $services = Layanan::whereIn('id', $serviceIds)->get()->keyBy('id');

            $invoice = Invoice::create([
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $dueDate->toDateString(),
                'id_siswa' => $request->id_siswa,
                'id_sekolah' => $schoolProfile->id,
                'status' => 'draft',
                'grand_total' => 0,
            ]);

            $grandTotal = 0;

            foreach ($itemsData as $item) {
                $totalBiaya = $item['kuantitas'] * $item['harga_satuan'];
                $grandTotal += $totalBiaya;

                $service = $services->get($item['id_layanan']);
                $deskripsiTambahan = $item['deskripsi_tambahan'] ?? null;

                if ($service && stripos($service->nama_layanan, 'SPP') !== false) {
                    $deskripsiTambahan = 'SPP ('.$dueMonthName.' '.$dueYear.')';
                }

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $deskripsiTambahan,
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_biaya' => $totalBiaya,
                ]);
            }

            $invoice->update(['grand_total' => $grandTotal]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan invoice.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        return view('invoices.show', compact('invoice', 'terbilang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $services = Layanan::all();
        $schoolProfile = ProfilSekolah::first();
        $invoice->load(['invoiceDetails.layanan', 'siswa']);

        return view('invoices.edit', compact('invoice', 'services', 'schoolProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal_invoice' => 'required|date',
            'jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:layanan,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $updateData = [
                'id_siswa' => $request->id_siswa,
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status,
            ];

            // Cek jika bulan atau tahun berubah, generate nomor baru agar reset sesuai bulan
            $oldDate = $invoice->tanggal_invoice;
            $newDate = Carbon::parse($request->tanggal_invoice);

            if ($oldDate->format('Y-m') !== $newDate->format('Y-m')) {
                $updateData['no_invoice'] = Invoice::generateInvoiceNumber($newDate);
            }

            $invoice->update($updateData);

            $dueDate = Carbon::parse($request->jatuh_tempo);
            $dueMonthName = $this->getIndonesianMonthName($dueDate->month);
            $dueYear = $dueDate->year;

            $itemsData = $request->items;
            $serviceIds = collect($itemsData)->pluck('id_layanan')->all();
            $services = Layanan::whereIn('id', $serviceIds)->get()->keyBy('id');

            // Hapus detail lama
            $invoice->invoiceDetails()->delete();

            $grandTotal = 0;

            foreach ($itemsData as $item) {
                $totalBiaya = $item['kuantitas'] * $item['harga_satuan'];
                $grandTotal += $totalBiaya;

                $service = $services->get($item['id_layanan']);
                $deskripsiTambahan = $item['deskripsi_tambahan'] ?? null;

                if ($service && stripos($service->nama_layanan, 'SPP') !== false) {
                    $deskripsiTambahan = 'SPP ('.$dueMonthName.' '.$dueYear.')';
                }

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $deskripsiTambahan,
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_biaya' => $totalBiaya,
                ]);
            }

            $invoice->update(['grand_total' => $grandTotal]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui invoice.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            $invoice->invoiceDetails()->delete();
            $invoice->delete();

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus invoice.']);
        }
    }

    /**
     * Preview PDF invoice di browser
     */
    public function previewPdf(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));

        // Set paper size to A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        $filename = $this->buildInvoiceFilename($invoice);

        return $pdf->stream($filename);
    }

    /**
     * Download PDF invoice
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        $invoice->update([
            'status' => 'Telah dicetak pada ' . now()->format('d/m/Y'),
        ]);

        $filename = $this->buildInvoiceFilename($invoice);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    protected function buildInvoiceFilename(Invoice $invoice): string
    {
        $dueDate = $invoice->jatuh_tempo ?? now();
        $monthAbbr = $dueDate->format('M');
        $studentName = Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');

        return 'Invoice-'.$monthAbbr.'_'.$studentName.'.pdf';
    }

    protected function getIndonesianMonthName(int $month): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$month] ?? '';
    }
}
