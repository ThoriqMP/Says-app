<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use Illuminate\Http\Request;

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
        ]);

        ReportCategory::create($request->all());

        return redirect()->route('admin.report-categories.index')
            ->with('success', 'Kategori raport berhasil ditambahkan.');
    }

    public function edit(ReportCategory $reportCategory)
    {
        return view('admin.report-categories.edit', compact('reportCategory'));
    }

    public function update(Request $request, ReportCategory $reportCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:report_categories,name,' . $reportCategory->id,
        ]);

        $reportCategory->update($request->all());

        return redirect()->route('admin.report-categories.index')
            ->with('success', 'Kategori raport berhasil diperbarui.');
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
