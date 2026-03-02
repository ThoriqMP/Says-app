@extends('layouts.app')

@section('title', 'Update Profil')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Update Profil</h2>
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

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   placeholder="Masukkan nama lengkap"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   placeholder="example@email.com"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Password Baru (Kosongkan jika tidak ganti)</label>
                            <input type="password" id="password" name="password"
                                   placeholder="Masukkan password baru"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password baru"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                    </div>

                    @if($user->isAdmin() || $user->isPimpinan())
                        <!-- Tanda Tangan User -->
                        <div class="mb-8 border-t border-gray-100 dark:border-gray-700 pt-8">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Tanda Tangan Digital</h3>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanda tangan Anda akan ditampilkan pada raport yang Anda terbitkan.</label>
                            
                            <!-- Current Signature -->
                            @if($user->signature_path)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($user->signature_path) }}" alt="Tanda Tangan" class="h-24 object-contain border border-gray-300 dark:border-gray-600 rounded-lg bg-white">
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
                                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-900">
                                        <canvas id="signatureCanvas" width="400" height="150" class="border border-gray-200 dark:border-gray-600 rounded bg-white cursor-crosshair w-full max-w-full"></canvas>
                                        <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                            <button type="button" id="clearSignature" class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 rounded-md text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </div>
                                        <input type="hidden" name="signature_data" id="signatureData" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
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
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clearSignature');
    const signatureDataInput = document.getElementById('signatureData');
    const form = canvas.closest('form');
    
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
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        
        return {
            x: (clientX - rect.left) * scaleX,
            y: (clientY - rect.top) * scaleY
        };
    }
    
    // Start drawing
    function startDrawing(e) {
        isDrawing = true;
        const pos = getPosition(e);
        lastX = pos.x;
        lastY = pos.y;
        if (e.cancelable) e.preventDefault();
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
        if (e.cancelable) e.preventDefault();
    }
    
    // Stop drawing
    function stopDrawing() {
        isDrawing = false;
    }
    
    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', stopDrawing);
    
    // Touch events
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);
    
    // Clear signature
    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureDataInput.value = '';
    });
    
    // On form submit, save canvas data
    form.addEventListener('submit', function() {
        // Check if canvas is not empty
        const isCanvasEmpty = ctx.getImageData(0, 0, canvas.width, canvas.height).data.every(val => val === 0);
        if (!isCanvasEmpty) {
            signatureDataInput.value = canvas.toDataURL('image/png');
        }
    });
});
</script>
@endpush
@endsection
