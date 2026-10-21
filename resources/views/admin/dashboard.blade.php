@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif & Peta Wilayah Karanganyar')

@push('styles')
<style>
    /* 3D Map Perspective Styles */
    .map-perspective-stage {
        perspective: 1200px;
        perspective-origin: 50% 50%;
    }

    .map-3d-board {
        transform-style: preserve-3d;
        transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .map-3d-active {
        transform: rotateX(38deg) rotateZ(-10deg) scale(0.96);
    }

    .map-2d-active {
        transform: rotateX(0deg) rotateZ(0deg) scale(1);
    }

    /* Floating elevation effect */
    @keyframes floatMap {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    .floating-anim {
        animation: floatMap 5s ease-in-out infinite;
    }

    /* Radar Pulse Animation */
    @keyframes radarPulse {
        0% {
            r: 4;
            opacity: 0.9;
        }
        70% {
            r: 22;
            opacity: 0.15;
        }
        100% {
            r: 28;
            opacity: 0;
        }
    }

    .radar-ring {
        animation: radarPulse 2.8s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
        transform-origin: center;
    }

    /* Light Beam glow */
    @keyframes beamGlow {
        0%, 100% {
            opacity: 0.4;
            transform: scaleY(1);
        }
        50% {
            opacity: 0.85;
            transform: scaleY(1.15);
        }
    }

    .beam-glow {
        animation: beamGlow 2.5s ease-in-out infinite;
        transform-origin: bottom;
    }

    /* Grid terrain background */
    .grid-terrain {
        background-image: 
            radial-gradient(circle at 50% 50%, rgba(0, 91, 159, 0.15) 0%, transparent 70%),
            linear-gradient(rgba(241, 245, 249, 0.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(241, 245, 249, 0.6) 1px, transparent 1px);
        background-size: 100% 100%, 28px 28px, 28px 28px;
    }
</style>
@endpush

@section('content')
<div class="space-y-7">
    {{-- Top Executive Banner (BPS Navy & Gold Orange) --}}
    <div class="bg-gradient-to-r from-[#002b6a] via-[#004282] to-[#04325e] rounded-3xl p-6 sm:p-8 text-white border-b-4 border-[#f7941d] shadow-lg relative overflow-hidden">
        {{-- Ambient decorative circles --}}
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-blue-500/10 pointer-events-none blur-3xl"></div>
        <div class="absolute right-48 -bottom-16 w-48 h-48 rounded-full bg-amber-500/10 pointer-events-none blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Portal PST • BPS Kabupaten Karanganyar</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#f7941d]/20 text-[#f7941d] text-xs font-black border border-[#f7941d]/40">
                        <span class="iconify text-sm" data-icon="lucide:bot"></span>
                        <span>KARTA AI Aktif</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Pusat Kendali & Dashboard Statistik Terpadu
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 max-w-2xl leading-relaxed">
                    Selamat bertugas, <strong>{{ auth()->user()->name }}</strong>. Pantau metrik layanan publik, aktivitas chatbot <strong>KARTA</strong>, tiket aduan, serta distribusi spasial 17 kecamatan se-Kabupaten Karanganyar secara real-time.
                </p>
            </div>

            {{-- Quick Command Buttons --}}
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/20 flex items-center gap-2 shadow-xs">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:file-text"></span>
                    <span>Laporan PDF</span>
                </a>
                <a href="{{ route('admin.conversations.index', ['status' => 'waiting']) }}" class="px-4 py-2.5 rounded-xl bg-[#f7941d] hover:bg-[#e07e0c] text-white text-xs font-black transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="iconify text-base" data-icon="lucide:messages-square"></span>
                    <span>Antrean Chat ({{ $stats['conversations_waiting'] }})</span>
                </a>
                <a href="{{ route('chat.index') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white text-[#002b6a] hover:bg-blue-50 text-xs font-black transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:bot"></span>
                    <span>Uji Coba KARTA</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 5 High-Impact KPI Metrics Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
        {{-- 1. Chat Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 border-t-4 border-t-[#005b9f] p-4 sm:p-5 shadow-xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Chat KARTA</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#005b9f] flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:message-square-text"></span>
                </span>
            </div>
            <p id="stat-conversations-today" class="text-2xl font-black text-slate-900">{{ number_format($stats['conversations_today'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Sesi Masuk Hari Ini</p>
        </div>

        {{-- 2. Antrean Petugas --}}
        <a href="{{ route('admin.conversations.index', ['status' => 'waiting']) }}"
           class="bg-white rounded-2xl border border-slate-200/90 border-t-4 border-t-amber-500 p-4 sm:p-5 shadow-xs hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Antrean Petugas</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:clock"></span>
                </span>
            </div>
            <p id="stat-conversations-waiting" class="text-2xl font-black text-amber-600">{{ number_format($stats['conversations_waiting'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Menunggu Tindak Lanjut</p>
        </a>

        {{-- 3. Tiket Aduan Baru --}}
        <a href="{{ route('admin.complaints.index', ['status' => 'new']) }}"
           class="bg-white rounded-2xl border border-slate-200/90 border-t-4 border-t-rose-500 p-4 sm:p-5 shadow-xs hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Aduan Baru</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:ticket"></span>
                </span>
            </div>
            <p id="stat-complaints-new" class="text-2xl font-black text-rose-600">{{ number_format($stats['complaints_new'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Tiket Belum Ditangani</p>
        </a>

        {{-- 4. Publikasi BPS Karanganyar --}}
        <a href="{{ route('publications.index') }}" target="_blank"
           class="bg-white rounded-2xl border border-slate-200/90 border-t-4 border-t-[#00a651] p-4 sm:p-5 shadow-xs hover:shadow-md transition-all group block">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-[#00a651] uppercase tracking-wider">Publikasi BPS</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-[#00a651] flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:book-marked"></span>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ number_format($totalPublications, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Buku & Rilis Resmi 3313</p>
        </a>

        {{-- 5. Basis Pengetahuan (Knowledge Articles) --}}
        <a href="{{ route('admin.articles.index') }}"
           class="bg-white rounded-2xl border border-slate-200/90 border-t-4 border-t-indigo-500 p-4 sm:p-5 shadow-xs hover:shadow-md transition-all group block col-span-2 sm:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Basis Data FAQ</span>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="iconify text-base" data-icon="lucide:layers"></span>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ number_format($stats['total_articles'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Artikel Panduan Aktif</p>
        </a>
    </div>

    {{-- SECTION UTAMA: PETA 3D INTERAKTIF & TERANIMASI KABUPATEN KARANGANYAR --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        {{-- Map Header & Controls Toolbar --}}
        <div class="px-5 sm:px-7 py-4.5 bg-gradient-to-r from-slate-50 via-white to-blue-50/40 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#005b9f] animate-ping"></span>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Peta Visual Spasial 3D Kabupaten Karanganyar</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-[#005b9f] border border-blue-200">
                            17 Kecamatan
                        </span>
                    </h2>
                </div>
                <p class="text-xs text-slate-500">
                    Eksplorasi topografi wilayah dari lereng Gunung Lawu (Timur) ke dataran Colomadu (Barat) beserta indikator kependudukan.
                </p>
            </div>

            {{-- Toolbar Controls --}}
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                {{-- Indikator Filter --}}
                <div class="flex items-center rounded-xl bg-slate-100 p-1 border border-slate-200 text-xs font-bold">
                    <button type="button" onclick="setMapIndicator('density')" id="btn-ind-density" class="map-ind-btn px-2.5 py-1.5 rounded-lg bg-white text-[#005b9f] shadow-xs cursor-pointer transition-all">
                        Kepadatan
                    </button>
                    <button type="button" onclick="setMapIndicator('population')" id="btn-ind-pop" class="map-ind-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 cursor-pointer transition-all">
                        Penduduk
                    </button>
                    <button type="button" onclick="setMapIndicator('area')" id="btn-ind-area" class="map-ind-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 cursor-pointer transition-all">
                        Luas Wilayah
                    </button>
                </div>

                {{-- Toggle 3D / 2D View --}}
                <div class="flex items-center rounded-xl bg-slate-100 p-1 border border-slate-200 text-xs font-bold">
                    <button type="button" onclick="toggleMapPerspective(true)" id="btn-mode-3d" class="map-mode-btn px-2.5 py-1.5 rounded-lg bg-[#002b6a] text-white shadow-xs cursor-pointer transition-all flex items-center gap-1">
                        <span class="iconify text-sm" data-icon="lucide:box"></span>
                        <span>3D Tilt</span>
                    </button>
                    <button type="button" onclick="toggleMapPerspective(false)" id="btn-mode-2d" class="map-mode-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 cursor-pointer transition-all flex items-center gap-1">
                        <span class="iconify text-sm" data-icon="lucide:map"></span>
                        <span>2D Datar</span>
                    </button>
                </div>

                {{-- Reset Spotlight --}}
                <button type="button" onclick="resetDistrictSpotlight()" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all border border-slate-200 cursor-pointer" title="Reset Sorotan Wilayah">
                    <span class="iconify text-base" data-icon="lucide:refresh-cw"></span>
                </button>
            </div>
        </div>

        {{-- Map Main Body (Split: Interactive 3D Canvas + Live Detail Drawer) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[520px]">
            {{-- 3D Interactive Map Canvas (8 Kolom) --}}
            <div class="lg:col-span-8 p-4 sm:p-6 bg-gradient-to-b from-slate-900 via-[#07162c] to-[#041021] relative overflow-hidden flex items-center justify-center">
                {{-- Ambient mountain lighting & horizon glow --}}
                <div class="absolute inset-0 pointer-events-none opacity-20 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px]"></div>
                <div class="absolute right-0 top-0 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
                <div class="absolute left-0 bottom-0 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

                {{-- Compass & Altitude Indicator --}}
                <div class="absolute top-4 left-4 z-20 flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 text-white text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-blue-200">BARAT (SOLO)</span>
                    <span class="iconify text-sm text-slate-400" data-icon="lucide:arrow-right"></span>
                    <span class="text-[#f7941d]">TIMUR (GN. LAWU 3.265 m)</span>
                </div>

                {{-- Floating Legend Badge --}}
                <div class="absolute bottom-4 left-4 z-20 hidden sm:flex items-center gap-3 px-3.5 py-2 rounded-xl bg-slate-900/80 backdrop-blur-md border border-white/10 text-white text-[11px]">
                    <span class="text-slate-400 font-semibold" id="map-legend-title">Elevasi:</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-md bg-blue-500"></span>
                        <span class="text-slate-300">Rendah</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-md bg-emerald-500"></span>
                        <span class="text-slate-300">Menengah</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-md bg-[#f7941d]"></span>
                        <span class="text-slate-300">Tinggi</span>
                    </div>
                </div>

                {{-- 3D Stage Container --}}
                <div class="map-perspective-stage w-full max-w-[820px] aspect-[16/10] flex items-center justify-center py-4">
                    <div id="map-3d-board" class="map-3d-board map-3d-active w-full h-full relative floating-anim">
                        {{-- Base Grid Plane (Isometric Floor) --}}
                        <svg viewBox="0 0 1000 650" class="w-full h-full drop-shadow-[0_35px_45px_rgba(0,0,0,0.8)] filter">
                            <defs>
                                {{-- Gradients for Districts & Topography --}}
                                <linearGradient id="grid-plane-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#0a2540" stop-opacity="0.85" />
                                    <stop offset="60%" stop-color="#06182c" stop-opacity="0.95" />
                                    <stop offset="100%" stop-color="#020b14" stop-opacity="0.98" />
                                </linearGradient>

                                <linearGradient id="lawu-glow" x1="0%" y1="100%" x2="50%" y2="0%">
                                    <stop offset="0%" stop-color="#f7941d" stop-opacity="0.1" />
                                    <stop offset="60%" stop-color="#f59e0b" stop-opacity="0.6" />
                                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0.9" />
                                </linearGradient>

                                <linearGradient id="connector-line-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#00a651" stop-opacity="0.3" />
                                    <stop offset="100%" stop-color="#f7941d" stop-opacity="0.6" />
                                </linearGradient>

                                <filter id="glow-filter" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="4" result="blur" />
                                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                </filter>
                            </defs>

                            {{-- Base Island Ground Polygon Kabupaten Karanganyar --}}
                            <path d="M 120 280 
                                     L 240 180 
                                     L 380 140 
                                     L 580 130 
                                     L 760 110 
                                     L 880 150 
                                     L 960 270 
                                     L 940 400 
                                     L 860 480 
                                     L 780 570 
                                     L 630 590 
                                     L 480 610 
                                     L 380 570 
                                     L 340 460 
                                     L 270 360 
                                     Z" 
                                  fill="url(#grid-plane-grad)" 
                                  stroke="#005b9f" 
                                  stroke-width="3" 
                                  stroke-dasharray="8 4"
                                  class="transition-all" />

                            {{-- Inner Contour Elevation 1: Foothills Lawu --}}
                            <path d="M 520 180 
                                     L 680 160 
                                     L 840 180 
                                     L 910 290 
                                     L 880 430 
                                     L 750 510 
                                     L 600 450 
                                     L 550 320 
                                     Z" 
                                  fill="#0c3258" 
                                  fill-opacity="0.5" 
                                  stroke="#38bdf8" 
                                  stroke-width="1.5" 
                                  stroke-opacity="0.6" />

                            {{-- Inner Contour Elevation 2: High Mountain Summit Lawu (East side) --}}
                            <path d="M 720 230 
                                     L 850 200 
                                     L 920 290 
                                     L 880 390 
                                     L 760 380 
                                     Z" 
                                  fill="url(#lawu-glow)" 
                                  stroke="#f7941d" 
                                  stroke-width="2" 
                                  filter="url(#glow-filter)" />

                            {{-- Lawu Peak Marker (3.265 mdpl) --}}
                            <g transform="translate(865, 280)">
                                <polygon points="0,-24 16,10 -16,10" fill="#ffffff" stroke="#f7941d" stroke-width="2" />
                                <text x="0" y="-30" fill="#fcd34d" font-size="11" font-weight="900" text-anchor="middle" font-family="sans-serif">Puncak Lawu (3.265 m)</text>
                                <circle cx="0" cy="10" r="18" fill="none" stroke="#f7941d" stroke-width="1.5" class="radar-ring" />
                            </g>

                            {{-- Inter-District Spatial Connecting Network (Corridor Jalan Utama) --}}
                            <path d="M 120 280 L 310 310 L 420 310 L 520 330 L 630 320 L 740 270 L 820 360" fill="none" stroke="url(#connector-line-grad)" stroke-width="2" stroke-linecap="round" stroke-dasharray="6 4" opacity="0.6" />
                            <path d="M 520 330 L 540 220 L 670 180 L 800 150" fill="none" stroke="#00a651" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.4" />
                            <path d="M 520 330 L 620 410 L 740 510" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.4" />
                            <path d="M 520 330 L 480 450 L 500 530 L 410 580" fill="none" stroke="#f7941d" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.4" />

                            {{-- 17 Interactive Districts Nodes --}}
                            <g id="district-nodes-layer">
                                {{-- Dynamically generated SVG Nodes via Javascript for high precision & interactions --}}
                            </g>
                        </svg>

                        {{-- Floating Glassmorphism Tooltip --}}
                        <div id="map-3d-tooltip" class="hidden absolute z-30 pointer-events-none p-3 rounded-2xl bg-slate-900/90 text-white border border-blue-400/40 shadow-xl backdrop-blur-md text-xs space-y-1 transform -translate-x-1/2 -translate-y-full transition-all duration-150 min-w-[200px]">
                            <div class="flex items-center justify-between gap-2 border-b border-white/10 pb-1.5">
                                <h4 id="tooltip-title" class="font-black text-sm text-amber-300">Kecamatan</h4>
                                <span id="tooltip-code" class="text-[10px] font-mono text-blue-200">3313</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-1 text-[11px]">
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">Penduduk</span>
                                    <strong id="tooltip-pop" class="text-white font-bold">0</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[9.5px]">Kepadatan</span>
                                    <strong id="tooltip-density" class="text-emerald-400 font-bold">0 /km²</strong>
                                </div>
                            </div>
                            <div class="pt-1 text-[10px] text-blue-200 truncate">
                                Sektor: <span id="tooltip-sector" class="font-medium text-slate-100">Pertanian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- District Spotlight Detail Drawer (4 Kolom Samping) --}}
            <div class="lg:col-span-4 p-5 sm:p-6 bg-slate-50 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-200">
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 text-[#005b9f] flex items-center justify-center">
                                <span class="iconify text-base" data-icon="lucide:info"></span>
                            </span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Detail Kecamatan</span>
                        </div>
                        <span id="spotlight-badge" class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-[#002b6a] text-white">
                            MFD: 3313
                        </span>
                    </div>

                    {{-- Dynamic Active District Card --}}
                    <div id="spotlight-card" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-[#005b9f] uppercase tracking-wider block" id="spot-capital">Ibu kota: Karanganyar</span>
                                <h3 class="text-xl font-black text-slate-900" id="spot-name">Kecamatan Karanganyar</h3>
                            </div>
                            <span class="px-2 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200" id="spot-villages">
                                12 Desa/Kel
                            </span>
                        </div>

                        {{-- 3 Key Metrics --}}
                        <div class="grid grid-cols-3 gap-2 py-2.5 border-y border-slate-100 text-center">
                            <div class="p-1.5 rounded-lg bg-slate-50">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Penduduk</span>
                                <p class="text-xs font-black text-slate-800" id="spot-population">89.650</p>
                            </div>
                            <div class="p-1.5 rounded-lg bg-slate-50">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Luas Wilayah</span>
                                <p class="text-xs font-black text-slate-800" id="spot-area">43.03 km²</p>
                            </div>
                            <div class="p-1.5 rounded-lg bg-slate-50">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase">Kepadatan</span>
                                <p class="text-xs font-black text-[#005b9f]" id="spot-density">2.083 /km²</p>
                            </div>
                        </div>

                        {{-- Sektor Unggulan --}}
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-1">
                                <span class="iconify text-[#00a651]" data-icon="lucide:award"></span> Sektor Unggulan:
                            </span>
                            <p class="text-xs text-slate-700 bg-emerald-50/70 border border-emerald-200 p-2.5 rounded-xl font-medium" id="spot-sector">
                                Pusat perdagangan, industri pengolahan, dan jasa pemerintahan.
                            </p>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed" id="spot-desc">
                            Kawasan pusat pemerintahan dan jantung perekonomian Kabupaten Karanganyar dengan fasilitas perkantoran terpadu.
                        </p>
                    </div>

                    {{-- Quick District Dropdown Selector --}}
                    <div>
                        <label for="select-district-jump" class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Pilih Langsung Kecamatan:
                        </label>
                        <select id="select-district-jump" onchange="jumpToDistrict(this.value)" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-bold text-slate-800 outline-none focus:ring-2 focus:ring-[#005b9f]">
                            @foreach($districts as $d)
                            <option value="{{ $d->id }}" {{ $d->name === 'Karanganyar' ? 'selected' : '' }}>
                                Kecamatan {{ $d->name }} ({{ number_format($d->population, 0, ',', '.') }} Jiwa)
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Total: <strong>17 Kecamatan</strong></span>
                    <a href="{{ route('districts.index') }}" target="_blank" class="font-bold text-[#005b9f] hover:underline flex items-center gap-1">
                        <span>Lihat Profil Lengkap</span>
                        <span class="iconify text-sm" data-icon="lucide:external-link"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics Grid: Tren 7 Hari & Status Aduan + Status Bot KARTA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik Tren 7 Hari Terakhir (2 Kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:trending-up"></span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">Tren Aktivitas Layanan (7 Hari Terakhir)</h3>
                            <p class="text-[11px] text-slate-400">Volume percakapan chatbot KARTA vs tiket pengaduan masyarakat</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                            <span class="text-slate-600">Chat KARTA</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-slate-600">Tiket Aduan</span>
                        </div>
                    </div>
                </div>
                <div class="h-64 sm:h-72 w-full">
                    <canvas id="trendActivityChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="iconify text-[#00a651] text-sm" data-icon="lucide:check-circle-2"></span>
                    <span>AI Deep Research Terhubung ke 464 Publikasi Resmi</span>
                </span>
                <span class="font-mono text-[11px] text-slate-500">PST BPS Karanganyar</span>
            </div>
        </div>

        {{-- Donut Chart Aduan + Status KARTA Widget (1 Kolom) --}}
        <div class="space-y-6">
            {{-- Status Tiket Aduan --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="iconify text-base" data-icon="lucide:pie-chart"></span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Status Tiket Aduan</h3>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500">Total: {{ array_sum($complaintDistribution) }}</span>
                </div>

                <div class="h-44 w-full relative flex items-center justify-center">
                    <canvas id="complaintStatusChart"></canvas>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                    <div class="p-2 rounded-xl bg-red-50/80 border border-red-100">
                        <p class="text-[10px] font-bold text-red-700 uppercase">Baru</p>
                        <p class="text-sm font-black text-red-800 mt-0.5">{{ $complaintDistribution['new'] }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50/80 border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-700 uppercase">Proses</p>
                        <p class="text-sm font-black text-amber-800 mt-0.5">{{ $complaintDistribution['processing'] }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50/80 border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-700 uppercase">Selesai</p>
                        <p class="text-sm font-black text-emerald-800 mt-0.5">{{ $complaintDistribution['resolved'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Status Bot KARTA Card --}}
            <div class="bg-gradient-to-br from-blue-50/80 via-white to-amber-50/40 rounded-2xl border border-blue-200/70 p-4.5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <div class="w-12 h-12 rounded-2xl bg-white p-0.5 shadow-md overflow-hidden border-2 border-[#f7941d]">
                                <img src="{{ asset('images/karta-avatar.jpg') }}" alt="KARTA AI" class="w-full h-full object-cover object-top rounded-xl">
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white animate-pulse"></span>
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-slate-900 flex items-center gap-1.5">
                                <span>KARTA AI</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-[#f7941d] text-white">BPS 3313</span>
                            </h4>
                            <p class="text-[10.5px] text-slate-500 font-medium">Konsultan AI Riset & Terpadu Angka</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Aktif Melayani</span>
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                    <div class="p-2 rounded-xl bg-white border border-slate-200">
                        <span class="text-[9.5px] text-slate-400 block font-semibold">Tingkat Kepuasan</span>
                        <strong class="text-xs font-black text-[#005b9f]">{{ $satisfactionRate }}%</strong>
                        <span class="text-[9px] text-slate-400 block">({{ $feedbackHelpful }}/{{ $feedbackTotal }} ulasan)</span>
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-slate-200">
                        <span class="text-[9.5px] text-slate-400 block font-semibold">Model Inti AI</span>
                        <strong class="text-xs font-black text-slate-800">Gemini 3.6 Flash</strong>
                        <span class="text-[9px] text-emerald-600 block font-bold">Deep Research ON</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main 2 Column Grid: Live Chats KARTA & Recent Complaints --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Percakapan Live KARTA Terkini --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:messages-square"></span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Percakapan Live Terkini (KARTA)</h3>
                    </div>
                    <a href="{{ route('admin.conversations.index') }}" class="text-xs font-bold text-[#005b9f] hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentConversations as $rc)
                    @php
                    $lastMessage = $rc->messages->first();
                    $badge = [
                        'waiting' => 'bg-amber-100 text-amber-800 border-amber-300 animate-pulse',
                        'handled' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'bot' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'closed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    ];
                    @endphp
                    <a href="{{ route('admin.conversations.show', $rc) }}" class="flex items-start justify-between gap-3 p-3 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-slate-200/70 hover:border-blue-200 transition-all group">
                        <div class="truncate">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $rc->visitor_name }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badge[$rc->status] ?? '' }}">
                                    {{ $rc->status === 'waiting' ? 'Menunggu' : ($rc->status === 'handled' ? 'Petugas' : ($rc->status === 'closed' ? 'Selesai' : 'KARTA AI')) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $lastMessage ? $lastMessage->content : 'Sesi konsultasi dimulai' }}
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0 mt-0.5">
                            {{ $rc->last_message_at ? $rc->last_message_at->diffForHumans() : $rc->created_at->diffForHumans() }}
                        </span>
                    </a>
                    @empty
                    <p class="text-center py-8 text-xs text-slate-400">Belum ada percakapan masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Layanan Konsultasi Online Aktif 24 Jam</span>
                </span>
                <a href="{{ route('chat.index') }}" target="_blank" class="text-[#005b9f] font-bold hover:underline">
                    Buka Halaman Chat
                </a>
            </div>
        </div>

        {{-- Aduan Masuk Terkini --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:ticket"></span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Tiket Aduan Masuk Terkini</h3>
                    </div>
                    <a href="{{ route('admin.complaints.index') }}" class="text-xs font-bold text-[#005b9f] hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentComplaints as $comp)
                    @php
                    $compBadge = [
                        'new' => 'bg-red-50 text-red-700 border-red-200',
                        'verified' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-slate-100 text-slate-600 border-slate-200',
                    ];
                    $compLabel = [
                        'new' => 'Baru',
                        'verified' => 'Diverifikasi',
                        'processing' => 'Diproses',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ];
                    @endphp
                    <a href="{{ route('admin.complaints.show', $comp) }}" class="flex items-start justify-between gap-3 p-3 rounded-xl bg-slate-50 hover:bg-rose-50/40 border border-slate-200/70 hover:border-rose-200 transition-all group">
                        <div class="truncate">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono font-bold text-xs text-[#005b9f]">{{ $comp->ticket_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $compBadge[$comp->status] ?? '' }}">
                                    {{ $compLabel[$comp->status] ?? $comp->status }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-xs text-slate-800 truncate">{{ $comp->reporter_name }} • <span class="text-slate-500 font-normal capitalize">{{ $comp->category }}</span></h4>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ $comp->description }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0 mt-0.5">
                            {{ $comp->created_at->diffForHumans() }}
                        </span>
                    </a>
                    @empty
                    <p class="text-center py-8 text-xs text-slate-400">Belum ada aduan masuk.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="flex items-center gap-1.5">
                    <span class="iconify text-emerald-600 text-sm" data-icon="lucide:shield-check"></span>
                    <span>Tingkat Penyelesaian Aduan: <strong>{{ round(($complaintDistribution['resolved'] / max(1, array_sum($complaintDistribution))) * 100) }}%</strong></span>
                </span>
                <a href="{{ route('aduan.create') }}" target="_blank" class="text-[#005b9f] font-bold hover:underline">
                    Form Aduan Publik
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

{{-- Dashboard Data in JSON Script tag --}}
<script id="dashboard-chart-data" type="application/json">
{
    "chartLabels": @json($chartLabels),
    "chartConversations": @json($chartConversations),
    "chartComplaints": @json($chartComplaints),
    "complaintDistribution": @json($complaintDistribution),
    "statsUrl": @json(route('admin.dashboard.stats')),
    "districts": @json($districts)
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawData = document.getElementById('dashboard-chart-data');
    const dashboardData = rawData ? JSON.parse(rawData.textContent) : {};
    const districts = dashboardData.districts || [];

    // ==========================================
    // 1. DATA KOORDINAT GEOGRAFIS 17 KECAMATAN
    // ==========================================
    // Berdasarkan bentang geografis Kabupaten Karanganyar: Barat (Colomadu, Jaten) ke Timur (Lawu, Tawangmangu)
    const districtCoords = {
        'colomadu':    { x: 130, y: 280, elev: 120 },
        'gondangrejo': { x: 260, y: 170, elev: 150 },
        'kebakkramat': { x: 380, y: 180, elev: 160 },
        'jaten':       { x: 310, y: 310, elev: 140 },
        'tasikmadu':   { x: 420, y: 310, elev: 170 },
        'karanganyar': { x: 520, y: 330, elev: 210 },
        'mojogedang':  { x: 540, y: 220, elev: 280 },
        'kerjo':       { x: 670, y: 180, elev: 420 },
        'jenawi':      { x: 800, y: 150, elev: 680 },
        'ngargoyoso':  { x: 740, y: 270, elev: 950 },
        'karangpandan':{ x: 630, y: 320, elev: 580 },
        'matesih':     { x: 620, y: 420, elev: 430 },
        'tawangmangu': { x: 820, y: 360, elev: 1350 },
        'jumantono':   { x: 480, y: 450, elev: 250 },
        'jumapolo':    { x: 500, y: 530, elev: 310 },
        'jatipuro':    { x: 410, y: 580, elev: 290 },
        'jatiyoso':    { x: 740, y: 510, elev: 820 },
    };

    let currentIndicator = 'density'; // 'density' | 'population' | 'area'
    let is3DMode = true;
    let selectedDistrictId = districts.find(d => d.name === 'Karanganyar')?.id || districts[0]?.id;

    // Hitung max value untuk normalisasi ketinggian 3D tower
    function getMaxMetric(metric) {
        if (!districts.length) return 1;
        if (metric === 'density') return Math.max(...districts.map(d => d.density || 0));
        if (metric === 'population') return Math.max(...districts.map(d => d.population || 0));
        if (metric === 'area') return Math.max(...districts.map(d => parseFloat(d.area_sqkm) || 0));
        return 1;
    }

    // Render 17 Nodes ke SVG
    function renderMapNodes() {
        const layer = document.getElementById('district-nodes-layer');
        if (!layer) return;

        layer.innerHTML = '';
        const maxVal = getMaxMetric(currentIndicator);

        districts.forEach(d => {
            const key = d.name.toLowerCase().trim();
            const coord = districtCoords[key] || { x: 500, y: 300, elev: 200 };

            // Nilai metrik aktif
            let val = d.density;
            let valLabel = `${d.density.toLocaleString('id-ID')} /km²`;
            if (currentIndicator === 'population') {
                val = d.population;
                valLabel = `${d.population.toLocaleString('id-ID')} Jiwa`;
            } else if (currentIndicator === 'area') {
                val = parseFloat(d.area_sqkm);
                valLabel = `${val.toFixed(2)} km²`;
            }

            // Normalisasi ketinggian tiang tower (15px - 70px)
            const heightRatio = Math.max(0.15, Math.min(1.0, val / maxVal));
            const towerHeight = Math.round(18 + heightRatio * 52);

            // Warna berdasarkan rasio
            let color = '#38bdf8'; // Blue
            let ringColor = 'rgba(56, 189, 248, 0.4)';
            if (heightRatio > 0.65) {
                color = '#f7941d'; // Orange/Gold
                ringColor = 'rgba(247, 148, 29, 0.5)';
            } else if (heightRatio > 0.35) {
                color = '#00a651'; // Emerald Green
                ringColor = 'rgba(0, 166, 81, 0.4)';
            }

            const isSelected = d.id === selectedDistrictId;

            // Group Node
            const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            g.setAttribute('class', 'district-node cursor-pointer transition-all');
            g.setAttribute('data-id', d.id);
            g.setAttribute('transform', `translate(${coord.x}, ${coord.y})`);

            // Radar Pulse Ring
            const ring = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            ring.setAttribute('cx', '0');
            ring.setAttribute('cy', '0');
            ring.setAttribute('r', '14');
            ring.setAttribute('fill', 'none');
            ring.setAttribute('stroke', color);
            ring.setAttribute('stroke-width', '1.5');
            ring.setAttribute('class', 'radar-ring');
            g.appendChild(ring);

            // Base footprint disk
            const baseDisk = document.createElementNS('http://www.w3.org/2000/svg', 'ellipse');
            baseDisk.setAttribute('cx', '0');
            baseDisk.setAttribute('cy', '0');
            baseDisk.setAttribute('rx', '10');
            baseDisk.setAttribute('ry', '5');
            baseDisk.setAttribute('fill', color);
            baseDisk.setAttribute('fill-opacity', '0.4');
            g.appendChild(baseDisk);

            // 3D Tower Cylinder (Menjulang ke atas dalam perspektif isometrik)
            const tower = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            tower.setAttribute('x', '-3.5');
            tower.setAttribute('y', `-${towerHeight}`);
            tower.setAttribute('width', '7');
            tower.setAttribute('height', towerHeight);
            tower.setAttribute('rx', '3');
            tower.setAttribute('fill', color);
            tower.setAttribute('fill-opacity', isSelected ? '1' : '0.85');
            tower.setAttribute('filter', isSelected ? 'url(#glow-filter)' : 'none');
            g.appendChild(tower);

            // Top Floating Beacon Marker
            const beacon = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            beacon.setAttribute('cx', '0');
            beacon.setAttribute('cy', `-${towerHeight}`);
            beacon.setAttribute('r', isSelected ? '6' : '4.5');
            beacon.setAttribute('fill', '#ffffff');
            beacon.setAttribute('stroke', color);
            beacon.setAttribute('stroke-width', '2.5');
            g.appendChild(beacon);

            // District Label Text
            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', '0');
            text.setAttribute('y', `-${towerHeight + 8}`);
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('fill', isSelected ? '#fcd34d' : '#e2e8f0');
            text.setAttribute('font-size', isSelected ? '11' : '9.5');
            text.setAttribute('font-weight', isSelected ? '900' : '700');
            text.setAttribute('font-family', 'sans-serif');
            text.textContent = d.name;
            g.appendChild(text);

            // Hover & Click Events
            g.addEventListener('mouseenter', function (e) {
                showTooltip(d, coord.x, coord.y - towerHeight);
            });
            g.addEventListener('mouseleave', function () {
                hideTooltip();
            });
            g.addEventListener('click', function () {
                spotlightDistrict(d);
            });

            layer.appendChild(g);
        });
    }

    // Tooltip Interaksi
    const tooltip = document.getElementById('map-3d-tooltip');
    function showTooltip(d, x, y) {
        if (!tooltip) return;
        document.getElementById('tooltip-title').textContent = 'Kec. ' + d.name;
        document.getElementById('tooltip-code').textContent = 'MFD: ' + d.code;
        document.getElementById('tooltip-pop').textContent = d.population.toLocaleString('id-ID') + ' Jiwa';
        document.getElementById('tooltip-density').textContent = d.density.toLocaleString('id-ID') + ' /km²';
        document.getElementById('tooltip-sector').textContent = d.featured_sector ? d.featured_sector.substring(0, 45) + '...' : '-';

        // Posisikan tooltip
        tooltip.style.left = `${(x / 1000) * 100}%`;
        tooltip.style.top = `${(y / 650) * 100}%`;
        tooltip.classList.remove('hidden');
    }

    function hideTooltip() {
        if (tooltip) tooltip.classList.add('hidden');
    }

    // Spotlight District pada Drawer Samping
    window.spotlightDistrict = function (d) {
        selectedDistrictId = d.id;

        const nameEl = document.getElementById('spot-name');
        const capEl = document.getElementById('spot-capital');
        const badgeEl = document.getElementById('spotlight-badge');
        const vilEl = document.getElementById('spot-villages');
        const popEl = document.getElementById('spot-population');
        const areaEl = document.getElementById('spot-area');
        const denEl = document.getElementById('spot-density');
        const secEl = document.getElementById('spot-sector');
        const descEl = document.getElementById('spot-desc');
        const jumpSelect = document.getElementById('select-district-jump');

        if (nameEl) nameEl.textContent = 'Kecamatan ' + d.name;
        if (capEl) capEl.textContent = 'Ibu kota: ' + d.capital_name;
        if (badgeEl) badgeEl.textContent = 'KODE: ' + d.code;
        if (vilEl) vilEl.textContent = d.villages_count + ' Desa/Kel';
        if (popEl) popEl.textContent = d.population.toLocaleString('id-ID') + ' Jiwa';
        if (areaEl) areaEl.textContent = parseFloat(d.area_sqkm).toFixed(2) + ' km²';
        if (denEl) denEl.textContent = d.density.toLocaleString('id-ID') + ' /km²';
        if (secEl) secEl.textContent = d.featured_sector || 'Pertanian & perkebunan';
        if (descEl) descEl.textContent = d.description || 'Wilayah administratif Kabupaten Karanganyar.';
        if (jumpSelect) jumpSelect.value = d.id;

        // Re-render nodes agar node yang dipilih bersinar
        renderMapNodes();
    };

    window.jumpToDistrict = function (id) {
        const found = districts.find(d => d.id == id);
        if (found) {
            spotlightDistrict(found);
        }
    };

    window.resetDistrictSpotlight = function () {
        const found = districts.find(d => d.name === 'Karanganyar') || districts[0];
        if (found) spotlightDistrict(found);
    };

    // Toggle Indikator Filter
    window.setMapIndicator = function (ind) {
        currentIndicator = ind;

        const btnDensity = document.getElementById('btn-ind-density');
        const btnPop = document.getElementById('btn-ind-pop');
        const btnArea = document.getElementById('btn-ind-area');
        const legTitle = document.getElementById('map-legend-title');

        [btnDensity, btnPop, btnArea].forEach(b => {
            b?.classList.remove('bg-white', 'text-[#005b9f]', 'shadow-xs');
            b?.classList.add('text-slate-600');
        });

        if (ind === 'density') {
            btnDensity?.classList.add('bg-white', 'text-[#005b9f]', 'shadow-xs');
            btnDensity?.classList.remove('text-slate-600');
            if (legTitle) legTitle.textContent = 'Kepadatan:';
        } else if (ind === 'population') {
            btnPop?.classList.add('bg-white', 'text-[#005b9f]', 'shadow-xs');
            btnPop?.classList.remove('text-slate-600');
            if (legTitle) legTitle.textContent = 'Penduduk:';
        } else if (ind === 'area') {
            btnArea?.classList.add('bg-white', 'text-[#005b9f]', 'shadow-xs');
            btnArea?.classList.remove('text-slate-600');
            if (legTitle) legTitle.textContent = 'Luas Wilayah:';
        }

        renderMapNodes();
    };

    // Toggle 3D vs 2D Perspective
    window.toggleMapPerspective = function (to3D) {
        is3DMode = to3D;
        const board = document.getElementById('map-3d-board');
        const btn3d = document.getElementById('btn-mode-3d');
        const btn2d = document.getElementById('btn-mode-2d');

        if (!board) return;

        if (to3D) {
            board.classList.remove('map-2d-active');
            board.classList.add('map-3d-active', 'floating-anim');
            btn3d?.classList.add('bg-[#002b6a]', 'text-white', 'shadow-xs');
            btn3d?.classList.remove('text-slate-600');
            btn2d?.classList.remove('bg-[#002b6a]', 'text-white', 'shadow-xs');
            btn2d?.classList.add('text-slate-600');
        } else {
            board.classList.remove('map-3d-active', 'floating-anim');
            board.classList.add('map-2d-active');
            btn2d?.classList.add('bg-[#002b6a]', 'text-white', 'shadow-xs');
            btn2d?.classList.remove('text-slate-600');
            btn3d?.classList.remove('bg-[#002b6a]', 'text-white', 'shadow-xs');
            btn3d?.classList.add('text-slate-600');
        }
    };

    // Initial Render Map
    renderMapNodes();
    const initialDistrict = districts.find(d => d.name === 'Karanganyar') || districts[0];
    if (initialDistrict) spotlightDistrict(initialDistrict);

    // ==========================================
    // 2. GRAFIK ANALITIK CHART.JS
    // ==========================================
    // 2.1 Line Chart: Tren Aktivitas 7 Hari
    const trendCanvas = document.getElementById('trendActivityChart');
    if (trendCanvas) {
        const trendCtx = trendCanvas.getContext('2d');
        const blueGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        blueGradient.addColorStop(0, 'rgba(0, 91, 159, 0.3)');
        blueGradient.addColorStop(1, 'rgba(0, 91, 159, 0.0)');

        const amberGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        amberGradient.addColorStop(0, 'rgba(247, 148, 29, 0.3)');
        amberGradient.addColorStop(1, 'rgba(247, 148, 29, 0.0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: dashboardData.chartLabels || [],
                datasets: [
                    {
                        label: 'Chat KARTA',
                        data: dashboardData.chartConversations || [],
                        borderColor: '#005b9f',
                        backgroundColor: blueGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#005b9f',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Tiket Aduan',
                        data: dashboardData.chartComplaints || [],
                        borderColor: '#f7941d',
                        backgroundColor: amberGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f7941d',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#002b6a',
                        titleFont: { size: 12, family: 'Inter, sans-serif' },
                        bodyFont: { size: 12, family: 'Inter, sans-serif' },
                        padding: 10,
                        cornerRadius: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11, family: 'Inter' },
                            color: '#94a3b8'
                        },
                        grid: { color: '#f1f5f9' },
                        border: { dash: [4, 4] }
                    },
                    x: {
                        ticks: {
                            font: { size: 11, family: 'Inter' },
                            color: '#94a3b8'
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2.2 Doughnut Chart: Distribusi Aduan
    const statusCanvas = document.getElementById('complaintStatusChart');
    if (statusCanvas) {
        const statusCtx = statusCanvas.getContext('2d');
        const dist = dashboardData.complaintDistribution || { new: 0, processing: 0, resolved: 0 };
        const newCount = Number(dist.new) || 0;
        const procCount = Number(dist.processing) || 0;
        const resCount = Number(dist.resolved) || 0;
        const totalAduan = newCount + procCount + resCount;

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Baru', 'Diproses', 'Selesai'],
                datasets: [{
                    data: totalAduan > 0 ? [newCount, procCount, resCount] : [1, 0, 0],
                    backgroundColor: totalAduan > 0 ? ['#ef4444', '#f59e0b', '#10b981'] : ['#e2e8f0', '#e2e8f0', '#e2e8f0'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11, family: 'Inter' },
                            boxWidth: 10,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        enabled: totalAduan > 0,
                        backgroundColor: '#002b6a',
                        padding: 8,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // Real-time counter updater on dashboard
    if (dashboardData.statsUrl) {
        function updateDashboardStats() {
            fetch(dashboardData.statsUrl)
                .then(r => r.json())
                .then(data => {
                    const todayEl = document.getElementById('stat-conversations-today');
                    const waitEl = document.getElementById('stat-conversations-waiting');
                    const newCompEl = document.getElementById('stat-complaints-new');

                    if (todayEl && data.conversations_today !== undefined) todayEl.textContent = data.conversations_today.toLocaleString('id-ID');
                    if (waitEl && data.conversations_waiting !== undefined) waitEl.textContent = data.conversations_waiting.toLocaleString('id-ID');
                    if (newCompEl && data.complaints_new !== undefined) newCompEl.textContent = data.complaints_new.toLocaleString('id-ID');
                })
                .catch(() => {});
        }
        setInterval(updateDashboardStats, 6000);
    }
});
</script>
@endpush
