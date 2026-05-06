@extends('layouts.app')

@section('title', 'Rekap Logbook Mingguan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Header --}}
<div class="mb-6">
    <a href="{{ route('logbook_harian.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-3 transition">
        <span class="material-symbols-outlined text-base">arrow_back</span>
        Kembali ke Logbook Harian
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Rekap Logbook Mingguan</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola dan ajukan rekap logbook harian per minggu ke dosen pengampu.</p>
</div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Minggu Belum Direkap --}}
    @if(count($mingguBelumRekap) > 0)
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h2 class="text-sm font-semibold text-blue-700 mb-3">
            <span class="material-symbols-outlined align-middle text-base">pending_actions</span>
            Minggu Siap Direkap
        </h2>
        <div class="space-y-2">
            @foreach($mingguBelumRekap as $item)
            <div class="flex items-center justify-between bg-white border border-blue-100 rounded-lg px-4 py-2">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $item['proyek']->judul_proyek }}</p>
                    <p class="text-xs text-gray-500">Minggu ke-{{ $item['minggu_ke'] }}</p>
                </div>
                <form method="POST" action="{{ route('logbook_mingguan.generate') }}">
                    @csrf
                    <input type="hidden" name="pengajuan_proyek_id" value="{{ $item['proyek']->id }}">
                    <input type="hidden" name="minggu_ke" value="{{ $item['minggu_ke'] }}">
                    <button type="submit"
                        class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                        <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                        Generate & Ajukan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Daftar Rekap Mingguan --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Riwayat Rekap Mingguan</h2>
        </div>

        @if($rekapList->isEmpty())
        <div class="px-5 py-10 text-center text-gray-400 text-sm">
            <span class="material-symbols-outlined text-4xl block mb-2">folder_open</span>
            Belum ada rekap mingguan.
        </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($rekapList as $rekap)
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $rekap->proyek->judul_proyek ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Minggu ke-{{ $rekap->minggu_ke }} &nbsp;·&nbsp;
                        Diajukan: {{ $rekap->diajukan_at ? $rekap->diajukan_at->format('d/m/Y') : '-' }}
                    </p>
                    @if($rekap->catatan_dosen)
                    <p class="text-xs text-amber-600 mt-1">
                        <span class="material-symbols-outlined text-xs align-middle">comment</span>
                        {{ $rekap->catatan_dosen }}
                    </p>
                    @endif
                </div>

                {{-- Badge Status --}}
                <div class="shrink-0">
                    @php
                        $badgeClass = match($rekap->status) {
                            'diajukan'  => 'bg-blue-100 text-blue-700',
                            'disetujui' => 'bg-green-100 text-green-700',
                            'ditolak'   => 'bg-red-100 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $badgeLabel = match($rekap->status) {
                            'diajukan'  => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak'   => 'Ditolak',
                            default     => 'Draft',
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeClass }}">
                        {{ $badgeLabel }}
                    </span>
                </div>

                {{-- Tombol Lihat PDF --}}
                @if($rekap->pdf_path)
                <a href="{{ route('logbook_mingguan.show', $rekap->id) }}"
                    target="_blank"
                    class="shrink-0 flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-300 px-3 py-1.5 rounded-lg transition">
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                    Lihat PDF
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection