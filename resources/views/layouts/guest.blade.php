<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Layanan Masuk & Pendaftaran' }} — BPS Kabupaten Karanganyar</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bps.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-sky-50/50 to-indigo-50/30 text-slate-800 flex flex-col justify-between selection:bg-blue-600 selection:text-white relative overflow-x-hidden font-sans">
    
    {{-- Modern Preloader Screen --}}
    <x-preloader />

    {{-- Decorative Background Elements (Soft, Bright & Clean) --}}
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-blue-100/60 via-sky-50/30 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed -bottom-20 -left-20 w-96 h-96 bg-blue-100/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed -top-20 -right-20 w-96 h-96 bg-sky-100/40 rounded-full blur-3xl pointer-events-none -z-10"></div>

    {{-- Main Container --}}
    <div class="flex-1 flex flex-col justify-center items-center px-4 py-8 sm:py-12 sm:px-6 lg:px-8">
        {{-- Logo Header --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-white p-2 flex items-center justify-center shadow-md shadow-slate-200 border border-slate-200/80 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="text-left">
                    <span class="block text-base sm:text-lg font-extrabold text-slate-900 leading-tight">BPS Karanganyar</span>
                    <span class="block text-[11px] text-blue-600 font-bold uppercase tracking-wider">Pelayanan Statistik Terpadu</span>
                </div>
            </a>
        </div>

        {{-- Card Box --}}
        <div class="w-full sm:max-w-md">
            <div class="bg-white/95 backdrop-blur-md border border-slate-200/90 p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-200/60">
                {{ $slot }}
            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center gap-1.5 font-bold">
                    <span class="iconify text-sm" data-icon="lucide:arrow-left"></span>
                    <span>Kembali ke Beranda Utama</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200/80 bg-white/50 backdrop-blur-sm">
        &copy; 2026 Badan Pusat Statistik Kabupaten Karanganyar • Hak Cipta Dilindungi
    </footer>

    {{-- Global Auth UX Scripts --}}
    <script>
        // Toggle Password Visibility
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btn.querySelector('.iconify');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.setAttribute('data-icon', 'lucide:eye-off');
            } else {
                input.type = 'password';
                if (icon) icon.setAttribute('data-icon', 'lucide:eye');
            }
        }

        // Caps Lock Warning Detection
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInputs = document.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(input => {
                input.addEventListener('keyup', (e) => {
                    const isCaps = e.getModifierState && e.getModifierState('CapsLock');
                    let warning = input.parentNode.parentNode.querySelector('.caps-lock-warning');
                    if (isCaps) {
                        if (!warning) {
                            warning = document.createElement('p');
                            warning.className = 'caps-lock-warning text-[11px] text-amber-600 font-bold mt-1 flex items-center gap-1';
                            warning.innerHTML = '<span class="iconify" data-icon="lucide:alert-triangle"></span> <span>Caps Lock sedang aktif</span>';
                            input.parentNode.parentNode.appendChild(warning);
                        }
                    } else if (warning) {
                        warning.remove();
                    }
                });
            });

            // Handle Loading State on Form Submit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        const icon = submitBtn.querySelector('.iconify');
                        if (icon) icon.setAttribute('data-icon', 'lucide:loader-2');
                        if (icon) icon.classList.add('animate-spin');
                    }
                });
            });

            // SweetAlert Toast for Flash Messages
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            @if(session('status'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: "{{ session('status') }}",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>

    {{-- Accessibility Menu Component --}}
    <x-accessibility-menu />
</body>
</html>
