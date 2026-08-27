<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Panel BPS Karanganyar</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bps.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50/80 font-sans antialiased text-slate-800 flex flex-col min-h-screen">
    <div class="min-h-screen flex flex-1">
        {{-- Modern Light Executive Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white text-slate-700 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-200 ease-in-out flex flex-col justify-between border-r border-slate-200/90 shadow-sm">
            <div>
                {{-- Logo BPS --}}
                <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 p-1.5 flex items-center justify-center border border-blue-100 shadow-xs">
                        <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-sm font-black text-slate-900 leading-tight">Panel Petugas BPS</h1>
                        <p class="text-[11px] text-blue-700 font-bold uppercase tracking-wider">Kabupaten Karanganyar</p>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="mt-4 px-3 space-y-1.5">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard*') ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <span class="iconify text-lg {{ request()->routeIs('admin.dashboard*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:layout-dashboard"></span>
                            <span>Dashboard Utama</span>
                        </div>
                    </a>

                    {{-- Percakapan / Live Chat --}}
                    <a href="{{ route('admin.conversations.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('admin.conversations*') ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <span class="iconify text-lg {{ request()->routeIs('admin.conversations*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:messages-square"></span>
                            <span>Percakapan Live</span>
                        </div>
                        <span id="badge-waiting" class="hidden px-2 py-0.5 text-[10px] font-black rounded-full bg-amber-100 text-amber-800 border border-amber-300">0</span>
                    </a>

                    {{-- Aduan Tiket --}}
                    <a href="{{ route('admin.complaints.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('admin.complaints*') ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <span class="iconify text-lg {{ request()->routeIs('admin.complaints*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:ticket"></span>
                            <span>Aduan Masuk</span>
                        </div>
                        <span id="badge-complaints" class="hidden px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-100 text-rose-800 border border-rose-300">0</span>
                    </a>

                    {{-- Laporan Rekapitulasi PDF --}}
                    <a href="{{ route('admin.reports.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ request()->routeIs('admin.reports*') ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3">
                            <span class="iconify text-lg {{ request()->routeIs('admin.reports*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:printer"></span>
                            <span>Laporan PDF</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-blue-100 text-blue-800">Cetak</span>
                    </a>

                    {{-- Basis Pengetahuan Submenu --}}
                    <div class="pt-3">
                        <p class="px-3.5 mb-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Basis Pengetahuan</p>
                        <a href="{{ route('admin.articles.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ request()->routeIs('admin.articles*') ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <span class="iconify text-base {{ request()->routeIs('admin.articles*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:file-text"></span>
                            <span>Artikel & FAQ</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ request()->routeIs('admin.categories*') ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <span class="iconify text-base {{ request()->routeIs('admin.categories*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:folder-tree"></span>
                            <span>Kategori Layanan</span>
                        </a>
                    </div>

                    {{-- Admin Only --}}
                    @if(auth()->user()->isAdmin())
                    <div class="pt-3">
                        <p class="px-3.5 mb-1.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Administrasi Sistem</p>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all {{ request()->routeIs('admin.users*') ? 'bg-blue-50 text-blue-700 font-bold border border-blue-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <span class="iconify text-base {{ request()->routeIs('admin.users*') ? 'text-blue-600' : 'text-slate-400' }}" data-icon="lucide:users"></span>
                            <span>Kelola Pengguna</span>
                        </a>
                    </div>
                    @endif
                </nav>
            </div>

            {{-- User bottom widget --}}
            <div class="p-4 border-t border-slate-100 bg-slate-50/70">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-blue-700 font-bold uppercase">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" id="form-logout">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Keluar">
                            <span class="iconify text-lg" data-icon="lucide:log-out"></span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Navbar --}}
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 lg:px-8 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="sidebar-toggle" class="lg:hidden p-2 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                        <span class="iconify text-xl text-slate-600" data-icon="lucide:menu"></span>
                    </button>
                    <div>
                        <h2 class="text-base font-black text-slate-900 tracking-tight">@yield('title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">Panel Layanan Informasi & Aduan BPS Karanganyar</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Clock / WIB indicator --}}
                    <div id="live-clock-badge" class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-mono text-slate-700">
                        <span class="iconify text-sm text-blue-600" data-icon="lucide:clock"></span>
                        <span id="live-clock-text">{{ now()->format('H:i:s') }} WIB</span>
                    </div>

                    {{-- Status Indicator --}}
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-emerald-800">Sistem Online</span>
                    </div>

                    {{-- Public Portal Link --}}
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-xl border border-slate-200 transition-colors shadow-xs">
                        <span class="iconify text-sm text-blue-600" data-icon="lucide:external-link"></span>
                        <span class="hidden sm:inline">Lihat Web Publik</span>
                    </a>
                </div>
            </header>

            {{-- Main content --}}
            <main class="flex-1 p-4 lg:p-8">
                @yield('content')
            </main>

            {{-- Admin Footer --}}
            <footer class="py-4 px-4 lg:px-8 border-t border-slate-200 bg-white text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Karanganyar • Sistem Layanan PST Terpadu
            </footer>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-20 hidden lg:hidden"></div>

    <script>
        // Sidebar Toggle for Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }

        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        // Realtime Clock
        function updateClock() {
            const clockEl = document.getElementById('live-clock-text');
            if (clockEl) {
                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${h}:${m}:${s} WIB`;
            }
        }
        setInterval(updateClock, 1000);

        // Confirm Logout
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sesi panel admin?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-logout').submit();
                }
            });
        }

        // Global SweetAlert Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif
        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
