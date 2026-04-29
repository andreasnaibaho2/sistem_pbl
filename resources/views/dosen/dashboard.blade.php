@extends('layouts.app')
@section('content')

<div class="space-y-6 max-w-[1400px] mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-on-surface">Dashboard Dosen Pengampu</h2>
            <p class="text-sm text-slate-500 mt-1">Selamat datang, <span class="font-semibold text-primary">{{ auth()->user()->name }}</span>. Verifikasi laporan dan penilaian mahasiswa.</p>
        </div>
        <a href="{{ route('penilaian.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-primary to-emerald-800 text-white font-semibold rounded-lg text-sm shadow-lg hover:opacity-90 transition-all w-fit">
            <span class="material-symbols-outlined text-sm">add</span> Input Penilaian
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
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">group</span>
            </div>
            <p class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Total Mahasiswa</p>
            <h3 class="text-3xl font-extrabold text-on-surface mt-1">{{ $totalMahasiswa }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">pending_actions</span>
            </div>
            <p class="text-slate-400 uppercase tracking-widest text-[10px] font-bold">Laporan Menunggu</p>
            <h3 class="text-3xl font-extrabold text-on-surface mt-1">{{ $laporanMenunggu }}</h3>
        </div>

        <div class="bg-gradient-to-br from-primary to-emerald-800 p-6 rounded-2xl shadow-md hover:-translate-y-0.5 transition-transform">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center text-white mb-4">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">edit_note</span>
            </div>
            <p class="text-teal-100/70 uppercase tracking-widest text-[10px] font-bold">Penilaian Selesai</p>
            <h3 class="text-3xl font-extrabold text-white mt-1">{{ $penilaianSelesai }}</h3>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Quick Actions --}}
        <div class="bg-white p-6 rounded-2xl border border-outline-variant/20 shadow-sm">
            <h4 class="text-[10px] font-bold text-slate-400 mb-4 uppercase tracking-wider">Aksi Cepat</h4>
            <div class="space-y-3">
                <a href="{{ route('laporan.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-teal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center group-hover:border-teal-200 transition-colors">
                        <span class="material-symbols-outlined text-lg text-slate-500 group-hover:text-primary">description</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-on-surface">Verifikasi Laporan</p>
                        <p class="text-[10px] text-slate-400">{{ $laporanMenunggu }} menunggu</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <a href="{{ route('penilaian.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-teal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center group-hover:border-teal-200 transition-colors">
                        <span class="material-symbols-outlined text-lg text-slate-500 group-hover:text-primary">edit_note</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-on-surface">Input Nilai</p>
                        <p class="text-[10px] text-slate-400">Form penilaian mahasiswa</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <a href="{{ route('laporan.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-teal-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center group-hover:border-teal-200 transition-colors">
                        <span class="material-symbols-outlined text-lg text-slate-500 group-hover:text-primary">folder_open</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-on-surface">Semua Laporan</p>
                        <p class="text-[10px] text-slate-400">Riwayat laporan mahasiswa</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>
            </div>
        </div>

        {{-- Proyek Aktif --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">work</span>
                    Proyek Aktif
                </h4>
                <span class="text-xs text-slate-400">{{ $totalProyek }} proyek</span>
            </div>

            @forelse($proyekList as $proyek)
            <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0"
                 x-data="{ open: false }">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">engineering</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-on-surface text-sm truncate">{{ $proyek->judul_proyek }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Manager: {{ $proyek->manager->name ?? '-' }}
                            · <span class="font-medium">{{ $proyek->mahasiswa->count() }} mahasiswa</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
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

                {{-- Mahasiswa collapse --}}
                @if($proyek->mahasiswa->count() > 0)
                <div x-show="open" x-transition class="mt-3 ml-14 space-y-2">
                    @foreach($proyek->mahasiswa as $mhs)
                    <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-2">
                        <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-primary text-xs font-bold shrink-0">
                            {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-on-surface truncate">{{ $mhs->nama }}</p>
                            <p class="text-[10px] text-slate-400">{{ $mhs->nim }} · {{ labelProdi($mhs->pivot->prodi) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="px-6 py-10 text-center text-slate-300 text-sm">
                Belum ada proyek aktif.
            </div>
            @endforelse
        </div>
    </div>

    {{-- LAPORAN TERBARU --}}
    @if($laporanTerbaru->count() > 0)
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface">Laporan Terbaru</h4>
            <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
        </div>
        @foreach($laporanTerbaru as $laporan)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">description</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm truncate">{{ $laporan->judul ?? 'Laporan MK/PPI' }}</p>
                    <p class="text-xs text-slate-400">{{ $laporan->mahasiswa->nama ?? '-' }} · {{ $laporan->created_at->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @php
                        $statusColor = match($laporan->status_verifikasi) {
                            'disetujui' => 'bg-teal-50 text-teal-700',
                            'ditolak'   => 'bg-red-50 text-red-700',
                            default     => 'bg-amber-50 text-amber-700',
                        };
                        $statusLabel = match($laporan->status_verifikasi) {
                            'disetujui' => 'Disetujui',
                            'ditolak'   => 'Ditolak',
                            default     => 'Menunggu',
                        };
                    @endphp
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                    <a href="{{ route('laporan.show', $laporan) }}" class="text-primary">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection