@extends('layouts.app')
@section('title', 'Monitoring & Laporan')
@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
            Monitoring <span class="text-[#2dce89]">& Laporan</span>
        </h1>
        <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
            Progress aktivitas logbook & laporan mahasiswa
        </p>
    </div>
    <a href="{{ route('laporan.admin') }}?export=1{{ $proyekId ? '&proyek_id='.$proyekId : '' }}"
        class="flex items-center gap-2 px-5 py-3 rounded-2xl text-[#7fffd4] text-xs font-black hover:opacity-80 transition-all shadow-xl shadow-teal-900/20"
        style="background:#004d4d;">
        <span class="material-symbols-outlined text-base">download</span>
        EXPORT CSV
    </a>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-4 gap-5 mb-8">
    <div class="relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between shadow-xl h-32" style="background:#004d4d;">
        <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black italic text-white tracking-tighter">{{ $totalMahasiswa }}</p>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/5" style="font-size:90px;">group</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between shadow-xl h-32" style="background:#2dce89;">
        <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Selesai</p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black italic text-white tracking-tighter">{{ $sudahSelesai }}</p>
            <span class="text-xs font-black text-white/60">Mahasiswa</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/20" style="font-size:90px;">task_alt</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between h-32 bg-white border-2 border-outline-variant/20">
        <p class="text-[10px] font-black uppercase tracking-widest" style="color:#004d4d;">Berjalan</p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black italic tracking-tighter" style="color:#004d4d;">{{ $sedangBerjalan }}</p>
            <span class="text-xs font-black text-gray-400">Mahasiswa</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-gray-100" style="font-size:90px;">pending_actions</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-6 flex flex-col justify-between h-32 bg-white border-2 border-orange-100">
        <p class="text-orange-400 text-[10px] font-black uppercase tracking-widest">Belum Mulai</p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black italic text-orange-400 tracking-tighter">{{ $belumMulai }}</p>
            <span class="text-xs font-black text-gray-400">Mahasiswa</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-orange-50" style="font-size:90px;">hourglass_empty</span>
    </div>
</div>

{{-- FILTER PROYEK --}}
<div class="mb-6">
    <form method="GET" action="{{ route('laporan.admin') }}">
        <select name="proyek_id" onchange="this.form.submit()"
            class="px-5 py-3 rounded-2xl border border-outline-variant/20 bg-white text-sm font-bold text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">Semua Proyek</option>
            @foreach($proyekList as $p)
                <option value="{{ $p->id }}" {{ $proyekId == $p->id ? 'selected' : '' }}>
                    {{ $p->judul_proyek }}
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- TABEL MONITORING --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-outline-variant/10 bg-surface-container-low/40">
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline w-10">#</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Mahasiswa</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Proyek</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">
                    Logbook<br><span class="normal-case font-medium text-[9px]">Mingguan</span>
                </th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">
                    Harian
                </th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">
                    Laporan
                </th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline">
                    Progress Logbook
                </th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">
                    Status
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/10">
            @forelse($mahasiswaProgress as $i => $m)
            @php
                $statusColor = match($m->status) {
                    'Selesai'     => 'bg-emerald-100 text-emerald-700',
                    'Berjalan'    => 'bg-blue-100 text-blue-700',
                    'Belum Mulai' => 'bg-orange-100 text-orange-600',
                    default       => 'bg-gray-100 text-gray-500',
                };
                $barColor = match($m->status) {
                    'Selesai'  => '#2dce89',
                    'Berjalan' => '#004d4d',
                    default    => '#e0e0e0',
                };
            @endphp
            <tr class="hover:bg-surface-container-lowest transition-colors">
                <td class="px-6 py-5 text-[10px] font-black text-outline/40">{{ $i + 1 }}</td>

                {{-- Mahasiswa --}}
                <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center font-black text-xs text-primary shrink-0">
                            {{ strtoupper(substr($m->nama, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-black text-on-surface text-sm">{{ $m->nama }}</p>
                            <p class="text-[10px] text-outline font-medium">{{ $m->nim }} · {{ $m->prodi }}</p>
                        </div>
                    </div>
                </td>

                {{-- Proyek --}}
                <td class="px-6 py-5">
                    <p class="text-sm font-medium text-on-surface-variant">{{ $m->proyek }}</p>
                </td>

                {{-- Logbook Mingguan --}}
                <td class="px-6 py-5 text-center">
                    <span class="font-black text-sm" style="color:#004d4d;">{{ $m->logbook_verified }}</span>
                    <span class="text-outline/40 text-xs font-bold"> / {{ $m->total_logbook }}</span>
                    <p class="text-[9px] text-outline/50 mt-0.5">verified</p>
                </td>

                {{-- Harian --}}
                <td class="px-6 py-5 text-center">
                    <span class="font-black text-sm text-on-surface-variant">{{ $m->total_harian }}</span>
                    <p class="text-[9px] text-outline/50 mt-0.5">entri</p>
                </td>

                {{-- Laporan --}}
                <td class="px-6 py-5 text-center">
                    <span class="font-black text-sm" style="color:#2dce89;">{{ $m->laporan_verified }}</span>
                    <span class="text-outline/40 text-xs font-bold"> / {{ $m->total_laporan }}</span>
                    <p class="text-[9px] text-outline/50 mt-0.5">verified</p>
                </td>

                {{-- Progress Bar --}}
                <td class="px-6 py-5">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all"
                                style="width:{{ $m->progress_logbook }}%; background:{{ $barColor }};"></div>
                        </div>
                        <span class="text-[10px] font-black text-outline w-8 text-right">{{ $m->progress_logbook }}%</span>
                    </div>
                </td>

                {{-- Status --}}
                <td class="px-6 py-5 text-center">
                    <span class="inline-block px-3 py-1.5 rounded-xl text-[10px] font-black {{ $statusColor }}">
                        {{ $m->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center">
                    <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">monitoring</span>
                    <p class="text-on-surface-variant/40 font-black italic text-sm">Belum ada data aktivitas.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection