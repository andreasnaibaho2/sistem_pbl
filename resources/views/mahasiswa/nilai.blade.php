@extends('layouts.app')
@section('title', 'Nilai Detail')
@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-black italic uppercase text-[#004d4d]">
        Nilai <span class="text-[#2dce89]">Detail</span>
    </h1>
    <p class="text-sm text-slate-400 mt-1 uppercase tracking-widest font-medium">Rekap nilai PBL Anda secara lengkap</p>
</div>

@if(!$nilaiManager && $nilaiDosen->isEmpty())
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm py-20 text-center">
    <span class="material-symbols-outlined text-6xl text-gray-200 block mb-3">grade</span>
    <p class="text-sm text-gray-400 font-medium">Belum ada nilai yang tersedia.</p>
</div>
@else

@php
    $nm = $nilaiManager->nilai_manager ?? 0;
    $nd = $nilaiDosen->sum('nilai_dosen');
    $nilaiAkhir = $nm + $nd;

    // Konversi ke skala 100
    $nmPer100 = $nm > 0 ? round($nm / 55 * 100, 1) : 0;
    $ndPer100 = $nd > 0 ? round($nd / 45 * 100, 1) : 0;

    $grade = $nilaiAkhir >= 85 ? 'A' : ($nilaiAkhir >= 75 ? 'B' : ($nilaiAkhir >= 65 ? 'C' : ($nilaiAkhir >= 55 ? 'D' : 'E')));
@endphp

{{-- Header Nilai Akhir --}}
<div class="bg-[#004d4d] rounded-[1.5rem] p-6 mb-4 flex items-center justify-between">
    <div>
        <p class="text-xs text-teal-100/60 mb-1 uppercase tracking-widest font-black">{{ $mahasiswa->nama }}</p>
        <h2 class="text-white font-black text-xl">{{ $mahasiswa->nim }}</h2>
    </div>
    <div class="text-right">
        <p class="text-xs text-teal-100/60 mb-1 uppercase tracking-widest font-black">Nilai Akhir</p>
        <p class="text-6xl font-black italic text-white">{{ number_format($nilaiAkhir, 1) }}</p>
        <span class="mt-2 inline-block px-4 py-1 rounded-full bg-[#2dce89] text-[#004d4d] text-sm font-black">
            {{ $grade }}
        </span>
    </div>
</div>

{{-- Progress Bar --}}
<div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 mb-4">
    <div class="flex justify-between items-center mb-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Progress Nilai</span>
        <span class="text-sm font-black text-[#004d4d]">{{ number_format($nilaiAkhir, 1) }} / 100</span>
    </div>
    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden mb-3">
        <div class="h-3 rounded-full bg-[#004d4d] transition-all" style="width: {{ min($nilaiAkhir, 100) }}%"></div>
    </div>
    <div class="flex justify-between text-xs text-slate-400 font-medium">
        <span>
            Manager Proyek (55%):
            <strong class="text-[#004d4d]">{{ number_format($nm, 1) }}</strong>
            <span class="text-gray-300 ml-1">= {{ number_format($nmPer100, 1) }}/100</span>
        </span>
        <span>
            Dosen Pengampu (45%):
            <strong class="text-[#004d4d]">{{ number_format($nd, 1) }}</strong>
            <span class="text-gray-300 ml-1">= {{ number_format($ndPer100, 1) }}/100</span>
        </span>
    </div>
</div>

{{-- Info Box Penjelasan Bobot --}}
<div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 mb-6 flex items-start gap-3">
    <span class="material-symbols-outlined text-amber-500 text-lg shrink-0 mt-0.5">info</span>
    <div class="text-xs text-amber-700 leading-relaxed">
        <span class="font-black block mb-1">Cara Membaca Nilai</span>
        Nilai Manager dan Dosen ditampilkan dalam <strong>skala bobot</strong> (Manager maks. 55, Dosen maks. 45)
        sekaligus <strong>skala 100</strong> untuk kemudahan perbandingan.
        Nilai Akhir = Nilai Manager + Nilai Dosen (total maks. 100).
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- MANAGER PROYEK --}}
    <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center text-[#004d4d]">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">manage_accounts</span>
                </div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Manager Proyek</h3>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-teal-50 text-[#004d4d] uppercase tracking-widest block">
                    {{ number_format($nm, 1) }} / 55
                </span>
                <span class="text-[10px] text-gray-400 font-medium mt-0.5 block">
                    ≈ {{ number_format($nmPer100, 1) }} / 100
                </span>
            </div>
        </div>
        <div class="p-6 space-y-5">
            @if($nilaiManager)
            @php
            $grupManager = [
                ['label'=>'Learning Skills','bobot'=>'20%','color'=>'bg-teal-500','items'=>[
                    ['Critical Thinking',    $nilaiManager->ls_critical_thinking],
                    ['Kolaborasi',           $nilaiManager->ls_kolaborasi],
                    ['Kreativitas & Inovasi',$nilaiManager->ls_kreativitas],
                    ['Komunikasi',           $nilaiManager->ls_komunikasi],
                ]],
                ['label'=>'Life Skills','bobot'=>'20%','color'=>'bg-purple-500','items'=>[
                    ['Fleksibilitas', $nilaiManager->lf_fleksibilitas],
                    ['Kepemimpinan',  $nilaiManager->lf_kepemimpinan],
                    ['Produktivitas', $nilaiManager->lf_produktivitas],
                    ['Social Skill',  $nilaiManager->lf_social_skill],
                ]],
                ['label'=>'Laporan Project','bobot'=>'15%','color'=>'bg-blue-500','items'=>[
                    ['RPP',            $nilaiManager->lp_rpp],
                    ['Logbook',        $nilaiManager->lp_logbook],
                    ['Dokumen Projek', $nilaiManager->lp_dokumen_projek],
                ]],
            ];
            @endphp
            @foreach($grupManager as $grup)
            <div>
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $grup['label'] }}</p>
                    <span class="text-[10px] font-black text-gray-400">{{ $grup['bobot'] }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($grup['items'] as [$label, $value])
                    <div class="flex items-center gap-3">
                        <span class="w-32 text-xs text-slate-500 font-medium shrink-0">{{ $label }}</span>
                        <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $grup['color'] }}" style="width: {{ min($value ?? 0, 100) }}%"></div>
                        </div>
                        <span class="w-8 text-right text-xs font-black text-[#004d4d]">{{ $value ?? '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            @if($nilaiManager->catatan_manager)
            <div class="p-4 rounded-2xl bg-teal-50 text-xs text-slate-600 leading-relaxed">
                <span class="font-black text-[#004d4d] block mb-1">Catatan Manager:</span>
                {{ $nilaiManager->catatan_manager }}
            </div>
            @endif
            @else
            <p class="text-sm text-gray-400 text-center py-6">Belum ada penilaian dari Manager.</p>
            @endif
        </div>
    </div>

    {{-- DOSEN PENGAMPU --}}
    <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">school</span>
                </div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Dosen Pengampu</h3>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 uppercase tracking-widest block">
                    {{ number_format($nd, 1) }} / 45
                </span>
                <span class="text-[10px] text-gray-400 font-medium mt-0.5 block">
                    ≈ {{ number_format($ndPer100, 1) }} / 100
                </span>
            </div>
        </div>
        <div class="p-6 space-y-5">
            @forelse($nilaiDosen as $pd)
            @php
                $ndSingle     = $pd->nilai_dosen ?? 0;
                $ndSinglePer100 = $ndSingle > 0 ? round($ndSingle / 45 * 100, 1) : 0;
            @endphp
            <div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-black text-[#004d4d] uppercase tracking-widest">
                        {{ $pd->supervisiMatkul->mataKuliah->nama_matkul ?? '-' }}
                        <span class="text-gray-400 font-normal normal-case tracking-normal">
                            — Sem {{ $pd->supervisiMatkul->semester ?? '' }}
                        </span>
                    </p>
                    <div class="text-right shrink-0 ml-2">
                        <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-lg block">
                            {{ number_format($ndSingle, 1) }} / 45
                        </span>
                        <span class="text-[10px] text-gray-400 font-medium mt-0.5 block">
                            ≈ {{ number_format($ndSinglePer100, 1) }} / 100
                        </span>
                    </div>
                </div>
                @php
                $grupDosen = [
                    ['label'=>'Literacy Skills','bobot'=>'15%','color'=>'bg-amber-500','items'=>[
                        ['Literasi Informasi', $pd->lit_informasi],
                        ['Literasi Media',     $pd->lit_media],
                        ['Literasi Teknologi', $pd->lit_teknologi],
                    ]],
                    ['label'=>'Presentasi','bobot'=>'15%','color'=>'bg-pink-500','items'=>[
                        ['Konten',          $pd->pr_konten],
                        ['Tampilan Visual', $pd->pr_visual],
                        ['Kosakata',        $pd->pr_kosakata],
                        ['Tanya Jawab',     $pd->pr_tanya_jawab],
                        ['Mata & Gerak',    $pd->pr_mata_gerak],
                    ]],
                    ['label'=>'Laporan Akhir','bobot'=>'15%','color'=>'bg-emerald-500','items'=>[
                        ['Penulisan Laporan', $pd->la_penulisan],
                        ['Pilihan Kata',      $pd->la_pilihan_kata],
                        ['Konten',            $pd->la_konten],
                    ]],
                ];
                @endphp
                @foreach($grupDosen as $grup)
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $grup['label'] }}</p>
                        <span class="text-[10px] font-black text-gray-400">{{ $grup['bobot'] }}</span>
                    </div>
                    <div class="space-y-2">
                        @foreach($grup['items'] as [$label, $value])
                        <div class="flex items-center gap-3">
                            <span class="w-32 text-xs text-slate-500 font-medium shrink-0">{{ $label }}</span>
                            <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $grup['color'] }}" style="width: {{ min($value ?? 0, 100) }}%"></div>
                            </div>
                            <span class="w-8 text-right text-xs font-black text-[#004d4d]">{{ $value ?? '-' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if($pd->catatan_dosen)
                <div class="p-4 rounded-2xl bg-purple-50 text-xs text-slate-600 leading-relaxed">
                    <span class="font-black text-purple-700 block mb-1">Catatan Dosen:</span>
                    {{ $pd->catatan_dosen }}
                </div>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada penilaian dari Dosen.</p>
            @endforelse
        </div>
    </div>

</div>
@endif
@endsection