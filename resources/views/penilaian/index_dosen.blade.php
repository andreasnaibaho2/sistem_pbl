@extends('layouts.app')
@section('title', 'Penilaian Dosen')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Penilaian <span class="text-teal-600">Dosen Pengampu</span></h1>
            <p class="text-sm text-gray-500 mt-1">{{ $penilaian->count() }} data penilaian</p>
        </div>
        <a href="{{ route('penilaian.dosen.create') }}"
            class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            <span class="material-symbols-outlined text-base">add</span> Tambah Penilaian
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Mahasiswa</th>
                    <th class="px-5 py-3 text-left">Mata Kuliah</th>
                    <th class="px-5 py-3 text-center">Nilai Dosen</th>
                    <th class="px-5 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($penilaian as $i => $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $p->mahasiswa->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $p->supervisiMatkul->mataKuliah->nama_matkul ?? '-' }}
                        <span class="text-xs text-gray-400 block">Sem {{ $p->supervisiMatkul->semester ?? '' }} — {{ $p->supervisiMatkul->tahun_ajaran ?? '' }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="font-bold text-teal-700 text-base">{{ number_format($p->nilai_dosen, 1) }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-1 rounded-full">Selesai</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        <span class="material-symbols-outlined text-4xl block mb-2">grade</span>
                        Belum ada penilaian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection