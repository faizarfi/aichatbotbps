@extends('layouts.public')

@section('title', 'Portal Resmi Layanan Statistik Terpadu')
@section('meta_description', 'Portal resmi layanan informasi statistik, konsultasi data, publikasi, dan pengaduan masyarakat BPS Kabupaten Karanganyar.')

@section('content')
{{-- HERO SECTION (Cerah, Elegan, dan Berwibawa) --}}
<section class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-sky-50/40 to-slate-100/60 pt-10 pb-16 lg:pt-16 lg:pb-28 border-b border-slate-200/60">
    {{-- Subtle institutional background accents --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-100/50 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 right-0 w-[500px] h-[500px] bg-sky-100/40 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:28px_28px] opacity-25"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">

            {{-- Left Content --}}
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                {{-- Official Badge --}}
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200 text-blue-900 shadow-xs">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-4 h-4 object-contain">
                    <span class="text-xs font-extrabold tracking-wide uppercase">Pelayanan Statistik Terpadu BPS</span>
                </div>

                {{-- Hero Heading --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.14]">
                    Akses Data & Layanan
                    <span class="text-blue-700 block mt-1">
                        Statistik Karanganyar
                    </span>
                </h1>

                <p class="text-sm sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0 font-normal">
                    Pusat publikasi resmi, konsultasi data, dan layanan pengaduan masyarakat Kabupaten Karanganyar dengan dukungan <strong class="text-slate-800 font-bold">Asisten Statistik Terpadu 24 Jam</strong>.
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                    <a href="{{ route('chat.index') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-sm font-extrabold rounded-xl transition-all shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 group">
                        <span class="iconify text-lg" data-icon="lucide:message-square-text"></span>
                        <span>Mulai Tanya Data</span>
                        <span class="iconify text-base opacity-75 group-hover:translate-x-1 transition-transform" data-icon="lucide:arrow-right"></span>
                    </a>
                    <a href="{{ route('aduan.create') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-800 text-sm font-bold rounded-xl transition-all border border-slate-200/90 shadow-xs">
                        <span class="iconify text-lg text-blue-600" data-icon="lucide:ticket"></span>
                        <span>Buat Pengaduan</span>
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="pt-6 flex flex-wrap items-center justify-center lg:justify-start gap-3 text-xs text-slate-600 border-t border-slate-200/80">
                    <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-bold text-slate-800">Layanan Aktif 24 Jam</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-xs">
                        <span class="iconify text-base text-blue-600" data-icon="lucide:badge-check"></span>
                        <span class="font-semibold text-slate-800">Data Resmi BPS 2024</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-xs">
                        <span class="iconify text-base text-emerald-600" data-icon="lucide:shield-check"></span>
                        <span class="font-semibold text-slate-800">Privasi Terlindungi</span>
                    </div>
                </div>
            </div>

            {{-- Right Interactive Simulator Card --}}
            <div class="lg:col-span-5">
                <div class="relative mx-auto max-w-md">
                    {{-- Soft subtle card container --}}
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xl shadow-slate-200/60 space-y-4">
                        {{-- Mockup Topbar --}}
                        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 p-1.5 flex items-center justify-center border border-blue-100">
                                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900 leading-tight">Asisten Statistik BPS</h3>
                                    <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1.5 mt-0.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Siap Melayani Pertanyaan Anda
                                    </span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                PST Online
                            </span>
                        </div>

                        {{-- Simulated Chat Messages --}}
                        <div class="space-y-3 text-xs">
                            {{-- User Bubble --}}
                            <div class="flex justify-end">
                                <div class="bg-blue-600 text-white px-4 py-2.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-sm">
                                    <p class="leading-relaxed font-medium">Berapa jumlah penduduk dan tingkat kemiskinan di Kabupaten Karanganyar?</p>
                                </div>
                            </div>

                            {{-- Bot Response Bubble --}}
                            <div class="flex gap-2.5 items-start">
                                <div class="w-7 h-7 rounded-full bg-blue-50 p-1 flex items-center justify-center shrink-0 border border-blue-100 mt-0.5">
                                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                                </div>
                                <div class="bg-slate-50 border border-slate-200 text-slate-800 p-4 rounded-2xl rounded-tl-sm max-w-[90%] space-y-2.5 shadow-xs">
                                    <p class="leading-relaxed font-semibold text-slate-900">
                                        Berdasarkan rilis resmi <strong>BPS Kabupaten Karanganyar (2024)</strong>:
                                    </p>
                                    <ul class="space-y-1.5 pl-3 text-slate-700 border-l-2 border-blue-600 text-[11px]">
                                        <li>👥 <strong>Jumlah Penduduk:</strong> 953.696 Jiwa</li>
                                        <li>📉 <strong>Persentase Kemiskinan:</strong> 8,48% (77,66 ribu jiwa)</li>
                                        <li>🎓 <strong>Indeks Pembangunan Manusia (IPM):</strong> 77,31 Poin</li>
                                        <li>📈 <strong>Pertumbuhan Ekonomi:</strong> 5,54% (PDRB)</li>
                                    </ul>
                                    <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between text-[10px] text-slate-500">
                                        <span class="flex items-center gap-1 font-bold text-emerald-700">
                                            <span class="iconify" data-icon="lucide:check-circle-2"></span> Terverifikasi Resmi
                                        </span>
                                        <a href="{{ route('chat.index') }}" class="text-blue-600 hover:text-blue-800 font-extrabold">Tanya Lengkap →</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Interactive Pills --}}
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Contoh Topik Data Populer:</p>
                            <div class="flex flex-wrap gap-1.5">
                                <a href="{{ route('chat.index') }}" class="px-3 py-1.5 rounded-xl text-[11px] font-semibold bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 transition-colors flex items-center gap-1">
                                    <span>🕒 Jadwal Buka PST</span>
                                </a>
                                <a href="{{ route('chat.index') }}" class="px-3 py-1.5 rounded-xl text-[11px] font-semibold bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 transition-colors flex items-center gap-1">
                                    <span>📖 Karanganyar Dalam Angka</span>
                                </a>
                                <a href="{{ route('chat.index') }}" class="px-3 py-1.5 rounded-xl text-[11px] font-bold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors flex items-center gap-1">
                                    <span>Tanya Lainnya →</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- STATISTIK KARANGANYAR SEKILAS (HIGHLIGHT DATA CARDS) --}}
