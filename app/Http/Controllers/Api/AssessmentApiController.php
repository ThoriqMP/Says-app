<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Http\Resources\AssessmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AssessmentApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessment::with('subject');

        if ($request->has('search')) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $assessments = $query->latest()->paginate(10);

        return AssessmentResource::collection($assessments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'test_date' => 'required|date',
            'psychologist_name' => 'required|string|max:255',
            'psychological' => 'nullable|array',
            'talents' => 'nullable|array',
            'scores' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $assessment = DB::transaction(function () use ($request) {
            $assessment = Assessment::create([
                'subject_id' => $request->subject_id,
                'test_date' => $request->test_date,
                'psychologist_name' => $request->psychologist_name,
            ]);

            if ($request->has('psychological')) {
                $assessment->psychologicalAssessment()->create($request->psychological);
            }

            if ($request->has('talents')) {
                $assessment->talentsMapping()->create($request->talents);
            }

            if ($request->has('scores')) {
                foreach ($request->scores as $category => $scores) {
                    $assessment->scores()->create([
                        'category' => $category,
                        'scores' => $scores,
                    ]);
                }
            }

            return $assessment;
        });

        return new AssessmentResource($assessment->load(['subject', 'psychologicalAssessment', 'talentsMapping', 'scores']));
    }

    public function show(Assessment $assessment)
    {
        return new AssessmentResource($assessment->load(['subject', 'psychologicalAssessment', 'talentsMapping', 'scores']));
    }

    public function update(Request $request, Assessment $assessment)
    {
        // Logika update dibuat sederhana untuk contoh ini
        $assessment->update($request->only(['test_date', 'psychologist_name']));

        if ($request->has('psychological')) {
            $assessment->psychologicalAssessment()->update($request->psychological);
        }

        // ... logika update untuk relasi lain

        return new AssessmentResource($assessment->load(['subject', 'psychologicalAssessment', 'talentsMapping', 'scores']));
    }

    public function destroy(Assessment $assessment)
    {
        DB::transaction(function () use ($assessment) {
            $assessment->psychologicalAssessment()->delete();
            $assessment->talentsMapping()->delete();
            $assessment->scores()->delete();
            $assessment->delete();
        });

        return response()->noContent();
    }
}
