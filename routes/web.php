<?php

use App\Http\Controllers\Assessment\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyMappingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Invoice Routes
    Route::get('/students/search', [InvoiceController::class, 'searchStudents'])->name('students.search');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/preview', [InvoiceController::class, 'previewPdf'])->name('invoices.preview');

    // Student Routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Subjects Routes
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // Service Routes
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // School Profile Routes
    Route::get('/school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
    Route::put('/school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');

    // Psychological Assessment Routes
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    Route::get('/assessments/{assessment}/pdf', [AssessmentController::class, 'pdf'])->name('assessments.pdf');
    Route::get('/assessments/{assessment}/pdf/view', [AssessmentController::class, 'pdfView'])->name('assessments.pdf.view');

    // New Psychological Assessment Module
    Route::prefix('/psychological-assessments')->name('psychological-assessments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'store'])->name('store');
        Route::get('/{assessment}/edit', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'edit'])->name('edit');
        Route::put('/{assessment}', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'update'])->name('update');
        Route::get('/{assessment}', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'show'])->name('show');
        Route::get('/{assessment}/pdf', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'pdf'])->name('pdf');
        Route::get('/{assessment}/docx', [\App\Http\Controllers\PsychologicalAssessment\PsychologicalAssessmentController::class, 'docx'])->name('docx');
    });

    // Family Mapping Routes
    Route::get('/family-mapping', [FamilyMappingController::class, 'index'])->name('family-mapping.index');
    Route::get('/family-mapping/pdf', [FamilyMappingController::class, 'pdf'])->name('family-mapping.pdf');
});

// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});
