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
        $latestReports = StudentReport::with(['category', 'teacher'])->where('siswa_id', $student->id)->latest()->take(4)->get();

        return view('student.dashboard', compact('student', 'latestInvoice', 'latestReports'));
    }

    public function invoices()
    {
        $student = Auth::user()->siswa;
        $invoices = Invoice::where('id_siswa', $student->id)->latest()->paginate(10);
        return view('student.invoices.index', compact('invoices'));
    }

    public function invoiceDetail(Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student' && $invoice->id_siswa !== $user->siswa->id) {
            abort(403);
        }

        $invoice->load(['siswa', 'invoiceDetails.layanan']);
        $schoolProfile = \App\Models\ProfilSekolah::first();
        return view('student.invoices.show', compact('invoice', 'schoolProfile'));
    }

    public function reports()
    {
        $student = Auth::user()->siswa;
        $reports = StudentReport::with(['category', 'teacher'])->where('siswa_id', $student->id)->latest()->get();
        return view('student.reports.index', compact('reports'));
    }

    public function reportDetail(StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Policy check: Student can only see their own. Admin/Guru (non-student) can see all.
        if ($user->role === 'student' && $report->siswa_id !== $user->siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke raport ini.');
        }

        $report->load(['category', 'grades.subject', 'probingActivities', 'teacher']);
        return view('student.reports.show', compact('report'));
    }

    public function downloadInvoicePdf(Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student' && $invoice->id_siswa !== $user->siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Reuse existing PDF logic from InvoiceController or implement here
        return redirect()->route('invoices.pdf', $invoice);
    }

    public function downloadReportPdf(StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student' && $report->siswa_id !== $user->siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke raport ini.');
        }

        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher']);
        $pdf = Pdf::loadView('student.reports.pdf', compact('report'));
        return $pdf->download("Raport_{$report->student->nama_siswa}_{$report->period}.pdf");
    }
}
