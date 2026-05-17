@extends('layouts.app')
@section('title', 'Rekap Penilaian')
@section('content')

@php
    $totalMhs     = $mahasiswaList->count();
    $sudahLengkap = $mahasiswaList->filter(fn($m) =>
        $m->penilaianManager->isNotEmpty() && $m->penilaianDosen->isNotEmpty()
    )->count();
    $belumLengkap = $totalMhs - $sudahLengkap;
@endphp

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
            Rekap <span class="text-[#2dce89]">Semua Penilaian</span>
        </h1>
        <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
            Rekapitulasi nilai akhir per mahasiswa
        </p>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between shadow-xl h-36" style="background:#004d4d;">
        <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-white tracking-tighter">{{ $totalMhs }}</p>
            <span class="text-xs font-black text-[#7fffd4]/60">Terdaftar</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/5" style="font-size:100px;">group</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between shadow-xl h-36" style="background:#2dce89;">
        <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Nilai Lengkap</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-white tracking-tighter">{{ $sudahLengkap }}</p>
            <span class="text-xs font-black text-white/60">Mahasiswa</span>
        </div>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/20" style="font-size:100px;">task_alt</span>
    </div>

    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between bg-white border-2 border-outline-variant/20 h-36">
        <p class="text-secondary text-[10px] font-black uppercase tracking-widest">Belum Lengkap</p>
        <div class="flex items-end justify-between">
            <p class="text-5xl font-black italic text-primary tracking-tighter">{{ $belumLengkap }}</p>
            <span class="text-xs font-black text-outline">Mahasiswa</span>
        </div>
        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden mt-2">
            @php $pct = $totalMhs > 0 ? round($sudahLengkap / $totalMhs * 100) : 0; @endphp
            <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#7fffd4,#008080);"></div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="bg-white rounded-[2rem] border border-outline-variant/20 shadow-sm px-7 py-5 mb-5">
    <div class="flex flex-wrap items-center gap-3">

        {{-- Search nama / NIM --}}
        <div class="relative w-64">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-base">search</span>
            <input type="text" id="searchNilai" oninput="applyFilter()" placeholder="Cari nama atau NIM..."
                class="w-full pl-9 pr-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-xs font-medium text-on-surface focus:outline-none focus:border-primary">
        </div>

        {{-- Divider --}}
        <div class="h-6 w-px bg-outline-variant/20"></div>

        {{-- Filter Status Nilai --}}
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-outline uppercase tracking-widest">Status:</span>
            @foreach(['semua'=>'Semua','lengkap'=>'Nilai Lengkap','sebagian'=>'Sebagian','belum'=>'Belum Dinilai'] as $val=>$lbl)
            <button onclick="setFilter('status','{{ $val }}')"
                data-filter="status" data-value="{{ $val }}"
                class="filter-btn px-3 py-2 rounded-xl text-[10px] font-black border transition-all
                {{ $val==='semua' ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container text-on-surface-variant border-outline-variant/20 hover:border-primary hover:text-primary' }}">
                {{ $lbl }}
                @if($val === 'belum' && $belumLengkap > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full bg-red-400 text-white text-[9px] font-black">{{ $belumLengkap }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- Reset & Counter --}}
        <div class="ml-auto flex items-center gap-3">
            <span id="filterCounter" class="hidden text-[10px] font-black text-outline">
                <span id="visibleCount">0</span> mahasiswa ditemukan
            </span>
            <button onclick="resetFilter()" id="btnReset"
                class="hidden flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-black border border-outline-variant/20 text-outline hover:text-red-500 hover:border-red-200 transition-all">
                <span class="material-symbols-outlined text-sm">filter_alt_off</span> Reset
            </button>
        </div>
    </div>
</div>

{{-- TABEL REKAP --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-outline-variant/10 bg-surface-container-low/40">
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline w-10">#</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Mahasiswa</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Proyek / Matkul</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Manager<br><span class="normal-case font-medium text-[9px]">(55%)</span></th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Dosen<br><span class="normal-case font-medium text-[9px]">(45%)</span></th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Nilai Akhir</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Grade</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Detail</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/10" id="nilaiTbody">
            @forelse($mahasiswaList as $i => $mhs)
            @php
                $pm         = $mhs->penilaianManager->first();
                $pd         = $mhs->penilaianDosen->first();
                $nilaiM     = $pm->nilai_manager ?? null;
                $nilaiD     = $pd->nilai_dosen   ?? null;
                $nilaiAkhir = ($nilaiM !== null && $nilaiD !== null) ? round($nilaiM + $nilaiD, 1) : null;
                $grade      = null;
                if ($nilaiAkhir !== null) {
                    $grade = $nilaiAkhir >= 85 ? 'A'
                           : ($nilaiAkhir >= 75 ? 'B'
                           : ($nilaiAkhir >= 65 ? 'C'
                           : ($nilaiAkhir >= 55 ? 'D' : 'E')));
                }
                $proyek = $pm->pengajuanProyek->judul_proyek ?? null;
                $matkul = $pd->supervisiMatkul->mataKuliah->nama_matkul ?? null;

                // status filter: lengkap = keduanya ada, sebagian = salah satu, belum = keduanya null
                $statusNilai = ($nilaiM !== null && $nilaiD !== null) ? 'lengkap'
                             : (($nilaiM !== null || $nilaiD !== null) ? 'sebagian' : 'belum');
            @endphp
            <tr class="nilai-row hover:bg-surface-container-lowest transition-colors"
                data-name="{{ strtolower($mhs->nama) }}"
                data-nim="{{ $mhs->nim }}"
                data-status="{{ $statusNilai }}">

                <td class="px-7 py-5 text-[10px] font-black text-outline/40 row-num">{{ $i + 1 }}</td>

                {{-- Mahasiswa --}}
                <td class="px-7 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center font-black text-xs text-primary shrink-0">
                            {{ strtoupper(substr($mhs->nama, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-black text-on-surface text-sm">{{ $mhs->nama }}</p>
                            <p class="text-[10px] text-outline font-medium">{{ $mhs->nim }}</p>
                        </div>
                    </div>
                </td>

                {{-- Proyek / Matkul --}}
                <td class="px-7 py-5">
                    @if($proyek)
                        <p class="text-sm font-medium text-on-surface-variant">{{ $proyek }}</p>
                    @endif
                    @if($matkul)
                        <p class="text-[10px] text-outline mt-0.5">{{ $matkul }}</p>
                    @endif
                    @if(!$proyek && !$matkul)
                        <span class="text-outline/40 text-sm">-</span>
                    @endif
                </td>

                {{-- Manager (55%) --}}
                <td class="px-7 py-5 text-center">
                    @if($nilaiM !== null)
                        <span class="font-black text-sm" style="color:#004d4d;">{{ number_format($nilaiM, 1) }}</span>
                    @else
                        <span class="text-outline/40 font-black text-sm">-</span>
                    @endif
                </td>

                {{-- Dosen (45%) --}}
                <td class="px-7 py-5 text-center">
                    @if($nilaiD !== null)
                        <span class="font-black text-sm" style="color:#2dce89;">{{ number_format($nilaiD, 1) }}</span>
                    @else
                        <span class="text-outline/40 font-black text-sm">-</span>
                    @endif
                </td>

                {{-- Nilai Akhir --}}
                <td class="px-7 py-5 text-center">
                    @if($nilaiAkhir !== null)
                        @php
                            $colorAkhir = $nilaiAkhir >= 85 ? 'bg-emerald-100 text-emerald-700'
                                        : ($nilaiAkhir >= 75 ? 'bg-blue-100 text-blue-700'
                                        : ($nilaiAkhir >= 65 ? 'bg-yellow-100 text-yellow-700'
                                        : ($nilaiAkhir >= 55 ? 'bg-orange-100 text-orange-700'
                                        : 'bg-red-100 text-red-700')));
                        @endphp
                        <span class="inline-block px-4 py-1.5 rounded-xl text-sm font-black {{ $colorAkhir }}">
                            {{ number_format($nilaiAkhir, 1) }}
                        </span>
                    @else
                        <span class="inline-block px-4 py-1.5 rounded-xl text-sm font-black bg-gray-100 text-gray-400">-</span>
                    @endif
                </td>

                {{-- Grade --}}
                <td class="px-7 py-5 text-center">
                    @if($grade)
                        @php
                            $colorGrade = $grade === 'A' ? 'bg-emerald-500'
                                        : ($grade === 'B' ? 'bg-blue-500'
                                        : ($grade === 'C' ? 'bg-yellow-500'
                                        : ($grade === 'D' ? 'bg-orange-500' : 'bg-red-500')));
                        @endphp
                        <span class="inline-flex w-9 h-9 rounded-xl items-center justify-center text-white font-black text-sm {{ $colorGrade }}">
                            {{ $grade }}
                        </span>
                    @else
                        <span class="text-outline/40 font-black text-sm">-</span>
                    @endif
                </td>

                {{-- Tombol Detail --}}
                <td class="px-7 py-5 text-center">
                    <button onclick="bukaDetail({{ $mhs->id }})"
                        class="px-4 py-2 rounded-xl text-xs font-black text-white transition-all hover:opacity-80"
                        style="background:#004d4d;">
                        Detail
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-7 py-16 text-center">
                    <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">group</span>
                    <p class="text-on-surface-variant/40 font-black italic text-sm">Belum ada data mahasiswa.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Empty filter state --}}
    <div id="emptyFilter" class="hidden px-7 py-16 text-center">
        <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">search_off</span>
        <p class="font-black italic text-on-surface-variant/40 text-sm">Tidak ada mahasiswa ditemukan.</p>
        <button onclick="resetFilter()" class="mt-4 text-[10px] font-black text-primary hover:underline uppercase tracking-widest">
            Reset Filter
        </button>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-7">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-black text-[#004d4d] italic uppercase">Detail <span class="text-[#2dce89]">Penilaian</span></h2>
                    <p id="modalNama" class="text-xs text-outline font-bold mt-0.5 uppercase tracking-widest"></p>
                </div>
                <button onclick="tutupDetail()" class="w-9 h-9 rounded-xl bg-surface-container flex items-center justify-center hover:bg-red-100 transition-colors">
                    <span class="material-symbols-outlined text-outline text-base">close</span>
                </button>
            </div>
            <div id="modalContent"></div>
        </div>
    </div>
</div>

{{-- DATA JSON + SCRIPT --}}
<script>
const dataPenilaian = {
    @foreach($mahasiswaList as $mhs)
    @php
        $pm = $mhs->penilaianManager->first();
        $pd = $mhs->penilaianDosen->first();
    @endphp
    {{ $mhs->id }}: {
        nama: @json($mhs->nama),
        nim:  @json($mhs->nim),
        manager: @if($pm) {
            proyek:          @json($pm->pengajuanProyek->judul_proyek ?? '-'),
            learning_skills: {{ $pm->learning_skills ?? 'null' }},
            life_skills:     {{ $pm->life_skills ?? 'null' }},
            laporan_project: {{ $pm->laporan_project ?? 'null' }},
            nilai_manager:   {{ $pm->nilai_manager ?? 'null' }},
            catatan:         @json($pm->catatan_manager ?? '')
        } @else null @endif,
        dosen: @if($pd) {
            matkul:          @json($pd->supervisiMatkul->mataKuliah->nama_matkul ?? '-'),
            literacy_skills: {{ $pd->literacy_skills ?? 'null' }},
            presentasi:      {{ $pd->presentasi ?? 'null' }},
            laporan_akhir:   {{ $pd->laporan_akhir ?? 'null' }},
            nilai_dosen:     {{ $pd->nilai_dosen ?? 'null' }},
            catatan:         @json($pd->catatan_dosen ?? '')
        } @else null @endif
    },
    @endforeach
};

// ===================== FILTER =====================
const activeFilters = { status: 'semua' };

function setFilter(type, value) {
    activeFilters[type] = value;
    document.querySelectorAll(`[data-filter="${type}"]`).forEach(btn => {
        const isActive = btn.dataset.value === value;
        btn.className = 'filter-btn px-3 py-2 rounded-xl text-[10px] font-black border transition-all ' +
            (isActive
                ? 'bg-primary text-on-primary border-primary'
                : 'bg-surface-container text-on-surface-variant border-outline-variant/20 hover:border-primary hover:text-primary');
    });
    applyFilter();
}

function applyFilter() {
    const q      = document.getElementById('searchNilai').value.toLowerCase().trim();
    const rows   = document.querySelectorAll('.nilai-row');
    let visible  = 0;

    rows.forEach(r => {
        const matchSearch = !q || r.dataset.name.includes(q) || r.dataset.nim.includes(q);
        const matchStatus = activeFilters.status === 'semua' || r.dataset.status === activeFilters.status;
        const show = matchSearch && matchStatus;
        r.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    // Nomor urut ulang
    let num = 1;
    rows.forEach(r => {
        if (r.style.display !== 'none') r.querySelector('.row-num').textContent = num++;
    });

    document.getElementById('emptyFilter').classList.toggle('hidden', visible > 0);

    const isFiltered = q || activeFilters.status !== 'semua';
    document.getElementById('btnReset').classList.toggle('hidden', !isFiltered);
    document.getElementById('filterCounter').classList.toggle('hidden', !isFiltered);
    document.getElementById('visibleCount').textContent = visible;
}

function resetFilter() {
    document.getElementById('searchNilai').value = '';
    setFilter('status', 'semua');
}

// ===================== MODAL =====================
function bukaDetail(id) {
    const d = dataPenilaian[id];
    if (!d) return;

    document.getElementById('modalNama').textContent = d.nama + ' — ' + d.nim;

    let html = '';

    html += `<div class="mb-5 p-5 rounded-2xl border border-outline-variant/20">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-base" style="color:#004d4d;">manage_accounts</span>
            <p class="font-black text-sm text-on-surface uppercase tracking-widest">Manager Proyek</p>
            <span class="ml-auto text-xs font-black px-3 py-1 rounded-full" style="background:#e6f7f2;color:#004d4d;">55%</span>
        </div>`;

    if (d.manager) {
        html += `
        <p class="text-xs text-outline mb-3">Proyek: <span class="font-black text-on-surface">${d.manager.proyek}</span></p>
        <div class="grid grid-cols-3 gap-3 mb-3">
            ${nilaiBox('Learning Skills', d.manager.learning_skills, '#004d4d')}
            ${nilaiBox('Life Skills',     d.manager.life_skills,     '#004d4d')}
            ${nilaiBox('Lap. Project',    d.manager.laporan_project,  '#004d4d')}
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl" style="background:#004d4d;">
            <span class="text-[#7fffd4] text-xs font-black uppercase tracking-widest">Total Manager (55%)</span>
            <span class="text-white font-black text-lg">${d.manager.nilai_manager ?? '-'}</span>
        </div>
        ${d.manager.catatan ? `<p class="text-xs text-outline mt-3 italic">"${d.manager.catatan}"</p>` : ''}`;
    } else {
        html += `<p class="text-sm text-outline/50 italic text-center py-4">Belum ada penilaian dari Manager.</p>`;
    }
    html += `</div>`;

    html += `<div class="p-5 rounded-2xl border border-outline-variant/20">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-base" style="color:#2dce89;">school</span>
            <p class="font-black text-sm text-on-surface uppercase tracking-widest">Dosen Pengampu</p>
            <span class="ml-auto text-xs font-black px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">45%</span>
        </div>`;

    if (d.dosen) {
        html += `
        <p class="text-xs text-outline mb-3">Mata Kuliah: <span class="font-black text-on-surface">${d.dosen.matkul}</span></p>
        <div class="grid grid-cols-3 gap-3 mb-3">
            ${nilaiBox('Literacy Skills', d.dosen.literacy_skills, '#2dce89')}
            ${nilaiBox('Presentasi',      d.dosen.presentasi,      '#2dce89')}
            ${nilaiBox('Lap. Akhir',      d.dosen.laporan_akhir,   '#2dce89')}
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl" style="background:#2dce89;">
            <span class="text-white text-xs font-black uppercase tracking-widest">Total Dosen (45%)</span>
            <span class="text-white font-black text-lg">${d.dosen.nilai_dosen ?? '-'}</span>
        </div>
        ${d.dosen.catatan ? `<p class="text-xs text-outline mt-3 italic">"${d.dosen.catatan}"</p>` : ''}`;
    } else {
        html += `<p class="text-sm text-outline/50 italic text-center py-4">Belum ada penilaian dari Dosen.</p>`;
    }
    html += `</div>`;

    document.getElementById('modalContent').innerHTML = html;
    const modal = document.getElementById('modalDetail');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function tutupDetail() {
    const modal = document.getElementById('modalDetail');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function nilaiBox(label, nilai, color) {
    return `<div class="p-3 rounded-xl text-center" style="background:#f8fafb;border:1px solid #e8e8e8;">
        <p class="text-[9px] font-black uppercase tracking-widest text-outline mb-1">${label}</p>
        <p class="text-xl font-black" style="color:${color}">${nilai ?? '-'}</p>
    </div>`;
}

document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) tutupDetail();
});
</script>

@endsection