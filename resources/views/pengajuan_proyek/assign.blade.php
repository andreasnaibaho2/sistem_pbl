@extends('layouts.app')

@section('title', 'Pilih Mahasiswa & Dosen Pengampu')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-black uppercase tracking-tight text-[#004d4d]">
            Pilih <span style="color:#2dce89;">Mahasiswa & Dosen</span>
        </h1>
        <p class="text-sm text-gray-400 mt-1">{{ $pengajuan_proyek->judul_proyek }} &mdash; {{ $pengajuan_proyek->kode_pengajuan }}</p>
    </div>

    <form action="{{ route('pengajuan_proyek.simpan_mahasiswa', $pengajuan_proyek) }}" method="POST">
        @csrf

        {{-- ====== SECTION: DOSEN PENGAMPU ====== --}}
        <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-black text-[#004d4d] text-base">Dosen Pengampu</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih dosen yang mengampu proyek ini (opsional)</p>
                </div>
                <span class="px-3 py-1 rounded-xl text-xs font-black bg-indigo-600 text-white uppercase tracking-widest">Dosen</span>
            </div>
            <div class="p-6">
                <select name="dosen_pengampu_id"
                    class="w-full border-2 border-gray-200 rounded-2xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-indigo-400 transition">
                    <option value="">-- Belum ditentukan --</option>
                    @foreach($dosenList as $dosen)
                        <option value="{{ $dosen->id }}"
                            {{ optional($pengajuan_proyek->dosenPengampu)->id === $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->user->name }}
                            @if($dosen->user->prodi)
                                ({{ labelProdi($dosen->user->prodi) }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ====== SECTION: MAHASISWA PER PRODI ====== --}}
        @foreach($pengajuan_proyek->kebutuhan as $kebutuhan)
        @php
            $prodi = $kebutuhan->prodi;
            $jumlah = $kebutuhan->jumlah_mahasiswa;
            $daftarMhs = $mahasiswaPerProdi[$prodi] ?? collect();
            $sudahDipilih = $pengajuan_proyek->mahasiswa->pluck('id')->toArray();
        @endphp

        <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header Prodi --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-black text-[#004d4d] text-base">{{ labelProdi($prodi) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Dibutuhkan <span class="font-black text-[#2dce89]">{{ $jumlah }} mahasiswa</span></p>
                </div>
                <span class="px-3 py-1 rounded-xl text-xs font-black bg-[#004d4d] text-white uppercase tracking-widest">{{ singkatProdi($prodi) }}</span>
            </div>

            {{-- Daftar Mahasiswa --}}
            <div class="p-6">
                @if($daftarMhs->isEmpty())
                    <div class="text-center py-8 text-gray-300">
                        <span class="material-symbols-outlined text-4xl">person_off</span>
                        <p class="text-sm mt-2">Belum ada mahasiswa terdaftar untuk prodi ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-3" x-data="{ selected_{{ $loop->index }}: {{ json_encode($sudahDipilih) }} }">

                        <p class="text-xs text-gray-400 font-bold mb-1">Pilih maksimal {{ $jumlah }} mahasiswa:</p>

                        @foreach($daftarMhs as $mhs)
                        <label class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all"
                            :class="selected_{{ $loop->parent->index }}.includes({{ $mhs->id }}) ? 'border-[#2dce89] bg-[#2dce89]/5' : 'border-gray-100 hover:border-gray-200'">

                            <input type="checkbox"
                                name="mahasiswa[]"
                                value="{{ $mhs->id }}"
                                class="hidden"
                                {{ in_array($mhs->id, $sudahDipilih) ? 'checked' : '' }}
                                x-on:change="
                                    if ($event.target.checked) {
                                        if (selected_{{ $loop->parent->index }}.length < {{ $jumlah }}) {
                                            selected_{{ $loop->parent->index }}.push({{ $mhs->id }})
                                        } else {
                                            $event.target.checked = false
                                        }
                                    } else {
                                        selected_{{ $loop->parent->index }} = selected_{{ $loop->parent->index }}.filter(id => id !== {{ $mhs->id }})
                                    }
                                ">

                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0"
                                :class="selected_{{ $loop->parent->index }}.includes({{ $mhs->id }}) ? 'bg-[#2dce89] text-white' : 'bg-gray-100 text-gray-400'">
                                {{ strtoupper(substr($mhs->nama, 0, 2)) }}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">
                                <p class="font-black text-sm text-[#004d4d]">{{ $mhs->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $mhs->nim }}</p>
                            </div>

                            {{-- Check icon --}}
                            <span class="material-symbols-outlined text-xl transition-all"
                                :class="selected_{{ $loop->parent->index }}.includes({{ $mhs->id }}) ? 'text-[#2dce89]' : 'text-gray-200'">
                                check_circle
                            </span>
                        </label>
                        @endforeach

                        {{-- Counter --}}
                        <p class="text-xs text-gray-400 mt-1">
                            Terpilih: <span class="font-black text-[#004d4d]" x-text="selected_{{ $loop->index }}.length"></span> / {{ $jumlah }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('pengajuan_proyek.show', $pengajuan_proyek) }}"
                class="px-6 py-3 rounded-2xl border-2 border-gray-200 text-sm font-black text-gray-400 hover:border-gray-300 transition">
                Kembali
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-2xl text-sm font-black text-white transition"
                style="background-color: #004d4d;">
                Simpan Penugasan
            </button>
        </div>

    </form>
</div>
@endsection