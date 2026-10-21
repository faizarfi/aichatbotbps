@extends('layouts.public')

@section('title', $publication->title . ' - BPS Kabupaten Karanganyar')
@section('meta_description', Str::limit($publication->abstract ?? $publication->title, 150))

@section('content')
<section class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-[#003c80]">Beranda</a>
            <span class="iconify text-xs" data-icon="lucide:chevron-right"></span>
            <a href="{{ route('publications.index') }}" class="hover:text-[#003c80]">Katalog Publikasi</a>
            <span class="iconify text-xs" data-icon="lucide:chevron-right"></span>
            <span class="text-slate-800 font-bold truncate max-w-sm">{{ $publication->title }}</span>
        </nav>

        {{-- Main Publication Container --}}
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
                           class="w-full py-2.5 px-4 bg-purple-50 hover:bg-purple-100 text-purple-900 font-extrabold text-xs rounded-xl border border-purple-200 transition-colors flex items-center justify-center gap-2 shadow-xs">
                            <span class="iconify text-base text-purple-700" data-icon="lucide:bot"></span>
                            <span>Konsultasi Dokumen ke KARTA AI</span>
                        </a>

                        <a href="https://karanganyarkab.bps.go.id/id/publication"
                           target="_blank"
                           rel="noopener"
                           class="w-full py-2 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold text-xs rounded-xl border border-slate-200 transition-colors flex items-center justify-center gap-1.5">
                            <span class="iconify text-xs" data-icon="lucide:external-link"></span>
                            <span>Buka di Portal Web BPS</span>
                        </a>
                    </div>
                </div>

                {{-- Detail Information Column --}}
                <div class="md:col-span-8 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-[#003c80] text-xs font-black border border-blue-200">
                                {{ $topicTag ?? 'Publikasi Resmi BPS' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                Kode Wilayah: 3313
                            </span>
                            @if($publication->size)
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                    Ukuran: {{ $publication->size }}
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                                Bebas Tarif Rp0,-
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            {{ $publication->title }}
                        </h1>

                        {{-- Metadata Table --}}
                        <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-500 block">Tanggal Rilis Resmi:</span>
                                    <span class="font-extrabold text-slate-900">
                                        {{ $publication->rl_date ? $publication->rl_date->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Nomor ISSN / ISBN:</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $publication->issn ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Jadwal Kalender Rilis (ARC):</span>
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

                        {{-- AI Deep Research Section --}}
                        <div class="mt-6 p-5 rounded-2xl bg-gradient-to-br from-blue-50/70 via-indigo-50/40 to-purple-50/60 border border-blue-200/80 shadow-xs">
                            <div class="flex items-center justify-between pb-3 border-b border-blue-200/60">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-[#002b6a] p-0.5 overflow-hidden border border-[#f7941d] shrink-0">
                                        <img src="{{ asset('images/karta-avatar.jpg') }}" alt="KARTA AI" class="w-full h-full object-cover object-top rounded-lg">
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-black text-slate-900 flex items-center gap-1.5">
                                            <span>Riset Cerdas dengan KARTA AI</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-[#f7941d] text-white">ASISTEN BPS</span>
                                        </h3>
                                        <p class="text-[11px] text-slate-500">Ajukan pertanyaan langsung mengenai statistik dan isi dokumen ini</p>
                                    </div>
                                </div>
                                <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    AI Siap Membantu
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 mt-3 font-semibold">Pilih pertanyaan rekomendasi riset:</p>

                            <div class="mt-2 space-y-2">
                                @foreach($aiPrompts ?? [] as $prompt)
                                    <a href="{{ route('chat.index', ['q' => $prompt]) }}"
                                       class="w-full text-left p-2.5 rounded-xl bg-white hover:bg-blue-100/60 border border-slate-200 hover:border-blue-300 text-xs text-slate-800 font-medium transition-all flex items-center justify-between group shadow-2xs">
                                        <span class="flex items-center gap-2">
                                            <span class="iconify text-sm text-[#003c80] group-hover:scale-110 transition-transform" data-icon="lucide:sparkles"></span>
                                            <span>{{ $prompt }}</span>
                                        </span>
                                        <span class="iconify text-xs text-slate-400 group-hover:text-[#003c80] group-hover:translate-x-0.5 transition-all" data-icon="lucide:arrow-right"></span>
                                    </a>
                                @endforeach
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

        {{-- Related Publications Section --}}
        @if(isset($related) && $related->count() > 0)
            <div class="mt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span class="iconify text-xl text-[#003c80]" data-icon="lucide:library"></span>
                        <span>Publikasi Terkait Lainnya</span>
                    </h2>
                    <a href="{{ route('publications.index') }}" class="text-xs font-bold text-[#003c80] hover:underline">
                        Lihat Semua Publikasi →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($related as $rel)
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 hover:shadow-md hover:border-[#003c80] transition-all flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">
                                    {{ $rel->rl_date ? $rel->rl_date->translatedFormat('d M Y') : 'Terbit' }}
                                </span>
                                <a href="{{ route('publications.show', $rel->slug ?? $rel->pub_id) }}" class="font-extrabold text-xs text-slate-900 hover:text-[#003c80] line-clamp-2">
                                    {{ $rel->title }}
                                </a>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ route('publications.show', $rel->slug ?? $rel->pub_id) }}" class="text-[11px] font-bold text-[#003c80] hover:underline">
                                    Detail Buku
                                </a>
                                @if($rel->pdf_url)
                                    <a href="{{ $rel->pdf_url }}" target="_blank" rel="noopener" class="text-[11px] font-bold text-[#f7941d] hover:underline flex items-center gap-0.5">
                                        <span class="iconify text-xs" data-icon="lucide:download"></span>
                                        <span>PDF</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
