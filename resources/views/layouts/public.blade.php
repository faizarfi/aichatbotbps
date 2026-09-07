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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head><body class="bg-slate-50 font-sans antialiased text-slate-800 selection:bg-[#003c80] selection:text-white flex flex-col min-h-screen">

    {{-- Modern Preloader Screen --}}
    <x-preloader />

    {{-- Official BPS Top Bar --}}
    <div class="bg-[#002b6a] text-slate-200 text-[11px] py-1.5 border-b border-blue-900/60 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-2.5 whitespace-nowrap overflow-hidden">
                <span class="flex items-center gap-1.5 font-semibold text-slate-300 shrink-0">
                    <span class="iconify text-xs text-[#f7941d]" data-icon="lucide:calendar"></span>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </span>
                <span class="text-blue-400/50 hidden lg:inline">•</span>
                <span class="font-medium text-slate-300 hidden lg:inline truncate">Badan Pusat Statistik Kabupaten Karanganyar (MFD 3313)</span>
            </div>
            <div class="flex items-center gap-3.5 shrink-0 whitespace-nowrap text-[11px]">
                <a href="https://karanganyarkab.bps.go.id" target="_blank" rel="noopener" class="hover:text-white text-slate-300 transition-colors flex items-center gap-1 font-semibold">
                    <span class="iconify text-xs text-[#f7941d]" data-icon="lucide:globe"></span>
                    <span>Portal BPS Karanganyar</span>
                </a>
                <span class="text-blue-400/50">•</span>
                <a href="https://pst.bps.go.id" target="_blank" rel="noopener" class="hover:text-white text-slate-300 transition-colors flex items-center gap-1 font-semibold">
                    <span class="iconify text-xs text-[#00a651]" data-icon="lucide:external-link"></span>
                    <span>PST BPS RI</span>
                </a>
                <span class="text-blue-400/50 hidden xl:inline">•</span>
                <span class="hidden xl:flex items-center gap-1 text-slate-300">
                    <span class="iconify text-xs text-sky-300" data-icon="lucide:phone"></span>
                    <span>(0271) 495047</span>
                </span>
                <span class="text-blue-400/50">•</span>
                <button type="button" onclick="openLanguageModal()" class="hover:text-white text-emerald-300 transition-colors flex items-center gap-1 font-bold cursor-pointer" title="Pilih Bahasa / Select Language">
                    <span class="iconify text-xs" data-icon="lucide:globe"></span>
                    <span id="current-lang-topbar">Indonesia</span>
                </button>
                <span class="text-blue-400/50">•</span>
                <button type="button" onclick="toggleA11yMenu()" class="hover:text-white text-[#f7941d] transition-colors flex items-center gap-1 font-bold cursor-pointer" title="Buka Menu Aksesibilitas">
                    <span class="iconify text-xs" data-icon="lucide:accessibility"></span>
                    <span>Aksesibilitas</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Main Navbar (BPS Official Style) --}}
    <nav class="sticky top-0 z-40 bg-white border-b-2 border-[#003c80] shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3">
                {{-- Brand Identity --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                    <div class="relative shrink-0">
                        <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS Karanganyar" class="w-9 h-9 sm:w-11 sm:h-11 object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="shrink-0 whitespace-nowrap">
                        <span class="text-xs sm:text-sm md:text-[15px] font-black text-slate-900 tracking-tight block leading-tight group-hover:text-[#003c80] transition-colors">
                            BADAN PUSAT STATISTIK
                        </span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] sm:text-[11px] font-extrabold text-[#003c80] tracking-wide uppercase">
                                KABUPATEN KARANGANYAR
                            </span>
                            <span class="px-1.5 py-0.2 rounded bg-amber-50 text-[#f7941d] border border-amber-200 text-[9px] font-bold">
                                PST
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Desktop Nav Menu --}}
                <div class="hidden lg:flex items-center gap-1 xl:gap-1.5">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-xs font-extrabold rounded-lg whitespace-nowrap {{ request()->routeIs('home') ? 'bg-[#003c80] text-white shadow-xs' : 'text-slate-700 hover:text-[#003c80] hover:bg-slate-100' }} transition-all">
                        Beranda
                    </a>
                    <a href="{{ route('chat.index') }}" class="px-3 py-2 text-xs font-extrabold rounded-lg whitespace-nowrap {{ request()->routeIs('chat.*') ? 'bg-[#003c80] text-white shadow-xs' : 'text-[#003c80] bg-blue-50/80 hover:bg-blue-100/80 border border-blue-200/60' }} transition-all flex items-center gap-1.5">
                        <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:bot"></span>
                        <span>Konsultasi PST</span>
                    </a>
                    <a href="{{ route('districts.index') }}" class="px-3 py-2 text-xs font-extrabold rounded-lg whitespace-nowrap {{ request()->routeIs('districts.*') ? 'bg-[#003c80] text-white shadow-xs' : 'text-slate-700 hover:text-[#003c80] hover:bg-slate-100' }} transition-all">
                        17 Kecamatan
                    </a>
                    <a href="{{ route('calculators.index') }}" class="px-3 py-2 text-xs font-extrabold rounded-lg whitespace-nowrap {{ request()->routeIs('calculators.*') ? 'bg-[#003c80] text-white shadow-xs' : 'text-slate-700 hover:text-[#003c80] hover:bg-slate-100' }} transition-all">
                        Kalkulator
                    </a>
                    <a href="{{ route('aduan.create') }}" class="px-3 py-2 text-xs font-extrabold rounded-lg whitespace-nowrap {{ request()->routeIs('aduan.*') ? 'bg-[#003c80] text-white shadow-xs' : 'text-slate-700 hover:text-[#003c80] hover:bg-slate-100' }} transition-all">
                        Aduan
                    </a>
                </div>

                {{-- Quick Action CTA & Auth Buttons --}}
                <div class="hidden lg:flex items-center gap-2 shrink-0">
                    @auth
                        @if(auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-[#003c80] text-xs font-extrabold rounded-xl border border-blue-200 transition-all flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                                <span class="iconify text-base text-[#f7941d]" data-icon="lucide:shield-check"></span>
                                <span>Panel Petugas</span>
                            </a>
                        @else
                            <a href="{{ route('my-profile.show') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl border border-slate-200 flex items-center gap-2 transition-all shrink-0">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="" class="w-6 h-6 rounded-full object-cover shrink-0" referrerpolicy="no-referrer">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-[#003c80] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-xs font-bold text-slate-800 max-w-[110px] truncate">{{ auth()->user()->name }}</span>
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline shrink-0">
                            @csrf
                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 text-xs font-bold rounded-xl border border-rose-200 transition-all flex items-center justify-center cursor-pointer" title="Keluar">
                                <span class="iconify text-base" data-icon="lucide:log-out"></span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-slate-700 hover:text-[#003c80] font-bold text-xs rounded-xl border border-slate-300 hover:border-[#003c80] hover:bg-slate-50 transition-all flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                            <span class="iconify text-sm text-slate-500" data-icon="lucide:log-in"></span>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-2 bg-[#003c80] hover:bg-[#002b6a] text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                            <span class="iconify text-sm" data-icon="lucide:user-plus"></span>
                            <span>Daftar</span>
                        </a>
                    @endauth

                    <div class="h-5 w-px bg-slate-200 mx-0.5 shrink-0"></div>

                    {{-- Global Language Selector Button (Desktop) --}}
                    <button type="button" 
                            onclick="openLanguageModal()" 
                            class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-[#003c80] text-xs font-bold rounded-xl border border-slate-200 flex items-center gap-1.5 transition-all shadow-xs cursor-pointer active:scale-95 whitespace-nowrap shrink-0" 
                            title="Pilih Bahasa / Select Language">
                        <span class="iconify text-base text-[#003c80]" data-icon="lucide:globe"></span>
                        <span id="current-lang-code" class="font-black text-xs uppercase text-slate-900">ID</span>
                        <span class="iconify text-xs text-slate-400" data-icon="lucide:chevron-down"></span>
                    </button>

                    <button type="button" onclick="toggleA11yMenu()" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#003c80] transition-all border border-slate-200 cursor-pointer shrink-0" title="Menu Aksesibilitas Web" aria-label="Buka Menu Aksesibilitas">
                        <span class="iconify text-base" data-icon="lucide:accessibility"></span>
                    </button>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center lg:hidden gap-1.5 shrink-0">
                    {{-- Global Language Button (Mobile) --}}
                    <button type="button" 
                            onclick="openLanguageModal()" 
                            class="p-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold flex items-center gap-1 border border-slate-200 cursor-pointer active:scale-95" 
                            title="Pilih Bahasa / Select Language">
                        <span class="iconify text-base text-[#003c80]" data-icon="lucide:globe"></span>
                        <span id="current-lang-code-mobile" class="font-black text-[11px] uppercase">ID</span>
                    </button>

                    <button type="button" onclick="toggleA11yMenu()" class="p-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold flex items-center gap-1 border border-slate-200" title="Menu Aksesibilitas">
                        <span class="iconify text-lg text-[#003c80]" data-icon="lucide:accessibility"></span>
                    </button>
                    @if(!request()->routeIs('chat.*'))
                    <a href="{{ route('chat.index') }}" class="p-2 rounded-xl bg-[#003c80] text-white text-xs font-bold flex items-center gap-1 shadow-sm transition-all" title="Buka Konsultasi PST">
                        <span class="iconify text-lg text-[#f7941d]" data-icon="lucide:bot"></span>
                    </a>
                    @endif
                    <button type="button" id="mobile-menu-btn" class="p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-colors border border-slate-200 cursor-pointer" aria-label="Menu">
                        <span class="iconify text-2xl" data-icon="lucide:menu"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-b-2 border-[#003c80] px-4 pt-3 pb-5 space-y-2 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('home') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                Beranda Utama
            </a>
            <a href="{{ route('chat.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('chat.*') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }} flex items-center gap-2">
                <span class="iconify text-base text-[#f7941d]" data-icon="lucide:bot"></span>
                <span>Konsultasi Data PST</span>
            </a>
            <a href="{{ route('districts.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('districts.*') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                Peta 17 Kecamatan
            </a>
            <a href="{{ route('calculators.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('calculators.*') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                Kalkulator Statistik Interaktif
            </a>
            <a href="{{ route('aduan.create') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('aduan.*') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                Pengaduan Layanan
            </a>
            <a href="{{ route('status-aduan') }}" class="block px-3 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('status-aduan') ? 'bg-[#003c80] text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                Lacak Status Tiket Aduan
            </a>
            <button type="button" onclick="toggleA11yMenu()" class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center justify-between border border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="iconify text-base text-[#003c80]" data-icon="lucide:accessibility"></span>
                    <span>Menu Aksesibilitas Web</span>
                </div>
                <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-50 text-[#003c80] font-bold">A11y</span>
            </button>

            <div class="pt-3 border-t border-slate-100 space-y-2">
                @auth
                    @if(auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 px-4 bg-blue-50 text-[#003c80] text-xs font-bold rounded-xl flex items-center justify-center gap-2 border border-blue-200">
                            <span class="iconify text-base text-[#f7941d]" data-icon="lucide:shield-check"></span>
                            <span>Panel Petugas ({{ auth()->user()->name }})</span>
                        </a>
                    @else
                        <a href="{{ route('my-profile.show') }}" class="w-full py-2.5 px-4 bg-slate-100 text-slate-800 text-xs font-bold rounded-xl flex items-center justify-center gap-2 border border-slate-200">
                            <span class="iconify text-base text-slate-600" data-icon="lucide:user"></span>
                            <span>Profil Saya</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl flex items-center justify-center gap-2 border border-rose-200">
                            <span class="iconify text-base" data-icon="lucide:log-out"></span>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('login') }}" class="py-2.5 px-4 text-center rounded-xl border border-slate-300 font-bold text-xs text-slate-700 hover:bg-slate-50">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="py-2.5 px-4 text-center rounded-xl bg-[#003c80] text-white font-bold text-xs hover:bg-[#002b6a]">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Official BPS Footer (Deep Navy Corporate & Orange Accent) --}}
    <footer class="bg-[#002b6a] text-slate-200 border-t-4 border-[#f7941d] mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12">
                {{-- Col 1: Brand & Bio --}}
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-white p-2 flex items-center justify-center shadow-md">
                            <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="font-black text-white block text-base tracking-tight leading-tight">BADAN PUSAT STATISTIK</span>
                            <span class="text-xs text-[#f7941d] font-extrabold tracking-wide uppercase">KABUPATEN KARANGANYAR</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-300 pr-4">
                        Penyedia data statistik berkualitas untuk Indonesia Maju. Melayani permohonan data statistik, publikasi berkala, rekomendasi statistik (ROMANTIK), data mikro berstandar PP 86/2021, dan konsultasi PST bagi masyarakat Kabupaten Karanganyar.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-2.5">
                        <a href="https://karanganyarkab.bps.go.id" target="_blank" rel="noopener" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-colors flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-[#f7941d]" data-icon="lucide:globe"></span>
                            <span>Website BPS Karanganyar</span>
                        </a>
                        <a href="https://pst.bps.go.id" target="_blank" rel="noopener" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-colors flex items-center gap-1.5 shadow-xs">
                            <span class="iconify text-[#00a651]" data-icon="lucide:external-link"></span>
                            <span>Portal PST BPS RI</span>
                        </a>
                    </div>
                </div>

                {{-- Col 2: Layanan & Ekosistem PST --}}
                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-white font-black text-xs uppercase tracking-widest border-b border-white/10 pb-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#f7941d]"></span>
                        <span>Layanan PST & Digital</span>
                    </h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Beranda Data Makro</a></li>
                        <li><a href="{{ route('chat.index') }}" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Konsultasi PST & Chat AI</a></li>
                        <li><a href="https://pst.bps.go.id/layanan/pembelian" target="_blank" rel="noopener" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Data Mikro (Tarif Rp0)</a></li>
                        <li><a href="https://romantik.bps.go.id" target="_blank" rel="noopener" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Rekomendasi ROMANTIK</a></li>
                        <li><a href="https://webapi.bps.go.id/developer/" target="_blank" rel="noopener" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> WebAPI BPS Developer</a></li>
                        <li><a href="{{ route('districts.index') }}" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Data 17 Kecamatan</a></li>
                        <li><a href="{{ route('aduan.create') }}" class="hover:text-white font-medium transition-colors flex items-center gap-2"><span class="iconify text-[#f7941d]" data-icon="lucide:chevron-right"></span> Saluran Aduan & SP4N</a></li>
                    </ul>
                </div>

                {{-- Col 3: Kontak PST Karanganyar --}}
                <div class="md:col-span-4 space-y-3">
                    <h4 class="text-white font-black text-xs uppercase tracking-widest border-b border-white/10 pb-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#00a651]"></span>
                        <span>Kontak Pelayanan PST</span>
                    </h4>
                    <div class="space-y-2.5 text-xs sm:text-sm text-slate-300">
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                            <span class="iconify mt-0.5 shrink-0 text-[#f7941d] text-lg" data-icon="lucide:map-pin"></span>
                            <div>
                                <span class="font-bold text-white block">Kantor BPS Karanganyar:</span>
                                <span class="text-slate-300 text-xs leading-relaxed">Komplek Perkantoran Cangakan, Jl. Majapahit No. 11 B, Badran Asri, Bejen, Karanganyar 57712 (Rujukan: Jl. Lawu No. 202B)</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                            <span class="iconify shrink-0 text-[#00a651] text-lg" data-icon="lucide:phone"></span>
                            <div>
                                <span class="font-bold text-white block">Telepon Pelayanan:</span>
                                <span class="text-slate-300 text-xs">(0271) 495047 / (0271) 495035</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                            <span class="iconify shrink-0 text-[#00a651] text-lg" data-icon="lucide:message-circle"></span>
                            <div>
                                <span class="font-bold text-white block">WhatsApp PST Resmi:</span>
                                <a href="https://wa.me/6289605933133" target="_blank" rel="noopener" class="text-emerald-300 hover:underline text-xs font-semibold">0896-0593-3133</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                            <span class="iconify shrink-0 text-sky-400 text-lg" data-icon="lucide:mail"></span>
                            <div>
                                <span class="font-bold text-white block">Email Resmi:</span>
                                <span class="text-slate-300 text-xs">bps3313@bps.go.id</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom info --}}
            <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Karanganyar (MFD: 3313). Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-3">
                    <span class="text-slate-300">Standar Pelayanan Terpadu (PST)</span>
                    <span>•</span>
                    <span class="text-[#f7941d] font-bold">#DataMencerdaskanBangsa</span>
                    <span>•</span>
                    <span class="text-[#00a651] font-bold">WBK / WBBM</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Interactive Floating Chatbot Widget (di semua halaman publik kecuali /chat) --}}
    @if(!request()->routeIs('chat.*'))
        @include('components.floating-chat')
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

    {{-- Accessibility Menu Component --}}
    <x-accessibility-menu />

    {{-- Global Language Selector Modal (80+ Bahasa Google Translate) --}}
    <x-language-modal />

    @stack('scripts')
</body>
</html>
