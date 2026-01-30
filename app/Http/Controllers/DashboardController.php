<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Layanan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        // Global Stats (All Time)
        $totalInvoices = Invoice::count();
        $totalStudents = Siswa::count();
        $totalServices = Layanan::count();

        // Filtered Stats Query
        $invoiceQuery = Invoice::query()->whereYear('jatuh_tempo', $year);
        if ($month) {
            $invoiceQuery->whereMonth('jatuh_tempo', $month);
        }

        // Clone query for different stats to avoid interference
        $pendingInvoices = (clone $invoiceQuery)->where('status', 'sent')->count();
        $paidInvoices = (clone $invoiceQuery)->where('status', 'paid')->count();
        $overdueInvoices = (clone $invoiceQuery)->where('status', 'overdue')->count();
        $draftInvoices = (clone $invoiceQuery)->where('status', 'draft')->count();

        // Total Revenue (Paid Invoices in selected period)
        $revenue = (clone $invoiceQuery)->where('status', 'paid')->sum('grand_total');
        
        // Potential Revenue (Sent + Overdue + Draft)
        $potentialRevenue = (clone $invoiceQuery)->whereIn('status', ['sent', 'overdue'])->sum('grand_total');

        // Chart Data 1: Monthly Revenue Trend (For the selected Year)
        // Ignores 'month' filter to show full year trend
        $monthlyRevenueData = Invoice::whereYear('jatuh_tempo', $year)
            ->where('status', 'paid')
            ->select(
                DB::raw('MONTH(jatuh_tempo) as month'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill 1-12
        $revenueChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueChart[] = $monthlyRevenueData[$i] ?? 0;
        }

        // Chart Data 2: Status Distribution (For the selected Period)
        $statusDistribution = (clone $invoiceQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $statusChart = [
            'paid' => $statusDistribution['paid'] ?? 0,
            'sent' => $statusDistribution['sent'] ?? 0,
            'overdue' => $statusDistribution['overdue'] ?? 0,
            'draft' => $statusDistribution['draft'] ?? 0,
        ];

        return view('dashboard', compact(
            'totalInvoices', 'totalStudents', 'totalServices',
            'pendingInvoices', 'paidInvoices', 'overdueInvoices', 'draftInvoices',
            'revenue', 'potentialRevenue',
            'revenueChart', 'statusChart',
            'year', 'month'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = Invoice::with(['siswa', 'invoiceDetails.layanan'])
            ->whereYear('jatuh_tempo', $year);

        if ($month) {
            $query->whereMonth('jatuh_tempo', $month);
        }
        
        // Sort by Date
        $query->orderBy('jatuh_tempo', 'desc');

        $filename = 'laporan_pendapatan_' . $year . ($month ? '_bulan_'.$month : '') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM

            fputcsv($handle, [
                'Tanggal Jatuh Tempo',
                'No Invoice',
                'Siswa',
                'Item',
                'Harga',
                'Status',
                'Total Invoice'
            ]);

            $query->chunk(100, function ($invoices) use ($handle) {
                foreach ($invoices as $invoice) {
                    foreach ($invoice->invoiceDetails as $detail) {
                        $itemName = $detail->layanan->nama_layanan ?? '-';
                        if ($detail->deskripsi_tambahan) $itemName .= ' (' . $detail->deskripsi_tambahan . ')';

                        fputcsv($handle, [
                            $invoice->jatuh_tempo->format('Y-m-d'),
                            $invoice->no_invoice,
                            $invoice->siswa->nama_siswa ?? 'Unknown',
                            $itemName,
                            $detail->harga_satuan,
                            ucfirst($invoice->status),
                            $invoice->grand_total
                        ]);
                    }
                }
            });
            fclose($handle);
        }, $filename);
    }
}
