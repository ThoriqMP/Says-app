@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Profil Sekolah</h2>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 dark:text-green-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-300">Berhasil</h3>
                                <div class="mt-1 text-sm text-green-700 dark:text-green-200">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Error</h3>
                                <div class="mt-1 text-sm text-red-700 dark:text-red-200">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Terjadi kesalahan</h3>
                                <div class="mt-1 text-sm text-red-700 dark:text-red-200">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('school-profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Logo Sekolah -->
                    <div class="mb-8">
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Sekolah</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            @if($schoolProfile->logo_path)
                                <img src="{{ Storage::url($schoolProfile->logo_path) }}" alt="Logo Sekolah" class="h-20 w-20 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                            @else
                                <div class="h-20 w-20 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">No Logo</span>
                                </div>
                            @endif
                            <div>
                                <input type="file" id="logo" name="logo" accept="image/*"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Sekolah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Sekolah</label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah', $schoolProfile->nama_sekolah) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        
                        <div>
                            <label for="pimpinan_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pimpinan</label>
                            <input type="text" id="pimpinan_nama" name="pimpinan_nama" value="{{ old('pimpinan_nama', $schoolProfile->pimpinan_nama) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>

                    <!-- Tanda Tangan Pimpinan -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanda Tangan Pimpinan</label>
                        
                        <!-- Current Signature -->
                        @if($schoolProfile->signature_path)
                            <div class="mb-4">
                                <img src="{{ Storage::url($schoolProfile->signature_path) }}" alt="Tanda Tangan" class="h-20 object-contain border border-gray-300 dark:border-gray-600 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tanda tangan saat ini</p>
                            </div>
                        @endif

                        <!-- Signature Options -->
                        <div class="space-y-4">
                            <!-- Upload Signature -->
                            <div>
                                <label for="signature" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Upload Tanda Tangan (JPG/PNG)</label>
                                <input type="file" id="signature" name="signature" accept="image/jpeg,image/jpg,image/png"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            </div>

                            <!-- OR Divider -->
                            <div class="flex items-center">
                                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                                <span class="mx-4 text-sm text-gray-500 dark:text-gray-400">ATAU</span>
                                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            </div>

                            <!-- Draw Signature -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Gambar Tanda Tangan</label>
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                                    <canvas id="signatureCanvas" width="400" height="150" class="border border-gray-200 dark:border-gray-600 rounded bg-white cursor-crosshair w-full max-w-full"></canvas>
                                    <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                        <button type="button" id="clearSignature" class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 rounded-md text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                                            Hapus
                                        </button>
                                        <button type="button" id="saveSignature" class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 rounded-md text-sm font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                                            Simpan Gambar
                                        </button>
                                    </div>
                                    <input type="hidden" name="signature_data" id="signatureData" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Sekolah</label>
                        <textarea id="alamat" name="alamat" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('alamat', $schoolProfile->alamat) }}</textarea>
                    </div>

                    <!-- Informasi Bank -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Informasi Rekening Bank</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="bank_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Bank</label>
                                <input type="text" id="bank_nama" name="bank_nama" value="{{ old('bank_nama', $schoolProfile->bank_nama) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label for="no_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Rekening</label>
                                <input type="text" id="no_rekening" name="no_rekening" value="{{ old('no_rekening', $schoolProfile->no_rekening) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label for="atas_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Atas Nama</label>
                                <input type="text" id="atas_nama" name="atas_nama" value="{{ old('atas_nama', $schoolProfile->atas_nama) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clearSignature');
    const saveBtn = document.getElementById('saveSignature');
    const signatureDataInput = document.getElementById('signatureData');
    
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    
    // Set canvas size
    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        
        // Set drawing styles
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Get mouse/touch position
    function getPosition(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }
    
    // Start drawing
    function startDrawing(e) {
        isDrawing = true;
        const pos = getPosition(e);
        lastX = pos.x;
        lastY = pos.y;
    }
    
    // Draw
    function draw(e) {
        if (!isDrawing) return;
        
        const pos = getPosition(e);
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        
        lastX = pos.x;
        lastY = pos.y;
    }
    
    // Stop drawing
    function stopDrawing() {
        isDrawing = false;
    }
    
    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Touch events for mobile
    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        canvas.dispatchEvent(mouseEvent);
    });
    
    // Clear canvas
    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureDataInput.value = '';
    });
    
    // Save signature
    saveBtn.addEventListener('click', function() {
        const dataURL = canvas.toDataURL('image/png');
        signatureDataInput.value = dataURL;
        
        // Show success message
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Tersimpan!';
        saveBtn.classList.remove('bg-green-100', 'text-green-700', 'hover:bg-green-200', 'dark:bg-green-900', 'dark:text-green-300', 'dark:hover:bg-green-800');
        saveBtn.classList.add('bg-green-500', 'text-white');
        
        setTimeout(function() {
            saveBtn.textContent = originalText;
            saveBtn.classList.remove('bg-green-500', 'text-white');
            saveBtn.classList.add('bg-green-100', 'text-green-700', 'hover:bg-green-200', 'dark:bg-green-900', 'dark:text-green-300', 'dark:hover:bg-green-800');
        }, 2000);
    });
});
</script>
@endsection
