<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use App\Models\ReportSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCategoryController extends Controller
{
    public function index()
    {
        $categories = ReportCategory::withCount('subjects')->get();
        return view('admin.report-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.report-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:report_categories,name',
            'subjects' => 'nullable|array',
            'subjects.*' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $category = ReportCategory::create(['name' => $request->name]);

            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectName) {
                    if (!empty($subjectName)) {
                        ReportSubject::create([
                            'report_category_id' => $category->id,
                            'name' => $subjectName
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.report-categories.index')
                ->with('success', 'Kategori raport berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(ReportCategory $reportCategory)
    {
        $reportCategory->load('subjects');
        return view('admin.report-categories.edit', compact('reportCategory'));
    }

    public function update(Request $request, ReportCategory $reportCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:report_categories,name,' . $reportCategory->id,
            'subjects' => 'nullable|array',
            'subjects.*.id' => 'nullable|exists:report_subjects,id',
            'subjects.*.name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $reportCategory->update(['name' => $request->name]);

            // Handle subjects
            $existingSubjectIds = $reportCategory->subjects()->pluck('id')->toArray();
            $updatedSubjectIds = [];

            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectData) {
                    if (isset($subjectData['id']) && !empty($subjectData['id'])) {
                        // Update existing
                        $subject = ReportSubject::find($subjectData['id']);
                        $subject->update(['name' => $subjectData['name']]);
                        $updatedSubjectIds[] = $subject->id;
                    } else {
                        // Create new
                        $newSubject = ReportSubject::create([
                            'report_category_id' => $reportCategory->id,
                            'name' => $subjectData['name']
                        ]);
                        $updatedSubjectIds[] = $newSubject->id;
                    }
                }
            }

            // Delete subjects not in the updated list
            $toDelete = array_diff($existingSubjectIds, $updatedSubjectIds);
            ReportSubject::whereIn('id', $toDelete)->delete();

            DB::commit();
            return redirect()->route('admin.report-categories.index')
                ->with('success', 'Kategori raport berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(ReportCategory $reportCategory)
    {
        if ($reportCategory->subjects()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki subjek nilai.');
        }

        $reportCategory->delete();

        return redirect()->route('admin.report-categories.index')
            ->with('success', 'Kategori raport berhasil dihapus.');
    }
}
