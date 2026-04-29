@extends('layouts.app')

@section('title', 'Input Penilaian')

@section('content')
@php $user = auth()->user(); @endphp

{{-- HEADING --}}
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('penilaian.index') }}"
        class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-gray-100 shadow-sm hover:bg-teal-50 transition text-[#004d4d]">
        <span class="material-symbols-outlined text-xl">arrow_back</span>
    </a>
    <div>
        <h1 class="text-3xl font-black italic uppercase text-[#004d4d]">
            Input <span class="text-[#2dce89]">Penilaian</span>
        </h1>
        <p class="text-sm text-slate-400 mt-1 uppercase tracking-widest font-medium">
            @if($user->isManager()) Porsi Anda: 55% — Learning Skills + Life Skills + Laporan Project
            @else Porsi Anda: 45% — Literacy Skills + Presentasi + Laporan Akhir
            @endif
        </p>
    </div>
</div>

@if($errors->any())
<div class="mb-6 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm">
    <div class="flex items-center gap-2 mb-2 font-black text-red-700">
        <span class="material-symbols-outlined text-base">error</span> Terdapat kesalahan input
    </div>
    <ul class="list-disc list-inside space-y-0.5 text-xs">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('penilaian.store') }}">
@csrf

{{-- PILIH MAHASISWA --}}
<div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 mb-5">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-[#004d4d]">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">person_search</span>
        </div>
        <div>
            <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Pilih Mahasiswa</h3>
            <p class="text-[10px] text-gray-400">Pilih kelas terlebih dahulu</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Kelas</label>
            <select name="kelas_id" id="kelas_id" required
                class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 text-[#004d4d] text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
                @endforeach
            </select>
            @error('kelas_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Mahasiswa</label>
            <select name="mahasiswa_id" id="mahasiswa_id" required
                class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 text-[#004d4d] text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition">
                <option value="">-- Pilih Kelas dulu --</option>
            </select>
            @error('mahasiswa_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

@php
$sectionClass = "bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 mb-5";
$inputClass   = "w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 text-[#004d4d] text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition";
$labelClass   = "block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2";
@endphp

@if($user->isManager())

{{-- Learning Skills --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-[#004d4d]">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">psychology</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Learning Skills</h3>
                <p class="text-[10px] text-gray-400">4 aspek × 5% = 20% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-teal-50 text-[#004d4d] uppercase tracking-widest">20%</span>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach([
            ['ls_critical_thinking', 'Critical Thinking'],
            ['ls_kolaborasi',        'Kolaborasi'],
            ['ls_kreativitas',       'Kreativitas & Inovasi'],
            ['ls_komunikasi',        'Komunikasi'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(5%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Life Skills --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">self_improvement</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Life Skills</h3>
                <p class="text-[10px] text-gray-400">4 aspek × 5% = 20% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 uppercase tracking-widest">20%</span>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach([
            ['lf_fleksibilitas', 'Fleksibilitas'],
            ['lf_kepemimpinan',  'Kepemimpinan'],
            ['lf_produktivitas', 'Produktivitas'],
            ['lf_social_skill',  'Social Skill'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(5%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Laporan Project --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">folder_open</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Laporan Project</h3>
                <p class="text-[10px] text-gray-400">3 aspek × 5% = 15% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 uppercase tracking-widest">15%</span>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['lp_rpp',            'RPP'],
            ['lp_logbook',        'Logbook Mingguan'],
            ['lp_dokumen_projek', 'Dokumen Projek'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(5%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Catatan Manager --}}
<div class="{{ $sectionClass }}">
    <label class="{{ $labelClass }}">Catatan <span class="text-gray-300">(opsional)</span></label>
    <textarea name="catatan_manager" rows="3" placeholder="Catatan tambahan untuk mahasiswa..."
        class="{{ $inputClass }} resize-none">{{ old('catatan_manager') }}</textarea>
</div>

@elseif($user->isDosen())

{{-- Literacy Skills --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">import_contacts</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Literacy Skills</h3>
                <p class="text-[10px] text-gray-400">3 aspek × 5% = 15% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 uppercase tracking-widest">15%</span>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['lit_informasi',  'Literasi Informasi'],
            ['lit_media',      'Literasi Media'],
            ['lit_teknologi',  'Literasi Teknologi'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(5%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Presentasi --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-pink-50 flex items-center justify-center text-pink-600">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">co_present</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Presentasi</h3>
                <p class="text-[10px] text-gray-400">5 aspek × 3% = 15% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-pink-50 text-pink-600 uppercase tracking-widest">15%</span>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach([
            ['pr_konten',      'Konten'],
            ['pr_visual',      'Tampilan Visual'],
            ['pr_kosakata',    'Kosakata'],
            ['pr_tanya_jawab', 'Tanya Jawab'],
            ['pr_mata_gerak',  'Mata & Gerak Tubuh'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(3%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Laporan Akhir --}}
<div class="{{ $sectionClass }}">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">task</span>
            </div>
            <div>
                <h3 class="font-black text-[#004d4d] text-sm uppercase tracking-widest">Laporan Akhir</h3>
                <p class="text-[10px] text-gray-400">3 aspek × 5% = 15% total</p>
            </div>
        </div>
        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 uppercase tracking-widest">15%</span>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['la_penulisan',    'Penulisan Laporan'],
            ['la_pilihan_kata', 'Pilihan Kata'],
            ['la_konten',       'Konten'],
        ] as [$name, $label])
        <div>
            <label class="{{ $labelClass }}">{{ $label }} <span class="text-gray-300">(5%)</span></label>
            <input type="number" name="{{ $name }}" min="0" max="100" step="0.01"
                value="{{ old($name) }}" placeholder="0 – 100" required class="{{ $inputClass }}">
            @error($name)<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Catatan Dosen --}}
<div class="{{ $sectionClass }}">
    <label class="{{ $labelClass }}">Catatan <span class="text-gray-300">(opsional)</span></label>
    <textarea name="catatan_dosen" rows="3" placeholder="Catatan tambahan untuk mahasiswa..."
        class="{{ $inputClass }} resize-none">{{ old('catatan_dosen') }}</textarea>
</div>

@endif

{{-- TOMBOL SUBMIT --}}
<div class="flex items-center gap-3 mt-2">
    <button type="submit"
        class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#004d4d] text-[#7fffd4] text-sm font-black hover:opacity-90 transition">
        <span class="material-symbols-outlined text-base">save</span>
        Simpan Nilai
    </button>
    <a href="{{ route('penilaian.index') }}"
        class="px-6 py-3 rounded-2xl border border-gray-100 text-slate-500 text-sm font-black hover:bg-gray-50 transition">
        Batal
    </a>
</div>

</form>

@php
$kelasJson = $kelas->map(function($k) {
    return [
        'id' => $k->id,
        'mahasiswa' => $k->mahasiswa->map(fn($m) => [
            'id'   => $m->id,
            'nama' => $m->nama,
            'nim'  => $m->nim,
        ])->values()
    ];
})->keyBy('id');
@endphp

@push('scripts')
<script>
const kelasData = {!! json_encode($kelasJson) !!};
document.getElementById('kelas_id').addEventListener('change', function () {
    const kelasId   = this.value;
    const mhsSelect = document.getElementById('mahasiswa_id');
    mhsSelect.innerHTML = '<option value="">-- Pilih Mahasiswa --</option>';
    if (kelasId && kelasData[kelasId]) {
        kelasData[kelasId].mahasiswa.forEach(function(m) {
            mhsSelect.innerHTML += `<option value="${m.id}">${m.nim} - ${m.nama}</option>`;
        });
    }
});
</script>
@endpush

@endsection