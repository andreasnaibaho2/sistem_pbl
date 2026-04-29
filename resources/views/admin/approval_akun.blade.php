@extends('layouts.app')

@section('title', 'Approval Akun')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-black text-on-surface tracking-tight">Approval Akun</h1>
        <p class="text-sm text-on-surface-variant mt-0.5">Persetujuan akun dosen & manager baru</p>
    </div>
    <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-100 text-amber-700">
        {{ $pendingDosen->count() }} Permintaan
    </span>
</div>

{{-- PENDING --}}
<div class="mb-8">
    <h2 class="text-base font-black text-on-surface mb-4">Menunggu Persetujuan</h2>

    @if($pendingDosen->isEmpty())
    <div class="bg-white border border-outline-variant/30 rounded-[2rem] p-16 text-center shadow-sm">
        <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">how_to_reg</span>
        <p class="font-black italic text-on-surface-variant/50 uppercase tracking-widest text-sm">Antrean Kosong</p>
        <p class="text-xs text-on-surface-variant/40 mt-2">Tidak ada permintaan akun baru.</p>
    </div>
    @else
    <div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant/10 bg-surface-container-low/50">
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Email</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">NIDN</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Nama</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Role</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Terdaftar</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($pendingDosen as $u)
                <tr class="hover:bg-surface-container-lowest transition-colors group">
                    <td class="px-7 py-5">
                        <div class="flex items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-base">mail</span>
                            <span class="text-sm font-bold">{{ $u->email }}</span>
                        </div>
                    </td>
                    <td class="px-7 py-5 text-center">
                        <span class="px-3 py-1 rounded-lg bg-surface-container font-mono text-xs font-black text-on-surface-variant">
                            {{ $u->dosen?->nidn ?? '-' }}
                        </span>
                    </td>
                    <td class="px-7 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs text-[#7fffd4] shrink-0" style="background:#004d4d;">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <span class="font-black text-on-surface uppercase tracking-tight text-sm">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-7 py-5 text-center">
                        @php
                            $roleColor = $u->role === 'manager_proyek' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700';
                            $roleLabel = $u->role === 'manager_proyek' ? 'Manager Proyek' : 'Dosen Pengampu';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $roleColor }}">{{ $roleLabel }}</span>
                    </td>
                    <td class="px-7 py-5 text-center text-xs text-on-surface-variant">
                        {{ $u->created_at->format('d M Y') }}
                    </td>
                    <td class="px-7 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('approval.reject', $u) }}"
                                  onsubmit="return confirm('Tolak dan hapus akun {{ $u->name }}?')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-500 rounded-xl text-xs font-black border border-red-200 hover:bg-red-500 hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                    Tolak
                                </button>
                            </form>
                            <form method="POST" action="{{ route('approval.approve', $u) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black text-[#004d4d] shadow-lg hover:scale-105 transition-all"
                                    style="background:#7fffd4;">
                                    <span class="material-symbols-outlined text-sm">check</span>
                                    Setujui
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- SUDAH DISETUJUI --}}
<div>
    <h2 class="text-base font-black text-on-surface mb-4">
        Sudah Disetujui
        <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-700">{{ $approvedDosen->count() }}</span>
    </h2>

    @if($approvedDosen->isEmpty())
    <div class="bg-white border border-outline-variant/30 rounded-[2rem] p-8 text-center shadow-sm">
        <p class="text-sm text-on-surface-variant/40 font-black italic">Belum ada dosen yang disetujui.</p>
    </div>
    @else
    <div class="bg-white rounded-[2rem] shadow-sm border border-outline-variant/20 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant/10 bg-surface-container-low/50">
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Nama</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Email</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">NIDN</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline text-center">Role</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase tracking-widest text-outline">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($approvedDosen as $u)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-7 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center font-black text-xs text-primary shrink-0">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold text-on-surface text-sm">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-7 py-4 text-sm text-on-surface-variant">{{ $u->email }}</td>
                    <td class="px-7 py-4 font-mono text-sm text-on-surface-variant">{{ $u->dosen?->nidn ?? '-' }}</td>
                    <td class="px-7 py-4 text-center">
                        @php
                            $rc = $u->role === 'manager_proyek' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700';
                            $rl = $u->role === 'manager_proyek' ? 'Manager Proyek' : 'Dosen Pengampu';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $rc }}">{{ $rl }}</span>
                    </td>
                    <td class="px-7 py-4 text-sm text-on-surface-variant">{{ $u->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection