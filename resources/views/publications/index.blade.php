@extends('layouts.public')

@section('title', 'Katalog Publikasi Resmi BPS Kabupaten Karanganyar')
@section('meta_description', 'Akses dan unduh seluruh publikasi resmi Badan Pusat Statistik Kabupaten Karanganyar (Domain 3313). Buku tahunan kompendium, Kecamatan Dalam Angka, dan statistik tematik lengkap dengan berkas PDF resmi.')

@section('content')
{{-- HERO SECTION --}}
<section class="relative bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white pt-10 pb-16 overflow-hidden border-b-4 border-[#f7941d]">
    <div class="absolute inset-0 pointer-events-none opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-slate-100 text-xs font-black shadow-sm">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-4 h-4 object-contain">
                <span>PORTAL RESMI PUBLIKASI BPS KABUPATEN KARANGANYAR (3313)</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Katalog Publikasi Statistik Daerah
            </h1>

            <p class="text-xs sm:text-sm lg:text-base text-blue-100 leading-relaxed font-normal max-w-2xl mx-auto">
                Koleksi lengkap kompendium statistik resmi BPS Kabupaten Karanganyar. Dapatkan buku tahunan Karanganyar Dalam Angka, seri 17 Kecamatan Dalam Angka, dan laporan survei sektoral lengkap dengan naskah PDF asli.
            </p>

            {{-- Stat counter badges --}}
            <div class="pt-2 flex flex-wrap items-center justify-center gap-2 text-xs">
                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20 font-bold text-white">
                    📚 <strong class="text-amber-300">{{ $stats['total'] ?? 464 }}</strong> Publikasi Terdata
                </span>
                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20 font-bold text-white">
                    🏛️ 17 Kecamatan Lengkap
                </span>
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 font-bold text-emerald-200">
                    ⚡ Akses Unduh Bebas Tarif Rp0,-
                </span>
                <span class="px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 font-bold text-purple-200">
                    🤖 Riset Cerdas KARTA AI
                </span>
            </div>
        </div>

        {{-- Advanced Search & Filter Bar --}}
        <div class="mt-8 max-w-5xl mx-auto bg-white rounded-2xl p-4 sm:p-6 shadow-2xl border border-slate-200 text-slate-800">
            <form action="{{ route('publications.index') }}" method="GET" class="space-y-4">
                {{-- Input Row 1: Keyword Search + Year Dropdown + Sort + Submit --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                    {{-- Search Input --}}
                    <div class="sm:col-span-6 relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="iconify text-lg text-[#003c80]" data-icon="lucide:search"></span>
                        </span>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari judul buku (mis: Jaten, Karanganyar Dalam Angka, PDRB, Kemiskinan)..."
                               class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#003c80] focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm text-slate-900 outline-none">
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="sm:col-span-2">
                        <select name="year" class="w-full py-2.5 px-3 rounded-xl border border-slate-300 focus:border-[#003c80] focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm text-slate-800 outline-none bg-white">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort Selector --}}
                    <div class="sm:col-span-2">
                        <select name="sort" class="w-full py-2.5 px-3 rounded-xl border border-slate-300 focus:border-[#003c80] focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm text-slate-800 outline-none bg-white">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                            <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Judul (Z - A)</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="sm:col-span-2 flex items-center gap-1.5">
                        <button type="submit" class="flex-1 py-2.5 bg-[#003c80] hover:bg-[#002b6a] active:scale-95 text-white font-black text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="iconify text-base" data-icon="lucide:filter"></span>
                            <span>Filter</span>
                        </button>

                        @if(request()->hasAny(['q', 'year', 'category', 'sort']))
                            <a href="{{ route('publications.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1" title="Reset Semua Filter">
                                <span class="iconify text-sm" data-icon="lucide:rotate-ccw"></span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Row 2: Thematic Category Pills Navigation --}}
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-slate-500 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1">
                            <span class="iconify text-xs text-[#003c80]" data-icon="lucide:tag"></span>
                            <span>Kategori Tematik Publikasi:</span>
                        </span>
                        @if(request('category'))
                            <a href="{{ route('publications.index', request()->except('category')) }}" class="text-[11px] text-rose-600 hover:underline font-bold flex items-center gap-0.5">
                                <span class="iconify text-xs" data-icon="lucide:x"></span>
                                <span>Hapus Kategori</span>
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 text-xs">
                        {{-- Semua --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('category', 'page'), [])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ !request('category') ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm" data-icon="lucide:layout-grid"></span>
                            <span>Semua</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ !request('category') ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['all'] ?? 464 }}
                            </span>
                        </a>

                        {{-- Karanganyar Dalam Angka --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'kda'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'kda' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-amber-500" data-icon="lucide:book-marked"></span>
                            <span>KDA Kompendium</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'kda' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['kda'] ?? 0 }}
                            </span>
                        </a>

                        {{-- 17 Kecamatan Dalam Angka --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'kecamatan'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'kecamatan' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-sky-500" data-icon="lucide:map-pin"></span>
                            <span>17 Kecamatan</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'kecamatan' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['kecamatan'] ?? 0 }}
                            </span>
                        </a>

                        {{-- Sosial & Kesejahteraan --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'sosial'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'sosial' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-rose-500" data-icon="lucide:users"></span>
                            <span>Sosial & Kesejahteraan</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'sosial' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['sosial'] ?? 0 }}
                            </span>
                        </a>

                        {{-- Ekonomi & PDRB --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'ekonomi'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'ekonomi' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-emerald-500" data-icon="lucide:trending-up"></span>
                            <span>Ekonomi & PDRB</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'ekonomi' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['ekonomi'] ?? 0 }}
                            </span>
                        </a>

                        {{-- Pertanian & Pangan --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'pertanian'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'pertanian' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-green-600" data-icon="lucide:sprout"></span>
                            <span>Pertanian & Pangan</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'pertanian' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['pertanian'] ?? 0 }}
                            </span>
                        </a>

                        {{-- Industri & Infrastruktur --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'industri'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'industri' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-indigo-500" data-icon="lucide:factory"></span>
                            <span>Industri & Sarana</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'industri' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['industri'] ?? 0 }}
                            </span>
                        </a>

                        {{-- Statistik Daerah --}}
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['category' => 'statistik_daerah'])) }}"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request('category') === 'statistik_daerah' ? 'bg-[#003c80] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            <span class="iconify text-sm text-purple-500" data-icon="lucide:file-bar-chart"></span>
                            <span>Statistik Daerah</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'statistik_daerah' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                                {{ $categoryCounts['statistik_daerah'] ?? 0 }}
                            </span>
                        </a>
                    </div>
                </div>

                {{-- Row 3: Quick Year Pills --}}
                <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-bold text-[11px] mr-1">Tahun Cepat:</span>
                    <a href="{{ route('publications.index', array_merge(request()->except('year', 'page'), [])) }}"
                       class="px-2.5 py-0.5 rounded-lg text-xs font-bold transition-all {{ !request('year') ? 'bg-slate-800 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                        Semua
                    </a>
                    @foreach($availableYears->take(6) as $yr)
                        <a href="{{ route('publications.index', array_merge(request()->except('page'), ['year' => $yr])) }}"
                           class="px-2.5 py-0.5 rounded-lg text-xs font-bold transition-all {{ request('year') == $yr ? 'bg-[#f7941d] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                            {{ $yr }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</section>

