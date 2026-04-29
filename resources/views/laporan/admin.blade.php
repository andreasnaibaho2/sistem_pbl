@extends('layouts.app')

@section('title', 'Monitoring & Laporan')

@section('content')

<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
            Monitoring <span class="text-[#2dce89]">& Laporan</span>
        </h1>
        <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
            Rekap nilai seluruh mahasiswa
        </p>
    </div>
    <a href="{{ route('laporan.admin') }}?export=1"
       class="flex items-center gap-2 px-6 py-3.5 bg-[#004d4d] text-[#7fffd4] rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-xl shadow-teal-900/20">
        <span class="material-symbols-outlined text-base">download</span> EXPORT CSV
    </a>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-[#004d4d] text-white p-6 rounded-[1.5rem] shadow-sm flex flex-col justify-between relative overflow-hidden">
        <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
        <p class="text-5xl font-black italic tracking-tighter">{{ $totalMahasiswa ?? 0 }}</p>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-white/5" style="font-size:80px;">group</span>
    </div>
    <div class="bg-white border-2 border-[#7fffd4] p-6 rounded-[1.5rem] shadow-sm flex flex-col justify-between relative overflow-hidden">
        <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Sudah Dinilai</p>
        <p class="text-5xl font-black italic tracking-tighter text-[#008080]">{{ $sudahDinilai ?? 0 }}</p>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-[#7fffd4]/20" style="font-size:80px;">grade</span>
    </div>
    <div class="bg-white border-2 border-amber-200 p-6 rounded-[1.5rem] shadow-sm flex flex-col justify-between relative overflow-hidden">
        <p class="text-amber-600 text-[10px] font-black uppercase tracking-widest">Belum Dinilai</p>
        <p class="text-5xl font-black italic tracking-tighter text-amber-500">{{ $belumDinilai ?? 0 }}</p>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-amber-100" style="font-size:80px;">pending_actions</span>
    </div>
</div>

{{-- FILTER --}}
<div class="flex items-center gap-3 mb-5">
    <form method="GET" action="{{ route('laporan.admin') }}" class="flex items-center gap-3">
        <select name="kelas_id" onchange="this.form.submit()"
            class="px-5 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-bold text-[#004d4d] focus:ring-2 focus:ring-[#7fffd4] outline-none shadow-sm">
            <option value="">Semua Kelas</option>
            @foreach($kelasList ?? [] as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kelas ?? $k->kode_kelas }}
            </option>
            @endforeach
        </select>
    </form>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">#</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Mahasiswa</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">NIM</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Kelas</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Nilai Manager (55%)</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Nilai Dosen (45%)</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Nilai Akhir</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Grade</th>
                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($mahasiswaRekap ?? [] as $idx => $row)
            <tr class="hover:bg-teal-50/30 transition-colors group">
                <td class="px-8 py-5 text-[10px] font-black text-gray-300">{{ $idx + 1 }}</td>
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#004d4d] text-[#7fffd4] flex items-center justify-center font-black text-xs flex-shrink-0">
                            {{ strtoupper(substr($row->nama ?? '-', 0, 2)) }}
                        </div>
                        <span class="font-bold text-[#004d4d] text-sm">{{ $row->nama }}</span>
                    </div>
                </td>
                <td class="px-8 py-5 font-mono text-xs font-bold text-gray-500">{{ $row->nim }}</td>
                <td class="px-8 py-5 text-xs font-bold text-gray-500">{{ $row->kelas ?? '-' }}</td>
                <td class="px-8 py-5 text-center">
                    <span class="font-black text-[#008080]">{{ $row->nilai_manager ?? '-' }}</span>
                </td>
                <td class="px-8 py-5 text-center">
                    <span class="font-black text-[#008080]">{{ $row->nilai_dosen ?? '-' }}</span>
                </td>
                <td class="px-8 py-5 text-center">
                    <span class="text-lg font-black italic text-[#004d4d]">{{ $row->nilai_akhir ?? '-' }}</span>
                </td>
                <td class="px-8 py-5 text-center">
                    @php
                        $grade = $row->grade ?? '-';
                        $gradeColor = match($grade) {
                            'A'  => 'bg-emerald-100 text-emerald-700',
                            'AB' => 'bg-teal-100 text-teal-700',
                            'B'  => 'bg-blue-100 text-blue-700',
                            'BC' => 'bg-cyan-100 text-cyan-700',
                            'C'  => 'bg-yellow-100 text-yellow-700',
                            'D'  => 'bg-orange-100 text-orange-700',
                            'E'  => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <span class="px-3 py-1.5 rounded-xl text-xs font-black {{ $gradeColor }}">{{ $grade }}</span>
                </td>
                <td class="px-8 py-5 text-center">
                    @if($row->nilai_akhir ?? false)
                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-100 text-emerald-700">Selesai</span>
                    @else
                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-100 text-amber-600">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-8 py-20 text-center opacity-40">
                    <span class="material-symbols-outlined text-5xl mb-2 text-gray-400 block">monitoring</span>
                    <p class="font-black italic text-gray-500">Belum ada data rekap nilai.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection