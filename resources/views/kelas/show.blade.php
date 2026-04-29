@extends('layouts.app')
@section('title', 'Anggota Kelas')
@section('content')

<div class="flex items-center gap-3 mb-8">
    <a href="{{ route('kelas.index') }}"
        class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Anggota Kelas: {{ $kelas->nama_kelas }}</h1>
        <p class="text-sm text-on-surface-variant mt-0.5">Kelola anggota mahasiswa di kelas ini</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Kiri: Daftar Anggota --}}
    <div class="col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 flex items-center justify-between">
            <p class="text-sm font-semibold text-on-surface">Daftar Mahasiswa</p>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-primary-container text-primary">
                <span class="material-symbols-outlined text-xs">group</span>
                {{ $kelas->mahasiswa->count() }} Mahasiswa
            </span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">No</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">NIM</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($kelas->mahasiswa as $mhs)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 text-on-surface-variant text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-mono text-on-surface text-sm">{{ $mhs->nim }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-on-primary text-xs font-bold shrink-0">
                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                            </div>
                            <span class="font-medium text-on-surface">{{ $mhs->nama }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('kelas.remove', [$kelas->id, $mhs->id]) }}" method="POST"
                            onsubmit="return confirm('Keluarkan mahasiswa ini dari kelas?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">person_remove</span>
                                Keluarkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-outline block mb-2">group_off</span>
                        <p class="text-sm text-on-surface-variant">Belum ada mahasiswa di kelas ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Kanan: Info Kelas + Tambah Mahasiswa --}}
    <div class="space-y-4">

        {{-- Info Kelas --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5">
            <p class="text-sm font-semibold text-on-surface mb-4">Info Kelas</p>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Kode</span>
                    <span class="font-semibold text-on-surface">{{ $kelas->kode_kelas }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Matkul</span>
                    <span class="font-semibold text-on-surface text-right">{{ $kelas->mataKuliah->nama_matkul ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Dosen</span>
                    <span class="font-semibold text-on-surface text-right">{{ $kelas->dosen->nama_dosen ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Tahun</span>
                    <span class="font-semibold text-on-surface">{{ $kelas->tahun_akademik }}</span>
                </div>
            </div>
        </div>

        {{-- Tambah Mahasiswa --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-5">
            <p class="text-sm font-semibold text-on-surface mb-4">Tambah Mahasiswa</p>
            <form action="{{ route('kelas.assign', $kelas->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1.5">Pilih Mahasiswa</label>
                    <select name="mahasiswa_ids[]" multiple
                        class="w-full px-3 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                        style="height: 120px;">
                        @foreach($semuaMahasiswa as $mhs)
                            @if(!$kelas->mahasiswa->contains($mhs->id))
                            <option value="{{ $mhs->id }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p class="text-xs text-on-surface-variant/60 mt-1">Tahan Ctrl untuk pilih lebih dari satu</p>
                </div>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-medium hover:bg-secondary transition-colors">
                    <span class="material-symbols-outlined text-base">person_add</span>
                    Tambahkan
                </button>
            </form>
        </div>
    </div>
</div>

@endsection