@extends('layouts.app')

@section('title', 'Asesmen Psikologi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ openPreview: false, previewUrl: null }">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Asesmen Psikologi
                    </h2>
                    <a href="{{ route('assessments.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center w-full sm:w-auto">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Asesmen
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Tes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Psikolog</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($assessments as $assessment)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $assessment->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $assessment->test_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $assessment->psychologist_name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <div class="flex flex-wrap gap-3">
                                            <a href="{{ route('assessments.show', $assessment) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('assessments.edit', $assessment) }}" 
                                               class="text-yellow-600 hover:text-yellow-800 transition">
                                                Edit
                                            </a>
                                            <button type="button"
                                                    @click="previewUrl = '{{ route('assessments.pdf.view', $assessment) }}'; openPreview = true"
                                                    class="text-green-600 hover:text-green-800 transition">
                                                PDF
                                            </button>
                                            <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Hapus asesmen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada asesmen yang dibuat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $assessments->links() }}
                </div>

                <div x-cloak x-show="openPreview"
                     @keydown.escape.window="openPreview = false; previewUrl = null"
                     @click.self="openPreview = false; previewUrl = null"
                     x-trap.noscroll="openPreview"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white w-full max-w-5xl mx-4 rounded-lg shadow-lg overflow-hidden">
                        <div class="flex justify-between items-center px-4 py-2 border-b relative z-10">
                            <h3 class="font-semibold">Preview PDF</h3>
                            <button type="button"
                                    @click="openPreview = false; previewUrl = null"
                                    class="text-gray-500 hover:text-gray-700">
                                Tutup
                            </button>
                        </div>
                        <div class="p-4 max-h-[85vh] relative z-0">
                            <iframe :src="previewUrl"
                                    class="w-full h-[75vh]"
                                    frameborder="0">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
