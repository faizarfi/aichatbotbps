<x-guest-layout>
    <div class="mb-5 text-center">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">Masuk untuk mengakses layanan data, konsultasi, atau panel petugas.</p>
    </div>

    <!-- Tab Switcher -->
    <div class="flex items-center p-1 bg-slate-100 rounded-2xl border border-slate-200/80 mb-5">
        <span class="w-1/2 py-2 text-center text-xs font-extrabold rounded-xl bg-white text-blue-700 shadow-sm">
            Masuk (Login)
        </span>
        <a href="{{ route('register') }}" class="w-1/2 py-2 text-center text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all">
            Daftar (Register)
        </a>
    </div>

    <!-- Google One-Click Login Button -->
    <div class="mb-5">
        <a href="{{ route('auth.google') }}" 
           class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-slate-50 active:scale-[0.98] border border-slate-300/90 text-slate-700 text-xs sm:text-sm font-bold transition-all shadow-xs flex items-center justify-center gap-3 group">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Masuk dengan Akun Google</span>
        </a>

        <div class="relative flex py-4 items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">atau email akun</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
    <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 flex items-center gap-2 font-medium">
        <span class="iconify text-base text-rose-600 shrink-0" data-icon="lucide:alert-circle"></span>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:mail"></span>
                </div>
                <input id="email" 
                       class="block w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       placeholder="nama@email.com atau akun BPS" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-blue-600 hover:text-blue-800 transition-colors font-bold" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:lock"></span>
                </div>
                <input id="password" 
                       class="block w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                       type="password"
                       name="password"
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••" />
                <button type="button" 
                        onclick="togglePassword('password', this)" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer"
                        title="Tampilkan/Sembunyikan Kata Sandi">
                    <span class="iconify text-lg" data-icon="lucide:eye"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold tracking-wide transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer">
                <span class="iconify text-base" data-icon="lucide:log-in"></span>
                <span>Masuk Sekarang</span>
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Belum memiliki akun?</span>
            <a href="{{ route('register') }}" class="text-xs text-blue-600 hover:text-blue-800 font-extrabold ml-1 transition-colors">
                Daftar Akun Baru
            </a>
        </div>
    </form>
</x-guest-layout>