<section class="relative -mt-6 z-20 max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @php
        $stats = [
            ['value' => '953.696', 'unit' => 'Jiwa', 'label' => 'Jumlah Penduduk', 'source' => 'BPS Karanganyar 2024', 'icon' => 'lucide:users', 'badge_bg' => 'bg-blue-50 text-blue-700 border-blue-200'],
            ['value' => '8,48%', 'unit' => '', 'label' => 'Tingkat Kemiskinan', 'source' => '77,66 Ribu Jiwa (Susenas)', 'icon' => 'lucide:trending-down', 'badge_bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            ['value' => '77,31', 'unit' => 'Poin', 'label' => 'Indeks IPM', 'source' => 'Kategori: TINGGI', 'icon' => 'lucide:award', 'badge_bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
            ['value' => '5,54%', 'unit' => '', 'label' => 'Pertumbuhan PDRB', 'source' => 'Atas Dasar Harga Konstan', 'icon' => 'lucide:bar-chart-3', 'badge_bg' => 'bg-amber-50 text-amber-700 border-amber-200'],
        ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 leading-tight uppercase tracking-wider">{{ $s['label'] }}</span>
                <div class="w-9 h-9 rounded-xl {{ $s['badge_bg'] }} border flex items-center justify-center shadow-xs">
                    <span class="iconify text-lg" data-icon="{{ $s['icon'] }}"></span>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $s['value'] }}</span>
                    @if($s['unit'])<span class="text-xs font-bold text-slate-500">{{ $s['unit'] }}</span>@endif
                </div>
                <p class="text-[11px] text-slate-500 font-medium mt-1 flex items-center gap-1">
                    <span class="iconify text-blue-600" data-icon="lucide:check"></span>
                    <span>{{ $s['source'] }}</span>
                </p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- 6 LAYANAN UTAMA PST BPS KARANGANYAR --}}
