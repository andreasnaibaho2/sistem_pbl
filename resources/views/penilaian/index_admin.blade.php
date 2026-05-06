@extends('layouts.app')
@section('title', 'Rekap Penilaian')
@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
    Rekap <span class="text-[#2dce89]">Semua Penilaian</span>
</h1>
<p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
    Rekapitulasi nilai manager & dosen pengampu
</p>
    </div>
    {{-- Bisa tambah tombol export PDF di sini nanti --}}
</div>

{{-- STAT SUMMARY CARDS --}}
<div class="grid grid-cols-3 gap-5 mb-8">
    @php
        $totalManager = $penilaianManager->count();
        $totalDosen   = $penilaianDosen->count();
        $rataManager  = $totalManager > 0 ? round($penilaianManager->avg('nilai_manager'), 1) : 0;
        $rataDosen    = $totalDosen   > 0 ? round($penilaianDosen->avg('nilai_dosen'), 1)     : 0;
    @endphp

    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between shadow-xl h-36"
         style="background:#004d4d;">
        <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Penilaian Manager</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-white tracking-tighter">{{ $totalManager }}</p>
            <span class="text-xs font-black text-[#7fffd4]/60">Rata-rata: {{ $rataManager }}</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/5" style="font-size:100px;">manage_accounts</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between shadow-xl h-36"
         style="background:#2dce89;">
        <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Penilaian Dosen</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-white tracking-tighter">{{ $totalDosen }}</p>
            <span class="text-xs font-black text-white/60">Rata-rata: {{ $rataDosen }}</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/20" style="font-size:100px;">school</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between bg-white border-2 border-primary-container h-36">
        <p class="text-secondary text-[10px] font-black uppercase tracking-widest">Total Data</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-primary tracking-tighter">{{ $totalManager + $totalDosen }}</p>
            <span class="text-xs font-black text-outline">Entri nilai</span>
        </div>
        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden mt-2">
            @php $pct = ($totalManager + $totalDosen) > 0 ? min(100, round($totalManager / max(1, $totalManager + $totalDosen) * 100)) : 0; @endphp
            <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#7fffd4,#008080);"></div>
        </div>
    </div>
</div>

