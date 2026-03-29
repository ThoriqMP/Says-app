<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Http\Resources\ReportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReportApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Report::with(['student', 'category']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('category_id')) {
            $query->where('report_category_id', $request->category_id);
        }

        $reports = $query->latest()->paginate(10);

        return ReportResource::collection($reports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'semester' => 'required|in:Ganjil,Genap',
            'academic_year' => 'required|string|max:9',
            'summary_notes' => 'nullable|string',
            'probing' => 'nullable|array',
            'grades' => 'nullable|array',
            'grades.*.subject_id' => 'required_with:grades|exists:subjects,id',
            'grades.*.score' => 'nullable|numeric',
            // Tambahkan validasi lain untuk grade sesuai kebutuhan
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $report = DB::transaction(function () use ($request) {
            $report = Report::create([
                'student_id' => $request->student_id,
                'report_category_id' => $request->report_category_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
                'summary_notes' => $request->summary_notes,
                'probing_data' => $request->probing ? json_encode($request->probing) : null,
            ]);

            if ($request->has('grades')) {
                foreach ($request->grades as $gradeData) {
                    $report->grades()->create($gradeData);
                }
            }

            return $report;
        });

        return new ReportResource($report->load(['student', 'category', 'grades']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return new ReportResource($report->load(['student', 'category', 'grades.subject']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        // Logika update bisa lebih kompleks, untuk saat ini kita buat sederhana
        $validator = Validator::make($request->all(), [
            'summary_notes' => 'sometimes|nullable|string',
            'probing' => 'sometimes|nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $report->update([
            'summary_notes' => $request->summary_notes ?? $report->summary_notes,
            'probing_data' => $request->probing ? json_encode($request->probing) : $report->probing_data,
        ]);

        // Logika untuk update grades bisa ditambahkan di sini

        return new ReportResource($report->load(['student', 'category', 'grades']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->grades()->delete();
        $report->delete();

        return response()->noContent();
    }
}
