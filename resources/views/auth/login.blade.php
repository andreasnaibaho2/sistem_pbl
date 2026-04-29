<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PBL Portal AE Polman Bandung</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#00503a",
                        "primary-container": "#006a4e",
                        "primary-fixed": "#9ef4d0",
                        "on-primary": "#ffffff",
                        "surface": "#f7f9fb",
                        "surface-container-low": "#f2f4f6",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#6f7a73",
                        "outline-variant": "#bec9c2",
                        "secondary": "#515f74",
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .abstract-lines {
            background-image: url("data:image/svg+xml,%3Csvg width='400' height='400' viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.1' stroke-width='2'%3E%3Cpath d='M0 100 L400 100 M100 0 L100 400 M0 300 L400 300 M300 0 L300 400'/%3E%3Cpath d='M50 50 L150 50 L150 150 L50 150 Z'/%3E%3Cpath d='M250 250 L350 250 L350 350 L250 350 Z'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 200px 200px;
        }
        .geometric-decor { position: absolute; border: 1px solid rgba(255,255,255,0.1); transform: rotate(45deg); }
    </style>
</head>
<body class="bg-surface text-gray-900 antialiased">
<main class="flex h-full w-full">
    <!-- Left Side: Branding -->
    <section class="hidden lg:flex flex-col relative w-1/2 bg-gradient-to-br from-primary to-primary-container overflow-hidden items-center justify-center p-12">
        <div class="absolute inset-0 abstract-lines opacity-20"></div>
        <div class="geometric-decor w-64 h-64 -top-20 -left-20 bg-white/5"></div>
        <div class="geometric-decor w-48 h-48 top-1/4 -right-24 bg-white/5"></div>
        <div class="geometric-decor w-80 h-80 -bottom-32 left-1/4 bg-white/5 opacity-50"></div>
        <div class="absolute top-10 left-10 opacity-20">
            <span class="material-symbols-outlined text-white text-8xl">precision_manufacturing</span>
        </div>
        <div class="absolute bottom-10 right-10 opacity-20">
            <span class="material-symbols-outlined text-white text-8xl">memory</span>
        </div>
        <div class="relative z-10 flex flex-col items-center text-center space-y-8">
            {{-- BARU --}}
<div class="w-48 h-48 bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-2xl border border-white/20 flex items-center justify-center">
    <img src="{{ asset('images/logo.png') }}" alt="AE Polman" class="w-full h-full object-contain drop-shadow-lg">
</div>
            <div class="space-y-2">
                <h1 class="text-white text-4xl font-black tracking-tight leading-tight">AE Polman Bandung</h1>
                <div class="flex items-center justify-center space-x-4">
                    <div class="h-[1px] w-8 bg-white/30"></div>
                    <p class="text-white/90 text-sm font-semibold tracking-[0.2em] uppercase">Automation Engineering</p>
                    <div class="h-[1px] w-8 bg-white/30"></div>
                </div>
            </div>
            <div class="max-w-md pt-8">
                <p class="text-white/70 text-sm leading-relaxed font-light tracking-wide">
                    Integrated Project-Based Learning Management System.
                    Empowering the next generation of mechatronics and automation experts.
                </p>
                <div class="mt-8 flex justify-center space-x-8 text-white/40">
                    <span class="material-symbols-outlined text-xl">settings_input_component</span>
                    <span class="material-symbols-outlined text-xl">account_tree</span>
                    <span class="material-symbols-outlined text-xl">developer_board</span>
                    <span class="material-symbols-outlined text-xl">smart_toy</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Right Side: Login Form -->
    <section class="w-full lg:w-1/2 bg-surface-container-lowest flex flex-col items-center justify-center p-8 md:p-16 lg:p-24 relative overflow-y-auto">
        <div class="w-full max-w-md space-y-10">
            <!-- Mobile Branding -->
            <div class="lg:hidden flex flex-col items-center mb-8 space-y-3">
             {{-- BARU --}}
<img src="{{ asset('images/logo.png') }}" alt="AE Polman" class="w-14 h-14 object-contain">
                <h2 class="text-2xl font-bold text-primary">PBL Portal</h2>
            </div>

            <div class="space-y-3">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
                <p class="text-secondary font-medium">Silakan masuk ke akun Akademik Anda</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="p-3 bg-green-50 text-green-700 rounded-lg text-sm font-medium">{{ session('status') }}</div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="p-3 bg-red-50 text-red-700 rounded-lg text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-outline uppercase tracking-widest ml-1" for="email">Email</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors text-xl">person</span>
                        <input class="w-full pl-12 pr-4 py-4 bg-surface-container-low border border-transparent rounded-lg focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary/30 transition-all placeholder:text-outline-variant text-gray-900 font-medium outline-none"
                               id="email" name="email" type="email"
                               placeholder="email@polman.ac.id"
                               value="{{ old('email') }}" required autofocus/>
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-outline uppercase tracking-widest ml-1" for="password">Kata Sandi</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors text-xl">lock</span>
                        <input class="w-full pl-12 pr-12 py-4 bg-surface-container-low border border-transparent rounded-lg focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary/30 transition-all placeholder:text-outline-variant text-gray-900 font-medium outline-none"
                               id="password" name="password" type="password"
                               placeholder="••••••••" required/>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" type="button"
                                onclick="const p=document.getElementById('password');p.type=p.type==='password'?'text':'password'">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-low" type="checkbox" name="remember" id="remember_me">
                        <span class="text-sm text-secondary font-medium group-hover:text-primary transition-colors">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold text-primary hover:underline decoration-2 underline-offset-4" href="{{ route('password.request') }}">
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>

                <button class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-lg shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 active:scale-95 uppercase tracking-wider text-sm" type="submit">
                    Masuk Sekarang
                </button>
            </form>

            <div class="pt-4 flex flex-col items-center space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-secondary">Butuh bantuan akses?</span>
                    <a class="text-sm font-bold text-primary hover:text-primary-container transition-colors" href="#">Hubungi Admin</a>
                </div>
            </div>
        </div>

        <footer class="mt-auto pt-8 w-full max-w-md">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0 text-[10px] uppercase tracking-[0.15em] text-outline/60 font-bold border-t border-outline-variant/30 pt-6">
                <span>© 2024 PBL Portal AE Polman</span>
                <div class="flex space-x-6">
                    <a class="hover:text-primary transition-colors" href="#">Privasi</a>
                    <a class="hover:text-primary transition-colors" href="#">Syarat</a>
                    <a class="hover:text-primary transition-colors" href="#">Support</a>
                </div>
            </div>
        </footer>
    </section>
</main>
</body>
</html>