@extends('layouts.app')

@section('title', 'Nilai Detail')

@section('content')

{{-- HEADING --}}
<div class="mb-8">
    <h1 class="text-3xl font-black italic uppercase text-[#004d4d]">
        Nilai <span class="text-[#2dce89]">Detail</span>
    </h1>
    <p class="text-sm text-slate-400 mt-1 uppercase tracking-widest font-medium">Rekap nilai PBL Anda secara lengkap</p>
</div>

@if(!isset($penilaianList) || $penilaianList->isEmpty())
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm py-20 text-center">
    <span class="material-symbols-outlined text-6xl text-gray-200 block mb-3">grade</span>
    <p class="text-sm text-gray-400 font-medium">Belum ada nilai yang tersedia.</p>
</div>
@else
@foreach($penilaianList as $p)
<div class="mb-10">

    {{-- Header Kelas --}}
    <div class="bg-[#004d4d] rounded-[1.5rem] p-6 mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs text-teal-100/60 mb-1 uppercase tracking-widest font-black">{{ $p->kelas->mataKuliah->nama_matkul ?? '-' }}</p>
            <h2 class="text-white font-black text-xl">{{ $p->kelas->nama_kelas ?? '-' }}</h2>
        </div>
        <div class="text-right">
            <p class="text-xs text-teal-100/60 mb-1 uppercase tracking-widest font-black">Nilai Akhir</p>
            <p class="text-6xl font-black italic text-white">{{ number_format($p->nilai_akhir ?? 0, 1) }}</p>
            <span class="mt-2 inline-block px-4 py-1 rounded-full bg-[#2dce89] text-[#004d4d] text-sm font-black">
                {{ $p->getGrade() }}
            </span>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 mb-4">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Progress Nilai</span>
            <span class="text-sm font-black text-[#004d4d]">{{ number_format($p->nilai_akhir ?? 0, 1) }} / 100</span>
        </div>
        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden mb-3">
            <div class="h-3 rounded-full bg-[#004d4d] transition-all"
                style="width: {{ min($p->nilai_akhir ?? 0, 100) }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-400 font-medium">
            <span>Manager Proyek (55%): <strong class="text-[#004d4d]">{{ number_format($p->nilai_manager ?? 0, 1) }}</strong></span>
            <span>Dosen Pengampu (45%): <strong class="text-[#004d4d]">{{ number_format($p->nilai_dosen ?? 0, 1) }}</strong></span>
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
                <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-teal-50 text-[#004d4d] uppercase tracking-widest">
                    {{ number_format($p->nilai_manager ?? 0, 1) }} / 55
                </span>
            </div>
            <div class="p-6 space-y-5">
                @php
                $grupManager = [
                    ['label' => 'Learning Skills', 'bobot' => '20%', 'color' => 'bg-teal-500', 'items' => [
                        ['Critical Thinking',    $p->ls_critical_thinking],
                        ['Kolaborasi',           $p->ls_kolaborasi],
                        ['Kreativitas & Inovasi',$p->ls_kreativitas],
                        ['Komunikasi',           $p->ls_komunikasi],
                    ]],
                    ['label' => 'Life Skills', 'bobot' => '20%', 'color' => 'bg-purple-500', 'items' => [
                        ['Fleksibilitas', $p->lf_fleksibilitas],
                        ['Kepemimpinan',  $p->lf_kepemimpinan],
                        ['Produktivitas', $p->lf_produktivitas],
                        ['Social Skill',  $p->lf_social_skill],
                    ]],
                    ['label' => 'Laporan Project', 'bobot' => '15%', 'color' => 'bg-blue-500', 'items' => [
                        ['RPP',            $p->lp_rpp],
                        ['Logbook',        $p->lp_logbook],
                        ['Dokumen Projek', $p->lp_dokumen_projek],
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

                @if($p->catatan_manager)
                <div class="p-4 rounded-2xl bg-teal-50 text-xs text-slate-600 leading-relaxed">
                    <span class="font-black text-[#004d4d] block mb-1">Catatan Manager:</span>
                    {{ $p->catatan_manager }}
                </div>
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
                <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 uppercase tracking-widest">
                    {{ number_format($p->nilai_dosen ?? 0, 1) }} / 45
                </span>
            </div>
            <div class="p-6 space-y-5">
                @php
                $grupDosen = [
                    ['label' => 'Literacy Skills', 'bobot' => '15%', 'color' => 'bg-amber-500', 'items' => [
                        ['Literasi Informasi', $p->lit_informasi],
                        ['Literasi Media',     $p->lit_media],
                        ['Literasi Teknologi', $p->lit_teknologi],
                    ]],
                    ['label' => 'Presentasi', 'bobot' => '15%', 'color' => 'bg-pink-500', 'items' => [
                        ['Konten',           $p->pr_konten],
                        ['Tampilan Visual',  $p->pr_visual],
                        ['Kosakata',         $p->pr_kosakata],
                        ['Tanya Jawab',      $p->pr_tanya_jawab],
                        ['Mata & Gerak',     $p->pr_mata_gerak],
                    ]],
                    ['label' => 'Laporan Akhir', 'bobot' => '15%', 'color' => 'bg-emerald-500', 'items' => [
                        ['Penulisan Laporan', $p->la_penulisan],
                        ['Pilihan Kata',      $p->la_pilihan_kata],
                        ['Konten',            $p->la_konten],
                    ]],
                ];
                @endphp
                @foreach($grupDosen as $grup)
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

                @if($p->catatan_dosen)
                <div class="p-4 rounded-2xl bg-purple-50 text-xs text-slate-600 leading-relaxed">
                    <span class="font-black text-purple-700 block mb-1">Catatan Dosen:</span>
                    {{ $p->catatan_dosen }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endforeach
@endif

@endsection