<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProbingActivity;
use App\Models\ReportCategory;
use App\Models\ReportGrade;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentReport::with(['student', 'category']);

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

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    public function create(Request $request)
    {
        $selectedStudentId = $request->student_id;
        $selectedCategoryId = $request->category_id;
        $students = Siswa::orderBy('nama_siswa')->get();
        $categories = ReportCategory::with('subjects')->get();
        $selectedCategory = ReportCategory::with('subjects')->find($selectedCategoryId);

        return view('admin.reports.create', compact('students', 'categories', 'selectedStudentId', 'selectedCategoryId', 'selectedCategory'));
    }

    public function show(StudentReport $report)
    {
        $report->load(['student', 'category', 'grades.subject', 'probingActivities']);
        return view('admin.reports.show', compact('report'));
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
            'grades.*.description' => 'nullable|string|max:255',
            'probing' => 'array',
            'probing.*.name' => 'required_with:probing|string|max:255',
            'probing.*.description' => 'nullable|string',
            'probing.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $report->update([
                'siswa_id' => $request->siswa_id,
                'report_category_id' => $request->report_category_id,
                'period' => $request->period,
                'summary_notes' => $request->summary_notes,
            ]);

            // Handle Existing Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $subjectId => $data) {
                    if (isset($data['score']) && $data['score'] !== '') {
                        ReportGrade::updateOrCreate(
                            ['student_report_id' => $report->id, 'report_subject_id' => $subjectId],
                            ['score' => $data['score'], 'description' => $data['description'] ?? null]
                        );
                    }
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

                    if (isset($newSubject['score']) && $newSubject['score'] !== '') {
                        ReportGrade::create([
                            'student_report_id' => $report->id,
                            'report_subject_id' => $subject->id,
                            'score' => $newSubject['score'],
                            'description' => $newSubject['description'] ?? null,
                        ]);
                    }
                }
            }

            // Handle Probing (Keep old images if not replaced)
            if ($request->has('probing')) {
                // If we want to replace all, we should delete old activities.
                // But typically we might want to keep some. For simplicity, let's replace all.
                // In a real app, we might want to track IDs.
                
                // Let's delete old ones.
                foreach($report->probingActivities as $old) {
                    if ($old->image_path) Storage::disk('public')->delete($old->image_path);
                    $old->delete();
                }

                foreach ($request->probing as $activity) {
                    if (empty($activity['name'])) continue;

                    $imagePath = null;
                    if (isset($activity['image']) && $activity['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $activity['image']->store('probing_activities', 'public');
                    }

                    ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'activity_name' => $activity['name'],
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
            'grades.*.description' => 'nullable|string|max:255',
            'new_subjects' => 'array',
            'new_subjects.*.name' => 'required_with:new_subjects|string|max:255',
            'new_subjects.*.score' => 'nullable|numeric|min:0|max:100',
            'new_subjects.*.description' => 'nullable|string|max:255',
            'probing' => 'array',
            'probing.*.name' => 'required_with:probing|string|max:255',
            'probing.*.description' => 'nullable|string',
            'probing.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
        ]);

        DB::beginTransaction();
        try {
            $report = StudentReport::create([
                'siswa_id' => $request->siswa_id,
                'report_category_id' => $request->report_category_id,
                'period' => $request->period,
                'summary_notes' => $request->summary_notes,
            ]);
            Log::info('Report created: ' . $report->id);

            // Handle Existing Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $subjectId => $data) {
                    if (isset($data['score']) && $data['score'] !== '') {
                        ReportGrade::create([
                            'student_report_id' => $report->id,
                            'report_subject_id' => $subjectId,
                            'score' => $data['score'],
                            'description' => $data['description'] ?? null,
                        ]);
                    }
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

                    if (isset($newSubject['score']) && $newSubject['score'] !== '') {
                        ReportGrade::create([
                            'student_report_id' => $report->id,
                            'report_subject_id' => $subject->id,
                            'score' => $newSubject['score'],
                            'description' => $newSubject['description'] ?? null,
                        ]);
                    }
                }
            }

            // Handle Probing Activities
            if ($request->has('probing')) {
                foreach ($request->probing as $activity) {
                    if (empty($activity['name'])) continue;

                    $imagePath = null;
                    if (isset($activity['image']) && $activity['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $activity['image']->store('probing_activities', 'public');
                    }

                    ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'activity_name' => $activity['name'],
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
