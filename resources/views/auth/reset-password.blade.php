<x-guest-layout>
    <div class="mb-5 text-center">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
            <span class="iconify text-2xl" data-icon="lucide:lock"></span>
        </div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Atur Ulang Kata Sandi</h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">Silakan buat kata sandi baru yang aman untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                       value="{{ old('email', $request->email) }}" 
                       required 
                       autofocus 
                       autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:lock"></span>
                </div>
                <input id="password" 
                       class="block w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="new-password" 
                       oninput="checkPasswordStrength(this.value); checkPasswordMatch();"
                       placeholder="Minimal 8 karakter" />
                <button type="button" 
                        onclick="togglePassword('password', this)" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer"
                        title="Tampilkan/Sembunyikan Kata Sandi">
                    <span class="iconify text-lg" data-icon="lucide:eye"></span>
                </button>
            </div>
            
            <!-- Live Password Strength Bar -->
            <div class="mt-2 space-y-1" id="strength-container" style="display: none;">
                <div class="flex items-center justify-between text-[11px] font-bold">
                    <span class="text-slate-500">Kekuatan Sandi:</span>
                    <span id="strength-text" class="text-rose-600">Sangat Lemah</span>
                </div>
                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                    <div id="strength-bar" class="h-full bg-rose-500 transition-all duration-300 w-1/4"></div>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:check-circle"></span>
                </div>
                <input id="password_confirmation" 
                       class="block w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
                       type="password"
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password" 
                       oninput="checkPasswordMatch()"
                       placeholder="Ulangi kata sandi baru" />
                <button type="button" 
                        onclick="togglePassword('password_confirmation', this)" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer"
                        title="Tampilkan/Sembunyikan Kata Sandi">
                    <span class="iconify text-lg" data-icon="lucide:eye"></span>
                </button>
            </div>
            
            <!-- Live Match Message -->
            <p id="match-msg" class="text-[11px] font-bold mt-1.5 hidden"></p>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold tracking-wide transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer">
                <span class="iconify text-base" data-icon="lucide:save"></span>
                <span>Simpan Kata Sandi Baru</span>
            </button>
        </div>
    </form>

    <script>
        function checkPasswordStrength(val) {
            const container = document.getElementById('strength-container');
            const text = document.getElementById('strength-text');
            const bar = document.getElementById('strength-bar');
            
            if (!val) {
                container.style.display = 'none';
                return;
            }
            
            container.style.display = 'block';
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            
            if (score <= 1) {
                text.textContent = 'Lemah';
                text.className = 'text-rose-600';
                bar.className = 'h-full bg-rose-500 transition-all duration-300 w-1/4';
            } else if (score === 2) {
                text.textContent = 'Cukup';
                text.className = 'text-amber-600';
                bar.className = 'h-full bg-amber-500 transition-all duration-300 w-2/4';
            } else if (score === 3) {
                text.textContent = 'Baik';
                text.className = 'text-blue-600';
                bar.className = 'h-full bg-blue-500 transition-all duration-300 w-3/4';
            } else {
                text.textContent = 'Sangat Kuat';
                text.className = 'text-emerald-600';
                bar.className = 'h-full bg-emerald-500 transition-all duration-300 w-full';
            }
        }

        function checkPasswordMatch() {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirmation').value;
            const msg = document.getElementById('match-msg');
            
            if (!p2) {
                msg.classList.add('hidden');
                return;
            }
            
            msg.classList.remove('hidden');
            if (p1 === p2) {
                msg.className = 'text-[11px] font-bold mt-1.5 text-emerald-600 flex items-center gap-1';
                msg.innerHTML = '<span class="iconify" data-icon="lucide:check"></span> <span>Kata sandi cocok</span>';
            } else {
                msg.className = 'text-[11px] font-bold mt-1.5 text-rose-600 flex items-center gap-1';
                msg.innerHTML = '<span class="iconify" data-icon="lucide:x"></span> <span>Kata sandi belum sama</span>';
            }
        }
    </script>
</x-guest-layout>
