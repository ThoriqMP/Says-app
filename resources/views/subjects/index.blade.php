@extends('layouts.app')

@section('title', 'Data Subjek')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        Data Subjek
                    </h2>
                    <a href="{{ route('subjects.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center w-full sm:w-auto">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Subjek
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Berhasil</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Error</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <input type="text" placeholder="Cari subjek..." 
                           x-data x-on:input="
                               const q = $el.value.toLowerCase();
                               document.querySelectorAll('[data-subject-row]').forEach(tr => {
                                   const name = tr.getAttribute('data-name').toLowerCase();
                                   tr.style.display = name.includes(q) ? '' : 'none';
                               });
                           "
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama</th>
                                <th class="px-4 py-2 text-left">Usia</th>
                                <th class="px-4 py-2 text-left">Jenis Kelamin</th>
                                <th class="px-4 py-2 text-left">No. HP</th>
                                <th class="px-4 py-2 text-left">Asesmen</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($subjects as $subject)
                                <tr data-subject-row data-name="{{ $subject->name }}">
                                    <td class="px-4 py-2">{{ $subject->name }}</td>
                                    <td class="px-4 py-2">{{ $subject->age }}</td>
                                    <td class="px-4 py-2">{{ $subject->gender === 'male' ? 'Laki-laki' : ($subject->gender === 'female' ? 'Perempuan' : '-') }}</td>
                                    <td class="px-4 py-2">{{ $subject->phone }}</td>
                                    <td class="px-4 py-2">{{ $subject->assessments_count ?? 0 }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('subjects.edit', $subject) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-yellow-500 text-white hover:bg-yellow-600 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Hapus subjek ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada subjek.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
