<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('assessments')->orderBy('name')->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:50',
        ]);

        Subject::create($data);

        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil dibuat.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:50',
        ]);

        $subject->update($data);

        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->assessments()->exists()) {
            return redirect()
                ->route('subjects.index')
                ->with('error', 'Subjek tidak bisa dihapus karena sudah memiliki data asesmen.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subjek berhasil dihapus.');
    }
}
