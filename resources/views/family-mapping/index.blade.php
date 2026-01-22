@extends('layouts.app')

@section('title', 'Family Mapping')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ openPreview: false, previewUrl: null, ayahId: {{ Illuminate\Support\Js::from(request('ayah_id', '')) }}, ibuId: {{ Illuminate\Support\Js::from(request('ibu_id', '')) }}, error: null }">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Family Mapping
                    </h2>
                    <a href="{{ route('assessments.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
                        Kembali
                    </a>
                </div>

                <form method="GET" action="{{ route('family-mapping.pdf') }}" class="space-y-6"
                      @keydown.enter.prevent
                      @submit="
                        error = null;
                        if (!ayahId || !ibuId) { $event.preventDefault(); error = 'Pilih data ayah dan ibu terlebih dahulu.'; }
                        else if (ayahId === ibuId) { $event.preventDefault(); error = 'Asesmen Ayah dan Ibu tidak boleh sama.'; }
                      ">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Ayah</label>
                            <select name="ayah_id" required x-model="ayahId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Pilih Asesmen Ayah</option>
                                @foreach($assessmentsAyah as $a)
                                    <option value="{{ $a->id }}">
                                        #{{ $a->id }} - {{ $a->subject->name }} ({{ $a->test_date?->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Ibu</label>
                            <select name="ibu_id" required x-model="ibuId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Pilih Asesmen Ibu</option>
                                @foreach($assessmentsIbu as $a)
                                    <option value="{{ $a->id }}">
                                        #{{ $a->id }} - {{ $a->subject->name }} ({{ $a->test_date?->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div x-cloak x-show="error" class="p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                        <span x-text="error"></span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button"
                                @click.prevent
                                @click="
                                    error = null;
                                    if (!ayahId || !ibuId) { error = 'Pilih data ayah dan ibu terlebih dahulu.'; return; }
                                    if (ayahId === ibuId) { error = 'Asesmen Ayah dan Ibu tidak boleh sama.'; return; }
                                    previewUrl = '{{ route('family-mapping.pdf') }}' + '?ayah_id=' + encodeURIComponent(ayahId) + '&ibu_id=' + encodeURIComponent(ibuId) + '&preview=1&t=' + Date.now();
                                    openPreview = true;
                                "
                                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Preview PDF
                        </button>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Download PDF
                        </button>
                    </div>
                </form>

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
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-gray-500 text-white hover:bg-gray-600 transition">
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
