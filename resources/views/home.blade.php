@extends('layouts.public')

@section('title', 'Portal Pelayanan Statistik Terpadu')
@section('meta_description', 'Portal resmi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar. Akses indikator strategis 2026, data mikro tarif Rp0,- mahasiswa, rekomendasi ROMANTIK, WebAPI developer, dan konsultasi data online.')

@section('content')
{{-- HERO SECTION: Official Prestigious BPS Gateway --}}
<section class="relative bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white pt-10 pb-20 lg:pt-14 lg:pb-28 overflow-hidden border-b-4 border-[#f7941d]">
    {{-- Institutional subtle pattern --}}
    <div class="absolute inset-0 pointer-events-none opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px]"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto space-y-4 sm:space-y-5">
            {{-- Official Agency Badge --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-slate-100 text-xs font-black shadow-sm">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-4 h-4 object-contain">
                <span>BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR</span>
            </div>

            {{-- Main Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Pelayanan Statistik Terpadu
            </h1>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-400/20 text-amber-200 border border-amber-400/30 text-xs font-bold">
                <div class="w-5 h-5 rounded-full overflow-hidden border border-amber-300 shrink-0">
                    <img src="{{ asset('images/karta-avatar.jpg') }}" alt="KARTA AI" class="w-full h-full object-cover object-top">
                </div>
                <span>Didukung KARTA: Konsultan AI Riset & Terpadu Angka BPS Karanganyar</span>
            </div>

            <p class="text-xs sm:text-sm lg:text-base text-blue-100 leading-relaxed font-normal max-w-2xl mx-auto">
                Layanan satu pintu penyediaan data statistik berkualitas, konsultasi statistik cerdas, permohonan data mikro (PP 86/2021), rekomendasi survei (ROMANTIK), dan katalog 460+ publikasi resmi.
            </p>

            <div class="inline-flex items-center gap-2 text-xs font-bold text-amber-300">
                <span class="iconify text-sm" data-icon="lucide:heart"></span>
                <span>Siap Melayani dengan Profesional, Integritas, dan Amanah #MelayaniDenganHati</span>
            </div>

            {{-- Official Prominent Search Bar --}}
            <div class="pt-2 max-w-2xl mx-auto">
                <form action="{{ route('chat.index') }}" method="GET" class="relative flex items-center bg-white rounded-2xl p-1.5 sm:p-2 shadow-2xl border border-slate-200">
                    <div class="pl-3.5 pr-2 text-slate-400">
                        <span class="iconify text-xl text-[#003c80]" data-icon="lucide:search"></span>
                    </div>
                    <input type="text"
                           name="q"
                           placeholder="Tanyakan ke KARTA: data jalan, kemiskinan, IPM, data mikro, publikasi..."
                           class="w-full py-2.5 px-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-transparent outline-none font-medium">
                    <button type="submit"
                            class="px-5 sm:px-7 py-2.5 sm:py-3 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-xs sm:text-sm font-black rounded-xl transition-all shadow-md shadow-orange-500/20 shrink-0 flex items-center gap-1.5 cursor-pointer">
                        <span>Tanya KARTA</span>
                        <span class="iconify text-base" data-icon="lucide:arrow-right"></span>
                    </button>
                </form>

                {{-- Search Shortcuts --}}
                <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mt-3 text-[11px] text-blue-100 font-medium">
                    <span class="text-blue-200 text-[10px] uppercase font-bold tracking-wider mr-1">Subjek Resmi BPS:</span>
                    <a href="{{ route('chat.index', ['q' => 'Data statistik Kependudukan dan Migrasi Kabupaten Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Kependudukan & Migrasi (519)
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data statistik Pertanian, Kehutanan, Perikanan dan produksi padi Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Pertanian, Kehutanan, Perikanan
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data statistik Transportasi dan kondisi panjang jalan Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Transportasi & Jalan
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data statistik kemiskinan dan garis kemiskinan Karanganyar 7,92%']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Kemiskinan 7,92%
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data Neraca Ekonomi PDRB Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Neraca PDRB
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data Tenaga Kerja dan Tingkat Pengangguran Terbuka TPT Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Tenaga Kerja
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Syarat permohonan data mikro skripsi tarif Rp0']) }}" class="px-2.5 py-1 rounded-lg bg-amber-400/20 hover:bg-amber-400/30 text-amber-200 border border-amber-400/30 transition-colors">
                        Data Mikro Tarif Rp0
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Apa itu ROMANTIK dan cara pengajuan survei OPD']) }}" class="px-2.5 py-1 rounded-lg bg-emerald-400/20 hover:bg-emerald-400/30 text-emerald-200 border border-emerald-400/30 transition-colors">
                        ROMANTIK OPD
                    </a>
                    <a href="{{ route('districts.index') }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        17 Kecamatan
                    </a>
                </div>
            </div>

            {{-- Fast Navigation Actions --}}
            <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('chat.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-[#003c80] hover:bg-blue-50 text-xs font-black shadow-md transition-all flex items-center gap-2">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:message-square-text"></span>
                    <span>Konsultasi Data PST Online</span>
                </a>
                <a href="https://pst.bps.go.id" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
                    <span class="iconify text-base text-emerald-400" data-icon="lucide:external-link"></span>
                    <span>Portal PST BPS RI</span>
                </a>
                <a href="{{ route('districts.index') }}" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
                    <span class="iconify text-base text-sky-300" data-icon="lucide:map"></span>
                    <span>Profil 17 Kecamatan</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- INDIKATOR MAKRO STRATEGIS KARANGANYAR 2026 --}}
<section class="relative -mt-10 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @php
        $macroStats = [
            [
                'label' => 'Kemiskinan (P0)',
                'value' => '7,92%',
                'unit' => '',
                'sub' => '72,40 Ribu Jiwa',
                'source' => 'Susenas 2026',
                'icon' => 'lucide:trending-down',
                'color' => 'text-[#ea580c]',
                'badge' => 'bg-orange-50 text-[#ea580c]',
            ],
            [
                'label' => 'Indeks IPM',
                'value' => '78,15',
                'unit' => '',
                'sub' => 'Status: TINGGI',
                'source' => 'AHH: 78,12 Thn',
                'icon' => 'lucide:award',
                'color' => 'text-[#003c80]',
                'badge' => 'bg-blue-50 text-[#003c80]',
            ],
            [
                'label' => 'Pertumbuhan PDRB',
                'value' => '5,68%',
                'unit' => '',
                'sub' => 'Harga Konstan',
                'source' => 'ADHB Rp 44,8 T',
                'icon' => 'lucide:trending-up',
                'color' => 'text-[#00a651]',
                'badge' => 'bg-emerald-50 text-[#00a651]',
            ],
            [
                'label' => 'Jumlah Penduduk',
                'value' => '962.480',
                'unit' => 'Jiwa',
                'sub' => '17 Kecamatan',
                'source' => 'KDA Bab 3',
                'icon' => 'lucide:users',
                'color' => 'text-slate-900',
                'badge' => 'bg-slate-100 text-slate-800',
            ],
            [
                'label' => 'Pengangguran TPT',
                'value' => '4,85%',
                'unit' => '',
                'sub' => 'TPAK: 72,40%',
                'source' => 'Sakernas 2026',
                'icon' => 'lucide:briefcase',
                'color' => 'text-indigo-600',
                'badge' => 'bg-indigo-50 text-indigo-700',
            ],
            [
                'label' => 'Inflasi Tahunan',
                'value' => '2,82%',
                'unit' => '',
                'sub' => 'IHK: 125,85',
                'source' => 'SBH Karanganyar',
                'icon' => 'lucide:percent',
                'color' => 'text-teal-600',
                'badge' => 'bg-teal-50 text-teal-700',
            ],
        ];
        @endphp

        @foreach($macroStats as $ms)
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider truncate">{{ $ms['label'] }}</span>
                    <div class="w-7 h-7 rounded-lg {{ $ms['badge'] }} flex items-center justify-center shrink-0">
                        <span class="iconify text-sm" data-icon="{{ $ms['icon'] }}"></span>
                    </div>
                </div>
                <div class="flex items-baseline gap-0.5">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $ms['value'] }}</span>
                    @if($ms['unit'])<span class="text-[10px] font-bold text-slate-500">{{ $ms['unit'] }}</span>@endif
                </div>
                <p class="text-[11px] text-slate-600 font-semibold mt-0.5 truncate">{{ $ms['sub'] }}</p>
            </div>
            <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400 font-medium">
                <span class="truncate">{{ $ms['source'] }}</span>
                <span class="iconify text-emerald-600 shrink-0" data-icon="lucide:check-circle-2"></span>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- 4 PILAR DISEMINASI STATISTIK RESMI BPS --}}
