<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\StudentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->siswa;
        if (!$student) {
            return redirect('/')->with('error', 'Profil siswa tidak ditemukan.');
        }

        $latestInvoice = Invoice::where('id_siswa', $student->id)->latest()->first();
        $latestReports = StudentReport::with('category')->where('siswa_id', $student->id)->latest()->take(4)->get();

        return view('student.dashboard', compact('student', 'latestInvoice', 'latestReports'));
    }

    public function invoices()
    {
        $student = Auth::user()->siswa;
        $invoices = Invoice::where('id_siswa', $student->id)->latest()->paginate(10);
        return view('student.invoices.index', compact('invoices'));
    }

    public function reports()
    {
        $student = Auth::user()->siswa;
        $reports = StudentReport::with('category')->where('siswa_id', $student->id)->latest()->get();
        return view('student.reports.index', compact('reports'));
    }

    public function reportDetail(StudentReport $report)
    {
        // Policy check
        if ($report->siswa_id !== Auth::user()->siswa->id) {
            abort(403);
        }

        $report->load(['category', 'grades.subject', 'probingActivities']);
        return view('student.reports.show', compact('report'));
    }

    public function downloadInvoicePdf(Invoice $invoice)
    {
        // Reuse existing PDF logic from InvoiceController or implement here
        // For brevity, assuming access to PDF generation
        return redirect()->route('invoices.pdf', $invoice);
    }

    public function downloadReportPdf(StudentReport $report)
    {
        if ($report->siswa_id !== Auth::user()->siswa->id) {
            abort(403);
        }

        $report->load(['student', 'category', 'grades.subject', 'probingActivities']);
        $pdf = Pdf::loadView('student.reports.pdf', compact('report'));
        return $pdf->download("Raport_{$report->student->nama_siswa}_{$report->period}.pdf");
    }
}
