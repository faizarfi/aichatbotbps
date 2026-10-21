@extends('layouts.public')

@section('title', $publication->title . ' - BPS Kabupaten Karanganyar')
@section('meta_description', Str::limit($publication->abstract ?? $publication->title, 150))

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-[#003c80]">Beranda</a>
            <span class="iconify text-xs" data-icon="lucide:chevron-right"></span>
            <a href="{{ route('publications.index') }}" class="hover:text-[#003c80]">Katalog Publikasi</a>
            <span class="iconify text-xs" data-icon="lucide:chevron-right"></span>
            <span class="text-slate-800 font-bold truncate max-w-sm">{{ $publication->title }}</span>
        </nav>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 p-6 sm:p-10">
                {{-- Cover Column --}}
                <div class="md:col-span-4 flex flex-col items-center">
                    <div class="relative w-full max-w-xs bg-slate-100 rounded-2xl p-4 shadow-md border border-slate-200 aspect-[3/4] flex items-center justify-center">
                        @if($publication->cover_url)
                            <img src="{{ $publication->cover_url }}"
                                 alt="Sampul {{ $publication->title }}"
                                 class="max-h-full max-w-full object-contain rounded-lg shadow-sm"
                                 onerror="this.onerror=null; this.src='https://web-api.bps.go.id/images/default.png';">
                        @else
                            <div class="text-center p-6 text-[#003c80]">
                                <span class="iconify text-5xl mb-2 mx-auto text-[#003c80]/40" data-icon="lucide:book-open"></span>
                                <span class="text-xs font-bold block">BPS Kabupaten Karanganyar</span>
                            </div>
                        @endif
                    </div>

                    {{-- Action buttons --}}
                    <div class="w-full max-w-xs space-y-2.5 mt-6">
                        @if($publication->pdf_url)
                            <a href="{{ $publication->pdf_url }}"
                               target="_blank"
                               rel="noopener"
                               class="w-full py-3 px-4 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white font-black text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                <span class="iconify text-lg" data-icon="lucide:download"></span>
                                <span>Unduh Berkas PDF Asli</span>
                            </a>
                        @endif

                        <a href="{{ route('chat.index', ['q' => 'Saya ingin tahu ringkasan dan data utama dari publikasi: ' . $publication->title]) }}"
                           class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-[#003c80] font-bold text-xs rounded-xl border border-blue-200 transition-colors flex items-center justify-center gap-2">
                            <span class="iconify text-base text-[#f7941d]" data-icon="lucide:bot"></span>
                            <span>Konsultasi Dokumen Ini ke AI</span>
                        </a>

                        <a href="https://karanganyarkab.bps.go.id/id/publication"
                           target="_blank"
                           rel="noopener"
                           class="w-full py-2 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold text-xs rounded-xl border border-slate-200 transition-colors flex items-center justify-center gap-1.5">
                            <span class="iconify text-xs" data-icon="lucide:external-link"></span>
                            <span>Buka di Portal Resmi BPS</span>
                        </a>
                    </div>
                </div>

                {{-- Detail Information Column --}}
                <div class="md:col-span-8 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-[#003c80] text-xs font-extrabold border border-blue-200">
                                Kode Wilayah: 3313 (Karanganyar)
                            </span>
                            @if($publication->size)
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                    Ukuran: {{ $publication->size }}
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                                Tarif: Rp0,- (Bebas Biaya)
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            {{ $publication->title }}
                        </h1>

                        {{-- Metadata Table --}}
                        <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-500 block">Tanggal Rilis:</span>
                                    <span class="font-extrabold text-slate-900">
                                        {{ $publication->rl_date ? $publication->rl_date->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Nomor ISSN/ISBN:</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $publication->issn ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Jadwal Terbit (ARC):</span>
                                    <span class="font-bold text-slate-800">
                                        {{ $publication->sch_date ? $publication->sch_date->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">ID Publikasi BPS:</span>
                                    <span class="font-mono text-slate-600">{{ $publication->pub_id }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Abstract Section --}}
                        @if($publication->abstract)
                            <div class="mt-6">
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <span class="iconify text-base text-[#003c80]" data-icon="lucide:file-text"></span>
                                    <span>Abstrak & Ringkasan Dokumen</span>
                                </h3>
                                <div class="text-xs sm:text-sm text-slate-700 leading-relaxed space-y-2 bg-blue-50/30 p-5 rounded-2xl border border-blue-100/70">
                                    {!! nl2br(e($publication->abstract)) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Notice Footer --}}
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>Penerbit: Badan Pusat Statistik Kabupaten Karanganyar</span>
                        <a href="{{ route('publications.index') }}" class="font-bold text-[#003c80] hover:underline flex items-center gap-1">
                            <span class="iconify text-sm" data-icon="lucide:arrow-left"></span>
                            <span>Kembali ke Katalog</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
