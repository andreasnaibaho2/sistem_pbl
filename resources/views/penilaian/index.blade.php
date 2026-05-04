@extends('layouts.app')

@section('title', 'Data Penilaian')

@section('content')
@php $user = auth()->user(); @endphp

{{-- HEADING --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-black italic uppercase text-[#004d4d]">
            Data <span class="text-[#2dce89]">Penilaian</span>
        </h1>
        <p class="text-sm text-slate-400 mt-1 uppercase tracking-widest font-medium">Rekap nilai mahasiswa PBL</p>
    </div>
    @if($user->isManager() || $user->isDosen())
    <a href="{{ auth()->user()->isManager() ? route('penilaian.manager.create') : route('penilaian.dosen.create') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#004d4d] text-[#7fffd4] text-sm font-black hover:opacity-90 transition">
        <span class="material-symbols-outlined text-base">add</span>
        Input Nilai
    </a>
    @endif
</div>

{{-- TABLE CARD --}}
<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
            Total: {{ $penilaian->count() }} Data
        </span>
        @if($user->isManager())
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-teal-50 text-[#004d4d] uppercase tracking-widest">Porsi Anda: 55%</span>
        @elseif($user->isDosen())
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-teal-50 text-[#004d4d] uppercase tracking-widest">Porsi Anda: 45%</span>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">No</th>
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Mahasiswa</th>
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Proyek</th>
                    @if(!$user->isMahasiswa())
                    <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Manager<br><span class="normal-case font-medium text-gray-300">55%</span></th>
                    <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Dosen<br><span class="normal-case font-medium text-gray-300">45%</span></th>
                    @endif
                    <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Nilai Akhir</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($penilaian as $p)
                <tr class="hover:bg-teal-50/30 transition-colors">
                    <td class="px-6 py-4 text-[10px] font-black text-gray-300">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-black bg-[#004d4d]">
                                {{ strtoupper(substr($p->mahasiswa->nama ?? 'M', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-black text-[#004d4d] text-sm">{{ $p->mahasiswa->nama ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $p->mahasiswa->nim ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">{{ $p->mahasiswa->proyekAktif()?->judul_proyek ?? '-' }}</td>

                    @if(!$user->isMahasiswa())
                    <td class="px-6 py-4 text-center">
                        @if($p->nilai_manager !== null)
                            <span class="font-black text-[#004d4d]">{{ number_format($p->nilai_manager, 1) }}</span>
                        @else
                            <span class="text-[10px] text-gray-300 italic font-medium">Belum diisi</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($p->nilai_dosen !== null)
                            <span class="font-black text-[#004d4d]">{{ number_format($p->nilai_dosen, 1) }}</span>
                        @else
                            <span class="text-[10px] text-gray-300 italic font-medium">Belum diisi</span>
                        @endif
                    </td>
                    @endif

                    <td class="px-6 py-4 text-center">
                        @if($p->nilai_akhir)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black text-white"
                            style="background-color: {{ $p->getGradeColor() }}">
                            {{ number_format($p->nilai_akhir, 1) }} · {{ $p->getGrade() }}
                        </span>
                        @else
                        <span class="text-[10px] text-gray-300 italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('penilaian.show', $p->id) }}"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-xl text-[10px] font-black bg-teal-50 text-[#004d4d] hover:bg-teal-100 transition w-fit uppercase tracking-wide">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-200 block mb-3">assignment</span>
                        <p class="text-sm text-gray-400 font-medium">Belum ada data penilaian</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection