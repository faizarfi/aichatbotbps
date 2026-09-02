@extends('layouts.public')

@section('title', 'Portal Resmi Pelayanan Statistik Terpadu')
@section('meta_description', 'Portal resmi layanan informasi data statistik, publikasi berkala, konsultasi PST, dan pengaduan masyarakat BPS Kabupaten Karanganyar.')

@section('content')
{{-- HERO SECTION: Official Prestigious BPS Gateway --}}
<section class="relative bg-gradient-to-br from-[#04325e] via-[#004b87] to-[#013a63] text-white pt-10 pb-16 lg:pt-14 lg:pb-24 overflow-hidden border-b-4 border-[#f7941d]">
    {{-- Institutional watermark pattern --}}
    <div class="absolute inset-0 pointer-events-none opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px]"></div>
    
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto space-y-5">
            {{-- Official Agency Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-slate-100 text-xs font-extrabold shadow-sm">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-4 h-4 object-contain">
                <span>BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR</span>
            </div>

            {{-- Main Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Portal Pelayanan Statistik Terpadu
            </h1>

            <p class="text-sm sm:text-base text-blue-100 leading-relaxed font-normal max-w-2xl mx-auto">
                Penyedia data statistik berkualitas, publikasi berkala resmi, konsultasi statistik daring, dan kanal penanganan aspirasi masyarakat Kabupaten Karanganyar.
            </p>

            {{-- Official Prominent Search Bar --}}
            <div class="pt-3 max-w-2xl mx-auto">
                <form action="{{ route('chat.index') }}" method="GET" class="relative flex items-center bg-white rounded-2xl p-1.5 sm:p-2 shadow-2xl border border-slate-200">
                    <div class="pl-3.5 pr-2 text-slate-400">
                        <span class="iconify text-xl text-[#005b9f]" data-icon="lucide:search"></span>
                    </div>
                    <input type="text"
                           name="q"
                           placeholder="Cari data penduduk, angka kemiskinan, PDRB, publikasi KDA..."
                           class="w-full py-2.5 px-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-transparent outline-none font-medium">
                    <button type="submit"
                            class="px-5 sm:px-7 py-2.5 sm:py-3 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-95 text-white text-xs sm:text-sm font-black rounded-xl transition-all shadow-md shadow-orange-500/20 shrink-0 flex items-center gap-1.5 cursor-pointer">
                        <span>Cari Data</span>
                        <span class="iconify text-base" data-icon="lucide:arrow-right"></span>
                    </button>
                </form>

                {{-- Search Shortcuts --}}
                <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mt-3 text-[11px] text-blue-100 font-medium">
                    <span class="text-blue-200 text-[10px] uppercase font-bold tracking-wider mr-1">Rujukan Cepat:</span>
                    <a href="{{ route('chat.index', ['q' => 'Jumlah penduduk Kabupaten Karanganyar 2026']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Penduduk 2026
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Data tingkat kemiskinan Karanganyar']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Angka Kemiskinan
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Publikasi Karanganyar Dalam Angka (KDA)']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Publikasi KDA
                    </a>
                    <a href="{{ route('chat.index', ['q' => 'Jadwal dan jam operasional PST']) }}" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        Jadwal PST
                    </a>
                    <a href="{{ route('districts.index') }}" class="px-2.5 py-1 rounded-lg bg-[#00a651]/30 hover:bg-[#00a651]/50 text-white border border-[#00a651]/40 transition-colors">
                        17 Kecamatan
                    </a>
                </div>
            </div>

            {{-- Fast Navigation Actions --}}
            <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('chat.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-[#005b9f] hover:bg-blue-50 text-xs font-black shadow-md transition-all flex items-center gap-2">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:message-square-text"></span>
                    <span>Konsultasi Data Online</span>
                </a>
                <a href="{{ route('districts.index') }}" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
                    <span class="iconify text-base text-emerald-400" data-icon="lucide:map"></span>
                    <span>Statistik 17 Kecamatan</span>
                </a>
                <a href="{{ route('aduan.create') }}" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
                    <span class="iconify text-base text-amber-300" data-icon="lucide:ticket"></span>
                    <span>Saluran Pengaduan</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- INDIKATOR MAKRO STRATEGIS KARANGANYAR 2026 --}}
<section class="relative -mt-8 z-20 max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        @php
        $macroStats = [
            [
                'label' => 'Jumlah Penduduk',
                'value' => '962.480',
                'unit' => 'Jiwa',
                'sub' => 'L: 483,2k | P: 479,2k',
                'source' => 'BPS Karanganyar 2026',
                'icon' => 'lucide:users',
                'color' => 'text-[#005b9f]',
                'badge' => 'bg-blue-50 border-blue-200 text-[#005b9f]',
            ],
            [
                'label' => 'Tingkat Kemiskinan',
                'value' => '7,92%',
                'unit' => '',
                'sub' => '72,40 Ribu Jiwa (Susenas)',
                'source' => 'GK: Rp 521.800/bln',
                'icon' => 'lucide:trending-down',
                'color' => 'text-[#ea580c]',
                'badge' => 'bg-orange-50 border-orange-200 text-[#ea580c]',
            ],
            [
                'label' => 'Indeks IPM',
                'value' => '78,15',
                'unit' => 'Poin',
                'sub' => 'Kategori: TINGGI',
                'source' => 'AHH: 78,12 Thn | HLS: 14,02',
                'icon' => 'lucide:award',
                'color' => 'text-indigo-700',
                'badge' => 'bg-indigo-50 border-indigo-200 text-indigo-700',
            ],
            [
                'label' => 'Pertumbuhan PDRB',
                'value' => '5,68%',
                'unit' => '',
                'sub' => 'Atas Dasar Harga Konstan',
                'source' => 'ADHB: Rp 44,8 Triliun',
                'icon' => 'lucide:trending-up',
                'color' => 'text-[#00a651]',
                'badge' => 'bg-emerald-50 border-emerald-200 text-[#00a651]',
            ],
        ];
        @endphp

        @foreach($macroStats as $ms)
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">{{ $ms['label'] }}</span>
                    <div class="w-8 h-8 rounded-xl {{ $ms['badge'] }} border flex items-center justify-center">
                        <span class="iconify text-base" data-icon="{{ $ms['icon'] }}"></span>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $ms['value'] }}</span>
                    @if($ms['unit'])<span class="text-xs font-bold text-slate-500">{{ $ms['unit'] }}</span>@endif
                </div>
                <p class="text-xs text-slate-600 font-semibold mt-1">{{ $ms['sub'] }}</p>
            </div>
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-medium">
                <span>{{ $ms['source'] }}</span>
                <span class="iconify text-emerald-600" data-icon="lucide:check-circle-2"></span>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- 6 LAYANAN UTAMA PELAYANAN STATISTIK TERPADU (PST) --}}
<section class="py-16 lg:py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-black text-[#005b9f] uppercase tracking-widest bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200">
                Layanan Publik Resmi
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                Layanan Pelayanan Statistik Terpadu (PST)
            </h2>
            <p class="mt-2 text-slate-600 text-sm sm:text-base">
                Akses resmi diseminasi data, konsultasi statistik, publikasi berkala, dan penanganan aspirasi BPS Karanganyar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $portalServices = [
                [
                    'icon' => 'lucide:message-square-text',
                    'title' => 'Konsultasi Statistik Online',
                    'desc' => 'Layanan tanya jawab dan permohonan data statistik resmi secara daring tanpa perlu datang langsung ke kantor BPS.',
                    'badge' => 'Online 24/7',
                    'badge_class' => 'bg-blue-50 text-[#005b9f] border-blue-200',
                    'icon_class' => 'bg-blue-50 text-[#005b9f] border-blue-200',
                    'url' => route('chat.index'),
                    'action' => 'Buka Konsultasi Data',
                ],
                [
                    'icon' => 'lucide:map-pin',
                    'title' => 'Statistik 17 Kecamatan',
                    'desc' => 'Eksplorasi data kependudukan, luas wilayah, kepadatan penduduk, dan komoditas unggulan 17 kecamatan di Karanganyar.',
                    'badge' => 'Profil Wilayah',
                    'badge_class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    'icon_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'url' => route('districts.index'),
                    'action' => 'Lihat Data Kecamatan',
                ],
                [
                    'icon' => 'lucide:calculator',
                    'title' => 'Kalkulator Statistik Interaktif',
                    'desc' => 'Hitung simulasi proyeksi pertumbuhan penduduk, inflasi kumulatif, dan estimasi sampel statistik dengan rumus baku.',
                    'badge' => 'Alat Bantu',
                    'badge_class' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                    'icon_class' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'url' => route('calculators.index'),
                    'action' => 'Buka Kalkulator',
                ],
                [
                    'icon' => 'lucide:book-open',
                    'title' => 'Publikasi Karanganyar Dalam Angka',
                    'desc' => 'Unduh publikasi berkala Karanganyar Dalam Angka, Produk Domestik Regional Bruto (PDRB), dan berita resmi statistik.',
                    'badge' => 'Publikasi Resmi',
                    'badge_class' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'icon_class' => 'bg-amber-50 text-[#f7941d] border-amber-200',
                    'url' => 'https://karanganyarkab.bps.go.id',
                    'action' => 'Katalog Publikasi',
                ],
                [
                    'icon' => 'lucide:ticket',
                    'title' => 'Saluran Pengaduan Resmi',
                    'desc' => 'Sampaikan pengaduan atau aspirasi pelayanan statistik secara transparan dengan nomor tiket unik pemantauan.',
                    'badge' => 'Resmi & Berkode',
                    'badge_class' => 'bg-rose-50 text-rose-800 border-rose-200',
                    'icon_class' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'url' => route('aduan.create'),
                    'action' => 'Kirim Pengaduan',
                ],
                [
                    'icon' => 'lucide:search',
                    'title' => 'Lacak Status Tiket Pengaduan',
                    'desc' => 'Pantau status tindak lanjut tiket pengaduan masyarakat secara mandiri dengan memasukkan nomor tiket resmi.',
                    'badge' => 'Lacak Mandiri',
                    'badge_class' => 'bg-slate-100 text-slate-800 border-slate-300',
                    'icon_class' => 'bg-slate-100 text-slate-700 border-slate-300',
                    'url' => route('status-aduan'),
                    'action' => 'Cek Status Tiket',
                ],
            ];
            @endphp

            @foreach($portalServices as $ps)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-[#005b9f]/50 transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl {{ $ps['icon_class'] }} border flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="iconify text-xl" data-icon="{{ $ps['icon'] }}"></span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $ps['badge_class'] }}">
                            {{ $ps['badge'] }}
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base mb-2 group-hover:text-[#005b9f] transition-colors">
                        {{ $ps['title'] }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $ps['desc'] }}
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100">
                    <a href="{{ $ps['url'] }}" class="text-xs font-black text-[#005b9f] hover:text-[#004b87] flex items-center gap-1.5 group-hover:gap-2 transition-all">
                        <span>{{ $ps['action'] }}</span>
                        <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STANDAR WAKTU & JADWAL OPERASIONAL PELAYANAN PST --}}
<section class="py-14 bg-white border-t border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-6 space-y-4">
                <span class="text-xs font-black text-[#f7941d] uppercase tracking-widest bg-orange-50 px-3.5 py-1.5 rounded-full border border-orange-200">
                    Jadwal & Standar Pelayanan
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    Jam Operasional Pelayanan Statistik Terpadu (PST)
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Pelayanan tatap muka dan verifikasi data resmi dilayani langsung oleh petugas PST BPS Kabupaten Karanganyar pada hari kerja. Layanan konsultasi data digital dapat diakses 24 jam.
                </p>
                <div class="pt-2 grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="font-bold text-slate-900 block mb-1">Senin — Kamis:</span>
                        <span class="text-[#005b9f] font-extrabold text-sm">08.00 — 15.30 WIB</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Istirahat: 12.00 — 13.00 WIB</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="font-bold text-slate-900 block mb-1">Jumat:</span>
                        <span class="text-[#005b9f] font-extrabold text-sm">08.00 — 15.00 WIB</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Istirahat: 11.30 — 13.00 WIB</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-[#04325e] rounded-2xl p-6 sm:p-8 text-white border-l-4 border-[#f7941d] shadow-lg space-y-4">
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

{{-- CTA PENGADUAN & KONSULTASI RESMI --}}
<section class="py-14 lg:py-16 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-gradient-to-r from-blue-900 to-[#04325e] text-white rounded-3xl p-8 sm:p-10 text-center space-y-5 shadow-lg border border-blue-900/50">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-slate-200 text-xs font-bold border border-white/20">
                <span class="iconify text-base text-[#00a651]" data-icon="lucide:check-circle-2"></span>
                <span>Saluran Aspirasi & Konsultasi Resmi</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-white max-w-2xl mx-auto leading-tight">
                Butuh Konsultasi Statistik Mendalam atau Menyampaikan Masukan?
            </h2>
            <p class="text-xs sm:text-sm text-blue-100 max-w-xl mx-auto leading-relaxed">
                Petugas Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar siap membantu permohonan data dan menindaklanjuti pengaduan Anda.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="{{ route('chat.index') }}" class="w-full sm:w-auto px-7 py-3 bg-[#f7941d] hover:bg-[#e07e0c] text-white font-black text-xs sm:text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-base" data-icon="lucide:message-square-text"></span>
                    <span>Konsultasi Data Sekarang</span>
                </a>
                <a href="{{ route('aduan.create') }}" class="w-full sm:w-auto px-7 py-3 bg-white hover:bg-slate-100 text-[#005b9f] font-extrabold text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:ticket"></span>
                    <span>Kirim Pengaduan</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
