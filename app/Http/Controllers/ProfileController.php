<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'signature' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle signature file upload
        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($user->signature_path) {
                Storage::disk('public')->delete($user->signature_path);
            }
            $userData['signature_path'] = $request->file('signature')->store('signatures/users', 'public');
        }

        // Handle canvas signature data
        if ($request->filled('signature_data')) {
            // Delete old signature if exists
            if ($user->signature_path) {
                Storage::disk('public')->delete($user->signature_path);
            }

            // Decode base64 signature
            $signatureData = $request->signature_data;
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $imageData = base64_decode($signatureData);

            // Generate filename
            $filename = 'signatures/users/sig_' . $user->id . '_' . time() . '.png';

            // Store signature
            Storage::disk('public')->put($filename, $imageData);
            $userData['signature_path'] = $filename;
        }

        $user->update($userData);

        return redirect()->back()->with('success', 'Profil dan tanda tangan berhasil diperbarui.');
    }
}
