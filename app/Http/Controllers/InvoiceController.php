<?php

namespace App\Http\Controllers;

use App\Helpers\TerbilangHelper;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            'jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
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

            $invoice = Invoice::create([
                'no_invoice' => Invoice::generateInvoiceNumber($request->tanggal_invoice),
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $request->jatuh_tempo,
                'id_siswa' => $request->id_siswa,
                'id_sekolah' => $schoolProfile->id,
                'status' => 'draft',
                'grand_total' => 0,
            ]);

            $grandTotal = 0;

            foreach ($request->items as $item) {
                $totalBiaya = $item['kuantitas'] * $item['harga_satuan'];
                $grandTotal += $totalBiaya;

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $item['deskripsi_tambahan'] ?? null,
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
        $students = Siswa::all();
        $services = Layanan::all();
        $schoolProfile = ProfilSekolah::first();
        $invoice->load(['invoiceDetails.layanan']);

        return view('invoices.edit', compact('invoice', 'students', 'services', 'schoolProfile'));
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
            'status' => 'required|in:draft,sent,paid,overdue',
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
            $invoice->update([
                'id_siswa' => $request->id_siswa,
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status,
            ]);

            // Hapus detail lama
            $invoice->invoiceDetails()->delete();

            $grandTotal = 0;

            foreach ($request->items as $item) {
                $totalBiaya = $item['kuantitas'] * $item['harga_satuan'];
                $grandTotal += $totalBiaya;

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $item['deskripsi_tambahan'] ?? null,
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

        // Tampilkan di browser (inline) tanpa download
        return $pdf->stream('invoice-'.$invoice->no_invoice.'.pdf');
    }

    /**
     * Download PDF invoice
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        $invoice->update([
            'status' => 'sent',
        ]);

        // Generate filename dinamis
        $bulan = now()->format('m');
        $namaSiswa = \Illuminate\Support\Str::slug($invoice->siswa->nama_siswa, '_');
        $nomorAkhir = substr($invoice->no_invoice, -4);
        $filename = 'invoice-'.$bulan.'_'.$namaSiswa.'_'.$nomorAkhir.'.pdf';

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));

        // Set paper size to A4 Landscape to ensure everything fits
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
