@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="/mahasiswa" class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant text-xl">arrow_back</span>
        </a>
        <div>
            <h2 class="text-xl font-extrabold text-on-surface tracking-tight">Edit Mahasiswa</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui data mahasiswa</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-6 space-y-5">
        <form method="POST" action="/mahasiswa/{{ $mahasiswa->id }}">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">NIM</label>
                    <input type="text" name="nim"
                           class="w-full px-4 py-3 bg-surface-container-low rounded-lg text-sm font-medium text-on-surface placeholder:text-slate-400 outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all {{ $errors->has('nim') ? 'ring-2 ring-red-400' : '' }}"
                           value="{{ old('nim', $mahasiswa->nim) }}" required>
                    @error('nim')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nama Lengkap</label>
                    <input type="text" name="nama"
                           class="w-full px-4 py-3 bg-surface-container-low rounded-lg text-sm font-medium text-on-surface placeholder:text-slate-400 outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all {{ $errors->has('nama') ? 'ring-2 ring-red-400' : '' }}"
                           value="{{ old('nama', $mahasiswa->nama) }}" required>
                    @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-br from-primary to-secondary text-white font-semibold rounded-lg text-sm shadow-lg shadow-primary/10 hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-sm">check</span> Update
                    </button>
                    <a href="/mahasiswa"
                       class="flex items-center gap-2 px-5 py-2.5 bg-surface-container-high text-on-surface-variant font-semibold rounded-lg text-sm hover:bg-surface-container-highest transition-all">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection