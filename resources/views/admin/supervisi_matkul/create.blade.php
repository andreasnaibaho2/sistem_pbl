@extends('layouts.app')
@section('title', 'Tambah Supervisi')
@section('content')

<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.supervisi.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-[#004d4d] mb-6 transition-colors">
        <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali
    </a>

    <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase mb-1">
        Tambah <span class="text-[#2dce89]">Supervisi</span>
    </h1>
    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-8">
        Assign Mahasiswa ke Mata Kuliah & Dosen Pengampu
    </p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 rounded-2xl px-5 py-4 mb-6">
        <ul class="text-xs text-red-600 font-bold space-y-1">
            @foreach($errors->all() as $e)
            <li>• {{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.supervisi.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7 space-y-6">

            {{-- Mata Kuliah --}}
            <div>
                <label class="block text-xs font-black text-[#004d4d] uppercase tracking-widest mb-2">Mata Kuliah</label>
                <select name="mata_kuliah_id" required
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-[#004d4d] font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($matkulList as $mk)
                    <option value="{{ $mk->id }}" {{ old('mata_kuliah_id')==$mk->id?'selected':'' }}>
                        {{ $mk->nama_matkul }} ({{ $mk->kode_matkul }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Dosen Pengampu --}}
            <div>
                <label class="block text-xs font-black text-[#004d4d] uppercase tracking-widest mb-2">Dosen Pengampu</label>
                <select name="dosen_id" required
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-[#004d4d] font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosenList as $d)
                    <option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>
                        {{ $d->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun Ajaran & Semester --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-[#004d4d] uppercase tracking-widest mb-2">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', '2025/2026') }}" required
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-[#004d4d] font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400">
                </div>
                <div>
                    <label class="block text-xs font-black text-[#004d4d] uppercase tracking-widest mb-2">Semester</label>
                    <select name="semester" required
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-[#004d4d] font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
                        <option value="1" {{ old('semester',1)==1?'selected':'' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester')==2?'selected':'' }}>Semester 2 (Genap)</option>
                    </select>
                </div>
            </div>

            {{-- Multi-select Mahasiswa --}}
            <div>
                <label class="block text-xs font-black text-[#004d4d] uppercase tracking-widest mb-1">Mahasiswa</label>
                <p class="text-[10px] text-gray-400 font-medium mb-3">Pilih satu atau lebih mahasiswa yang akan di-assign.</p>

                {{-- Filter Prodi --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <button type="button" data-prodi="all"
                        class="prodi-filter px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#004d4d] text-[#7fffd4] transition-all">
                        Semua
                    </button>
                    @foreach($prodiList as $prodi)
                    <button type="button" data-prodi="{{ $prodi }}"
                        class="prodi-filter px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-500 hover:bg-teal-50 hover:text-teal-700 transition-all">
                        {{ $prodi }}
                    </button>
                    @endforeach
                </div>

                {{-- Select All --}}
                <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-100">
                    <input type="checkbox" id="selectAll"
                        class="w-4 h-4 rounded accent-teal-600 cursor-pointer">
                    <label for="selectAll" class="text-xs font-black text-gray-500 cursor-pointer uppercase tracking-widest">
                        Pilih Semua
                    </label>
                </div>

                {{-- Checklist --}}
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1" id="mahasiswaList">
                    @foreach($mahasiswaList as $m)
                    <label data-prodi="{{ $m->user->prodi }}"
                        class="mahasiswa-item flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:border-teal-300 hover:bg-teal-50/30 cursor-pointer transition-all group">
                        <input type="checkbox" name="mahasiswa_ids[]" value="{{ $m->id }}"
                            class="mahasiswa-check w-4 h-4 rounded accent-teal-600"
                            {{ is_array(old('mahasiswa_ids')) && in_array($m->id, old('mahasiswa_ids')) ? 'checked' : '' }}>
                        <div class="w-8 h-8 rounded-xl bg-[#004d4d] text-[#7fffd4] flex items-center justify-center font-black text-[10px] flex-shrink-0">
                            {{ strtoupper(substr($m->nama, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-[#004d4d] group-hover:text-teal-700">{{ $m->nama }}</p>
                            <p class="text-[10px] text-gray-400">{{ $m->nim }} &nbsp;·&nbsp; {{ $m->user->prodi }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('mahasiswa_ids')
                <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.supervisi.index') }}"
               class="px-6 py-3 rounded-2xl border border-gray-200 text-xs font-black text-gray-500 hover:bg-gray-50 transition-all">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 bg-[#004d4d] text-[#7fffd4] rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-lg shadow-teal-900/20">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
// Filter prodi
const filterBtns = document.querySelectorAll('.prodi-filter');
const items      = document.querySelectorAll('.mahasiswa-item');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => {
            b.classList.remove('bg-[#004d4d]', 'text-[#7fffd4]');
            b.classList.add('bg-gray-100', 'text-gray-500');
        });
        btn.classList.remove('bg-gray-100', 'text-gray-500');
        btn.classList.add('bg-[#004d4d]', 'text-[#7fffd4]');

        const prodi = btn.dataset.prodi;
        items.forEach(item => {
            if (prodi === 'all' || item.dataset.prodi === prodi) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
                item.querySelector('input[type=checkbox]').checked = false;
            }
        });
        updateSelectAll();
    });
});

// Select All
const selectAll = document.getElementById('selectAll');
const checks    = document.querySelectorAll('.mahasiswa-check');

selectAll.addEventListener('change', () => {
    items.forEach(item => {
        if (item.style.display !== 'none') {
            item.querySelector('input[type=checkbox]').checked = selectAll.checked;
        }
    });
});

checks.forEach(c => {
    c.addEventListener('change', updateSelectAll);
});

function updateSelectAll() {
    const visible  = [...items].filter(i => i.style.display !== 'none');
    const allChked = visible.every(i => i.querySelector('input[type=checkbox]').checked);
    selectAll.checked = allChked && visible.length > 0;
}
</script>

@endsection