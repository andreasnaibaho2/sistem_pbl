@extends('layouts.app')

@section('title', 'Pengajuan Proyek')

@section('content')
<div class="flex-1 flex flex-col">

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#004d4d] tracking-tighter italic uppercase">
                Pengajuan <span class="text-[#2dce89]">Proyek</span>
            </h1>
            <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">
                {{ $pengajuan->count() }} pengajuan ditemukan
            </p>
        </div>
        @if(auth()->user()->isManager())
        <a href="{{ route('pengajuan_proyek.create') }}"
           class="flex items-center gap-2 px-6 py-3.5 bg-[#004d4d] text-[#7fffd4] rounded-2xl text-xs font-black hover:scale-105 transition-all shadow-xl shadow-teal-900/20">
            <span class="material-symbols-outlined text-base">add</span> AJUKAN PROYEK
        </a>
        @endif
    </div>

    {{-- STAT STRIP --}}
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-[#004d4d] text-white p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#7fffd4] text-[10px] font-black uppercase tracking-widest">Total Pengajuan</p>
            <p class="text-5xl font-black italic tracking-tighter">{{ $pengajuan->count() }}</p>
        </div>
        <div class="bg-white border-2 border-amber-200 p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-amber-500 text-[10px] font-black uppercase tracking-widest">Menunggu</p>
            <p class="text-4xl font-black italic tracking-tighter text-amber-500">
                {{ $pengajuan->where('status', 'pending')->count() }}
            </p>
        </div>
        <div class="bg-white border-2 border-[#7fffd4] p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-[#004d4d] text-[10px] font-black uppercase tracking-widest">Disetujui</p>
            <p class="text-4xl font-black italic tracking-tighter text-[#004d4d]">
                {{ $pengajuan->where('status', 'approved')->count() }}
            </p>
        </div>
        <div class="bg-white border-2 border-red-100 p-6 rounded-[1.5rem] flex flex-col justify-between shadow-sm">
            <p class="text-red-400 text-[10px] font-black uppercase tracking-widest">Ditolak</p>
            <p class="text-4xl font-black italic tracking-tighter text-red-400">
                {{ $pengajuan->where('status', 'rejected')->count() }}
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 w-10">#</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Proyek</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Manager</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Periode</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Kebutuhan</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Status</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengajuan as $idx => $p)
                <tr class="hover:bg-teal-50/30 transition-colors">
                    <td class="px-8 py-5 text-[10px] font-black text-gray-300">{{ $idx + 1 }}</td>

                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#008080] to-[#004d4d] flex items-center justify-center text-white shadow-md flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">rocket_launch</span>
                            </div>
                            <div>
                                <span class="font-bold text-[#004d4d] block leading-none">{{ $p->judul_proyek }}</span>
                                <span class="text-[10px] text-gray-400 font-medium mt-0.5 block uppercase tracking-wider">{{ $p->kode_pengajuan }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-gray-600">{{ $p->manager->name ?? '-' }}</span>
                    </td>

                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-gray-600 block">{{ $p->tanggal_mulai->format('d M Y') }}</span>
                        <span class="text-[10px] text-gray-400">s/d {{ $p->tanggal_selesai->format('d M Y') }}</span>
                    </td>

                    <td class="px-8 py-5 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 text-teal-700 border border-teal-100 rounded-xl text-[10px] font-black">
                            <span class="material-symbols-outlined text-sm">group</span>
                            {{ $p->getTotalMahasiswa() }} mhs
                        </span>
                    </td>

                    <td class="px-8 py-5 text-center">
                        @if($p->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 border border-green-100 rounded-xl text-[10px] font-black uppercase">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Disetujui
                            </span>
                        @elseif($p->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-500 border border-red-100 rounded-xl text-[10px] font-black uppercase">
                                <span class="material-symbols-outlined text-sm">cancel</span> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-500 border border-amber-100 rounded-xl text-[10px] font-black uppercase">
                                <span class="material-symbols-outlined text-sm">pending</span> Menunggu
                            </span>
                        @endif
                    </td>

                    <td class="px-8 py-5 text-center">
                        <a href="{{ route('pengajuan_proyek.show', $p->id) }}"
                           class="p-2.5 text-gray-400 hover:text-[#004d4d] hover:bg-teal-50 rounded-xl transition-all border border-transparent hover:border-teal-100 inline-flex"
                           title="Detail">
                            <span class="material-symbols-outlined text-base">visibility</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-20 text-center opacity-40">
                        <span class="material-symbols-outlined text-5xl text-gray-300">rocket_launch</span>
                        <p class="font-black italic text-gray-400 mt-2">Belum ada pengajuan proyek.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection