@extends('layouts.app')
@section('title', 'Data Mahasiswa')
@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
            Master Data <span class="text-[#2dce89]">Mahasiswa</span>
        </h1>
        <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
            {{ $mahasiswas->count() }} mahasiswa terdaftar
        </p>
    </div>
    <div class="flex items-center gap-3">
    <a href="{{ route('mahasiswa.batch.create') }}"
       class="flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-lg text-[#004d4d]"
       style="background:#7fffd4;">
        <span class="material-symbols-outlined text-base">table_rows</span> Input Massal
    </a>
    <a href="{{ route('mahasiswa.create') }}"
       class="flex items-center gap-2 px-5 py-3 bg-primary text-on-primary rounded-2xl text-xs font-black hover:opacity-90 hover:scale-105 transition-all shadow-lg">
        <span class="material-symbols-outlined text-base">person_add</span> Tambah Mahasiswa
    </a>
</div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="mb-6 px-5 py-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 text-xs font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-base">check_circle</span>
    {{ session('success') }}
</div>
@endif

@if(session('warnings'))
<div class="mb-6 px-5 py-4 rounded-2xl bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-bold">
    <p class="font-black mb-1 flex items-center gap-2">
        <span class="material-symbols-outlined text-base">warning</span>
        Beberapa baris dilewati:
    </p>
    <ul class="list-disc list-inside space-y-1 mt-1">
        @foreach(session('warnings') as $w)
        <li>{{ $w }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- STAT CARDS --}}
@php
    $cTRMO = $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'mekatronika')->count();
    $cTRO  = $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'otomasi')->count();
    $cTRIN = $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'informatika')->count();
@endphp
<div class="grid grid-cols-4 gap-5 mb-8">
    <div class="relative overflow-hidden rounded-[2rem] p-7 flex flex-col justify-between shadow-xl h-36" style="background:#004d4d;">
        <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
        <p class="text-5xl font-black italic tracking-tighter text-white">{{ $mahasiswas->count() }}</p>
        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-white/5" style="font-size:100px;">group</span>
    </div>
    <div class="bg-white rounded-[2rem] border-2 border-purple-200 p-7 flex flex-col justify-between h-36 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-purple-400 inline-block"></span>
            <p class="text-purple-500 text-[10px] font-black uppercase tracking-widest">Prodi TRMO</p>
        </div>
        <p class="text-5xl font-black italic tracking-tighter text-on-surface">{{ $cTRMO }}</p>
    </div>
    <div class="bg-white rounded-[2rem] border-2 border-blue-200 p-7 flex flex-col justify-between h-36 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
            <p class="text-blue-500 text-[10px] font-black uppercase tracking-widest">Prodi TRO</p>
        </div>
        <p class="text-5xl font-black italic tracking-tighter text-on-surface">{{ $cTRO }}</p>
    </div>
    <div class="bg-white rounded-[2rem] border-2 border-primary-container p-7 flex flex-col justify-between h-36 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-secondary inline-block"></span>
            <p class="text-secondary text-[10px] font-black uppercase tracking-widest">Prodi TRIN</p>
        </div>
        <p class="text-5xl font-black italic tracking-tighter text-on-surface">{{ $cTRIN }}</p>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">

    {{-- Search bar --}}
    <div class="px-7 py-5 border-b border-outline-variant/10 flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-base">search</span>
            <input type="text" id="searchMhs" oninput="filterMhs()" placeholder="Cari mahasiswa..."
                class="w-full pl-9 pr-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-xs font-medium text-on-surface focus:outline-none focus:border-primary">
        </div>
        <div class="flex items-center gap-2 ml-auto">
            @foreach(['semua'=>'Semua','mekatronika'=>'TRMO','otomasi'=>'TRO','informatika'=>'TRIN'] as $val=>$lbl)
            <button onclick="filterProdi('{{ $val }}')"
                data-prodi="{{ $val }}"
                class="prodi-btn px-3 py-2 rounded-xl text-[10px] font-black border transition-all
                {{ $val==='semua' ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container text-on-surface-variant border-outline-variant/20 hover:border-primary hover:text-primary' }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>
    </div>

    <table class="w-full text-left" id="mhsTable">
        <thead>
            <tr class="border-b border-outline-variant/10 bg-surface-container-low/30">
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline w-10">#</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Identitas</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">NIM</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Program Studi</th>
                <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/10" id="mhsTbody">
            @forelse($mahasiswas as $idx => $mhs)
            @php
                $prodi = $mhs->user->prodi ?? '';
                $prodiLabel = match($prodi) {
                    'mekatronika' => 'D4 TRMO – Teknik Rekayasa Mekatronika',
                    'otomasi'     => 'D4 TRO – Teknik Rekayasa Otomasi',
                    'informatika' => 'D4 TRIN – Informatika Industri',
                    default       => $prodi ?: '-'
                };
                $badgeColor = match($prodi) {
                    'mekatronika' => 'bg-purple-100 text-purple-700',
                    'otomasi'     => 'bg-blue-100 text-blue-700',
                    'informatika' => 'bg-primary-container text-primary',
                    default       => 'bg-surface-container text-on-surface-variant'
                };
            @endphp
            <tr class="mhs-row hover:bg-surface-container-lowest transition-colors group"
                data-prodi="{{ $prodi }}"
                data-name="{{ strtolower($mhs->nama) }}">
                <td class="px-7 py-5 text-[10px] font-black text-outline/40">{{ $idx + 1 }}</td>
                <td class="px-7 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary font-black text-xs shrink-0">
                            {{ strtoupper(substr($mhs->nama, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-black text-on-surface text-sm leading-none">{{ $mhs->nama }}</p>
                            <p class="text-[10px] text-outline font-medium mt-0.5">{{ $mhs->user->email ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-7 py-5">
                    <span class="inline-flex px-3 py-1.5 bg-surface-container text-on-surface-variant rounded-xl text-[10px] font-black font-mono tracking-wider">
                        {{ $mhs->nim }}
                    </span>
                </td>
                <td class="px-7 py-5">
                    <span class="inline-flex px-3 py-1.5 rounded-xl text-[10px] font-black {{ $badgeColor }}">
                        {{ $prodiLabel }}
                    </span>
                </td>
                <td class="px-7 py-5">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('mahasiswa.edit', $mhs->id) }}"
                           class="p-2.5 text-outline hover:text-primary hover:bg-primary-container rounded-xl transition-all"
                           title="Edit">
                            <span class="material-symbols-outlined text-base">edit</span>
                        </a>
                        <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST"
                              onsubmit="return confirm('Hapus mahasiswa ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-2.5 text-outline hover:text-white hover:bg-red-500 rounded-xl transition-all"
                                    title="Hapus">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-7 py-20 text-center">
                    <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">person_search</span>
                    <p class="font-black italic text-on-surface-variant/40 text-sm">Belum ada data mahasiswa.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Empty filter state --}}
    <div id="emptyFilter" class="hidden px-7 py-16 text-center">
        <span class="material-symbols-outlined text-5xl text-outline-variant/40 block mb-3">search_off</span>
        <p class="font-black italic text-on-surface-variant/40 text-sm">Tidak ada mahasiswa ditemukan.</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
let activeProdi = 'semua';

function filterMhs() {
    const q = document.getElementById('searchMhs').value.toLowerCase();
    const rows = document.querySelectorAll('.mhs-row');
    let visible = 0;
    rows.forEach(r => {
        const matchProdi = activeProdi === 'semua' || r.dataset.prodi === activeProdi;
        const matchName  = !q || r.dataset.name.includes(q);
        const show = matchProdi && matchName;
        r.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('emptyFilter').classList.toggle('hidden', visible > 0);
}

function filterProdi(prodi) {
    activeProdi = prodi;
    document.querySelectorAll('.prodi-btn').forEach(btn => {
        const isActive = btn.dataset.prodi === prodi;
        btn.className = btn.className
            .replace(/bg-primary text-on-primary border-primary/g, '')
            .replace(/bg-surface-container text-on-surface-variant border-outline-variant\/20 hover:border-primary hover:text-primary/g, '');
        if (isActive) {
            btn.classList.add('bg-primary','text-on-primary','border-primary');
        } else {
            btn.classList.add('bg-surface-container','text-on-surface-variant','border-outline-variant/20','hover:border-primary','hover:text-primary');
        }
    });
    filterMhs();
}
</script>
@endpush