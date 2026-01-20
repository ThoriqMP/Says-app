<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Layanan;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInvoices = Invoice::count();
        $totalStudents = Siswa::count();
        $totalServices = Layanan::count();

        // Hitung invoice berdasarkan status
        $pendingInvoices = Invoice::where('status', 'sent')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        // Hitung total pendapatan bulan ini
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('tanggal_invoice', now()->month)
            ->sum('grand_total');

        // Data untuk chart
        $monthlyData = Invoice::select(
            DB::raw('MONTH(tanggal_invoice) as month'),
            DB::raw('SUM(grand_total) as total'),
            'status'
        )
            ->whereYear('tanggal_invoice', now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_invoice)'), 'status')
            ->get();

        return view('dashboard', compact(
            'totalInvoices',
            'totalStudents',
            'totalServices',
            'pendingInvoices',
            'paidInvoices',
            'overdueInvoices',
            'monthlyRevenue',
            'monthlyData'
        ));
    }
}
