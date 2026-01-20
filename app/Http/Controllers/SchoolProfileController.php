<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SchoolProfileController extends Controller
{
    /**
     * Show the form for editing school profile.
     */
    public function edit()
    {
        $schoolProfile = ProfilSekolah::first();

        if (! $schoolProfile) {
            $schoolProfile = ProfilSekolah::create([
                'nama_sekolah' => 'Nama Sekolah',
                'alamat' => 'Alamat Sekolah',
                'logo_path' => null,
                'bank_nama' => '',
                'no_rekening' => '',
                'atas_nama' => '',
                'pimpinan_nama' => '',
            ]);
        }

        return view('school-profile.edit', compact('schoolProfile'));
    }

    /**
     * Update school profile.
     */
    public function update(Request $request)
    {
        $schoolProfile = ProfilSekolah::first();

        if (! $schoolProfile) {
            return redirect()->back()->with('error', 'Profil sekolah tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'bank_nama' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:255',
            'pimpinan_nama' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'signature_data' => 'nullable|string', // For canvas signature
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle logo upload
        $logoPath = $schoolProfile->logo_path;
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($schoolProfile->logo_path) {
                Storage::disk('public')->delete($schoolProfile->logo_path);
            }

            // Simpan logo baru
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Handle signature upload
        $signaturePath = $schoolProfile->signature_path;
        if ($request->hasFile('signature')) {
            // Hapus signature lama jika ada
            if ($schoolProfile->signature_path) {
                Storage::disk('public')->delete($schoolProfile->signature_path);
            }

            // Simpan signature baru
            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }

        // Handle canvas signature
        if ($request->filled('signature_data')) {
            // Hapus signature lama jika ada
            if ($schoolProfile->signature_path) {
                Storage::disk('public')->delete($schoolProfile->signature_path);
            }

            // Decode base64 signature
            $signatureData = $request->signature_data;
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $imageData = base64_decode($signatureData);

            // Generate filename
            $filename = 'signatures/signature_'.time().'.png';

            // Simpan signature
            Storage::disk('public')->put($filename, $imageData);
            $signaturePath = $filename;
        }

        $schoolProfile->update([
            'nama_sekolah' => $request->nama_sekolah,
            'alamat' => $request->alamat,
            'logo_path' => $logoPath,
            'bank_nama' => $request->bank_nama,
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
            'pimpinan_nama' => $request->pimpinan_nama,
            'signature_path' => $signaturePath,
        ]);

        return redirect()->back()
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}
