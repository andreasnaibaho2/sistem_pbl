<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PBL Portal') - AE Polman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        "primary": "#004d4d",
                        "primary-container": "#b2dfdb",
                        "on-primary": "#ffffff",
                        "secondary": "#00695c",
                        "surface": "#f4f7f6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f2f4f6",
                        "surface-container": "#eceef0",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#3f4944",
                        "outline": "#6f7a73",
                        "outline-variant": "#bec9c2",
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(127,255,212,0.2); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(127,255,212,0.4); }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f4f7f6] font-sans text-gray-800 overflow-hidden h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="w-72 bg-[#004d4d] text-white flex flex-col z-20 shadow-2xl flex-shrink-0 h-screen">

      {{-- Logo --}}
<div class="p-6 flex items-center gap-4 border-b border-white/10">
    <img src="{{ asset('images/logo.png') }}" alt="AE Polman" class="w-16 h-16 object-contain flex-shrink-0">
    <span class="text-3xl font-black tracking-tighter italic uppercase text-white">
        PBL <span class="text-[#7fffd4]">PORTAL</span>
    </span>
</div>
        {{-- Nav --}}
        <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto pb-6">

            @php $path = request()->path(); @endphp

            @if(auth()->user()->isAdmin())
                <p class="px-4 text-[10px] uppercase tracking-[0.2em] text-[#7fffd4]/50 font-bold mb-3">Main Menu</p>

                <a href="{{ route('dashboard') }}"
                   class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all
                   {{ $path === 'dashboard' ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    <span>Dashboard</span>
                </a>

                @php $pendingCount = \App\Models\User::whereIn('role',['dosen','manager_proyek'])->where('status','pending')->count(); @endphp
                <a href="{{ route('approval.index') }}"
                   class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all
                   {{ str_starts_with($path,'approval-dosen') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">how_to_reg</span>
                    <div class="flex items-center gap-2 flex-1">
                        <span>Approval Akun</span>
                        @if($pendingCount > 0)
                            <span class="ml-auto text-[9px] px-2 py-0.5 rounded-full bg-amber-400 text-white font-black">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </a>

                @php
                    $masterActive = str_starts_with($path,'mahasiswa') || str_starts_with($path,'dosen') || str_starts_with($path,'kelas') || str_starts_with($path,'admin/matkul') || str_starts_with($path,'admin/prodi');
                @endphp
                <div x-data="{ open: {{ $masterActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl transition-all
                        {{ $masterActive ? 'text-[#7fffd4]' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-xl">database</span>
                            <span>Master Data</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-90' : ''">chevron_right</span>
                    </button>
                    <div x-show="open" x-cloak class="ml-10 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('mahasiswa.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'mahasiswa') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Data Mahasiswa</a>
                        <a href="{{ route('dosen.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'dosen') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Data Dosen</a>
                        <a href="{{ route('kelas.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'kelas') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Data Kelas</a>
                        <a href="{{ route('admin.matkul.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'admin/matkul') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Data Mata Kuliah</a>
                        <a href="{{ route('admin.prodi.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'admin/prodi') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Data Program Studi</a>
                    </div>
                </div>

                <div class="h-px bg-white/5 my-3 mx-4"></div>
                <p class="px-4 text-[10px] uppercase tracking-[0.2em] text-[#7fffd4]/50 font-bold mb-3">Academic & PBL</p>

                @php $kurikulumActive = str_starts_with($path,'rubrik') || str_starts_with($path,'bobot'); @endphp
                <div x-data="{ open: {{ $kurikulumActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl transition-all
                        {{ $kurikulumActive ? 'text-[#7fffd4]' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-xl">menu_book</span>
                            <span>Kurikulum PBL</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-90' : ''">chevron_right</span>
                    </button>
                    <div x-show="open" x-cloak class="ml-10 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('rubrik.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'rubrik') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Rubrik Penilaian</a>
                        <a href="{{ route('bobot.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ str_starts_with($path,'bobot') ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Bobot Penilaian</a>
                    </div>
                </div>

                @php $pendingProyek = \App\Models\PengajuanProyek::where('status','pending')->count(); @endphp
                <a href="{{ route('pengajuan_proyek.index') }}"
                   class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all
                   {{ str_starts_with($path,'pengajuan-proyek') || str_starts_with($path,'proyek-pbl') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">verified</span>
                    <div class="flex items-center gap-2 flex-1">
                        <span>Approval Proyek</span>
                        @if($pendingProyek > 0)
                            <span class="ml-auto text-[9px] px-2 py-0.5 rounded-full bg-blue-400 text-white font-black">{{ $pendingProyek }}</span>
                        @endif
                    </div>
                </a>

                <a href="{{ route('laporan.admin') }}"
                   class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all
                   {{ str_starts_with($path,'laporan-admin') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">monitoring</span>
                    <span>Monitoring & Laporan</span>
                </a>

                @php $asesmenActive = str_starts_with($path,'penilaian'); @endphp
                <div x-data="{ open: {{ $asesmenActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl transition-all
                        {{ $asesmenActive ? 'text-[#7fffd4]' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-xl">grade</span>
                            <span>Asesmen & Nilai</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-90' : ''">chevron_right</span>
                    </button>
                    <div x-show="open" x-cloak class="ml-10 mt-1 space-y-1 border-l border-white/10 pl-3">
                        <a href="{{ route('penilaian.index') }}" class="block py-2.5 text-xs font-bold transition-colors {{ $path === 'penilaian' ? 'text-[#7fffd4]' : 'text-white/40 hover:text-[#7fffd4]' }}">Semua Penilaian</a>
                    </div>
                </div>

            @elseif(auth()->user()->isManager())
                <p class="px-4 text-[10px] uppercase tracking-[0.2em] text-[#7fffd4]/50 font-bold mb-3">Menu</p>
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ $path==='dashboard' ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span><span>Dashboard</span>
                </a>
                <a href="{{ route('penilaian.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'penilaian') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">grade</span><span>Penilaian</span>
                </a>
                <a href="{{ route('pengajuan_proyek.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'pengajuan') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">rocket_launch</span><span>Pengajuan Proyek</span>
                </a>
                <a href="{{ route('logbook.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'logbook') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">fact_check</span><span>Verifikasi Logbook</span>
                </a>

            @elseif(auth()->user()->isDosen())
                <p class="px-4 text-[10px] uppercase tracking-[0.2em] text-[#7fffd4]/50 font-bold mb-3">Menu</p>
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ $path==='dashboard' ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span><span>Dashboard</span>
                </a>
                <a href="{{ route('penilaian.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'penilaian') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">grade</span><span>Penilaian</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'laporan') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">description</span><span>Verifikasi Laporan</span>
                </a>

            @else
                <p class="px-4 text-[10px] uppercase tracking-[0.2em] text-[#7fffd4]/50 font-bold mb-3">Menu</p>
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ $path==='dashboard' ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span><span>Dashboard</span>
                </a>
                <a href="{{ route('logbook.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'logbook') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">menu_book</span><span>Logbook</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'laporan') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">description</span><span>Pengumpulan Laporan</span>
                </a>
                <a href="{{ route('nilai.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'nilai') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">workspace_premium</span><span>Nilai Detail</span>
                </a>
                <a href="{{ route('feedback.index') }}" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ str_starts_with($path,'feedback') ? 'bg-[#7fffd4] text-[#004d4d] font-black shadow-lg' : 'text-white/60 hover:bg-white/5 hover:text-[#7fffd4]' }}">
                    <span class="material-symbols-outlined text-xl">forum</span><span>Feedback Hub</span>
                </a>
            @endif

        </nav>

        {{-- User info di bawah sidebar --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-3 py-3 rounded-2xl hover:bg-white/5 transition">
                <div class="w-10 h-10 rounded-xl bg-[#003d3d] border border-white/20 flex items-center justify-center font-black text-white text-sm flex-shrink-0">
    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-[#7fffd4]/50 uppercase tracking-widest font-bold">
                        {{ match(auth()->user()->role) {
                            'admin'          => 'Administrator',
                            'manager_proyek' => 'Manager Proyek',
                            'dosen'          => 'Dosen Pengampu',
                            'mahasiswa'      => 'Mahasiswa',
                            default          => auth()->user()->role
                        } }}
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-white/30 hover:text-red-400 hover:bg-white/5 transition-all" title="Logout">
                        <span class="material-symbols-outlined text-xl">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ================= MAIN AREA ================= --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- TOPBAR --}}
        <header class="bg-white border-b border-gray-100 px-8 h-16 flex items-center justify-between flex-shrink-0 shadow-sm">

            {{-- Kiri: breadcrumb --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">AE Polman Bandung</p>
                <p class="text-xs font-black italic text-[#004d4d] uppercase">@yield('title', 'Dashboard')</p>
            </div>

            {{-- Kanan --}}
            <div class="flex items-center gap-2">

                {{-- Search --}}
                <div class="relative mr-2">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-lg">search</span>
                    <input type="text" placeholder="Cari mahasiswa, proyek..."
                        class="pl-10 pr-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#7fffd4] outline-none text-sm w-60 placeholder:text-gray-300">
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ session('error') }}
                </div>
                @endif

                {{-- Notifikasi --}}
                <button class="w-9 h-9 flex items-center justify-center rounded-2xl hover:bg-gray-50 transition text-gray-300 hover:text-[#004d4d] relative">
                    <span class="material-symbols-outlined text-xl">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#2dce89] rounded-full"></span>
                </button>

                {{-- Settings --}}
                <button class="w-9 h-9 flex items-center justify-center rounded-2xl hover:bg-gray-50 transition text-gray-300 hover:text-[#004d4d]">
                    <span class="material-symbols-outlined text-xl">settings</span>
                </button>

                {{-- Divider --}}
                <div class="w-px h-6 bg-gray-100 mx-1"></div>

                {{-- User info --}}
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-black text-[#004d4d] leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                            {{ match(auth()->user()->role) {
                                'admin'          => 'Administrator',
                                'manager_proyek' => 'Manager Proyek',
                                'dosen'          => 'Dosen Pengampu',
                                'mahasiswa'      => 'Mahasiswa',
                                default          => auth()->user()->role
                            } }}
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-2xl bg-[#004d4d] flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto p-10 bg-[#f4f7f6]">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>