<section class="py-14 bg-white border-b border-slate-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-black text-[#003c80] uppercase tracking-widest bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200">
                Pilar Diseminasi Data
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-3 tracking-tight">
                Empat Pilar Produk Diseminasi BPS
            </h2>
            <p class="mt-2 text-slate-600 text-xs sm:text-sm">
                Standar penyebarluasan informasi statistik resmi BPS Kabupaten Karanganyar bagi perencana, peneliti, dan masyarakat umum.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-[#003c80] transition-all group">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#003c80] flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                    <span class="iconify text-xl" data-icon="lucide:table"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-[#003c80] transition-colors">Tabel Dinamis</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">Query builder interaktif untuk merancang tabel statistik kustom sesuai indikator dan tahun.</p>
                <a href="https://karanganyarkab.bps.go.id/id/statistics-table?subject=519&sortBy=date%2Ctitle&sortOrder=desc%2Casc" target="_blank" rel="noopener" class="text-xs font-extrabold text-[#003c80] hover:text-[#f7941d] flex items-center gap-1">
                    <span>Buka Tabel Statistik</span>
                    <span class="iconify" data-icon="lucide:arrow-right"></span>
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-[#003c80] transition-all group">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-[#f7941d] flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                    <span class="iconify text-xl" data-icon="lucide:book-open"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-[#003c80] transition-colors">Publikasi Resmi PDF</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">Buku kompendium tahunan Karanganyar Dalam Angka (KDA) dan 17 Buku Kecamatan Dalam Angka.</p>
                <a href="https://karanganyarkab.bps.go.id/id/publication" target="_blank" rel="noopener" class="text-xs font-extrabold text-[#003c80] hover:text-[#f7941d] flex items-center gap-1">
                    <span>Katalog Publikasi</span>
                    <span class="iconify" data-icon="lucide:arrow-right"></span>
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-[#003c80] transition-all group">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#00a651] flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                    <span class="iconify text-xl" data-icon="lucide:file-text"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-[#003c80] transition-colors">Berita Resmi Statistik</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">Rilis berkala indikator inflasi, pertumbuhan ekonomi PDRB, kemiskinan, dan ketenagakerjaan.</p>
                <a href="https://karanganyarkab.bps.go.id/id/pressrelease" target="_blank" rel="noopener" class="text-xs font-extrabold text-[#003c80] hover:text-[#f7941d] flex items-center gap-1">
                    <span>Lihat Rilis BRS</span>
                    <span class="iconify" data-icon="lucide:arrow-right"></span>
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-[#003c80] transition-all group">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                    <span class="iconify text-xl" data-icon="lucide:pie-chart"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-[#003c80] transition-colors">Infografik Tematik</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">Visualisasi data statistik ramah publik yang ringkas dan komunikatif untuk masyarakat luas.</p>
                <a href="https://karanganyarkab.bps.go.id/id/infographic" target="_blank" rel="noopener" class="text-xs font-extrabold text-[#003c80] hover:text-[#f7941d] flex items-center gap-1">
                    <span>Lihat Infografik</span>
                    <span class="iconify" data-icon="lucide:arrow-right"></span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- PUBLIKASI RESMI BPS KABUPATEN KARANGANYAR (DOMAIN 3313) --}}
<section class="py-14 bg-gradient-to-b from-slate-50 to-white border-b border-slate-200" id="publikasi-resmi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[#003c80] text-xs font-black mb-3">
                    <span class="iconify text-base text-[#00a651]" data-icon="lucide:book-check"></span>
                    <span>Katalog Publikasi Resmi BPS (Domain 3313)</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Publikasi Statistik Resmi Karanganyar
                </h2>
                <p class="mt-2 text-slate-600 text-xs sm:text-sm max-w-2xl">
                    Koleksi lengkap buku publikasi tahunan, seri 17 Kecamatan Dalam Angka, dan statistik tematik resmi BPS Kabupaten Karanganyar. Unduh berkas PDF asli bebas tarif Rp0,-.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('publications.index') }}" class="px-5 py-2.5 rounded-xl bg-[#003c80] hover:bg-[#002b6a] text-white text-xs font-black shadow-md transition-all flex items-center gap-2">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:library"></span>
                    <span>Katalog Lengkap ({{ $publicationStats['total'] ?? 464 }} Buku)</span>
                    <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                </a>
            </div>
        </div>

        {{-- Featured Highlights Row (KDA & Sektoral Utama) --}}
        @if(isset($featuredPublications) && $featuredPublications->count() > 0)
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-2 h-2 rounded-full bg-[#f7941d]"></span>
                <span class="text-xs font-black uppercase text-slate-700 tracking-wider">Publikasi Kompendium Unggulan:</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($featuredPublications->take(3) as $fPub)
                <div class="bg-gradient-to-br from-[#002b6a] to-[#003c80] rounded-2xl p-5 text-white flex flex-col justify-between shadow-md hover:shadow-xl transition-all border-l-4 border-[#f7941d] group">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-3">
                            <span class="px-2 py-0.5 rounded bg-white/20 text-blue-100 text-[10px] font-bold">
                                {{ $fPub->rl_date ? $fPub->rl_date->translatedFormat('d F Y') : 'Terbit Resmi' }}
                            </span>
                            @if($fPub->size)
                            <span class="px-2 py-0.5 rounded bg-amber-400 text-slate-900 text-[10px] font-black">
                                {{ $fPub->size }}
                            </span>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-sm sm:text-base leading-snug group-hover:text-amber-300 transition-colors line-clamp-2">
                            {{ $fPub->title }}
                        </h3>
                        @if($fPub->issn)
                        <p class="text-[11px] text-blue-200 mt-1 font-mono">ISSN: {{ $fPub->issn }}</p>
                        @endif
                        @if($fPub->abstract)
                        <p class="text-xs text-blue-100/90 mt-2 line-clamp-2 leading-relaxed">
                            {{ Str::limit($fPub->abstract, 110) }}
                        </p>
                        @endif
                    </div>
                    <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between gap-2">
                        @if($fPub->pdf_url)
                        <a href="{{ $fPub->pdf_url }}" target="_blank" rel="noopener" class="flex-1 py-2 px-3 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-xs font-black rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5">
                            <span class="iconify text-base" data-icon="lucide:download"></span>
                            <span>Unduh PDF</span>
                        </a>
                        @endif
                        <a href="{{ route('chat.index', ['q' => 'Jelaskan isi pokok data dari publikasi ' . $fPub->title]) }}" class="py-2 px-3 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1">
                            <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:bot"></span>
                            <span>Tanya AI</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid Publikasi Terbaru --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($latestPublications ?? [] as $pub)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-lg hover:border-[#003c80] transition-all flex flex-col justify-between overflow-hidden group">
                {{-- Cover Image --}}
                <div class="relative bg-slate-100 aspect-[3/4] overflow-hidden flex items-center justify-center p-3 border-b border-slate-100">
                    @if($pub->cover_url)
                        <img src="{{ $pub->cover_url }}"
                             alt="Cover {{ $pub->title }}"
                             loading="lazy"
                             class="max-h-full max-w-full object-contain rounded-md shadow-xs group-hover:scale-105 transition-transform duration-300"
                             onerror="this.onerror=null; this.src='https://web-api.bps.go.id/images/default.png';">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-blue-50/50 text-[#003c80] p-4 text-center">
                            <span class="iconify text-3xl mb-1 text-[#003c80]/40" data-icon="lucide:book-open"></span>
                            <span class="text-[10px] font-bold">BPS Karanganyar</span>
                        </div>
                    @endif

                    <div class="absolute top-2 left-2">
                        <span class="px-2 py-0.5 rounded-md bg-[#003c80]/90 text-white text-[9px] font-black shadow-xs">
                            {{ $pub->rl_date ? $pub->rl_date->translatedFormat('d M Y') : 'Terbit' }}
                        </span>
                    </div>

                    @if($pub->size)
                    <div class="absolute top-2 right-2">
                        <span class="px-1.5 py-0.5 rounded bg-white text-slate-700 text-[9px] font-extrabold border border-slate-200 shadow-xs">
                            {{ $pub->size }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Content & Actions --}}
                <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between space-y-2.5">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm leading-snug group-hover:text-[#003c80] transition-colors line-clamp-2" title="{{ $pub->title }}">
                            {{ $pub->title }}
                        </h4>
                        @if($pub->issn)
                        <p class="text-[10px] text-slate-500 mt-1">ISSN: <span class="font-mono">{{ $pub->issn }}</span></p>
                        @endif
                    </div>

                    <div class="pt-2.5 border-t border-slate-100 flex items-center gap-1.5">
                        @if($pub->pdf_url)
                        <a href="{{ $pub->pdf_url }}"
                           target="_blank"
                           rel="noopener"
                           class="flex-1 py-1.5 px-2.5 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-[11px] font-black rounded-xl transition-all shadow-xs flex items-center justify-center gap-1">
                            <span class="iconify text-sm" data-icon="lucide:download"></span>
                            <span>Unduh</span>
                        </a>
                        @endif
                        <a href="{{ route('chat.index', ['q' => 'Apa isi data penting dari publikasi ' . $pub->title . '?']) }}"
                           class="p-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#003c80] border border-blue-200 transition-colors"
                           title="Konsultasikan ke Bot AI">
                            <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:bot"></span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bottom CTA Strip --}}
        <div class="mt-8 p-4 rounded-2xl bg-white border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#00a651] border border-emerald-200 flex items-center justify-center shrink-0">
                    <span class="iconify text-xl" data-icon="lucide:database"></span>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900">Mencari Publikasi Kecamatan Tertentu atau Arsip Lama?</h4>
                    <p class="text-[11px] text-slate-500">Tersedia {{ $publicationStats['total'] ?? 464 }} judul buku statistik resmi mulai tahun 2016 hingga 2026.</p>
                </div>
            </div>
            <a href="{{ route('publications.index') }}" class="w-full sm:w-auto px-5 py-2 bg-[#003c80] hover:bg-[#002b6a] text-white text-xs font-black rounded-xl transition-all shadow-xs shrink-0 text-center">
                Buka Seluruh Katalog Publikasi →
            </a>
        </div>
    </div>
</section>

{{-- KATALOG TAKSONOMI 37 SUBJEK STATISTIK RESMI BPS --}}
<section class="py-14 bg-slate-50/80 border-b border-slate-200" id="subjek-bps">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <span class="text-xs font-black text-[#005b9f] uppercase tracking-widest bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200">
                Taksonomi Resmi Portal BPS
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-3 tracking-tight">
                Katalog 3 Kategori & 37 Subjek Statistik BPS
            </h2>
            <p class="mt-2 text-slate-600 text-xs sm:text-sm">
                Struktur klasifikasi data statistik resmi Badan Pusat Statistik (BPS) Karanganyar untuk mempermudah pencarian tabel, publikasi, dan konsultasi data.
            </p>
        </div>

        @php
            $bpsTaxonomy = \App\Services\KnowledgeSearchService::getBpsCategories();
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($bpsTaxonomy as $key => $cat)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:border-slate-300 transition-all">
                <div>
                    {{-- Header Kategori --}}
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center border {{ $cat['badge'] }}">
                            <span class="iconify text-xl" data-icon="{{ $cat['icon'] }}"></span>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-snug">{{ $cat['name'] }}</h3>
                            <p class="text-[11px] font-semibold text-slate-400">{{ count($cat['subjects']) }} Subjek Statistik</p>
                        </div>
                    </div>

                    {{-- Daftar Subjek --}}
                    <ul class="space-y-1.5 text-xs">
                        @foreach($cat['subjects'] as $sKey => $sub)
                        <li class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition-colors group">
                            <a href="{{ route('chat.index', ['q' => 'Data statistik resmi subjek ' . $sub['name'] . ' Kabupaten Karanganyar']) }}"
                               class="text-slate-700 hover:text-[#005b9f] font-medium flex items-center gap-2 flex-1 pr-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-[#005b9f] transition-colors"></span>
                                <span class="line-clamp-1">{{ $sub['name'] }}</span>
                                @if(isset($sub['code']))
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-700 shrink-0">Subjek {{ $sub['code'] }}</span>
                                @endif
                            </a>
                            <a href="{{ $sub['url'] }}" target="_blank" rel="noopener"
                               class="text-slate-400 hover:text-[#005b9f] p-1 rounded-lg hover:bg-blue-50 transition-colors shrink-0"
                               title="Buka Tabel Statistik di Portal BPS Karanganyar">
                                <span class="iconify text-xs" data-icon="lucide:external-link"></span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Footer Action --}}
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">Portal BPS Karanganyar</span>
                    <a href="{{ route('chat.index', ['q' => 'Konsultasi statistik kategori ' . $cat['name']]) }}"
                       class="text-xs font-bold text-[#005b9f] hover:text-[#04325e] flex items-center gap-1">
                        <span>Konsultasi Topik Ini</span>
                        <span class="iconify text-xs" data-icon="lucide:chevron-right"></span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- EKOSISTEM PELAYANAN STATISTIK TERPADU (PST BPS RI & DAERAH) --}}
