@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Detail Raport</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ $report->category->name }} | {{ $report->period }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('student.reports') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition text-sm">Kembali</a>
                    <a href="{{ route('student.reports.pdf', $report) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Ekspor PDF
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content (Grades or Activities) -->
                <div class="lg:col-span-2">
                    @if($report->category->name === 'Probing')
                        <div class="space-y-6">
                            @foreach($report->probingActivities as $activity)
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm">
                                    @if($activity->image_path)
                                        <img src="{{ asset('storage/' . $activity->image_path) }}" alt="{{ $activity->activity_name }}" class="w-full h-64 object-cover">
                                    @endif
                                    <div class="p-6">
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $activity->activity_name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $activity->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="overflow-hidden border border-gray-100 dark:border-gray-800 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Nilai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($report->grades as $grade)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $grade->subject->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-blue-600 dark:text-blue-400">{{ $grade->score }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $grade->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Sidebar (Summary) -->
                <div class="lg:col-span-1">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-100 dark:border-blue-800 sticky top-24">
                        <h4 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-4">Catatan Perkembangan</h4>
                        <div class="text-blue-800 dark:text-blue-200 text-sm leading-relaxed whitespace-pre-line">
                            {{ $report->summary_notes ?? 'Tidak ada catatan tambahan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
