<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Layanan informasi data statistik terpadu, konsultasi PST, dan pengaduan masyarakat BPS Kabupaten Karanganyar.')">
    <title>@yield('title', 'Beranda') — Portal Layanan BPS Kabupaten Karanganyar</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bps.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50/70 font-sans antialiased text-slate-800 selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">

    {{-- Clean Professional Navbar (Single Unified Header) --}}
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-slate-200/80 shadow-xs transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16 sm:h-20">
                {{-- Brand Identity --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative shrink-0">
                        <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS Karanganyar" class="w-10 h-10 sm:w-11 sm:h-11 object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div>
                        <span class="text-sm sm:text-base font-black text-slate-900 tracking-tight block leading-tight group-hover:text-blue-700 transition-colors">
                            BPS Kabupaten Karanganyar
                        </span>
                        <span class="text-[11px] font-bold text-blue-700 tracking-wide uppercase block">
                            Pelayanan Statistik Terpadu
                        </span>
                    </div>
                </a>

                {{-- Desktop Nav Menu --}}
                <div class="hidden md:flex items-center gap-0.5 bg-slate-100/90 p-1 rounded-2xl border border-slate-200/80">
                    <a href="{{ route('home') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('home') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Beranda
                    </a>
                    <a href="{{ route('chat.index') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('chat.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all flex items-center gap-1">
                        <span class="iconify text-sm text-blue-600" data-icon="lucide:message-square-text"></span>
                        <span>Chatbot</span>
                    </a>
                    <a href="{{ route('districts.index') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('districts.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Peta
                    </a>
                    <a href="{{ route('calculators.index') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('calculators.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Kalkulator
                    </a>
                    <a href="{{ route('reservasi.create') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('reservasi.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Reservasi
                    </a>
                    <a href="{{ route('layanan-data.create') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('layanan-data.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Data Mikro
                    </a>
                    <a href="{{ route('survei.create') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('survei.*') ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Survei SKM
                    </a>
                    <a href="{{ route('aduan.create') }}" class="px-2.5 py-1.5 text-xs font-bold rounded-xl {{ request()->routeIs('aduan.*') ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }} transition-all">
                        Aduan
                    </a>
                </div>

                {{-- Quick Action CTA & Auth Buttons --}}
                <div class="hidden lg:flex items-center gap-2.5">
                    @auth
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl border border-blue-200 transition-all flex items-center gap-1.5 shadow-xs">
                                <span class="iconify text-base" data-icon="lucide:shield-check"></span>
                                <span>Dashboard Admin</span>
                            </a>
                        @else
                            <div class="px-3 py-1.5 bg-slate-100 rounded-xl border border-slate-200 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-800 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-rose-600 hover:bg-rose-50 text-xs font-bold rounded-xl border border-rose-200 transition-all flex items-center gap-1 cursor-pointer" title="Keluar">
                                <span class="iconify text-base" data-icon="lucide:log-out"></span>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-2 text-slate-700 hover:text-blue-700 font-bold text-xs rounded-xl border border-slate-200/90 hover:border-blue-300 hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:log-in"></span>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition-all flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-base" data-icon="lucide:user-plus"></span>
                            <span>Daftar</span>
                        </a>
                    @endauth

                    <a href="{{ route('chat.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-md shadow-blue-500/20 flex items-center gap-1.5 ml-1">
                        <span class="iconify text-base" data-icon="lucide:bot"></span>
                        <span>Tanya Asisten</span>
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center md:hidden gap-2">
                    <a href="{{ route('chat.index') }}" class="p-2 rounded-xl bg-blue-600 text-white text-xs font-bold flex items-center gap-1">
                        <span class="iconify text-base" data-icon="lucide:bot"></span>
                    </a>
                    <button type="button" id="mobile-menu-btn" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors border border-slate-200" aria-label="Menu">
                        <span class="iconify text-2xl" data-icon="lucide:menu"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-slate-200 px-4 pt-3 pb-5 space-y-2 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Beranda Utama
            </a>
            <a href="{{ route('chat.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('chat.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }} flex items-center gap-2">
                <span class="iconify text-base text-blue-600" data-icon="lucide:bot"></span>
                <span>Chatbot Statistik 24 Jam</span>
            </a>
            <a href="{{ route('districts.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('districts.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Peta 17 Kecamatan
            </a>
            <a href="{{ route('calculators.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('calculators.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Kalkulator Statistik Interaktif
            </a>
            <a href="{{ route('reservasi.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('reservasi.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Reservasi Tatap Muka PST
            </a>
            <a href="{{ route('layanan-data.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('layanan-data.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Permintaan Data & ROMANTIK
            </a>
            <a href="{{ route('survei.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('survei.*') ? 'bg-amber-50 text-amber-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Survei Kepuasan Masyarakat (SKM)
            </a>
            <a href="{{ route('aduan.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('aduan.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Pengaduan Layanan
            </a>
            <a href="{{ route('status-aduan') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('status-aduan') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50' }}">
                Lacak Status Tiket Aduan
            </a>

            <div class="pt-3 border-t border-slate-100 space-y-2">
                @auth
                    @if(auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 px-4 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl flex items-center justify-center gap-2 border border-blue-200">
                            <span class="iconify text-base" data-icon="lucide:shield-check"></span>
                            <span>Panel Admin ({{ auth()->user()->name }})</span>
                        </a>
                    @else
                        <div class="p-2.5 bg-slate-100 rounded-xl text-center text-xs font-bold text-slate-800">
                            👤 {{ auth()->user()->name }} (Pengguna)
                        </div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl flex items-center justify-center gap-2 border border-rose-200">
                            <span class="iconify text-base" data-icon="lucide:log-out"></span>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-colors border border-slate-200">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:log-in"></span>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}" class="py-2.5 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-colors border border-blue-200">
                            <span class="iconify text-base text-blue-600" data-icon="lucide:user-plus"></span>
                            <span>Daftar</span>
                        </a>
                    </div>
                @endauth
                <a href="{{ route('chat.index') }}" class="w-full py-3 bg-blue-600 text-white text-center text-xs font-extrabold rounded-xl shadow-md flex items-center justify-center gap-2">
                    <span class="iconify text-base" data-icon="lucide:message-circle"></span>
                    <span>Buka Chatbot Sekarang</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Main Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Clean Light Institutional Footer --}}
    <footer class="bg-white text-slate-600 border-t border-slate-200 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12">
                {{-- Col 1: Brand & Bio --}}
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-blue-50 p-1.5 flex items-center justify-center border border-blue-100 shadow-xs">
                            <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="font-black text-slate-900 block text-base tracking-tight leading-tight">BPS Kabupaten Karanganyar</span>
                            <span class="text-xs text-blue-700 font-bold tracking-wide">Badan Pusat Statistik Republik Indonesia</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-500 pr-4">
                        Pusat rujukan data statistik terpercaya, penyedia data kependudukan, sosial, ekonomi, pertanian, serta kanal resmi pengaduan masyarakat Kabupaten Karanganyar.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-2.5">
                        <a href="https://karanganyarkab.bps.go.id" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 text-xs font-bold border border-slate-200 transition-colors flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-blue-600" data-icon="lucide:globe"></span>
                            <span>Website Resmi BPS</span>
                        </a>
                        <a href="https://pst.bps.go.id" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 text-xs font-bold border border-slate-200 transition-colors flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-blue-600" data-icon="lucide:external-link"></span>
                            <span>Portal PST Pusat</span>
                        </a>
                    </div>
                </div>

                {{-- Col 2: Layanan --}}
                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-slate-900 font-black text-xs uppercase tracking-widest">Kanal Layanan</h4>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                        <li><a href="{{ route('home') }}" class="hover:text-blue-700 font-medium transition-colors flex items-center gap-2"><span class="iconify text-blue-600" data-icon="lucide:chevron-right"></span> Beranda Utama</a></li>
                        <li><a href="{{ route('chat.index') }}" class="hover:text-blue-700 font-medium transition-colors flex items-center gap-2"><span class="iconify text-blue-600" data-icon="lucide:chevron-right"></span> Chatbot Statistik 24 Jam</a></li>
                        <li><a href="{{ route('aduan.create') }}" class="hover:text-blue-700 font-medium transition-colors flex items-center gap-2"><span class="iconify text-blue-600" data-icon="lucide:chevron-right"></span> Formulir Pengaduan</a></li>
                        <li><a href="{{ route('status-aduan') }}" class="hover:text-blue-700 font-medium transition-colors flex items-center gap-2"><span class="iconify text-blue-600" data-icon="lucide:chevron-right"></span> Lacak Status Aduan</a></li>
                        <li><a href="{{ route('kebijakan-privasi') }}" class="hover:text-blue-700 font-medium transition-colors flex items-center gap-2"><span class="iconify text-blue-600" data-icon="lucide:chevron-right"></span> Kebijakan Privasi Data</a></li>
                    </ul>
                </div>

                {{-- Col 3: Kontak PST --}}
                <div class="md:col-span-4 space-y-3">
                    <h4 class="text-slate-900 font-black text-xs uppercase tracking-widest">Kantor & Pelayanan</h4>
                    <div class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="iconify mt-0.5 shrink-0 text-blue-600 text-lg" data-icon="lucide:map-pin"></span>
                            <div>
                                <span class="font-bold text-slate-900 block">Alamat Kantor:</span>
                                <span class="text-slate-500">Jl. Lawu No. 202B, Badran Asri, Cangakan, Karanganyar 57714</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="iconify shrink-0 text-blue-600 text-lg" data-icon="lucide:phone"></span>
                            <div>
                                <span class="font-bold text-slate-900 block">Telepon Pelayanan:</span>
                                <span class="text-slate-500">(0271) 495035</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="iconify shrink-0 text-blue-600 text-lg" data-icon="lucide:mail"></span>
                            <div>
                                <span class="font-bold text-slate-900 block">Email Resmi:</span>
                                <span class="text-slate-500">bps3313@bps.go.id</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom info --}}
            <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Karanganyar. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3.5 py-1.5 rounded-full border border-slate-200">
                    <span class="iconify text-emerald-600" data-icon="lucide:clock"></span>
                    <span>Waktu Server: <strong>Asia/Jakarta (WIB)</strong></span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating Chatbot Button (on all pages except chat) --}}
    @if(!request()->routeIs('chat.*'))
    <div class="fixed bottom-5 right-5 sm:bottom-7 sm:right-7 z-30">
        <a href="{{ route('chat.index') }}"
           class="flex items-center gap-3 px-4 py-3 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-full shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 transform hover:-translate-y-1 transition-all duration-300 group border border-blue-500/40">
            <div class="relative shrink-0">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-6 h-6 object-contain bg-white rounded-full p-0.5 shadow-sm">
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-blue-700 animate-pulse"></span>
            </div>
            <div class="text-left pr-1.5 hidden sm:block">
                <span class="text-[10px] text-blue-100 block uppercase font-bold tracking-wider leading-none">Asisten PST</span>
                <span class="text-xs font-bold tracking-tight leading-tight">Tanya Data BPS</span>
            </div>
            <span class="sm:hidden text-xs font-bold pr-1">Tanya Data</span>
        </a>
    </div>
    @endif

    {{-- Mobile menu toggle --}}
    <script>
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Global SweetAlert Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    </script>

    @stack('scripts')
</body>
</html>
