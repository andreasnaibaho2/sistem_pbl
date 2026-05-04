@extends('layouts.app')
@section('content')

<div class="space-y-6 max-w-[1400px] mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-on-surface">Dashboard Manager Proyek</h2>
            <p class="text-sm text-slate-500 mt-1">Selamat datang, <span class="font-semibold text-primary">{{ auth()->user()->name }}</span>. Kelola proyek PBL Anda.</p>
        </div>
        <a href="{{ route('pengajuan_proyek.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-primary to-emerald-800 text-white font-semibold rounded-lg text-sm shadow-lg hover:opacity-90 transition-all w-fit">
            <span class="material-symbols-outlined text-sm">add</span> Ajukan Proyek
        </a>
    </div>

    {{-- STAT CARDS --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center text-primary mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">folder_open</span>
            </div>
            <p class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Total Proyek</p>
            <h3 class="text-3xl font-extrabold text-on-surface mt-1">{{ $totalProyek }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center text-primary mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">check_circle</span>
            </div>
            <p class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Disetujui</p>
            <h3 class="text-3xl font-extrabold text-on-surface mt-1">{{ $proyekDisetujui }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">pending</span>
            </div>
            <p class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Menunggu</p>
            <h3 class="text-3xl font-extrabold text-on-surface mt-1">{{ $proyekPending }}</h3>
        </div>

        <div class="bg-gradient-to-br from-primary to-emerald-800 p-6 rounded-2xl shadow-md hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center text-white mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">group</span>
            </div>
            <p class="text-teal-100/70 uppercase tracking-widest text-[10px] font-bold">Total Mahasiswa</p>
            <h3 class="text-3xl font-extrabold text-white mt-1">{{ $totalMahasiswa }}</h3>
        </div>
    </section>

    {{-- SHORTCUT VERIFIKASI LOGBOOK --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('logbook_mingguan.daftar_verifikasi') }}"
           class="flex items-center gap-4 bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors shrink-0">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">rate_review</span>
            </div>
            <div>
                <p class="font-bold text-sm text-on-surface">Verifikasi Logbook Mingguan</p>
                <p class="text-xs text-slate-400 mt-0.5">Review & ACC rekap mingguan mahasiswa</p>
            </div>
            <span class="material-symbols-outlined text-slate-300 group-hover:text-indigo-400 ml-auto transition-colors">chevron_right</span>
        </a>

        <a href="{{ route('logbook_harian.rekap') }}"
           class="flex items-center gap-4 bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-teal-100 transition-colors shrink-0">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">calendar_month</span>
            </div>
            <div>
                <p class="font-bold text-sm text-on-surface">Rekap Logbook Harian</p>
                <p class="text-xs text-slate-400 mt-0.5">Lihat aktivitas harian mahasiswa per minggu</p>
            </div>
            <span class="material-symbols-outlined text-slate-300 group-hover:text-primary ml-auto transition-colors">chevron_right</span>
        </a>
    </section>

    {{-- PROYEK LIST --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">work</span>
                Proyek Saya
            </h4>
            <a href="{{ route('pengajuan_proyek.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
        </div>

        @forelse($proyekList as $proyek)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0"
             x-data="{ open: false }">

            {{-- ROW UTAMA --}}
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">engineering</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm truncate">{{ $proyek->judul_proyek }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $proyek->tanggal_mulai?->format('d M Y') }} — {{ $proyek->tanggal_selesai?->format('d M Y') }}
                        · <span class="font-medium">{{ $proyek->mahasiswa->count() }} mahasiswa</span>
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $proyek->getStatusBadgeColor() }}">
                        {{ $proyek->getStatusLabel() }}
                    </span>
                    @if($proyek->mahasiswa->count() > 0)
                    <button @click="open = !open"
                            class="text-xs font-semibold text-slate-400 hover:text-primary flex items-center gap-0.5 transition-colors">
                        <span class="material-symbols-outlined text-sm" x-text="open ? 'expand_less' : 'expand_more'">expand_more</span>
                    </button>
                    @endif
                    <a href="{{ route('pengajuan_proyek.show', $proyek) }}"
                       class="text-xs font-semibold text-primary hover:underline flex items-center gap-0.5 whitespace-nowrap">
                        Detail
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

            {{-- MAHASISWA COLLAPSE --}}
            @if($proyek->mahasiswa->count() > 0)
            <div x-show="open" x-transition class="mt-3 ml-14 space-y-2">
                @foreach($proyek->mahasiswa as $mhs)
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-2">
                    <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-primary text-xs font-bold shrink-0">
                        {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-on-surface truncate">{{ $mhs->nama }}</p>
                        <p class="text-[10px] text-slate-400">{{ $mhs->nim }} · {{ $mhs->pivot->prodi ?? '-' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-300 text-sm">
            Belum ada proyek yang diajukan.
            <a href="{{ route('pengajuan_proyek.create') }}" class="text-primary font-semibold hover:underline">Ajukan sekarang →</a>
        </div>
        @endforelse
    </div>

</div>
@endsection