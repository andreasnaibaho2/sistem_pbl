@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-black text-on-surface tracking-tight">Dashboard</h1>
        <p class="text-sm text-on-surface-variant mt-0.5">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <div class="flex items-center gap-3">
        @if(($stats['pending_dosen'] ?? 0) > 0)
        <a href="{{ route('approval.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl text-xs font-bold hover:bg-amber-100 transition-all">
            <span class="material-symbols-outlined text-base">how_to_reg</span>
            {{ $stats['pending_dosen'] }} Pending Approval
        </a>
        @endif
        @if(($stats['pending_proyek'] ?? 0) > 0)
        <a href="{{ route('pengajuan_proyek.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl text-xs font-bold hover:bg-blue-100 transition-all">
            <span class="material-symbols-outlined text-base">pending_actions</span>
            {{ $stats['pending_proyek'] }} Proyek Pending
        </a>
        @endif
    </div>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Card 1: Total Mahasiswa --}}
    <div class="relative overflow-hidden rounded-[2rem] h-44 p-7 flex flex-col justify-between shadow-xl"
         style="background: #004d4d;">
        <div class="relative z-10">
            <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest mb-1">Total Mahasiswa</p>
            <h2 class="text-6xl font-black italic tracking-tighter text-white">{{ $stats['total_mahasiswa'] }}</h2>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/5"
              style="font-size: 120px;">group</span>
    </div>

    {{-- Card 2: Proyek Aktif --}}
    <div class="relative overflow-hidden rounded-[2rem] h-44 p-7 flex flex-col justify-between shadow-xl"
         style="background: #2dce89;">
        <div class="relative z-10">
            <p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-1">Proyek Aktif</p>
            <h2 class="text-6xl font-black italic tracking-tighter text-white">{{ $stats['total_proyek_aktif'] ?? 0 }}</h2>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/20"
              style="font-size: 120px;">rocket_launch</span>
    </div>

    {{-- Card 3: Progress --}}
    <div class="relative overflow-hidden rounded-[2rem] h-44 p-7 flex flex-col justify-between bg-white border-2 border-primary-container">
        <div>
            <p class="text-secondary text-[10px] font-black uppercase tracking-widest mb-1">Progres Monitoring</p>
            @php
                $total   = $stats['total_mahasiswa'] ?: 1;
                $dinilai = \App\Models\PenilaianDosen::whereNotNull('nilai_dosen')->count();
                $pct     = round(($dinilai / $total) * 100);
            @endphp
            <h2 class="text-5xl font-black italic text-primary">{{ $pct }}%</h2>
        </div>
        <div class="w-full bg-surface-container-highest h-2.5 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all"
                 style="width: {{ $pct }}%; background: linear-gradient(90deg, #7fffd4, #008080);"></div>
        </div>
    </div>
</div>