{{-- SECTION: PENILAIAN MANAGER (55%) --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#004d4d;">
            <span class="material-symbols-outlined text-[#7fffd4] text-base">manage_accounts</span>
        </div>
        <div>
            <h2 class="text-base font-black text-on-surface">Penilaian Manager Proyek</h2>
            <p class="text-[10px] text-outline font-bold uppercase tracking-widest">Bobot 55% &mdash; Learning Skills + Life Skills + Laporan Project</p>
        </div>
        <span class="ml-auto px-3 py-1 rounded-full text-[10px] font-black bg-primary-container text-primary">
            {{ $totalManager }} data
        </span>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant/10 bg-surface-container-low/40">
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline w-10">#</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Mahasiswa</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Proyek</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Learning Skills</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Life Skills</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Lap. Project</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Total (55%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($penilaianManager as $i => $p)
                <tr class="hover:bg-surface-container-lowest transition-colors group">
                    <td class="px-7 py-5 text-[10px] font-black text-outline/40">{{ $i + 1 }}</td>
                    <td class="px-7 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center font-black text-xs text-primary shrink-0">
                                {{ strtoupper(substr($p->mahasiswa->nama ?? '-', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-black text-on-surface text-sm">{{ $p->mahasiswa->nama ?? '-' }}</p>
                                <p class="text-[10px] text-outline font-medium">{{ $p->mahasiswa->nim ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-7 py-5">
                        <p class="text-sm font-medium text-on-surface-variant">{{ $p->pengajuanProyek->judul_proyek ?? '-' }}</p>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-primary text-sm">{{ $p->learning_skills ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-primary text-sm">{{ $p->life_skills ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-primary text-sm">{{ $p->laporan_project ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        @php $nm = $p->nilai_manager ?? null; @endphp
                        @if($nm !== null)
                            @php
                                $color = $nm >= 85 ? 'bg-emerald-100 text-emerald-700'
                                       : ($nm >= 75 ? 'bg-blue-100 text-blue-700'
                                       : ($nm >= 65 ? 'bg-yellow-100 text-yellow-700'
                                       : ($nm >= 55 ? 'bg-orange-100 text-orange-700'
                                       : 'bg-red-100 text-red-700')));
                            @endphp
                            <span class="inline-block px-4 py-1.5 rounded-xl text-sm font-black {{ $color }}">
                                {{ number_format($nm, 1) }}
                            </span>
                        @else
                            <span class="text-outline/40 font-black text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-7 py-16 text-center">
                        <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">manage_accounts</span>
                        <p class="text-on-surface-variant/40 font-black italic text-sm">Belum ada penilaian manager.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SECTION: PENILAIAN DOSEN (45%) --}}
<div>
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#2dce89;">
            <span class="material-symbols-outlined text-white text-base">school</span>
        </div>
        <div>
            <h2 class="text-base font-black text-on-surface">Penilaian Dosen Pengampu</h2>
            <p class="text-[10px] text-outline font-bold uppercase tracking-widest">Bobot 45% &mdash; Literacy Skills + Presentasi + Laporan Akhir</p>
        </div>
        <span class="ml-auto px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700">
            {{ $totalDosen }} data
        </span>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant/10 bg-surface-container-low/40">
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline w-10">#</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Mahasiswa</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Mata Kuliah</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Literacy Skills</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Presentasi</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Lap. Akhir</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Total (45%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($penilaianDosen as $i => $p)
                <tr class="hover:bg-surface-container-lowest transition-colors group">
                    <td class="px-7 py-5 text-[10px] font-black text-outline/40">{{ $i + 1 }}</td>
                    <td class="px-7 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs text-[#7fffd4] shrink-0" style="background:#004d4d;">
                                {{ strtoupper(substr($p->mahasiswa->nama ?? '-', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-black text-on-surface text-sm">{{ $p->mahasiswa->nama ?? '-' }}</p>
                                <p class="text-[10px] text-outline font-medium">{{ $p->mahasiswa->nim ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-7 py-5">
                        <div>
                            <p class="text-sm font-bold text-on-surface-variant">
                                {{ $p->supervisiMatkul->mataKuliah->nama_matkul ?? '-' }}
                            </p>
                            <p class="text-[10px] text-outline mt-0.5">
                                {{ $p->supervisiMatkul->mataKuliah->kode_matkul ?? '' }}
                            </p>
                        </div>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-secondary text-sm">{{ $p->literacy_skills ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-secondary text-sm">{{ $p->presentasi ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="font-black text-secondary text-sm">{{ $p->laporan_akhir ?? '-' }}</span>
                    </td>
                    <td class="px-7 py-5 text-center">
                        @php $nd = $p->nilai_dosen ?? null; @endphp
                        @if($nd !== null)
                            @php
                                $colorD = $nd >= 85 ? 'bg-emerald-100 text-emerald-700'
                                        : ($nd >= 75 ? 'bg-blue-100 text-blue-700'
                                        : ($nd >= 65 ? 'bg-yellow-100 text-yellow-700'
                                        : ($nd >= 55 ? 'bg-orange-100 text-orange-700'
                                        : 'bg-red-100 text-red-700')));
                            @endphp
                            <span class="inline-block px-4 py-1.5 rounded-xl text-sm font-black {{ $colorD }}">
                                {{ number_format($nd, 1) }}
                            </span>
                        @else
                            <span class="text-outline/40 font-black text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-7 py-16 text-center">
                        <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">school</span>
                        <p class="text-on-surface-variant/40 font-black italic text-sm">Belum ada penilaian dosen.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection