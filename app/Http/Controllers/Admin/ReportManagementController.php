<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ReportHelper;
use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\ProbingActivity;
use App\Models\ReportCategory;
use App\Models\ReportGrade;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentReport::with(['student', 'category', 'teacher']);

        // Filter by Student Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('report_category_id', $request->category_id);
        }

        $reports = $query->latest()->paginate(12)->withQueryString();
        $categories = ReportCategory::orderBy('name')->get();
        
        // Fetch all students for the modal list
        $allStudents = Siswa::orderBy('nama_siswa')->get(['id', 'nama_siswa', 'nis', 'class']);

        return view('admin.reports.index', compact('reports', 'categories', 'allStudents'));
    }

    public function create(Request $request)
    {
        $selectedStudentId = $request->student_id;
        $selectedCategoryId = $request->category_id;
        $students = Siswa::orderBy('nama_siswa')->get();
        $categories = ReportCategory::with('subjects')->get();
        
        // If we don't have student or category yet, we just show the index or handle via modal
        if (!$selectedStudentId || !$selectedCategoryId) {
            return redirect()->route('admin.reports.index')->with('error', 'Silakan pilih siswa dan kategori raport terlebih dahulu.');
        }

        $selectedCategory = ReportCategory::with('subjects')->find($selectedCategoryId);

        return view('admin.reports.create', compact('students', 'categories', 'selectedStudentId', 'selectedCategoryId', 'selectedCategory'));
    }

    public function searchStudents(Request $request)
    {
        $search = $request->get('query');
        $students = Siswa::where('nama_siswa', 'like', "%{$search}%")
            ->orWhere('nis', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama_siswa', 'nis', 'class']);
            
        return response()->json($students);
    }

    public function show(StudentReport $report)
    {
        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher']);
        // Use the same view as the student portal
        return view('student.reports.show', compact('report'));
    }

    public function downloadPdf(StudentReport $report)
    {
        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher']);
        $school = ProfilSekolah::first();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('student.reports.pdf', compact('report', 'school'));
        return $pdf->download("Raport_{$report->student->nama_siswa}_{$report->period}.pdf");
    }

    public function edit(StudentReport $report)
    {
        $report->load(['grades', 'probingActivities']);
        $students = Siswa::orderBy('nama_siswa')->get();
        $categories = ReportCategory::with('subjects')->get();
        return view('admin.reports.edit', compact('report', 'students', 'categories'));
    }

    public function update(Request $request, StudentReport $report)
    {
        Log::info('Updating report: ', $request->all());

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'period' => 'required|string|max:255',
            'summary_notes' => 'nullable|string',
            'grades' => 'array',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pts' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pas' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_remedial' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_harian' => 'nullable|numeric|min:0|max:100',
            'grades.*.predicate' => 'nullable|string|max:10',
            'grades.*.ayat_range' => 'nullable|string|max:255',
            'grades.*.description' => 'nullable|string|max:255',
            'probing' => 'array',
            'probing.*.title' => 'required_with:probing|string|max:255',
            'probing.*.description' => 'nullable|string',
            'probing.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $report->update([
                'siswa_id' => $request->siswa_id,
                'report_category_id' => $request->report_category_id,
                'teacher_id' => Auth::id(), // Update teacher to last editor
                'period' => $request->period,
                'summary_notes' => $request->summary_notes,
            ]);

            // Handle Existing Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $subjectId => $data) {
                    $score = $data['score'] ?? null;
                    $predicate = $data['predicate'] ?? ReportHelper::calculatePredicate($score);

                    ReportGrade::updateOrCreate(
                        ['student_report_id' => $report->id, 'report_subject_id' => $subjectId],
                        [
                            'score' => $score,
                            'score_pts' => $data['score_pts'] ?? null,
                            'score_pas' => $data['score_pas'] ?? null,
                            'score_remedial' => $data['score_remedial'] ?? null,
                            'score_harian' => $data['score_harian'] ?? null,
                            'predicate' => $predicate,
                            'ayat_range' => $data['ayat_range'] ?? null,
                            'description' => $data['description'] ?? null
                        ]
                    );
                }
            }

            // Handle New Subjects
            if ($request->has('new_subjects')) {
                foreach ($request->new_subjects as $newSubject) {
                    if (empty($newSubject['name'])) continue;

                    $subject = ReportSubject::create([
                        'report_category_id' => $request->report_category_id,
                        'name' => $newSubject['name'],
                    ]);

                    $score = $newSubject['score'] ?? null;
                    $predicate = $newSubject['predicate'] ?? ReportHelper::calculatePredicate($score);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subject->id,
                        'score' => $score,
                        'score_pts' => $newSubject['score_pts'] ?? null,
                        'score_pas' => $newSubject['score_pas'] ?? null,
                        'score_remedial' => $newSubject['score_remedial'] ?? null,
                        'score_harian' => $newSubject['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $newSubject['ayat_range'] ?? null,
                        'description' => $newSubject['description'] ?? null,
                    ]);
                }
            }

            // Handle Probing (Keep old images if not replaced)
            if ($request->has('probing')) {
                // For Probing, we might want to be more specific about updates.
                // But let's follow the previous logic of replacing for simplicity, 
                // OR better: track by index/ID.
                
                // For now, let's keep the "replace all" but update fields.
                foreach($report->probingActivities as $old) {
                    if ($old->image_path) Storage::disk('public')->delete($old->image_path);
                    $old->delete();
                }

                foreach ($request->probing as $activity) {
                    if (empty($activity['title'])) continue;

                    $imagePath = null;
                    if (isset($activity['image']) && $activity['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $activity['image']->store('probing_activities', 'public');
                    }

                    ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'activity_name' => $activity['title'], // activity_name is used as title in some places
                        'activity_title' => $activity['title'],
                        'description' => $activity['description'] ?? null,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('students.show', $report->siswa_id)->with('success', 'Raport siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui raport: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui raport: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isPimpinan()) {
            abort(403, 'Hanya Pimpinan yang dapat menghapus raport.');
        }

        $siswaId = $report->siswa_id;
        DB::beginTransaction();
        try {
            foreach($report->probingActivities as $old) {
                if ($old->image_path) Storage::disk('public')->delete($old->image_path);
            }
            $report->delete();
            DB::commit();
            return redirect()->route('students.show', $siswaId)->with('success', 'Raport berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus raport.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Storing report: ', $request->all());

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'period' => 'required|string|max:255',
            'summary_notes' => 'nullable|string',
            'grades' => 'array',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pts' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_remedial' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_harian' => 'nullable|numeric|min:0|max:100',
            'grades.*.predicate' => 'nullable|string|max:10',
            'grades.*.ayat_range' => 'nullable|string|max:255',
            'grades.*.description' => 'nullable|string|max:255',
            'new_subjects' => 'array',
            'new_subjects.*.name' => 'required_with:new_subjects|string|max:255',
            'new_subjects.*.score' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.score_pts' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.score_pas' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.score_remedial' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.score_harian' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.predicate' => 'nullable|string|max:10',
            'new_subjects.*.ayat_range' => 'nullable|string|max:255',
            'new_subjects.*.description' => 'nullable|string|max:255',
            'probing' => 'array',
            'probing.*.title' => 'required_with:probing|string|max:255',
            'probing.*.description' => 'nullable|string',
            'probing.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $report = StudentReport::create([
                'siswa_id' => $request->siswa_id,
                'report_category_id' => $request->report_category_id,
                'teacher_id' => Auth::id(),
                'period' => $request->period,
                'summary_notes' => $request->summary_notes,
            ]);
            Log::info('Report created: ' . $report->id);

            // Handle Existing Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $subjectId => $data) {
                    $score = $data['score'] ?? null;
                    $predicate = $data['predicate'] ?? ReportHelper::calculatePredicate($score);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subjectId,
                        'score' => $score,
                        'score_pts' => $data['score_pts'] ?? null,
                        'score_pas' => $data['score_pas'] ?? null,
                        'score_remedial' => $data['score_remedial'] ?? null,
                        'score_harian' => $data['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $data['ayat_range'] ?? null,
                        'description' => $data['description'] ?? null,
                    ]);
                }
            }

            // Handle New Subjects
            if ($request->has('new_subjects')) {
                foreach ($request->new_subjects as $newSubject) {
                    if (empty($newSubject['name'])) continue;

                    $subject = ReportSubject::create([
                        'report_category_id' => $request->report_category_id,
                        'name' => $newSubject['name'],
                    ]);

                    $score = $newSubject['score'] ?? null;
                    $predicate = $newSubject['predicate'] ?? ReportHelper::calculatePredicate($score);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subject->id,
                        'score' => $score,
                        'score_pts' => $newSubject['score_pts'] ?? null,
                        'score_pas' => $newSubject['score_pas'] ?? null,
                        'score_remedial' => $newSubject['score_remedial'] ?? null,
                        'score_harian' => $newSubject['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $newSubject['ayat_range'] ?? null,
                        'description' => $newSubject['description'] ?? null,
                    ]);
                }
            }

            // Handle Probing Activities
            if ($request->has('probing')) {
                foreach ($request->probing as $activity) {
                    if (empty($activity['title'])) continue;

                    $imagePath = null;
                    if (isset($activity['image']) && $activity['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $activity['image']->store('probing_activities', 'public');
                    }

                    ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'activity_name' => $activity['title'],
                        'activity_title' => $activity['title'],
                        'description' => $activity['description'] ?? null,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('students.show', $report->siswa_id)->with('success', 'Raport siswa berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan raport: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan raport: ' . $e->getMessage())->withInput();
        }
    }
}