{{-- FILTER & TABLE --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">

    {{-- Filter Bar --}}
    <div class="flex items-center justify-between px-7 py-5 border-b border-outline-variant/10">
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Prodi Filter --}}
            <div class="relative" id="prodiDropdownWrapper">
                <button onclick="toggleProdiDropdown()"
                    class="flex items-center gap-3 px-5 py-3 rounded-xl font-bold text-xs text-white shadow-lg border border-[#7fffd4]/20 transition-all"
                    style="background:#004d4d;">
                    <span class="material-symbols-outlined text-[#7fffd4] text-base">school</span>
                    <span id="prodiLabel">Semua Prodi</span>
                    <span class="material-symbols-outlined text-[#7fffd4] text-base">expand_more</span>
                </button>
                <div id="prodiDropdown"
                     class="hidden absolute top-full mt-2 left-0 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 z-50 min-w-[300px] py-2">
                    <button onclick="filterProdi('semua')"
                        class="w-full text-left px-5 py-3 text-xs font-bold text-on-surface hover:bg-surface-container-low transition-colors">
                        Semua Program Studi
                    </button>
                    @foreach(['informatika' => 'D4 TRIN - Teknologi Rekayasa Informatika Industri', 'otomasi' => 'D4 TRO - Teknologi Rekayasa Otomasi', 'mekatronika' => 'D4 TRMO - Teknologi Rekayasa Mekatronika'] as $key => $label)
                    <button onclick="filterProdi('{{ $key }}')"
                        class="w-full text-left px-5 py-3 text-xs font-medium text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Search --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-base">search</span>
                <input type="text" id="searchInput" oninput="filterSearch()"
                    placeholder="Cari mahasiswa..."
                    class="pl-9 pr-4 py-2.5 rounded-xl border border-outline-variant/30 text-xs font-medium text-on-surface bg-surface-container-low focus:outline-none focus:border-primary w-48">
            </div>
        </div>

        <button onclick="exportCSV()"
            class="flex items-center gap-2 px-5 py-2.5 bg-white border border-outline-variant/30 rounded-xl text-xs font-bold text-on-surface-variant hover:bg-surface-container-low transition-all shadow-sm">
            <span class="material-symbols-outlined text-base">download</span>
            Export
        </button>
    </div>

    {{-- Table --}}
    <table class="w-full text-left" id="mahasiswaTable">
        <thead>
            <tr class="border-b border-outline-variant/10">
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline">Mahasiswa</th>
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline">NIM</th>
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline">Prodi</th>
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline">Nilai Supervisi</th>
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline">Nilai Proyek</th>
                <th class="px-7 py-5 text-[10px] font-black uppercase tracking-widest text-outline text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-outline-variant/10">
            @forelse($mahasiswaData as $s)
            <tr class="mahasiswa-row hover:bg-surface-container-lowest transition-colors group"
                data-prodi="{{ $s['prodi'] }}"
                data-name="{{ strtolower($s['name']) }}">
                <td class="px-7 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center text-primary font-black text-xs border border-primary-container">
                            {{ strtoupper(substr($s['name'], 0, 2)) }}
                        </div>
                        <span class="font-bold text-on-surface">{{ $s['name'] }}</span>
                    </div>
                </td>
                <td class="px-7 py-5 text-sm font-medium text-on-surface-variant font-mono">{{ $s['nim'] ?? '-' }}</td>
                <td class="px-7 py-5">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-primary-container text-primary">
                        {{ singkatProdi($s['prodi']) }}
                    </span>
                </td>
                <td class="px-7 py-5">
                    <span class="font-black text-primary">{{ $s['nilai_supervisi'] ?? '-' }}</span>
                </td>
                <td class="px-7 py-5">
                    <span class="font-black text-primary">{{ $s['nilai_proyek'] ?? '-' }}</span>
                </td>
                <td class="px-7 py-5 text-center">
                    <a href="{{ route('penilaian.index') }}?mahasiswa_id={{ $s['id'] }}"
                       class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-black text-[#7fffd4] hover:scale-105 transition-all shadow-lg"
                       style="background:#004d4d;">
                        Detail
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-7 py-20 text-center text-on-surface-variant/40 font-black italic">
                    Belum ada data mahasiswa.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Empty State --}}
    <div id="emptyState" class="hidden px-7 py-20 text-center">
        <span class="material-symbols-outlined text-5xl text-outline-variant mb-3 block">person_search</span>
        <p class="text-on-surface-variant/50 font-black italic text-sm">Tidak ada mahasiswa ditemukan.</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
const prodiLabels = {
    'semua':       'Semua Prodi',
    'informatika': 'D4 TRIN - Teknologi Rekayasa Informatika Industri',
    'otomasi':     'D4 TRO - Teknologi Rekayasa Otomasi',
    'mekatronika': 'D4 TRMO - Teknologi Rekayasa Mekatronika',
};

let currentProdi = 'semua';

function applyFilter() {
    const searchVal = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('.mahasiswa-row');
    let visible = 0;

    rows.forEach(row => {
        const matchProdi = currentProdi === 'semua' || row.dataset.prodi === currentProdi;
        const matchSearch = !searchVal || row.dataset.name.includes(searchVal);
        const show = matchProdi && matchSearch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
}

function filterProdi(prodi) {
    currentProdi = prodi;
    document.getElementById('prodiLabel').textContent = prodiLabels[prodi];
    document.getElementById('prodiDropdown').classList.add('hidden');
    applyFilter();
}

function filterSearch() {
    applyFilter();
}

function exportCSV() {
    window.location.href = "{{ route('laporan.admin') }}";
}

function toggleProdiDropdown() {
    document.getElementById('prodiDropdown').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('prodiDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('prodiDropdown').classList.add('hidden');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    applyFilter();
});
</script>
@endpush