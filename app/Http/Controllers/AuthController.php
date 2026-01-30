<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($this->getRedirectPath());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Get redirect path based on user permissions
     */
    private function getRedirectPath()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pimpinan always goes to dashboard
        if ($user->isPimpinan()) {
            return route('dashboard');
        }

        // Check permissions in priority order
        $permissions = [
            'dashboard' => 'dashboard',
            'invoices.index' => 'invoices.index',
            'assessments.index' => 'assessments.index',
            'psychological-assessments.index' => 'psychological-assessments.index',
            'family-mapping.index' => 'family-mapping.index',
            'subjects.index' => 'subjects.index',
            'students.index' => 'students.index',
            'services.index' => 'services.index',
            'school-profile.edit' => 'school-profile.edit',
        ];

        foreach ($permissions as $permission => $route) {
            if ($user->hasPermission($permission)) {
                return route($route);
            }
        }

        // Fallback if no permissions (should not happen ideally)
        return route('dashboard');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,pimpinan',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
