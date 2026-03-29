<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('student');

        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('student', function ($q) use ($request) {
                    $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                      ->orWhere('nama_orang_tua', 'like', '%' . $request->search . '%');
                });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);

        return InvoiceResource::collection($invoices);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'tanggal_invoice' => 'required|date',
            'jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|in:draft,sent,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:services,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $invoice = DB::transaction(function () use ($request) {
            $total = collect($request->items)->sum(function ($item) {
                return $item['kuantitas'] * $item['harga_satuan'];
            });

            $invoice = Invoice::create([
                'student_id' => $request->student_id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total_amount' => $total,
                'status' => $request->status,
            ]);

            $invoice->items()->createMany($request->items);

            return $invoice;
        });

        return new InvoiceResource($invoice->load(['student', 'items.service']));
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice->load(['student', 'items.service']));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Logika update invoice bisa sangat kompleks, ini versi sederhananya
        $invoice->update($request->only(['status']));
        return new InvoiceResource($invoice->fresh(['student', 'items.service']));
    }

    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $invoice->items()->delete();
            $invoice->delete();
        });

        return response()->noContent();
    }
}
