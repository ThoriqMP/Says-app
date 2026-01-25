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
            'date_of_birth' => 'required|date',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:50',
        ]);

        // Calculate age for backward compatibility or sorting
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

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
            'date_of_birth' => 'nullable|date', // Nullable in case they don't want to update it, but preferably required
            'age' => 'nullable|integer|min:0|max:120', // Allow manual age update if DOB is not provided? No, user wants replacement.
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:50',
        ]);

        if ($request->filled('date_of_birth')) {
             $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }
        // If age is provided manually (legacy), it might be in $data['age'] already
        
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
