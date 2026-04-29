<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PBL Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        ::selection { background: #7fffd4; color: #004d4d; }
        .role-btn { transition: all 180ms cubic-bezier(0.34,1.56,0.64,1); }
        .role-btn:hover { transform: scale(1.02); }
        .role-btn:active { transform: scale(0.97); }
        .role-btn.active { border-color: #008080; background: #f0fdfa; color: #004d4d; }
        .prodi-btn { transition: all 180ms cubic-bezier(0.34,1.56,0.64,1); }
        .prodi-btn:hover { transform: scale(1.02); }
        .prodi-btn:active { transform: scale(0.97); }
        .prodi-btn.active { border-color: #7fffd4; background: #f0fdfa; color: #004d4d; }
        .kelas-btn { transition: all 180ms cubic-bezier(0.34,1.56,0.64,1); }
        .kelas-btn:hover { transform: scale(1.02); }
        .kelas-btn:active { transform: scale(0.97); }
        .kelas-btn.active { border-color: #008080; background: #008080; color: white; }
        input:focus { outline: none; border-color: #40e0d0; box-shadow: 0 0 0 3px rgba(64,224,208,0.15); }
    </style>
</head>
<body class="min-h-screen flex font-sans bg-white">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="w-1/2 relative hidden lg:flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-[#004d4d]"></div>
        <div class="absolute -top-[10%] -right-[10%] w-[80%] h-[80%] bg-[#7fffd4]/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] left-[10%] w-[60%] h-[60%] bg-[#008080]/40 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[70%] h-[70%] bg-[#40e0d0]/20 rounded-full blur-[110px]"></div>
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 2px 2px, #7fffd4 1px, transparent 0); background-size: 32px 32px;"></div>

        <div class="relative z-10 w-full max-w-lg px-12">
            <div class="relative group mb-10 inline-block">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#7fffd4] to-[#008080] rounded-[2rem] blur opacity-30"></div>
                <div class="relative bg-white/5 backdrop-blur-3xl p-8 rounded-[2rem] border border-white/10 shadow-2xl">
                    {{-- BARU --}}
<img src="{{ asset('images/logo.png') }}" alt="AE Polman" class="w-16 h-16 object-contain">
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-px w-8 bg-[#7fffd4]"></div>
                        <span class="text-[#7fffd4] font-mono text-[10px] tracking-[0.4em] uppercase">Next-Gen Automation</span>
                    </div>
                    <h1 class="text-6xl font-black text-white leading-none tracking-tighter">
                        Assessment <br>
                        <span class="text-transparent bg-clip-text"
                              style="background: linear-gradient(135deg, #7fffd4, #40e0d0, #008080); -webkit-background-clip: text;">
                            System.
                        </span>
                    </h1>
                </div>
                <p class="text-[#7fffd4]/60 text-lg leading-relaxed max-w-sm font-light italic">
                    Streamlining <span class="text-white not-italic font-medium">industrial excellence</span>
                    through smart project-based evaluation.
                </p>
                <div class="flex flex-wrap gap-2 pt-4">
                    @foreach(['Smart', 'Efficient', 'Integrated'] as $tag)
                    <div class="px-5 py-1.5 rounded-full border border-[#7fffd4]/20 bg-[#7fffd4]/5 text-[#7fffd4] text-[10px] uppercase tracking-widest font-bold">
                        {{ $tag }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="absolute bottom-12 left-12 right-12 h-px bg-gradient-to-r from-transparent via-[#7fffd4]/20 to-transparent"></div>
    </div>

    {{-- ===== RIGHT PANEL - FORM ===== --}}
    <div class="w-full lg:w-1/2 bg-[#fafafa] flex items-center justify-center p-8 overflow-y-auto">
        <div class="w-full max-w-md py-8">

            <h2 class="text-4xl font-black text-[#004d4d] tracking-tight mb-2">Daftar Akun</h2>
            <p class="text-gray-400 mb-8 font-medium">Lengkapi data kredensial PBL Anda.</p>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">error</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
                @csrf

                {{-- ROLE --}}
<div>
    <label class="block text-sm font-bold text-[#004d4d] mb-2">Daftar Sebagai</label>
    <div class="grid grid-cols-3 gap-3">
        <button type="button" onclick="setRole('mahasiswa')"
            id="role-mahasiswa"
            class="role-btn active flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-[#008080] bg-[#f0fdfa] text-[#004d4d]">
            <span class="material-symbols-outlined text-xl">school</span>
            <span class="font-bold text-xs text-center leading-tight">Mahasiswa</span>
        </button>
        <button type="button" onclick="setRole('dosen')"
            id="role-dosen"
            class="role-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-gray-100 bg-white text-gray-400">
            <span class="material-symbols-outlined text-xl">co_present</span>
            <span class="font-bold text-xs text-center leading-tight">Dosen Pengampu</span>
        </button>
        <button type="button" onclick="setRole('manager_proyek')"
            id="role-manager_proyek"
            class="role-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-gray-100 bg-white text-gray-400">
            <span class="material-symbols-outlined text-xl">manage_accounts</span>
            <span class="font-bold text-xs text-center leading-tight">Manager Proyek</span>
        </button>
    </div>
    <input type="hidden" name="role" id="roleInput" value="mahasiswa">
</div>

                {{-- INFO PENDING --}}
<div id="dosenInfo" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-2xl">
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-500 text-lg mt-0.5">info</span>
        <p class="text-sm text-amber-700 font-medium">Akun dosen memerlukan <strong>persetujuan Admin</strong> sebelum bisa digunakan.</p>
    </div>
</div>

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-bold text-[#004d4d] mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Masukkan nama lengkap..."
                        class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-white text-[#004d4d] font-medium text-sm transition-all">
                </div>

                {{-- EMAIL & NIM/NIDN --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-[#004d4d] mb-2">Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="email@example.com"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-white text-[#004d4d] font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#004d4d] mb-2" id="nimLabel">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required
                            placeholder="23010xxx"
                            id="nimInput"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-white text-[#004d4d] font-mono font-medium text-sm transition-all">
                    </div>
                </div>

                {{-- PRODI - Mahasiswa only --}}
                <div id="prodiSection">
                    <label class="block text-sm font-bold text-[#004d4d] mb-2">Program Studi</label>
                    <div class="grid grid-cols-3 gap-2">
                        @php
                        $prodis = [
                            ['id' => 'informatika', 'label' => 'Informatika', 'icon' => 'terminal'],
                            ['id' => 'otomasi',     'label' => 'Otomasi',     'icon' => 'settings_suggest'],
                            ['id' => 'mekatronika', 'label' => 'Mekatronika', 'icon' => 'precision_manufacturing'],
                        ];
                        @endphp
                        @foreach($prodis as $p)
                        <button type="button" onclick="setProdi('{{ $p['id'] }}')"
                            id="prodi-{{ $p['id'] }}"
                            class="prodi-btn flex flex-col items-center gap-2 p-3 rounded-2xl border-2 border-gray-100 bg-white text-gray-400">
                            <span class="material-symbols-outlined text-xl">{{ $p['icon'] }}</span>
                            <span class="font-black uppercase text-[10px]">{{ $p['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="prodi" id="prodiInput" value="{{ old('prodi') }}">
                </div>

                {{-- KELAS - muncul setelah prodi dipilih --}}
                <div id="kelasSection" class="hidden">
                    <label class="block text-sm font-bold text-[#004d4d] mb-2">Pilih Kelas</label>
                    <div class="grid grid-cols-4 gap-2" id="kelasGrid"></div>
                    <input type="hidden" name="kelas_register" id="kelasInput" value="{{ old('kelas_register') }}">
                </div>

                {{-- PASSWORD --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-[#004d4d] mb-2">Kata Sandi</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-white text-[#004d4d] font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#004d4d] mb-2">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-100 bg-white text-[#004d4d] font-medium text-sm transition-all">
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-4 rounded-2xl font-black text-sm uppercase tracking-widest text-white hover:scale-[1.01] active:scale-[0.98] transition-all"
                        style="background: linear-gradient(135deg, #004d4d, #008080); box-shadow: 0 6px 20px rgba(0,128,128,0.22);">
                        Daftar Sekarang
                    </button>

                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-[#008080] hover:underline">
                            Sudah punya akun? Masuk
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
const kelasMap = {
    informatika: ['AEC 1','AEC 2','AEC 3','AEC 4'],
    otomasi:     ['AEB 1','AEB 2','AEB 3','AEB 4'],
    mekatronika: ['AEA 1','AEA 2','AEA 3','AEA 4'],
};

function setRole(role) {
    document.getElementById('roleInput').value = role;

    ['mahasiswa','dosen','manager_proyek'].forEach(r => {
        const btn = document.getElementById('role-' + r);
        if (!btn) return;
        btn.classList.remove('active','border-[#008080]','bg-[#f0fdfa]','text-[#004d4d]');
        btn.classList.add('border-gray-100','bg-white','text-gray-400');
    });

    const active = document.getElementById('role-' + role);
    active.classList.add('active','border-[#008080]','bg-[#f0fdfa]','text-[#004d4d]');
    active.classList.remove('border-gray-100','bg-white','text-gray-400');

    const isMhs = role === 'mahasiswa';
    const isDosen = role === 'dosen' || role === 'manager_proyek';

    document.getElementById('prodiSection').classList.toggle('hidden', !isMhs);
    document.getElementById('kelasSection').classList.toggle('hidden', true);
    document.getElementById('dosenInfo').classList.toggle('hidden', !isDosen);
    document.getElementById('nimLabel').textContent = isMhs ? 'NIM' : 'NIDN';
    document.getElementById('nimInput').placeholder = isMhs ? '23010xxx' : '19880xxx';

    // Reset prodi & kelas
    document.getElementById('prodiInput').value = '';
    document.getElementById('kelasInput').value = '';
    document.querySelectorAll('.prodi-btn').forEach(b => {
        b.classList.remove('active','border-[#7fffd4]','bg-[#f0fdfa]','text-[#004d4d]');
        b.classList.add('border-gray-100','bg-white','text-gray-400');
    });
}

function setProdi(prodi) {
    document.getElementById('prodiInput').value = prodi;
    document.getElementById('kelasInput').value = '';

    document.querySelectorAll('.prodi-btn').forEach(b => {
        b.classList.remove('active','border-[#7fffd4]','bg-[#f0fdfa]','text-[#004d4d]');
        b.classList.add('border-gray-100','bg-white','text-gray-400');
    });

    const active = document.getElementById('prodi-' + prodi);
    active.classList.add('active','border-[#7fffd4]','bg-[#f0fdfa]','text-[#004d4d]');
    active.classList.remove('border-gray-100','bg-white','text-gray-400');

    // Render kelas
    const grid = document.getElementById('kelasGrid');
    grid.innerHTML = '';
    kelasMap[prodi].forEach(kls => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = kls;
        btn.className = 'kelas-btn flex items-center justify-center py-3 rounded-2xl border-2 border-gray-100 bg-white text-gray-400 font-black text-xs';
        btn.onclick = () => setKelas(kls);
        btn.id = 'kelas-' + kls.replace(' ','-');
        grid.appendChild(btn);
    });

    document.getElementById('kelasSection').classList.remove('hidden');
}

function setKelas(kls) {
    document.getElementById('kelasInput').value = kls;
    document.querySelectorAll('.kelas-btn').forEach(b => {
        b.classList.remove('active','border-[#008080]','bg-[#008080]','text-white');
        b.classList.add('border-gray-100','bg-white','text-gray-400');
    });
    const active = document.getElementById('kelas-' + kls.replace(' ','-'));
    active.classList.add('active','border-[#008080]','bg-[#008080]','text-white');
    active.classList.remove('border-gray-100','bg-white','text-gray-400');
}
</script>

</body>
</html>