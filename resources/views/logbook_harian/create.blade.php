@extends('layouts.app')
@section('title', 'Input Logbook Harian')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('logbook_harian.index') }}"
           class="p-2.5 text-gray-400 hover:text-primary hover:bg-teal-50 rounded-xl transition-all border border-gray-100">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-on-surface">Input Logbook Harian</h1>
            <p class="text-xs text-slate-400 mt-0.5">Catat aktivitas harian proyek PBL</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm p-8 space-y-6">

        @if($errors->any())
        <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-sm text-red-600">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('logbook_harian.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Proyek --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Proyek</label>
                @if($proyek->count() === 1)
                    <input type="hidden" name="pengajuan_proyek_id" value="{{ $proyek->first()->id }}">
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-on-surface font-semibold">
                        {{ $proyek->first()->judul_proyek }}
                    </div>
                @else
                    <select name="pengajuan_proyek_id" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($proyek as $p)
                        <option value="{{ $p->id }}" {{ old('pengajuan_proyek_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->judul_proyek }}
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Minggu & Hari --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Minggu Ke</label>
                    <select name="minggu_ke" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">-- Pilih --</option>
                        @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ old('minggu_ke') == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Hari</label>
                    <select name="hari" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">-- Pilih Hari --</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                        <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            {{-- Aktivitas --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Aktivitas Harian</label>
                <textarea name="aktivitas" rows="5" required
                          placeholder="Deskripsikan aktivitas yang dilakukan hari ini..."
                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ old('aktivitas') }}</textarea>
            </div>

            {{-- Dokumentasi --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                    Dokumentasi <span class="text-slate-300 font-normal normal-case">(opsional · jpg, png, pdf · maks 2MB)</span>
                </label>
                <input type="file" name="dokumentasi" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-primary hover:file:bg-teal-100">
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('logbook_harian.index') }}"
                   class="flex-1 py-3 text-center text-sm font-semibold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-3 text-sm font-bold text-white bg-gradient-to-br from-primary to-emerald-800 rounded-xl hover:opacity-90 transition-all shadow-sm">
                    Simpan Logbook
                </button>
            </div>
        </form>
    </div>
</div>
@endsection