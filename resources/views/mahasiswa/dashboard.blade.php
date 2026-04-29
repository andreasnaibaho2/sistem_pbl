@extends('layouts.app')
@section('content')

<div class="space-y-6 max-w-[1400px] mx-auto">

    {{-- HERO CARD --}}
    <div class="bg-gradient-to-br from-primary via-emerald-800 to-secondary rounded-2xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-lg">
        <div>
            <p class="text-teal-200/70 text-xs font-bold uppercase tracking-widest mb-1">Selamat Datang</p>
            <h2 class="text-2xl font-extrabold text-white">{{ auth()->user()->name }}</h2>
            <p class="text-teal-100/60 text-sm mt-1">NIM: {{ $mahasiswa?->nim ?? '-' }}</p>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-center">
                <p class="text-teal-200/60 text-[10px] uppercase font-bold">Nilai Akhir</p>
                <p class="text-4xl font-extrabold text-white mt-1">{{ $nilaiAkhir ? number_format($nilaiAkhir, 1) : '—' }}</p>
            </div>
            <div class="w-px h-12 bg-white/20"></div>
            <div class="text-center">
                <p class="text-teal-200/60 text-[10px] uppercase font-bold">Logbook</p>
                <p class="text-4xl font-extrabold text-white mt-1">{{ $totalLogbook }}</p>
            </div>
            <div class="w-px h-12 bg-white/20"></div>
            <div class="text-center">
                <p class="text-teal-200/60 text-[10px] uppercase font-bold">Laporan</p>
                <p class="text-4xl font-extrabold text-white mt-1">{{ $totalLaporan }}</p>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('logbook.create') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">edit_note</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Input Logbook</p>
            <p class="text-[10px] text-slate-400">Catat aktivitas mingguan</p>
        </a>

        <a href="{{ route('laporan.create') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">upload_file</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Upload Laporan</p>
            <p class="text-[10px] text-slate-400">Kumpulkan laporan PBL</p>
        </a>

        <a href="{{ route('logbook.index') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">checklist</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Riwayat Logbook</p>
            <p class="text-[10px] text-slate-400">Lihat semua logbook</p>
        </a>

        <a href="{{ route('penilaian.index') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">analytics</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Nilai Saya</p>
            <p class="text-[10px] text-slate-400">Lihat rekap penilaian</p>
        </a>
    </div>

    {{-- PROYEK YANG DITUGASKAN --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">work</span>
                Proyek Saya
            </h4>
            <span class="text-xs text-slate-400">{{ $proyekDitugaskan->count() }} proyek</span>
        </div>

        @forelse($proyekDitugaskan as $proyek)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">engineering</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm truncate">{{ $proyek->judul_proyek }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Manager: {{ $proyek->manager->name ?? '-' }}</p>
                    <p class="text-xs text-slate-400">
                        {{ $proyek->tanggal_mulai?->format('d M Y') }} — {{ $proyek->tanggal_selesai?->format('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $proyek->getStatusBadgeColor() }}">
                        {{ $proyek->getStatusLabel() }}
                    </span>
                    <a href="{{ route('pengajuan_proyek.show', $proyek) }}"
                       class="text-xs font-semibold text-primary hover:underline flex items-center gap-0.5 whitespace-nowrap">
                        Detail
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-300 text-sm">
            Belum ada proyek yang ditugaskan.
        </div>
        @endforelse
    </div>

    {{-- LOGBOOK TERBARU --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface">Logbook Terbaru</h4>
            <a href="{{ route('logbook.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
        </div>
        @forelse($logbookTerbaru as $logbook)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                    W{{ $logbook->minggu_ke }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm">Minggu ke-{{ $logbook->minggu_ke }}</p>
                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($logbook->tanggal)->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @php
                        $statusColor = match($logbook->status_verifikasi) {
                            'disetujui' => 'bg-teal-50 text-teal-700',
                            'ditolak'   => 'bg-red-50 text-red-700',
                            default     => 'bg-amber-50 text-amber-700',
                        };
                        $statusLabel = match($logbook->status_verifikasi) {
                            'disetujui' => 'Disetujui',
                            'ditolak'   => 'Ditolak',
                            default     => 'Menunggu',
                        };
                    @endphp
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                    <a href="{{ route('logbook.show', $logbook) }}" class="text-primary">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-300 text-sm">
            Belum ada logbook.
            <a href="{{ route('logbook.create') }}" class="text-primary font-semibold hover:underline">Buat sekarang →</a>
        </div>
        @endforelse
    </div>

</div>
@endsection