<section class="py-16 lg:py-24 bg-slate-50/60">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-extrabold text-blue-700 uppercase tracking-widest bg-blue-100/70 px-3.5 py-1.5 rounded-full border border-blue-200">
                Pusat Pelayanan Terpadu
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 mt-4 tracking-tight">
                Layanan Statistik Resmi BPS
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base leading-relaxed">
                Kemudahan akses permohonan data, konsultasi statistik, publikasi berkala, hingga saluran pengaduan resmi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @php
            $services = [
                [
                    'icon' => 'lucide:map-pin',
                    'title' => 'Peta 17 Kecamatan Karanganyar',
                    'desc' => 'Eksplorasi data statistik kependudukan, luas wilayah, kepadatan, dan sektor unggulan di setiap kecamatan se-Kabupaten Karanganyar.',
                    'badge' => 'Peta Tematik',
                    'icon_color' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'url' => route('districts.index'),
                    'action' => 'Jelajahi Peta Wilayah',
                ],
                [
                    'icon' => 'lucide:calendar-clock',
                    'title' => 'Reservasi Tatap Muka PST',
                    'desc' => 'Jadwalkan kunjungan tatap muka langsung ke ruang PST Kantor BPS Karanganyar dengan tiket reservasi digital ber-QR Code.',
                    'badge' => 'Tiket Digital',
                    'icon_color' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'url' => route('reservasi.create'),
                    'action' => 'Booking Jadwal Konsultasi',
                ],
                [
                    'icon' => 'lucide:database',
                    'title' => 'Permintaan Data & ROMANTIK',
                    'desc' => 'Pengajuan permohonan data mikro, data sektoral, dan rekomendasi kegiatan statistik resmi dengan pelacak status real-time.',
                    'badge' => 'Format Resmi',
                    'icon_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'url' => route('layanan-data.create'),
                    'action' => 'Ajukan Permohonan Data',
                ],
                [
                    'icon' => 'lucide:bot',
                    'title' => 'Asisten Chatbot AI 24 Jam',
                    'desc' => 'Tanyakan berbagai informasi statistik kapan saja tanpa antre. Dilengkapi fitur pengalihan langsung ke petugas bila butuh respon mendalam.',
                    'badge' => 'Online 24/7',
                    'icon_color' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                    'url' => route('chat.index'),
                    'action' => 'Mulai Konsultasi Bot',
                ],
                [
                    'icon' => 'lucide:ticket',
                    'title' => 'Pengaduan Layanan Resmi',
                    'desc' => 'Saluran aspirasi dan pengaduan pelayanan publik transparan dengan nomor tiket unik serta pemantauan status tindak lanjut berkala.',
                    'badge' => 'Resmi & Berkode',
                    'icon_color' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'url' => route('aduan.create'),
                    'action' => 'Buat Laporan Aduan',
                ],
                [
                    'icon' => 'lucide:search',
                    'title' => 'Pelacak Status Layanan',
                    'desc' => 'Cek status tindak lanjut tiket aduan masyarakat, kode reservasi kunjungan, maupun permohonan data mikro Anda kapan saja.',
                    'badge' => 'Lacak Mandiri',
                    'icon_color' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'url' => route('status-aduan'),
                    'action' => 'Cek Status Tiket',
                ],
            ];
            @endphp

            @foreach($services as $svc)
            <div class="bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 rounded-2xl {{ $svc['icon_color'] }} border flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform p-2.5">
                            <span class="iconify text-2xl" data-icon="{{ $svc['icon'] }}"></span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                            {{ $svc['badge'] }}
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-lg mb-2.5 group-hover:text-blue-700 transition-colors tracking-tight">
                        {{ $svc['title'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $svc['desc'] }}
                    </p>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ $svc['url'] }}" class="text-xs font-extrabold text-blue-600 hover:text-blue-800 flex items-center gap-1.5 group-hover:gap-2 transition-all">
                        <span>{{ $svc['action'] }}</span>
                        <span class="iconify" data-icon="lucide:arrow-right"></span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PANDUAN PENGGUNAAN CEPAT --}}
<section class="py-16 lg:py-24 bg-white border-t border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-xl mx-auto mb-14">
            <span class="text-xs font-extrabold text-blue-700 uppercase tracking-widest bg-blue-100/70 px-3.5 py-1.5 rounded-full border border-blue-200">
                Alur Praktis
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 mt-4 tracking-tight">
                3 Langkah Mudah Menemukan Data
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                Dapatkan informasi statistik resmi Kabupaten Karanganyar dalam hitungan detik.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            @php
            $steps = [
                ['step' => '01', 'title' => 'Buka Ruang Obrolan', 'desc' => 'Klik menu Chatbot pada website ini kapan saja melalui smartphone atau komputer tanpa perlu instalasi aplikasi.'],
                ['step' => '02', 'title' => 'Ketik Topik / Kata Kunci', 'desc' => 'Tanyakan angka kemiskinan, jumlah penduduk, PDRB, jadwal PST, atau cara download publikasi BPS Karanganyar.'],
                ['step' => '03', 'title' => 'Dapatkan Data Terverifikasi', 'desc' => 'Asisten menyajikan kutipan rilis data akurat, tautan dokumen resmi, atau menghubungkan langsung ke petugas BPS bila dibutuhkan.'],
            ];
            @endphp

            @foreach($steps as $st)
            <div class="bg-slate-50/80 rounded-3xl p-8 border border-slate-200 relative group hover:bg-blue-50/50 hover:border-blue-200 transition-all duration-300">
                <div class="text-4xl font-black text-blue-200 group-hover:text-blue-400 font-mono mb-4 transition-colors">
                    {{ $st['step'] }}
                </div>
                <h3 class="font-extrabold text-slate-900 text-lg mb-2 tracking-tight">
                    {{ $st['title'] }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $st['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA PENGADUAN & KONSULTASI RESMI (Cerah, Bersih & Berwibawa) --}}
<section class="py-14 lg:py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 border border-blue-200/90 rounded-3xl p-8 sm:p-12 text-center space-y-6 shadow-sm">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-blue-800 text-xs font-bold border border-blue-200 shadow-xs">
                <span class="iconify text-base text-blue-600" data-icon="lucide:shield-check"></span>
                <span>Keterbukaan & Integritas Pelayanan Publik</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-black tracking-tight text-slate-900 max-w-3xl mx-auto leading-tight">
                Membutuhkan Bantuan Khusus atau Ingin Menyampaikan Aspirasi?
            </h2>
            <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Petugas Pelayanan Statistik Terpadu BPS Kabupaten Karanganyar siap membantu konsultasi data dan menindaklanjuti setiap masukan Anda secara transparan.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 pt-3">
                <a href="{{ route('aduan.create') }}" class="w-full sm:w-auto px-7 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-lg" data-icon="lucide:send"></span>
                    <span>Kirim Aduan / Konsultasi Resmi</span>
                </a>
                <a href="{{ route('status-aduan') }}" class="w-full sm:w-auto px-7 py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs sm:text-sm rounded-xl border border-slate-200 transition-all flex items-center justify-center gap-2 shadow-xs">
                    <span class="iconify text-lg text-blue-600" data-icon="lucide:search"></span>
                    <span>Lacak Status Tiket Aduan</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
