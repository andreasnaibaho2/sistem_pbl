@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="flex-1 flex flex-col">

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
                Master Data <span class="text-[#2dce89]">Kelas</span>
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
                {{ $kelas->count() }} kelas terdaftar
            </p>
        </div>
        <a href="{{ route('kelas.create') }}"
           class="flex items-center gap-2 px-6 py-3.5 bg-[#004d4d] text-[#7fffd4] rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-xl shadow-teal-900/20">
            <span class="material-symbols-outlined text-base">add</span> TAMBAH KELAS
        </a>
    </div>

    {{-- STAT STRIP --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-[#004d4d] text-white p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Kelas</p>
            <p class="text-5xl font-black italic tracking-tighter">{{ $kelas->count() }}</p>
        </div>
        <div class="bg-white border-2 border-[#7fffd4] p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Tahun Akademik</p>
            <p class="text-xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $kelas->first()->tahun_akademik ?? '-' }}
            </p>
        </div>
        <div class="bg-white border-2 border-teal-100 p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
            <p class="text-4xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $kelas->sum(fn($k) => $k->mahasiswa->count()) }}
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 w-10">#</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Kelas</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Mata Kuliah</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Dosen / Manager</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Mahasiswa</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($kelas as $idx => $k)
                <tr class="hover:bg-teal-50/30 transition-colors group">
                    <td class="px-8 py-5 text-[10px] font-black text-gray-300">{{ $idx + 1 }}</td>

                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#008080] to-[#004d4d] flex items-center justify-center text-white shadow-md flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">class</span>
                            </div>
                            <div>
                                <span class="font-bold text-[#004d4d] block leading-none">{{ $k->nama_kelas }}</span>
                                <span class="text-[10px] text-gray-400 font-medium mt-0.5 block uppercase tracking-wider">
                                    {{ $k->kode_kelas }} · {{ $k->tahun_akademik }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-5">
                        <span class="font-bold text-sm text-gray-700">{{ $k->mataKuliah->nama_matkul ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400 block mt-0.5">{{ $k->mataKuliah->kode_matkul ?? '' }}</span>
                    </td>

                    <td class="px-8 py-5">
                        <div class="space-y-1.5">
                            @if($k->dosen)
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-amber-400 text-sm">school</span>
                                <span class="text-[11px] font-bold text-gray-500">{{ $k->dosen->nama_dosen }}</span>
                            </div>
                            @endif
                            @if($k->manager)
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-blue-400 text-sm">manage_accounts</span>
                                <span class="text-[11px] font-bold text-gray-500">{{ $k->manager->name }}</span>
                            </div>
                            @endif
                            @if(!$k->dosen && !$k->manager)
                                <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-8 py-5 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 text-teal-700 border border-teal-100 rounded-xl text-[10px] font-black">
                            <span class="material-symbols-outlined text-sm">group</span>
                            {{ $k->mahasiswa->count() }} siswa
                        </span>
                    </td>

                    <td class="px-8 py-5">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('kelas.show', $k->id) }}"
                               class="p-2.5 text-gray-400 hover:text-[#004d4d] hover:bg-teal-50 rounded-xl transition-all border border-transparent hover:border-teal-100"
                               title="Detail">
                                <span class="material-symbols-outlined text-base">visibility</span>
                            </a>
                            <a href="{{ route('kelas.edit', $k->id) }}"
                               class="p-2.5 text-gray-400 hover:text-[#004d4d] hover:bg-teal-50 rounded-xl transition-all border border-transparent hover:border-teal-100"
                               title="Edit">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form action="{{ route('kelas.destroy', $k->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-2.5 text-gray-400 hover:text-white hover:bg-red-500 rounded-xl transition-all border border-transparent hover:border-red-500"
                                        title="Hapus">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center opacity-40">
                        <span class="material-symbols-outlined text-5xl text-gray-300">class</span>
                        <p class="font-black italic text-gray-400 mt-2">Belum ada data kelas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection