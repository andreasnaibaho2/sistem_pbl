{{-- resources/views/logbook_harian/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Logbook Harian')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('logbook_harian.show', $logbook_harian->id) }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Logbook Harian</h1>
            <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($logbook_harian->tanggal)->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('logbook_harian.update', $logbook_harian->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Info Proyek (read-only) --}}
            <div class="mb-5 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-xs font-semibold text-blue-400 uppercase tracking-wide mb-1">Proyek</p>
                <p class="text-sm font-semibold text-blue-800">
                    {{ $logbook_harian->proyek->judul_proyek ?? '-' }}
                </p>
            </div>

            {{-- Minggu Ke --}}
            <div class="mb-5">
                <label for="minggu_ke" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Minggu Ke <span class="text-red-500">*</span>
                </label>
                <input type="number" id="minggu_ke" name="minggu_ke"
                       value="{{ old('minggu_ke', $logbook_harian->minggu_ke) }}"
                       min="1" max="20"
                       class="w-full px-4 py-2.5 rounded-xl border @error('minggu_ke') border-red-400 bg-red-50 @else border-gray-200 @enderror text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                @error('minggu_ke')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Hari --}}
            <div class="mb-5">
                <label for="hari" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Hari <span class="text-red-500">*</span>
                </label>
                <select id="hari" name="hari"
                        class="w-full px-4 py-2.5 rounded-xl border @error('hari') border-red-400 bg-red-50 @else border-gray-200 @enderror text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
                        <option value="{{ $h }}" {{ old('hari', $logbook_harian->hari) === $h ? 'selected' : '' }}>
                            {{ $h }}
                        </option>
                    @endforeach
                </select>
                @error('hari')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div class="mb-5">
                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date" id="tanggal" name="tanggal"
                       value="{{ old('tanggal', \Carbon\Carbon::parse($logbook_harian->tanggal)->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 rounded-xl border @error('tanggal') border-red-400 bg-red-50 @else border-gray-200 @enderror text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                @error('tanggal')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Aktivitas --}}
            <div class="mb-5">
                <label for="aktivitas" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Aktivitas <span class="text-red-500">*</span>
                </label>
                <textarea id="aktivitas" name="aktivitas" rows="5"
                          class="w-full px-4 py-2.5 rounded-xl border @error('aktivitas') border-red-400 bg-red-50 @else border-gray-200 @enderror text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none transition"
                          placeholder="Deskripsikan aktivitas yang dilakukan...">{{ old('aktivitas', $logbook_harian->aktivitas) }}</textarea>
                @error('aktivitas')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Dokumentasi --}}
            <div class="mb-6" x-data="{ hasFile: false, fileName: '' }">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dokumentasi</label>

                {{-- File lama --}}
                @if($logbook_harian->dokumentasi)
                <div class="mb-3 flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="material-symbols-outlined text-blue-500">description</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 mb-0.5">File saat ini:</p>
                        <p class="text-sm text-gray-700 font-medium truncate">
                            {{ basename($logbook_harian->dokumentasi) }}
                        </p>
                    </div>
                    <a href="{{ asset('storage/' . $logbook_harian->dokumentasi) }}" target="_blank"
                       class="text-xs text-blue-600 hover:underline flex-shrink-0">Lihat</a>
                </div>
                <p class="text-xs text-gray-400 mb-2">Upload file baru untuk mengganti, atau biarkan kosong untuk mempertahankan.</p>
                @endif

                {{-- Input file baru --}}
                <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition">
                    <input type="file" name="dokumentasi" class="hidden"
                           accept=".pdf,.jpg,.jpeg,.png"
                           x-on:change="hasFile = $event.target.files.length > 0; fileName = $event.target.files[0]?.name">
                    <template x-if="!hasFile">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-gray-400 text-3xl">upload_file</span>
                            <p class="text-xs text-gray-500 mt-1">Klik untuk upload file baru</p>
                            <p class="text-xs text-gray-400">PDF, JPG, PNG (maks. 2MB)</p>
                        </div>
                    </template>
                    <template x-if="hasFile">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-blue-500 text-3xl">check_circle</span>
                            <p class="text-xs text-blue-600 mt-1 font-medium" x-text="fileName"></p>
                        </div>
                    </template>
                </label>
                @error('dokumentasi')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>{{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('logbook_harian.show', $logbook_harian->id) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 active:scale-95 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection