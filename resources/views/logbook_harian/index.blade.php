@extends('layouts.app')
@section('title', 'Logbook Harian')

@section('content')
<div class="space-y-6 max-w-[1400px] mx-auto">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-on-surface">Logbook Harian</h1>
            <p class="text-xs text-slate-400 mt-0.5">Riwayat aktivitas harian proyek PBL</p>
        </div>
        <a href="{{ route('logbook_harian.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-primary to-emerald-800 text-white font-semibold rounded-xl text-sm shadow hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm">add</span> Input Hari Ini
        </a>
    </div>

    {{-- Rekap mingguan CTA --}}
    <div class="bg-gradient-to-br from-primary via-emerald-800 to-secondary rounded-2xl p-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-teal-200/70 text-xs font-bold uppercase tracking-widest mb-1">Fitur</p>
            <h3 class="text-lg font-extrabold text-white">Rekap Mingguan</h3>
            <p class="text-teal-100/60 text-xs mt-1">Lihat gabungan logbook harian per minggu</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('logbook_harian.rekap') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white font-bold text-sm rounded-xl transition-all whitespace-nowrap">
                <span class="material-symbols-outlined text-sm">summarize</span> Lihat Rekap
            </a>
            <a href="{{ route('logbook_mingguan.index') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-[#7fffd4] hover:bg-[#5fffca] text-[#004d4d] font-bold text-sm rounded-xl transition-all whitespace-nowrap">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span> Logbook Mingguan
            </a>
        </div>
    </div>

    {{-- List per minggu --}}
    @forelse($logbook as $minggu => $entries)
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings:'FILL' 1">calendar_month</span>
                Minggu ke-{{ $minggu }}
            </h4>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400">{{ $entries->count() }} hari</span>
                <a href="{{ route('logbook_harian.rekap', ['minggu_ke' => $minggu]) }}"
                   class="text-xs font-semibold text-primary hover:underline">Rekap →</a>
            </div>
        </div>

        @foreach($entries->sortBy(fn($e) => ['Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5][$e->hari]) as $entry)
        <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                    {{ substr($entry->hari, 0, 3) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-on-surface text-sm">{{ $entry->hari }}, {{ $entry->tanggal->format('d M Y') }}</p>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ Str::limit($entry->aktivitas, 80) }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
    @if($entry->dokumentasi)
    <a href="{{ asset('storage/' . $entry->dokumentasi) }}" target="_blank"
       title="Lihat dokumentasi"
       class="material-symbols-outlined text-slate-400 hover:text-primary text-lg transition-colors">
        attach_file
    </a>
    @endif

    {{-- Tombol Edit — hanya status pending --}}
    @if($entry->status_verifikasi === 'pending')
    <a href="{{ route('logbook_harian.edit', $entry) }}"
       title="Edit logbook"
       class="material-symbols-outlined text-slate-400 hover:text-amber-500 text-lg transition-colors">
        edit
    </a>
    @endif

    {{-- Tombol Hapus — hanya status pending --}}
    @if($entry->status_verifikasi === 'pending')
    <form method="POST" action="{{ route('logbook_harian.destroy', $entry) }}"
        onsubmit="return confirm('Hapus logbook {{ $entry->hari }}, {{ $entry->tanggal->format('d M Y') }}?')">
        @csrf
        @method('DELETE')
        <button type="submit" title="Hapus logbook"
            class="material-symbols-outlined text-slate-400 hover:text-red-500 text-lg transition-colors bg-transparent border-0 cursor-pointer p-0">
            delete
        </button>
    </form>
    @endif

    <a href="{{ route('logbook_harian.show', $entry) }}" class="text-primary">
        <span class="material-symbols-outlined text-xl">chevron_right</span>
    </a>
</div>
            </div>
        </div>
        @endforeach
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm px-6 py-16 text-center">
        <span class="material-symbols-outlined text-4xl text-slate-200">edit_note</span>
        <p class="text-slate-300 text-sm mt-3 font-semibold">Belum ada logbook harian.</p>
        <a href="{{ route('logbook_harian.create') }}"
           class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-primary text-white font-bold text-sm rounded-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm">add</span> Input Sekarang
        </a>
    </div>
    @endforelse

</div>
@endsection