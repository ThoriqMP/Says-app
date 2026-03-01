<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function show(Siswa $student)
    {
        $student->load(['user', 'reports.category', 'reports.grades', 'reports.probingActivities']);
        $categories = \App\Models\ReportCategory::all();
        return view('students.show', compact('student', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $userId = null;
            if ($request->filled('email')) {
                $user = User::create([
                    'name' => $request->nama_siswa,
                    'email' => $request->email,
                    'password' => bcrypt($request->password ?? 'password123'),
                    'role' => 'student',
                ]);
                $userId = $user->id;
                Log::info('User created: ' . $user->id);
            }

            $siswa = Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'nama_orang_tua' => $request->nama_orang_tua,
                'alamat_tagihan' => $request->alamat_tagihan,
                'user_id' => $userId,
                'nis' => $request->nis,
                'class' => $request->class,
            ]);
            Log::info('Siswa created: ' . $siswa->id);

            DB::commit();
            return redirect()->route('students.show', $siswa->id)
                ->with('success', 'Siswa dan akun login berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan siswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Siswa $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Siswa $student)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $student)
    {
        if ($student->invoices()->count() > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Siswa tidak dapat dihapus karena memiliki invoice.');
        }

        DB::beginTransaction();
        try {
            if ($student->user) {
                $student->user->delete();
            }
            $student->delete();
            DB::commit();
            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('students.index')
                ->with('error', 'Gagal menghapus siswa.');
        }
    }
}
