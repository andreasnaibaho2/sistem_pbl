@extends('layouts.app')
@section('title', 'Detail Laporan')
@section('content')
@php
    $status = $laporan->status_verifikasi ?? 'menunggu';
    $cfg = match($status) {
        'disetujui' => ['cls' => 'bg-emerald-50 text-emerald-700', 'icon' => 'check_circle',   'label' => 'Disetujui'],
        'ditolak'   => ['cls' => 'bg-red-50 text-red-600',         'icon' => 'cancel',          'label' => 'Ditolak'],
        default     => ['cls' => 'bg-amber-50 text-amber-600',     'icon' => 'hourglass_empty', 'label' => 'Menunggu'],
    };
@endphp

{{-- HEADING --}}
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('laporan.index') }}"
        class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-gray-100 shadow-sm hover:bg-teal-50 transition text-[#004d4d]">
        <span class="material-symbols-outlined text-xl">arrow_back</span>
    </a>
    <div class="flex-1">
        <h1 class="text-3xl font-black italic uppercase text-[#004d4d]">
            Detail <span class="text-[#2dce89]">Laporan</span>
        </h1>
        <p class="text-sm text-slate-400 mt-1 uppercase tracking-widest font-medium">Informasi lengkap laporan mahasiswa</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black {{ $cfg['cls'] }} uppercase tracking-widest">
        <span class="material-symbols-outlined text-sm">{{ $cfg['icon'] }}</span>
        {{ $cfg['label'] }}
    </span>
</div>

<div class="grid grid-cols-3 gap-4">

    {{-- INFO LAPORAN --}}
    <div class="col-span-2 space-y-4">
        <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Informasi Laporan</p>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach([
                    ['Mahasiswa',      $laporan->mahasiswa->nama ?? '-'],
                    ['NIM',            $laporan->mahasiswa->nim ?? '-'],
                    ['Kelas',          $laporan->kelas->nama_kelas ?? '-'],
                    ['Tanggal Upload', $laporan->created_at->format('d M Y, H:i')],
                ] as [$key, $val])
                <div class="px-6 py-4 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 w-36 shrink-0">{{ $key }}</span>
                    <span class="text-sm font-black text-[#004d4d]">{{ $val }}</span>
                </div>
                @endforeach

                <div class="px-6 py-4 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 w-36 shrink-0">Jenis Laporan</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-black bg-teal-50 text-[#004d4d] uppercase tracking-wide">
                        <span class="material-symbols-outlined text-xs">description</span>
                        {{ $laporan->jenis_laporan }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 w-36 shrink-0">File</span>
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($laporan->file_path) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-[#004d4d] text-[#7fffd4] text-[10px] font-black hover:opacity-90 transition uppercase tracking-wide">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Download File
                    </a>
                </div>
            </div>
        </div>

        {{-- Catatan Dosen --}}
        @if($laporan->catatan_verifikasi)
        <div class="bg-amber-50 border border-amber-100 rounded-[1.5rem] px-6 py-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-amber-500 text-lg" style="font-variation-settings:'FILL' 1">comment</span>
                <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Catatan Dosen</p>
            </div>
            <p class="text-sm text-amber-800 leading-relaxed">{{ $laporan->catatan_verifikasi }}</p>
        </div>
        @endif
    </div>

    {{-- VERIFIKASI (Dosen) --}}
    <div>
        @if(auth()->user()->isDosen() && ($laporan->status_verifikasi === null || $laporan->status_verifikasi === 'menunggu'))
        <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Verifikasi Laporan</p>
            </div>
            <div class="p-6">
                <form action="{{ route('laporan.verifikasi', $laporan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                            Catatan <span class="text-gray-300 font-medium normal-case">(opsional)</span>
                        </label>
                        <textarea name="catatan_verifikasi" rows="4"
                            placeholder="Catatan untuk mahasiswa..."
                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm text-[#004d4d] font-medium focus:outline-none focus:ring-2 focus:ring-teal-200 transition resize-none"></textarea>
                    </div>
                    <button name="status_verifikasi" value="disetujui" type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-emerald-500 text-white font-black text-sm hover:opacity-90 transition">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        Setujui
                    </button>
                    <button name="status_verifikasi" value="ditolak" type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-red-50 text-red-600 font-black text-sm hover:bg-red-100 transition border border-red-100">
                        <span class="material-symbols-outlined text-base">cancel</span>
                        Tolak
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- Status sudah diverifikasi --}}
        <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-200 block mb-3" style="font-variation-settings:'FILL' 1">
                {{ $status === 'disetujui' ? 'check_circle' : ($status === 'ditolak' ? 'cancel' : 'hourglass_empty') }}
            </span>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Status Laporan</p>
            <p class="text-sm font-black text-[#004d4d] mt-1">{{ $cfg['label'] }}</p>
        </div>
        @endif
    </div>
</div>

@endsection