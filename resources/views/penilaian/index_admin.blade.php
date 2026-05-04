@extends('layouts.app')
@section('title', 'Rekap Penilaian')
@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <h1 class="text-2xl font-bold text-gray-800">Rekap <span class="text-indigo-600">Semua Penilaian</span></h1>

    {{-- Penilaian Manager --}}
    <div>
        <h2 class="text-base font-semibold text-gray-700 mb-3">Penilaian Manager (55%)</h2>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Mahasiswa</th>
                        <th class="px-5 py-3 text-left">Proyek</th>
                        <th class="px-5 py-3 text-center">Nilai Manager</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penilaianManager as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $p->mahasiswa->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $p->pengajuanProyek->judul_proyek ?? '-' }}</td>
                        <td class="px-5 py-3 text-center font-bold text-emerald-700">{{ number_format($p->nilai_manager, 1) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Penilaian Dosen --}}
    <div>
        <h2 class="text-base font-semibold text-gray-700 mb-3">Penilaian Dosen (45%)</h2>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Mahasiswa</th>
                        <th class="px-5 py-3 text-left">Mata Kuliah</th>
                        <th class="px-5 py-3 text-center">Nilai Dosen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penilaianDosen as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $p->mahasiswa->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $p->supervisiMatkul->mataKuliah->nama_matkul ?? '-' }}</td>
                        <td class="px-5 py-3 text-center font-bold text-teal-700">{{ number_format($p->nilai_dosen, 1) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection