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
                <span>PORTAL RESMI WEBAPI & PUBLIKASI BPS KARANGANYAR (3313)</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Katalog Publikasi Statistik Daerah
            </h1>

            <p class="text-xs sm:text-sm lg:text-base text-blue-100 leading-relaxed font-normal max-w-2xl mx-auto">
                Koleksi lengkap kompendium statistik resmi BPS Kabupaten Karanganyar. Dapatkan buku tahunan Karanganyar Dalam Angka, seri 17 Kecamatan Dalam Angka, dan laporan hasil survei sektoral lengkap dengan naskah PDF asli.
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
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="mt-8 max-w-4xl mx-auto bg-white rounded-2xl p-4 sm:p-5 shadow-2xl border border-slate-200 text-slate-800">
            <form action="{{ route('publications.index') }}" method="GET" class="space-y-3">
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="iconify text-lg text-[#003c80]" data-icon="lucide:search"></span>
                        </span>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari judul buku (mis: Jaten, Karanganyar Dalam Angka, SKD, Kemiskinan)..."
                               class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-slate-300 focus:border-[#003c80] focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm text-slate-900 outline-none">
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="w-full sm:w-44">
                        <select name="year" class="w-full py-2.5 px-3 rounded-xl border border-slate-300 focus:border-[#003c80] focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm text-slate-800 outline-none bg-white">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="px-6 py-2.5 bg-[#003c80] hover:bg-[#002b6a] active:scale-95 text-white font-black text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="iconify text-base" data-icon="lucide:filter"></span>
                        <span>Saring</span>
                    </button>

                    @if(request()->hasAny(['q', 'year', 'category']))
                        <a href="{{ route('publications.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1" title="Reset Filter">
                            <span class="iconify text-sm" data-icon="lucide:x"></span>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>

                {{-- Fast Categories Quick Pill --}}
                <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
                    <span class="text-slate-400 font-bold text-[11px] mr-1">Kategori Cepat:</span>
                    <a href="{{ route('publications.index') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ !request('category') ? 'bg-[#003c80] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Semua Publikasi
                    </a>
                    <a href="{{ route('publications.index', ['category' => 'kda']) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ request('category') === 'kda' ? 'bg-[#003c80] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Karanganyar Dalam Angka
                    </a>
                    <a href="{{ route('publications.index', ['category' => 'kecamatan']) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ request('category') === 'kecamatan' ? 'bg-[#003c80] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Kecamatan Dalam Angka (17 Wilayah)
                    </a>
                    <a href="{{ route('publications.index', ['category' => 'statistik_daerah']) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ request('category') === 'statistik_daerah' ? 'bg-[#003c80] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        Statistik Daerah
                    </a>
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
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">
                    Daftar Dokumen Publikasi Resmi
                </h2>
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $publications->firstItem() ?? 0 }} - {{ $publications->lastItem() ?? 0 }} dari total {{ $publications->total() }} publikasi
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('chat.index', ['q' => 'Rekomendasikan publikasi BPS Karanganyar yang relevan untuk penelitian saya']) }}" class="px-3.5 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all">
                    <span class="iconify text-sm" data-icon="lucide:sparkles"></span>
                    <span>Tanya AI Publikasi</span>
                </a>
            </div>
        </div>

        {{-- Cards Grid --}}
        @if($publications->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-6">
                @foreach($publications as $pub)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-[#003c80] transition-all flex flex-col justify-between overflow-hidden group">
                        {{-- Cover Image --}}
                        <div class="relative bg-slate-100 aspect-[3/4] overflow-hidden flex items-center justify-center p-3 border-b border-slate-100">
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

                            {{-- Release Date Badge --}}
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2 py-0.5 rounded-md bg-[#003c80]/90 backdrop-blur-xs text-white text-[10px] font-extrabold shadow-xs">
                                    {{ $pub->rl_date ? $pub->rl_date->translatedFormat('d M Y') : 'Terbit' }}
                                </span>
                            </div>

                            @if($pub->size)
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="px-2 py-0.5 rounded-md bg-white/95 text-slate-700 text-[10px] font-black border border-slate-200 shadow-xs">
                                        {{ $pub->size }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata & Content --}}
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm leading-snug group-hover:text-[#003c80] transition-colors line-clamp-2" title="{{ $pub->title }}">
                                    {{ $pub->title }}
                                </h3>

                                @if($pub->issn)
                                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                                        <span class="font-semibold text-slate-600">ISSN/ISBN:</span>
                                        <span class="font-mono text-slate-700">{{ $pub->issn }}</span>
                                    </p>
                                @endif

                                @if($pub->abstract)
                                    <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                                        {{ Str::limit($pub->abstract, 140) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                @if($pub->pdf_url)
                                    <a href="{{ $pub->pdf_url }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex-1 py-2 px-3 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-xs font-black rounded-xl transition-all shadow-xs flex items-center justify-center gap-1.5"
                                       title="Unduh Berkas PDF Resmi">
                                        <span class="iconify text-base" data-icon="lucide:download"></span>
                                        <span>Unduh PDF</span>
                                    </a>
                                @endif

                                <a href="{{ route('chat.index', ['q' => 'Jelaskan ringkasan dan data penting dari publikasi ' . $pub->title]) }}"
                                   class="p-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#003c80] border border-blue-200 transition-colors"
                                   title="Tanyakan isi buku ini ke Asisten AI">
                                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:bot"></span>
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
