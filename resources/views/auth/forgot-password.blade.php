<x-guest-layout>
    <div class="mb-5 text-center">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
            <span class="iconify text-2xl" data-icon="lucide:key-round"></span>
        </div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
            Masukkan alamat email akun Anda yang terdaftar. Kami akan mengirimkan tautan reset kata sandi ke email Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                       placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold tracking-wide transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer">
                <span class="iconify text-base" data-icon="lucide:send"></span>
                <span>Kirim Tautan Reset Sandi</span>
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:text-blue-800 font-extrabold transition-colors inline-flex items-center gap-1">
                <span class="iconify text-sm" data-icon="lucide:chevron-left"></span>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>
    </form>
</x-guest-layout>