{{-- PUBLICATIONS GRID --}}
<section class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Results Info Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-slate-200 gap-3">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span>Daftar Dokumen Publikasi Resmi</span>
                    @if(request('category'))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-[#003c80]">
                            Kategori: {{ ucfirst(str_replace('_', ' ', request('category'))) }}
                        </span>
                    @endif
                    @if(request('year'))
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-[#ea580c]">
                            Tahun {{ request('year') }}
                        </span>
                    @endif
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan <strong>{{ $publications->firstItem() ?? 0 }} - {{ $publications->lastItem() ?? 0 }}</strong> dari total <strong>{{ $publications->total() }}</strong> publikasi resmi
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('chat.index', ['q' => 'Rekomendasikan publikasi BPS Karanganyar yang relevan untuk kebutuhan riset data saya']) }}" class="px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 text-xs font-extrabold flex items-center gap-2 shadow-xs transition-all">
                    <img src="{{ asset('images/karta-avatar.jpg') }}" alt="KARTA AI" class="w-4 h-4 rounded-full object-cover">
                    <span>Tanya KARTA AI Publikasi</span>
                </a>
            </div>
        </div>

        {{-- Cards Grid --}}
        @if($publications->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-6">
                @foreach($publications as $pub)
                    @php
                        // Deteksi kategori tag kartu
                        $titleLower = strtolower($pub->title);
                        $cardTag = 'Publikasi BPS';
                        $cardColor = 'bg-blue-50 text-[#003c80] border-blue-200';
                        if (str_contains($titleLower, 'kabupaten karanganyar dalam angka')) {
                            $cardTag = 'KDA Utama';
                            $cardColor = 'bg-amber-50 text-amber-900 border-amber-300';
                        } elseif (str_contains($titleLower, 'dalam angka') && str_contains($titleLower, 'kecamatan')) {
                            $cardTag = 'Kecamatan';
                            $cardColor = 'bg-sky-50 text-sky-900 border-sky-300';
                        } elseif (str_contains($titleLower, 'pdrb') || str_contains($titleLower, 'produk domestik') || str_contains($titleLower, 'ekonomi')) {
                            $cardTag = 'Ekonomi / PDRB';
                            $cardColor = 'bg-emerald-50 text-emerald-900 border-emerald-300';
                        } elseif (str_contains($titleLower, 'kemiskinan') || str_contains($titleLower, 'sosial') || str_contains($titleLower, 'pembangunan manusia')) {
                            $cardTag = 'Sosial & Kesra';
                            $cardColor = 'bg-rose-50 text-rose-900 border-rose-300';
                        } elseif (str_contains($titleLower, 'pertanian') || str_contains($titleLower, 'padi')) {
                            $cardTag = 'Pertanian & Pangan';
                            $cardColor = 'bg-green-50 text-green-900 border-green-300';
                        } elseif (str_contains($titleLower, 'statistik daerah')) {
                            $cardTag = 'Statistik Daerah';
                            $cardColor = 'bg-purple-50 text-purple-900 border-purple-300';
                        }
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-[#003c80] transition-all flex flex-col justify-between overflow-hidden group">
                        {{-- Cover Image Link --}}
                        <a href="{{ route('publications.show', $pub->slug ?? $pub->pub_id) }}" class="relative bg-slate-100 aspect-[3/4] overflow-hidden flex items-center justify-center p-3 border-b border-slate-100 block">
                            @if($pub->cover_url)
                                <img src="{{ $pub->cover_url }}"
                                     alt="Cover {{ $pub->title }}"
                                     loading="lazy"
                                     class="max-h-full max-w-full object-contain rounded-md shadow-sm group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='https://web-api.bps.go.id/images/default.png';">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-blue-50/50 text-[#003c80] p-4 text-center">
                                    <span class="iconify text-4xl mb-2 text-[#003c80]/40" data-icon="lucide:book-open"></span>
                                    <span class="text-[11px] font-bold">BPS Karanganyar</span>
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border shadow-xs {{ $cardColor }}">
                                    {{ $cardTag }}
                                </span>
                            </div>

                            @if($pub->size)
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="px-2 py-0.5 rounded-md bg-white/95 text-slate-700 text-[10px] font-black border border-slate-200 shadow-xs">
                                        {{ $pub->size }}
                                    </span>
                                </div>
                            @endif
                        </a>

                        {{-- Metadata & Content --}}
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                                    <span class="flex items-center gap-1">
                                        <span class="iconify text-xs text-[#003c80]" data-icon="lucide:calendar"></span>
                                        <span>{{ $pub->rl_date ? $pub->rl_date->translatedFormat('d M Y') : 'Terbit Resmi' }}</span>
                                    </span>
                                    @if($pub->issn)
                                        <span class="font-mono text-[10px] text-slate-400">ISSN {{ $pub->issn }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('publications.show', $pub->slug ?? $pub->pub_id) }}">
                                    <h3 class="font-extrabold text-slate-900 text-sm leading-snug group-hover:text-[#003c80] transition-colors line-clamp-2" title="{{ $pub->title }}">
                                        {{ $pub->title }}
                                    </h3>
                                </a>

                                @if($pub->abstract)
                                    <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($pub->abstract, 120) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-1.5">
                                <a href="{{ route('publications.show', $pub->slug ?? $pub->pub_id) }}"
                                   class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1"
                                   title="Buka Halaman Detail Publikasi">
                                    <span class="iconify text-sm" data-icon="lucide:info"></span>
                                    <span>Detail</span>
                                </a>

                                @if($pub->pdf_url)
                                    <a href="{{ $pub->pdf_url }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex-1 py-2 px-3 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-xs font-black rounded-xl transition-all shadow-xs flex items-center justify-center gap-1"
                                       title="Unduh Berkas PDF Resmi Bebas Tarif Rp0">
                                        <span class="iconify text-sm" data-icon="lucide:download"></span>
                                        <span>Unduh PDF</span>
                                    </a>
                                @endif

                                <a href="{{ route('chat.index', ['q' => 'Jelaskan ringkasan dan data penting dari publikasi ' . $pub->title]) }}"
                                   class="p-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition-colors flex items-center justify-center shrink-0"
                                   title="Tanyakan isi buku ini ke KARTA AI">
                                    <span class="iconify text-base" data-icon="lucide:bot"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-10 flex justify-center">
                {{ $publications->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-12 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                    <span class="iconify text-3xl" data-icon="lucide:book-x"></span>
                </div>
                <h3 class="text-base font-extrabold text-slate-800 mb-1">Publikasi Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mb-4">
                    Tidak ditemukan dokumen publikasi yang sesuai dengan kata kunci atau filter pencarian Anda.
                </p>
                <a href="{{ route('publications.index') }}" class="px-5 py-2 bg-[#003c80] text-white text-xs font-bold rounded-xl hover:bg-[#002b6a] transition-all">
                    Tampilkan Semua Publikasi
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
