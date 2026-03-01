<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    private $permissions = [
        'dashboard' => 'Dashboard',
        'invoices.index' => 'Invoice',
        'reports.manage' => 'Manajemen Raport',
        'assessments.index' => 'Personal Mapping',
        'psychological-assessments.index' => 'Asesmen Psikologis',
        'family-mapping.index' => 'Family Mapping',
        'subjects.index' => 'Subjek',
        'students.index' => 'Siswa',
        'services.index' => 'Layanan',
        'school-profile.edit' => 'Profil Sekolah',
    ];

    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'guru'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->paginate(10);
        return view('admin-management.index', compact('admins'));
    }

    public function create(Request $request)
    {
        $role = $request->query('role', 'admin');
        if (!in_array($role, ['admin', 'guru'])) {
            $role = 'admin';
        }
        $permissions = $this->permissions;
        return view('admin-management.create', compact('permissions', 'role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,guru',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('admin-management.index')
            ->with('success', ucfirst($validated['role']) . ' berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'guru'])) {
            abort(404);
        }
        $permissions = $this->permissions;
        return view('admin-management.edit', compact('admin', 'permissions'));
    }

    public function update(Request $request, User $admin)
    {
        if (!in_array($admin->role, ['admin', 'guru'])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,guru',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? [],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $admin->update($data);

        return redirect()->route('admin-management.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->delete();

        return redirect()->route('admin-management.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
