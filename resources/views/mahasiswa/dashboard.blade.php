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
    @if($nilaiAkhir)
        <p class="text-4xl font-extrabold text-white mt-1">{{ number_format($nilaiAkhir, 1) }}</p>
        <p class="text-teal-300 text-xs font-bold mt-0.5">Grade {{ $grade }}</p>
    @else
        <p class="text-2xl font-extrabold text-white/50 mt-1">—</p>
        <p class="text-teal-300/60 text-[10px] mt-0.5">Belum dinilai</p>
    @endif
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
        <a href="{{ route('logbook_harian.create') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">edit_note</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Input Logbook</p>
            <p class="text-[10px] text-slate-400">Catat aktivitas harian</p>
        </a>

        <a href="{{ route('laporan.create') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1">upload_file</span>
            </div>
            <p class="text-sm font-bold text-on-surface">Upload Laporan</p>
            <p class="text-[10px] text-slate-400">Kumpulkan laporan PBL</p>
        </a>

        <a href="{{ route('logbook_harian.index') }}" class="bg-white p-5 rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex flex-col items-center gap-3 text-center group">
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
    {{-- SUPERVISI SAYA --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">school</span>
                Supervisi Saya
            </h4>
            <span class="text-xs text-slate-400">{{ $supervisiList->count() }} matkul</span>
        </div>

        @forelse($supervisiList as $s)
        <div class="px-6 py-4 border-b border-slate-50 last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">menu_book</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm">{{ $s->mataKuliah->nama_matkul ?? '-' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $s->mataKuliah->kode_matkul ?? '' }} · {{ $s->mataKuliah->sks ?? '' }} SKS</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-semibold text-on-surface">{{ $s->dosen->nama_dosen ?? '-' }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Dosen Pengampu</p>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-300 text-sm">
            Belum ada supervisi yang ditugaskan.
        </div>
        @endforelse
    </div>
    {{-- LOGBOOK HARIAN TERBARU --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">edit_note</span>
                Logbook Terbaru
            </h4>
            <a href="{{ route('logbook_harian.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
        </div>
        @forelse($logbookTerbaru as $logbook)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                    {{ isset($logbook->hari) ? substr($logbook->hari, 0, 3) : 'W'.$logbook->minggu_ke }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm">
                        {{ isset($logbook->hari) ? $logbook->hari.', Minggu ke-'.$logbook->minggu_ke : 'Minggu ke-'.$logbook->minggu_ke }}
                    </p>
                    <p class="text-xs text-slate-400 truncate">
                        {{ \Carbon\Carbon::parse($logbook->tanggal)->format('d M Y') }}
                        @if(isset($logbook->aktivitas))
                        · {{ Str::limit($logbook->aktivitas, 50) }}
                        @endif
                    </p>
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
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-slate-300 text-sm">
            Belum ada logbook.
            <a href="{{ route('logbook_harian.create') }}" class="text-primary font-semibold hover:underline">Buat sekarang →</a>
        </div>
        @endforelse
    </div>

</div>
@endsection