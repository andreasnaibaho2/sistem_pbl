@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('content')

<div class="max-w-xl">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('kelas.index') }}"
            class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Tambah Kelas</h1>
            <p class="text-sm text-on-surface-variant mt-0.5">Isi form berikut untuk menambahkan kelas baru</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm p-6">

        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('kelas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Kode Kelas</label>
                <input type="text" name="kode_kelas" value="{{ old('kode_kelas') }}"
                    placeholder="contoh: 3-AEC-4"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}"
                    placeholder="contoh: 3 AEC 4"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Mata Kuliah</label>
                <select name="matkul_id"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mataKuliah as $mk)
                    <option value="{{ $mk->id }}" {{ old('matkul_id') == $mk->id ? 'selected' : '' }}>
                        {{ $mk->nama_matkul }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Dosen Pengampu</label>
                <select name="dosen_id"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosen as $d)
                    <option value="{{ $d->id }}" {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_dosen }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Tahun Akademik</label>
                <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik') }}"
                    placeholder="contoh: 2024/2025"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container-low text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-medium hover:bg-secondary transition-colors">
                    <span class="material-symbols-outlined text-base">check</span>
                    Simpan
                </button>
                <a href="{{ route('kelas.index') }}"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection