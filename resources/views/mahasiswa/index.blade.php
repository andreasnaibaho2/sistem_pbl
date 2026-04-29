@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="flex-1 flex flex-col">

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
                Master Data <span class="text-[#2dce89]">Mahasiswa</span>
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
                {{ $mahasiswas->count() }} mahasiswa terdaftar
            </p>
        </div>
        <a href="{{ route('mahasiswa.create') }}"
           class="flex items-center gap-2 px-6 py-3.5 bg-[#004d4d] text-[#7fffd4] rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-xl shadow-teal-900/20">
            <span class="material-symbols-outlined text-base">person_add</span> TAMBAH MAHASISWA
        </a>
    </div>

    {{-- STAT STRIP --}}
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-[#004d4d] text-white p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Mahasiswa</p>
            <p class="text-5xl font-black italic tracking-tighter">{{ $mahasiswas->count() }}</p>
        </div>
        <div class="bg-white border-2 border-[#7fffd4] p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Prodi TRMO</p>
            <p class="text-4xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'mekatronika')->count() }}
            </p>
        </div>
        <div class="bg-white border-2 border-teal-100 p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Prodi TRO</p>
            <p class="text-4xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'otomasi')->count() }}
            </p>
        </div>
        <div class="bg-white border-2 border-teal-100 p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Prodi TRIN</p>
            <p class="text-4xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $mahasiswas->filter(fn($m) => $m->user && $m->user->prodi === 'informatika')->count() }}
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 w-10">#</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Identitas</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">NIM</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Program Studi</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mahasiswas as $idx => $mhs)
                <tr class="hover:bg-teal-50/30 transition-colors group">
                    <td class="px-8 py-5 text-[10px] font-black text-gray-300">{{ $idx + 1 }}</td>

                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#008080] to-[#004d4d] flex items-center justify-center text-white font-black text-xs shadow-md flex-shrink-0">
                                {{ strtoupper(substr($mhs->nama, 0, 2)) }}
                            </div>
                            <div>
                                <span class="font-bold text-[#004d4d] block leading-none">{{ $mhs->nama }}</span>
                                <span class="text-[10px] text-gray-400 font-medium mt-0.5 block">{{ $mhs->user->email ?? '-' }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-5">
                        <span class="inline-flex items-center px-3 py-1.5 bg-teal-50 text-teal-700 border border-teal-100 rounded-xl text-[10px] font-black uppercase tracking-wider">
                            {{ $mhs->nim }}
                        </span>
                    </td>

                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-gray-500 italic">
                            {{ labelProdi($mhs->user->prodi ?? '') }}
                        </span>
                    </td>

                    <td class="px-8 py-5">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('mahasiswa.edit', $mhs->id) }}"
                               class="p-2.5 text-gray-400 hover:text-[#004d4d] hover:bg-teal-50 rounded-xl transition-all border border-transparent hover:border-teal-100"
                               title="Edit">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus mahasiswa ini?')">
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
                    <td colspan="5" class="px-8 py-20 text-center opacity-40">
                        <span class="material-symbols-outlined text-5xl text-gray-300">person_search</span>
                        <p class="font-black italic text-gray-400 mt-2">Belum ada data mahasiswa.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsectiona