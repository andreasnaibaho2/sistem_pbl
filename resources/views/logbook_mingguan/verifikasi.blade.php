@extends('layouts.app')

@section('title', 'Verifikasi Logbook Mingguan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6 space-y-5" x-data="{ tab: 'semua' }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Verifikasi Logbook Mingguan</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar rekap logbook mingguan mahasiswa yang perlu diverifikasi.</p>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1.5 rounded-full font-medium self-start md:self-auto">
            Total: {{ $rekapList->count() }} pengajuan
        </span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- SUMMARY BAR --}}
    @php
        $jumlahMenunggu  = $rekapList->where('status', 'diajukan')->count();
        $jumlahDisetujui = $rekapList->where('status', 'disetujui')->count();
        $jumlahDitolak   = $rekapList->where('status', 'ditolak')->count();
    @endphp
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">pending</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Menunggu</p>
                <p class="text-2xl font-extrabold text-amber-700">{{ $jumlahMenunggu }}</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Disetujui</p>
                <p class="text-2xl font-extrabold text-green-700">{{ $jumlahDisetujui }}</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center text-red-500 shrink-0">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">cancel</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Ditolak</p>
                <p class="text-2xl font-extrabold text-red-600">{{ $jumlahDitolak }}</p>
            </div>
        </div>
    </div>

    {{-- FILTER TAB --}}
    <div class="flex gap-2 border-b border-gray-200 pb-0">
        <button @click="tab = 'semua'"
            :class="tab === 'semua' ? 'border-b-2 border-indigo-600 text-indigo-600 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="text-xs px-4 py-2.5 transition-all -mb-px">
            Semua ({{ $rekapList->count() }})
        </button>
        <button @click="tab = 'menunggu'"
            :class="tab === 'menunggu' ? 'border-b-2 border-amber-500 text-amber-600 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="text-xs px-4 py-2.5 transition-all -mb-px">
            Menunggu ({{ $jumlahMenunggu }})
        </button>
        <button @click="tab = 'disetujui'"
            :class="tab === 'disetujui' ? 'border-b-2 border-green-500 text-green-600 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="text-xs px-4 py-2.5 transition-all -mb-px">
            Disetujui ({{ $jumlahDisetujui }})
        </button>
        <button @click="tab = 'ditolak'"
            :class="tab === 'ditolak' ? 'border-b-2 border-red-500 text-red-600 font-bold' : 'text-gray-500 hover:text-gray-700'"
            class="text-xs px-4 py-2.5 transition-all -mb-px">
            Ditolak ({{ $jumlahDitolak }})
        </button>
    </div>

    {{-- LIST --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        @if($rekapList->isEmpty())
        <div class="px-5 py-12 text-center text-gray-400 text-sm">
            <span class="material-symbols-outlined text-4xl block mb-2 text-gray-300">task_alt</span>
            Tidak ada rekap yang perlu diverifikasi.
        </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($rekapList as $rekap)
            @php
                $tabStatus = match($rekap->status) {
                    'diajukan'  => 'menunggu',
                    'disetujui' => 'disetujui',
                    'ditolak'   => 'ditolak',
                    default     => 'semua',
                };
            @endphp
            <div class="px-5 py-4" x-data="{ open: false }"
                 x-show="tab === 'semua' || tab === '{{ $tabStatus }}'">

                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        {{-- Avatar --}}
                        <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center text-primary text-sm font-bold shrink-0">
                            {{ strtoupper(substr($rekap->mahasiswa->user->name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $rekap->mahasiswa->user->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $rekap->proyek->judul_proyek ?? '-' }}
                                &nbsp;·&nbsp; Minggu ke-{{ $rekap->minggu_ke }}
                                &nbsp;·&nbsp; Diajukan: {{ $rekap->diajukan_at?->format('d/m/Y') ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @php
                            $badgeClass = match($rekap->status) {
                                'diajukan'  => 'bg-amber-100 text-amber-700',
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

                        @if($rekap->pdf_path)
                        <a href="{{ route('logbook_mingguan.show', $rekap->id) }}" target="_blank"
                            class="flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-300 px-3 py-1.5 rounded-lg transition">
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                            PDF
                        </a>
                        @endif

                        @if($rekap->status === 'diajukan')
                        <button @click="open = !open"
                            class="flex items-center gap-1 text-xs font-medium bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition">
                            <span class="material-symbols-outlined text-sm">rate_review</span>
                            Verifikasi
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Catatan jika sudah diverifikasi --}}
                @if($rekap->status !== 'diajukan' && $rekap->catatan_dosen)
                <div class="mt-3 ml-12 px-3 py-2 bg-gray-50 rounded-lg border-l-2 border-gray-200">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Catatan Manager</p>
                    <p class="text-xs text-gray-600">{{ $rekap->catatan_dosen }}</p>
                </div>
                @endif

                {{-- Form Verifikasi --}}
                @if($rekap->status === 'diajukan')
                <div x-show="open" x-transition class="mt-4 ml-12 bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <form method="POST" action="{{ route('logbook_mingguan.verifikasi', $rekap->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan (opsional)</label>
                            <textarea name="catatan_dosen" rows="2"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="Tulis catatan untuk mahasiswa..."></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" name="aksi" value="disetujui"
                                class="flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                ACC / Setujui
                            </button>
                            <button type="submit" name="aksi" value="ditolak"
                                class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">cancel</span>
                                Tolak
                            </button>
                            <button type="button" @click="open = false"
                                class="text-xs text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg border border-gray-200 transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
                @endif

            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection