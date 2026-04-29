<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PBL Portal - AE Polman Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        aqua: {
                            light: '#7fffd4',
                            DEFAULT: '#40e0d0',
                            dark: '#008080',
                            deeper: '#004d4d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::selection { background: #7fffd4; color: #004d4d; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        .float { animation: float 6s ease-in-out infinite; }
        .pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
        .gradient-text {
            background: linear-gradient(135deg, #008080, #40e0d0, #7fffd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen bg-white font-sans">

    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-[#7fffd4]/30">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-aqua-deeper flex items-center justify-center">
                    <span class="text-[#7fffd4] font-black text-sm">AE</span>
                </div>
                <div class="h-8 w-px bg-[#7fffd4]/50"></div>
                <span class="font-black text-aqua-dark tracking-tighter text-xl uppercase">PBL Portal</span>
            </div>

            <div class="flex items-center gap-6">
    <a href="{{ route('register') }}"
       class="text-aqua-deeper font-bold text-sm hover:text-aqua-dark transition tracking-widest">
        REGISTER
    </a>
    <a href="{{ route('login') }}"
       class="bg-[#7fffd4] text-aqua-deeper px-8 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-[#7fffd4]/40 hover:bg-[#40e0d0] transition-all active:scale-95 border border-[#008080]/10">
        MASUK
    </a>
</div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="relative pt-32 pb-20 overflow-hidden min-h-screen flex items-center">

        {{-- Background Blobs --}}
        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -right-[5%] w-[60%] h-[70%] bg-[#7fffd4]/30 rounded-full blur-[120px] pulse-slow"></div>
            <div class="absolute top-[20%] -left-[10%] w-[50%] h-[60%] bg-[#40e0d0]/20 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 right-[20%] w-[40%] h-[40%] bg-[#7fffd4]/10 rounded-full blur-[80px]"></div>
            {{-- Grid dots --}}
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image: radial-gradient(#008080 1px, transparent 0); background-size: 28px 28px;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center w-full">

            {{-- Left: Text --}}
            <div class="space-y-10">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-[#7fffd4]/20 border border-[#7fffd4]/50">
                    <span class="w-2 h-2 rounded-full bg-aqua-dark"></span>
                    <span class="text-aqua-deeper font-mono text-[10px] tracking-[0.3em] uppercase font-bold">Automation Engineering · Polman Bandung</span>
                </div>

                <h1 class="text-7xl lg:text-8xl font-black text-aqua-deeper leading-[0.85] tracking-tighter">
                    Build <br>
                    <span class="gradient-text">The Future.</span>
                </h1>

                <p class="text-aqua-deeper/70 text-xl max-w-md leading-relaxed font-medium italic border-l-4 border-[#7fffd4] pl-6">
                    "Streamlining industrial excellence through smart project-based evaluation."
                </p>

                {{-- Stats --}}
                <div class="flex gap-8">
                    <div>
                        <p class="text-3xl font-black text-aqua-dark">4</p>
                        <p class="text-xs text-aqua-deeper/50 font-bold uppercase tracking-widest">Role Pengguna</p>
                    </div>
                    <div class="w-px bg-[#7fffd4]/40"></div>
                    <div>
                        <p class="text-3xl font-black text-aqua-dark">3</p>
                        <p class="text-xs text-aqua-deeper/50 font-bold uppercase tracking-widest">Program Studi</p>
                    </div>
                    <div class="w-px bg-[#7fffd4]/40"></div>
                    <div>
                        <p class="text-3xl font-black text-aqua-dark">100%</p>
                        <p class="text-xs text-aqua-deeper/50 font-bold uppercase tracking-widest">Terintegrasi</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('login') }}"
                       class="px-10 py-4 bg-[#7fffd4] text-aqua-deeper rounded-2xl font-black shadow-2xl shadow-[#7fffd4]/50 hover:scale-105 transition-all active:scale-95 border border-[#008080]/20 text-sm">
                        MASUK SEKARANG
                        <span class="material-symbols-outlined text-base align-middle ml-1">arrow_forward</span>
                    </a>
                </div>
            </div>

            {{-- Right: Card Visual --}}
            <div class="relative hidden lg:block float">
                <div class="absolute -inset-10 bg-[#7fffd4]/20 rounded-full blur-3xl opacity-50"></div>
                <div class="relative bg-white/40 backdrop-blur-2xl rounded-[3rem] border border-white/50 p-2 shadow-2xl">
                    <div class="bg-aqua-deeper rounded-[2.8rem] p-10 overflow-hidden relative">
                        {{-- Grid Pattern --}}
                        <div class="absolute inset-0 opacity-10"
                             style="background-image: radial-gradient(#7fffd4 1px, transparent 0); background-size: 24px 24px;"></div>

                        <div class="relative z-10 space-y-8">
                            {{-- Header --}}
                            <div class="flex justify-between items-start">
                                <div class="h-14 w-14 bg-[#7fffd4]/20 rounded-2xl flex items-center justify-center border border-[#7fffd4]/30">
                                    <span class="font-black text-[#7fffd4] text-lg">AE</span>
                                </div>
                                <span class="text-[#7fffd4] font-mono text-xs opacity-50">VER 2.0.26</span>
                            </div>

                            {{-- Title --}}
                            <div class="space-y-2">
                                <h3 class="text-3xl font-black text-white leading-tight">Integrated<br>Assessment</h3>
                                <p class="text-[#7fffd4]/60 font-light text-sm">Monitoring progres otomasi industri secara presisi dan terstruktur.</p>
                            </div>

                            {{-- Mini Stats --}}
                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                    <div class="text-[#7fffd4] text-2xl font-black">55%</div>
                                    <div class="text-[10px] text-white/40 uppercase tracking-widest font-bold">Manager</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                    <div class="text-[#7fffd4] text-2xl font-black">45%</div>
                                    <div class="text-[10px] text-white/40 uppercase tracking-widest font-bold">Dosen</div>
                                </div>
                            </div>

                            {{-- Feature List --}}
                            <div class="space-y-3 pt-2">
                                @foreach(['Penilaian Berbasis Rubrik', 'Logbook Mingguan', 'Feedback Real-time', 'Laporan & Export'] as $fitur)
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full bg-[#7fffd4]/20 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[#7fffd4] text-xs">check</span>
                                    </div>
                                    <span class="text-white/70 text-sm font-medium">{{ $fitur }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ===== FEATURES SECTION ===== --}}
    <section class="py-24 bg-gradient-to-b from-white to-[#7fffd4]/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[10px] font-bold tracking-[0.4em] uppercase text-aqua-dark">Fitur Sistem</span>
                <h2 class="text-4xl font-black text-aqua-deeper mt-3 tracking-tight">Semua yang kamu butuhkan</h2>
                <p class="text-aqua-deeper/50 mt-3 text-lg">dalam satu platform terintegrasi</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $features = [
                    ['icon' => 'menu_book', 'title' => 'Logbook Mingguan', 'desc' => 'Catat aktivitas proyek setiap minggu dan dapatkan verifikasi dari Manager Proyek.', 'color' => 'bg-[#7fffd4]/20 text-aqua-dark'],
                    ['icon' => 'grade', 'title' => 'Penilaian Terstruktur', 'desc' => 'Sistem penilaian berbasis rubrik dengan bobot Manager 55% dan Dosen 45%.', 'color' => 'bg-[#40e0d0]/20 text-aqua-dark'],
                    ['icon' => 'forum', 'title' => 'Feedback Hub', 'desc' => 'Kumpulkan semua feedback dari dosen dan manager dalam satu tempat.', 'color' => 'bg-[#7fffd4]/20 text-aqua-dark'],
                    ['icon' => 'analytics', 'title' => 'Laporan & Rekap', 'desc' => 'Admin dapat melihat rekap nilai seluruh mahasiswa dan export ke Excel.', 'color' => 'bg-[#40e0d0]/20 text-aqua-dark'],
                ];
                @endphp

                @foreach($features as $f)
                <div class="bg-white rounded-3xl p-7 border border-[#7fffd4]/20 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-2xl {{ $f['color'] }} flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">{{ $f['icon'] }}</span>
                    </div>
                    <h3 class="font-black text-aqua-deeper text-base mb-2">{{ $f['title'] }}</h3>
                    <p class="text-aqua-deeper/50 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== ROLE SECTION ===== --}}
    <section class="py-24 bg-aqua-deeper overflow-hidden relative">
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(#7fffd4 1px, transparent 0); background-size: 24px 24px;"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <span class="text-[10px] font-bold tracking-[0.4em] uppercase text-[#7fffd4]/60">Pengguna Sistem</span>
                <h2 class="text-4xl font-black text-white mt-3 tracking-tight">4 Role Terintegrasi</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                $roles = [
                    ['icon' => 'admin_panel_settings', 'role' => 'Admin', 'sub' => 'KPS / Kaprodi', 'desc' => 'Kelola data master, rubrik, rekap nilai, dan approve proyek.'],
                    ['icon' => 'manage_accounts', 'role' => 'Manager Proyek', 'sub' => 'Dosen Pembimbing', 'desc' => 'Penilaian 55%, verifikasi logbook, dan pengajuan proyek.'],
                    ['icon' => 'school', 'role' => 'Dosen Pengampu', 'sub' => 'Dosen MK', 'desc' => 'Penilaian 45% dan verifikasi laporan supervisi mahasiswa.'],
                    ['icon' => 'person', 'role' => 'Mahasiswa', 'sub' => 'Peserta PBL', 'desc' => 'Input logbook, upload laporan, lihat nilai dan feedback.'],
                ];
                @endphp

                @foreach($roles as $r)
                <div class="p-6 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-2xl bg-[#7fffd4]/10 border border-[#7fffd4]/20 flex items-center justify-center mb-5 group-hover:bg-[#7fffd4]/20 transition-colors">
                        <span class="material-symbols-outlined text-[#7fffd4] text-xl">{{ $r['icon'] }}</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#7fffd4]/50 uppercase tracking-widest mb-1">{{ $r['sub'] }}</p>
                    <h3 class="font-black text-white text-lg mb-2">{{ $r['role'] }}</h3>
                    <p class="text-white/40 text-sm leading-relaxed">{{ $r['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section class="py-24 bg-white">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <div class="bg-gradient-to-br from-[#7fffd4]/20 to-[#40e0d0]/10 rounded-[3rem] p-14 border border-[#7fffd4]/30">
                <span class="material-symbols-outlined text-5xl text-aqua-dark mb-4 block">rocket_launch</span>
                <h2 class="text-4xl font-black text-aqua-deeper tracking-tight mb-4">Siap memulai?</h2>
                <p class="text-aqua-deeper/60 mb-8 text-lg">Masuk ke sistem dan mulai kelola proyek PBL kamu sekarang.</p>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-3 px-12 py-5 bg-aqua-deeper text-[#7fffd4] rounded-2xl font-black text-sm shadow-2xl hover:scale-105 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-lg">login</span>
                    MASUK KE SISTEM
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="py-10 bg-white border-t border-[#7fffd4]/20">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3 opacity-60">
                <div class="h-7 w-7 rounded-lg bg-aqua-deeper flex items-center justify-center">
                    <span class="text-[#7fffd4] font-black text-xs">AE</span>
                </div>
                <span class="font-bold text-aqua-deeper text-sm">AE POLMAN SYSTEM</span>
            </div>
            <p class="text-[10px] text-gray-400 font-bold tracking-[0.3em] uppercase">
                Built with Precision — Automation Engineering · Polman Bandung
            </p>
            <p class="text-[10px] text-gray-400">© {{ date('Y') }} AE Polman Bandung</p>
        </div>
    </footer>

</body>
</html>