<section class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-black text-[#f7941d] uppercase tracking-widest bg-orange-50 px-3.5 py-1.5 rounded-full border border-orange-200">
                Layanan Terpadu Satu Atap
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                Ekosistem Pelayanan Statistik Terpadu (PST)
            </h2>
            <p class="mt-2 text-slate-600 text-xs sm:text-sm">
                Sistem terintegrasi standar PST BPS RI (pst.bps.go.id) untuk memenuhi kebutuhan data publik, peneliti, OPD, dan pengembang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Card 1: Konsultasi Data Online --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#003c80] border border-blue-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:message-square-text"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-blue-50 text-[#003c80] border-blue-200">
                            Tarif Rp0,-
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        Konsultasi Statistik Online & Tatap Muka
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Layanan konsultasi metodologi survei, penentuan sampel, konsep definisi indikator, metadata, dan klasifikasi statistik (KBLI/KBJI) secara daring maupun langsung.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('chat.index') }}" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Tanya Asisten AI PST</span>
                        <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                    </a>
                </div>
            </div>

            {{-- Card 2: Permohonan Data Mikro PP 86/2021 --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-[#f7941d] border border-amber-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:database"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-amber-50 text-[#ea580c] border-amber-200">
                            PP 86/2021
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        Data Mikro & Peta Wilkerstat SHP
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Penyediaan raw data sampel survei (Susenas, Sakernas, Podes) dan peta digital Shapefile. Bebas biaya PNBP (Tarif Rp0,-) untuk mahasiswa skripsi dan instansi pemda.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('chat.index', ['q' => 'Jelaskan syarat data mikro tarif Rp0 untuk skripsi mahasiswa']) }}" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Cek Prosedur & Syarat</span>
                        <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                    </a>
                </div>
            </div>

            {{-- Card 3: Rekomendasi ROMANTIK --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-[#00a651] border border-emerald-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:file-check-2"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-emerald-50 text-[#00a651] border-emerald-200">
                            Satu Data Indonesia
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        Rekomendasi Statistik Sektoral (ROMANTIK)
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Layanan telaah rancangan survei bagi Organisasi Perangkat Daerah (OPD) Pemkab Karanganyar untuk menerbitkan Surat Rekomendasi Statistik resmi dan kode identitas data.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="https://romantik.bps.go.id" target="_blank" rel="noopener" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Portal romantik.bps.go.id</span>
                        <span class="iconify text-sm" data-icon="lucide:external-link"></span>
                    </a>
                </div>
            </div>

            {{-- Card 4: WebAPI BPS Developer --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:code-2"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-purple-50 text-purple-700 border-purple-200">
                            Developer REST API
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        WebAPI BPS Developer
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Akses API data statistik BPS dalam format JSON untuk menghubungkan indikator makro dan tabel dinamis ke dashboard instansi atau aplikasi pihak ketiga.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="https://webapi.bps.go.id/developer/" target="_blank" rel="noopener" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Daftar App ID WebAPI</span>
                        <span class="iconify text-sm" data-icon="lucide:external-link"></span>
                    </a>
                </div>
            </div>

            {{-- Card 5: StatInaLab Data Enclave --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-sky-50 text-[#0093dd] border border-sky-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:server"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-sky-50 text-[#0093dd] border-sky-200">
                            Secure Lab
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        StatInaLab (Statistics Data Lab)
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Fasilitas lingkungan komputasi penelitian berkeamanan tinggi on-site untuk peneliti dan akademisi tingkat lanjut dalam memproses data mikro detail tanpa melanggar privasi.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="https://statinalab.bps.go.id" target="_blank" rel="noopener" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Kunjungi StatInaLab</span>
                        <span class="iconify text-sm" data-icon="lucide:external-link"></span>
                    </a>
                </div>
            </div>

            {{-- Card 6: Aduan & SP4N-LAPOR! --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="lucide:shield-alert"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border bg-rose-50 text-rose-700 border-rose-200">
                            Kanal SP4N
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#003c80] transition-colors">
                        Aspirasi & Pengaduan Pelayanan
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sampaikan pengaduan, masukan perbaikan layanan, atau laporkan pelanggaran integritas. Terintegrasi dengan sistem tiket berkode dan SP4N-LAPOR!.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('aduan.create') }}" class="text-xs font-black text-[#003c80] hover:text-[#f7941d] flex items-center gap-1.5">
                        <span>Buat Tiket Pengaduan</span>
                        <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STANDAR WAKTU & JADWAL OPERASIONAL PELAYANAN PST --}}
<section class="py-14 bg-white border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-6 space-y-4">
                <span class="text-xs font-black text-[#f7941d] uppercase tracking-widest bg-orange-50 px-3.5 py-1.5 rounded-full border border-orange-200">
                    Jadwal & Standar Pelayanan
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    Jam Operasional Pelayanan Statistik Terpadu (PST)
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Pelayanan tatap muka dan verifikasi data resmi dilayani langsung oleh petugas PST BPS Kabupaten Karanganyar pada hari kerja. Layanan daring website dan Chatbot AI aktif 24 jam nonstop.
                </p>
                <div class="pt-2 grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="font-bold text-slate-900 block mb-1">Senin — Kamis:</span>
                        <span class="text-[#003c80] font-extrabold text-sm">08.00 — 15.30 WIB</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Istirahat: 12.00 — 13.00 WIB</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="font-bold text-slate-900 block mb-1">Jumat:</span>
                        <span class="text-[#003c80] font-extrabold text-sm">08.00 — 15.00 WIB</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Istirahat: 11.30 — 13.00 WIB</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-[#002b6a] rounded-2xl p-6 sm:p-8 text-white border-l-4 border-[#f7941d] shadow-lg space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-[#f7941d]">
                            <span class="iconify text-2xl" data-icon="lucide:shield-check"></span>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-white">Maklumat Pelayanan BPS</h3>
                            <p class="text-xs text-blue-200">Komitmen Integritas & Keterbukaan Data</p>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-200">
                        "Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai ketentuan peraturan perundang-undangan."
                    </p>
                    <div class="pt-2 flex items-center justify-between text-xs text-blue-200 border-t border-white/10">
                        <span>Badan Pusat Statistik Karanganyar</span>
                        <span class="text-[#f7941d] font-bold">Zona Integritas Bebas Korupsi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA KONSULTASI RESMI --}}
<section class="py-14 lg:py-16 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-gradient-to-r from-[#002b6a] to-[#003c80] text-white rounded-3xl p-8 sm:p-10 text-center space-y-5 shadow-lg border border-blue-900/50 relative overflow-hidden">
            <div class="flex justify-center">
                <div class="relative">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white p-1 shadow-xl overflow-hidden border-2 border-[#f7941d]">
                        <img src="{{ asset('images/karta-avatar.jpg') }}" alt="KARTA AI" class="w-full h-full object-cover object-top rounded-full">
                    </div>
                    <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full bg-emerald-400 border-2 border-[#002b6a] animate-pulse"></span>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-slate-200 text-xs font-bold border border-white/20">
                <span class="iconify text-base text-[#00a651]" data-icon="lucide:check-circle-2"></span>
                <span>PST Daring BPS Kabupaten Karanganyar</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-white max-w-2xl mx-auto leading-tight">
                Konsultasikan Riset & Data Anda dengan KARTA AI
            </h2>
            <p class="text-xs sm:text-sm text-blue-100 max-w-xl mx-auto leading-relaxed">
                <strong>KARTA</strong> (Konsultan AI Riset & Terpadu Angka) dan petugas resmi BPS Kabupaten Karanganyar siap membantu kebutuhan data mikro tarif Rp0, metodologi, dan publikasi Anda secara cepat & akurat.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="{{ route('chat.index') }}" class="w-full sm:w-auto px-7 py-3 bg-[#f7941d] hover:bg-[#e07e0c] text-white font-black text-xs sm:text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-base" data-icon="lucide:bot"></span>
                    <span>Tanya KARTA Sekarang</span>
                </a>
                <a href="https://wa.me/6289605933133" target="_blank" rel="noopener" class="w-full sm:w-auto px-7 py-3 bg-white hover:bg-slate-100 text-[#003c80] font-extrabold text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-base text-[#00a651]" data-icon="lucide:message-circle"></span>
                    <span>WhatsApp PST: 0896-0593-3133</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
