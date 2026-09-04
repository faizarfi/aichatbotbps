@extends('layouts.public')

@section('title', 'Layanan Konsultasi Statistik Online')
@section('meta_description', 'Layanan resmi konsultasi data statistik, publikasi berkala, jadwal PST, dan pengaduan BPS Kabupaten Karanganyar.')

@section('content')
<div class="max-w-4xl mx-auto px-1.5 sm:px-6 py-2 sm:py-6">
    {{-- Chat Box Card --}}
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col h-[calc(100dvh-5rem)] sm:h-[calc(100vh-160px)] min-h-[480px]">

        {{-- Chat Header (Official BPS Navy & Orange) --}}
        <div class="px-3 sm:px-6 py-3 sm:py-4 border-b-2 border-[#f7941d] bg-[#002b6a] flex items-center justify-between text-white shadow-md gap-2">
            <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0 flex-1">
                <div class="relative shrink-0">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-white p-1 sm:p-1.5 flex items-center justify-center shadow-xs">
                        <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-emerald-400 border-2 border-[#002b6a]"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <h2 class="text-xs sm:text-base font-black text-white tracking-tight leading-tight">Pelayanan Statistik Terpadu (PST)</h2>
                        <span id="chat-status-pill" class="shrink-0 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Layanan Terhubung</span>
                        </span>
                    </div>
                    <p class="text-[10px] sm:text-xs text-blue-200 truncate mt-0.5 font-medium">BPS Kabupaten Karanganyar • Konsultasi Data & Rujukan Resmi</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                {{-- Language Selector in Chat Header --}}
                <button type="button" onclick="openLanguageModal()" class="p-2 sm:px-3 sm:py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer shadow-xs" title="Pilih Bahasa / Select Language">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:globe"></span>
                    <span id="chat-lang-label" class="font-black text-xs uppercase text-white">ID</span>
                    <span class="iconify text-xs text-blue-200" data-icon="lucide:chevron-down"></span>
                </button>

                <button onclick="downloadConsultationPdf()" class="p-2 sm:px-3 sm:py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer shadow-xs" title="Unduh Lembar Rekapitulasi Konsultasi PDF">
                    <span class="iconify text-base text-[#f7941d]" data-icon="lucide:file-down"></span>
                    <span class="hidden sm:inline">Unduh PDF</span>
                </button>
                <button id="btn-request-officer" onclick="requestOfficerHandoff()" class="p-2 sm:px-3.5 sm:py-2 rounded-xl bg-[#f7941d] hover:bg-[#e07e0c] text-white text-xs font-bold border border-amber-600 transition-all flex items-center gap-1.5 active:scale-95 shadow-sm cursor-pointer" title="Hubungkan ke Petugas">
                    <span class="iconify text-base text-white" data-icon="lucide:headset"></span>
                    <span class="hidden sm:inline">Hubungi Petugas</span>
                </button>
                <button onclick="resetConversation()" class="p-2 sm:px-3 sm:py-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer" title="Mulai Sesi Baru">
                    <span class="iconify text-base" data-icon="lucide:refresh-cw"></span>
                    <span class="hidden md:inline">Mulai Baru</span>
                </button>
            </div>
        </div>

        {{-- Handover Waiting Indicator Bar (Tanda Merah Jika Belum Dialihkan Dari Admin) --}}
        <div id="handover-waiting-bar" class="hidden bg-rose-50 border-b border-rose-200 px-3.5 sm:px-6 py-2.5 flex items-center justify-between gap-3 text-rose-800 text-xs transition-all shadow-inner">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="relative flex h-3 w-3 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-600"></span>
                </span>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <strong class="font-bold text-rose-900">Menunggu Respon Petugas BPS</strong>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-200/70 text-rose-900 border border-rose-300">Belum Dialihkan oleh Admin</span>
                    </div>
                    <p class="text-rose-700 text-[11px] sm:text-xs mt-0.5 truncate">Permintaan Anda sedang dalam antrean. Anda dapat membatalkan dan kembali ke AI kapan saja.</p>
                </div>
            </div>
            <button type="button" onclick="cancelOfficerHandoff()" class="shrink-0 px-3 py-1.5 rounded-xl bg-white hover:bg-rose-100 text-rose-700 border border-rose-300 font-bold text-xs flex items-center gap-1.5 cursor-pointer shadow-xs transition-all active:scale-95" title="Batalkan Panggilan Petugas">
                <span class="iconify text-sm text-rose-600" data-icon="lucide:x-circle"></span>
                <span>Batalkan & Balik ke AI</span>
            </button>
        </div>

        {{-- Messages Scroll Area --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-3 sm:p-6 space-y-3.5 sm:space-y-5 bg-slate-50/70">

            {{-- Official Welcome Bubble --}}
            <div class="flex gap-2 sm:gap-3 max-w-2xl">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-xs p-1 mt-0.5">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0 space-y-1">
                    <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl rounded-tl-sm p-3.5 sm:p-5 shadow-xs text-slate-800 space-y-2.5 sm:space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="text-[10px] sm:text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="iconify text-sm" data-icon="lucide:check-circle-2"></span>
                                Layanan PST BPS Karanganyar
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">BPS Karanganyar</span>
                        </div>
                        <p class="text-xs sm:text-sm leading-relaxed">
                            Selamat datang di <strong>Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar</strong>.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Silakan ketik pertanyaan atau data statistik yang Anda cari (seperti <em>publikasi Karanganyar Dalam Angka</em>, jumlah penduduk, angka kemiskinan, PDRB, jadwal layanan, hingga pengaduan masyarakat).
                        </p>
                    </div>
                    <div class="flex items-center gap-1.5 text-[9px] sm:text-[10px] text-slate-400 font-semibold mt-1 ml-1.5">
                        <span class="inline-flex items-center gap-1 text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200 font-semibold">
                            <span class="iconify text-xs text-blue-600" data-icon="lucide:shield-check"></span> Layanan Resmi BPS
                        </span>
                        <span>•</span>
                        <span>Kabupaten Karanganyar</span>
                    </div>
                </div>
            </div>

            {{-- Quick Topic Chips --}}
            <div id="quick-questions-wrapper" class="ml-0 sm:ml-12 pt-1 space-y-2">
                <p class="text-[10px] sm:text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">PILIHAN TOPIK LAYANAN PST & STATISTIK 2026:</p>
                <div class="flex flex-wrap gap-1.5 sm:gap-2">
                    <button onclick="sendQuickMessage('Bagaimana syarat pengajuan data mikro skripsi mahasiswa tarif Rp0 sesuai PP 86/2021?')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-[#ea580c] bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-[#ea580c]" data-icon="lucide:database"></span>
                        <span>Data Mikro Tarif Rp0</span>
                    </button>
                    <button onclick="sendQuickMessage('Apa itu ROMANTIK dan bagaimana alur rekomendasi survei statistik untuk dinas/OPD?')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-[#00a651]" data-icon="lucide:file-check-2"></span>
                        <span>Rekomendasi ROMANTIK</span>
                    </button>
                    <button onclick="sendQuickMessage('Berapa panjang jalan rusak di Kabupaten Karanganyar tahun 2026?')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-[#003c80] border border-slate-200 hover:border-blue-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-[#003c80]" data-icon="lucide:route"></span>
                        <span>Jalan Rusak 2026</span>
                    </button>
                    <button onclick="sendQuickMessage('Berapa angka kemiskinan dan garis kemiskinan Karanganyar 2026?')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-[#003c80] border border-slate-200 hover:border-blue-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-rose-600" data-icon="lucide:trending-down"></span>
                        <span>Kemiskinan 7,92%</span>
                    </button>
                    <button onclick="sendQuickMessage('Bagaimana cara developer mengakses WebAPI BPS untuk integrasi data?')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-purple-800 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-purple-700" data-icon="lucide:code-2"></span>
                        <span>WebAPI Developer</span>
                    </button>
                    <button onclick="sendQuickMessage('Jadwal jam buka layanan PST dan kontak WhatsApp resmi')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-[#003c80] border border-slate-200 hover:border-blue-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-amber-600" data-icon="lucide:clock"></span>
                        <span>Jadwal & Kontak PST</span>
                    </button>
                    <button onclick="sendQuickMessage('Tampilkan grafik tren kemiskinan Karanganyar')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-sky-800 bg-sky-50 hover:bg-sky-100 border border-sky-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-[#0093dd]" data-icon="lucide:bar-chart-2"></span>
                        <span>Grafik Kemiskinan</span>
                    </button>
                    <button onclick="sendQuickMessage('Tampilkan grafik capaian IPM Karanganyar')" class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 active:scale-98 cursor-pointer">
                        <span class="iconify text-sm text-indigo-600" data-icon="lucide:line-chart"></span>
                        <span>Grafik IPM</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Processing / Loading Indicator --}}
        <div id="typing-indicator" class="hidden px-3 sm:px-6 py-2 bg-slate-50 border-t border-slate-100">
            <div class="flex items-center gap-2 text-slate-400">
                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200">
                    <span class="iconify text-[10px] sm:text-xs" data-icon="lucide:message-square-text"></span>
                </div>
                <div class="flex items-center gap-1.5 bg-white border border-slate-200 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 150ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 300ms;"></span>
                    <span class="text-[10px] sm:text-[11px] font-semibold text-slate-600 ml-1">Memproses permintaan data...</span>
                </div>
            </div>
        </div>

        {{-- Input Bar --}}
        <div class="p-2.5 sm:p-5 border-t border-slate-200 bg-white shadow-xs">
            <form id="public-chat-form" class="flex items-end gap-1.5 sm:gap-2.5">
                @csrf
                <div class="flex-1 relative">
                    <textarea id="public-chat-input"
                              rows="1"
                              maxlength="1000"
                              placeholder="Ketik permintaan data atau pertanyaan statistik di sini..."
                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 pr-12 rounded-xl sm:rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none transition-all shadow-xs leading-relaxed"
                              style="max-height: 100px;"></textarea>
                    <span id="chat-char-counter" class="absolute bottom-2.5 right-2.5 text-[9px] sm:text-[10px] text-slate-400 font-mono">0/1000</span>
                </div>

                {{-- Microphone Voice Input Button --}}
                <button type="button" id="voice-input-btn" onclick="toggleVoiceRecording()" title="Bicara dengan Suara (Voice-to-Text)"
                        class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 border border-slate-300 flex items-center justify-center transition-all shrink-0 cursor-pointer relative group">
                    <span id="voice-mic-icon" class="iconify text-lg sm:text-xl group-hover:scale-110 transition-transform" data-icon="lucide:mic"></span>
                    <span id="voice-pulse-ring" class="hidden absolute inset-0 rounded-xl sm:rounded-2xl border-2 border-rose-500 animate-ping"></span>
                </button>

                <button type="submit" id="public-send-btn"
                        class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-[#005b9f] hover:bg-[#04325e] active:scale-95 text-white flex items-center justify-center transition-all shrink-0 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                        disabled>
                    <span class="iconify text-lg sm:text-xl" data-icon="lucide:send"></span>
                </button>
            </form>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 mt-2 pt-2 text-[10px] sm:text-[11px] text-slate-400 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1">
                        <span class="iconify text-emerald-600 text-xs sm:text-sm shrink-0" data-icon="lucide:shield-check"></span>
                        <span class="truncate">Data resmi BPS Karanganyar.</span>
                    </span>
                    <span class="text-slate-300">•</span>
                    <button type="button" onclick="openLanguageModal()" class="text-slate-600 hover:text-[#005b9f] font-bold flex items-center gap-1 bg-slate-100 hover:bg-blue-50 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors cursor-pointer shadow-2xs active:scale-95" title="Ubah Bahasa AI & Halaman">
                        <span class="iconify text-xs text-[#005b9f]" data-icon="lucide:globe"></span>
                        <span class="text-[10px] text-slate-400">Bahasa:</span>
                        <span id="chat-input-lang-badge" class="font-black text-[10px] uppercase text-slate-800">ID</span>
                        <span class="iconify text-[10px] text-slate-400" data-icon="lucide:chevron-down"></span>
                    </button>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('aduan.create') }}" class="text-blue-600 font-bold hover:underline flex items-center gap-1">
                        <span class="iconify" data-icon="lucide:ticket"></span> Buat Aduan
                    </a>
                    <span>•</span>
                    <a href="{{ route('kebijakan-privasi') }}" class="text-slate-400 hover:text-slate-600 hover:underline">
                        Privasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const messagesArea = document.getElementById('chat-messages');
const chatForm = document.getElementById('public-chat-form');
const chatInput = document.getElementById('public-chat-input');
const sendBtn = document.getElementById('public-send-btn');
const typingIndicator = document.getElementById('typing-indicator');
const charCounter = document.getElementById('chat-char-counter');
const statusPill = document.getElementById('chat-status-pill');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const seenMessageIds = new Set();
let isPollingHistory = false;

// Retrieve or generate visitor session token
let visitorSession = localStorage.getItem('bps_chat_session');
if (!visitorSession) {
    visitorSession = 'bps_' + Math.random().toString(36).substring(2, 15) + '_' + Date.now();
    localStorage.setItem('bps_chat_session', visitorSession);
}

// Auto-expand textarea & enable button
chatInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    sendBtn.disabled = this.value.trim().length === 0;
    charCounter.textContent = this.value.length + '/1000';
});

chatInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        if (this.value.trim().length > 0) {
            chatForm.dispatchEvent(new Event('submit'));
        }
    }
});

function scrollToBottom() {
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
}


function replaceIconsAndFilterEmojis(text) {
    if (!text) return '';

    // 1. Ubah tag [icon:name] menjadi elemen icon Lucide
    let res = text.replace(/\[icon:([a-z0-9\-]+)\]/gi, '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:$1"></span>');

    // 2. Konversi emoji yang sering muncul menjadi Lucide Icons
    const emojiMap = {
        '📊': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:bar-chart-2"></span>',
        '📈': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:trending-up"></span>',
        '📉': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:trending-down"></span>',
        '📌': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:bookmark"></span>',
        '🛣️': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:route"></span>',
        '🛣': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:route"></span>',
        'ℹ️': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:info"></span>',
        'ℹ': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:info"></span>',
        '🏛️': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:landmark"></span>',
        '🏛': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:landmark"></span>',
        '📍': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:map-pin"></span>',
        '📅': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:calendar"></span>',
        '📞': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:phone"></span>',
        '✉️': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:mail"></span>',
        '✉': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:mail"></span>',
        '🔗': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:external-link"></span>',
        '🔍': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:search"></span>',
        '💡': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:lightbulb"></span>',
        '🧠': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:sparkles"></span>',
        '⚠️': '<span class="iconify text-amber-600 inline-block align-middle mr-1.5" data-icon="lucide:alert-circle"></span>',
        '🌾': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:wheat"></span>',
        '👥': '<span class="iconify text-blue-600 inline-block align-middle mr-1.5" data-icon="lucide:users"></span>',
    };

    for (const [emoji, iconHtml] of Object.entries(emojiMap)) {
        res = res.split(emoji).join(iconHtml);
    }

    // 3. Bersihkan sisa emoji Unicode grafis lainnya agar bebas emoji
    res = res.replace(/[\u{1F300}-\u{1F6FF}\u{1F900}-\u{1F9FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/gu, '');

    return res;
}

function formatBotContent(raw) {
    if (!raw) return '';

    let cleaned = replaceIconsAndFilterEmojis(raw);

    // 1. Prioritaskan Marked.js bila library sudah dimuat
    if (typeof marked !== 'undefined' && typeof marked.parse === 'function') {
        try {
            const renderer = new marked.Renderer();
            // Hindari teks bold hitam yang kaku, ganti dengan tipografi natural dan nyaman dibaca
            renderer.strong = function(token) {
                const text = typeof token === 'object' ? token.text : token;
                return '<span class="font-medium text-slate-800">' + text + '</span>';
            };
            marked.use({ renderer });
            marked.setOptions({
                gfm: true,
                breaks: true,
                smartypants: false
            });
            let html = marked.parse(cleaned);
            html = html.replace(/<a /g, '<a target="_blank" rel="noopener" class="text-blue-600 font-medium hover:underline inline-flex items-center gap-1" ');
            return html;
        } catch (e) {
            console.warn('Marked parser warning:', e);
        }
    }

    // 2. Parser Markdown Pure JS Fallback
    let text = cleaned;
    text = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
    text = text.replace(/^######[ \t]+(.*)$/gim, '<h6 class="text-xs font-semibold text-slate-800 mt-2.5 mb-1">$1</h6>');
    text = text.replace(/^#####[ \t]+(.*)$/gim, '<h5 class="text-xs font-semibold text-slate-800 mt-2.5 mb-1">$1</h5>');
    text = text.replace(/^####[ \t]+(.*)$/gim, '<h4 class="text-xs font-semibold text-blue-900 mt-3 mb-1">$1</h4>');
    text = text.replace(/^###[ \t]+(.*)$/gim, '<h3 class="text-sm font-semibold text-blue-800 mt-3.5 mb-1.5">$1</h3>');
    text = text.replace(/^##[ \t]+(.*)$/gim, '<h2 class="text-base font-semibold text-slate-900 mt-4 mb-2 pb-1 border-b border-slate-100">$1</h2>');
    text = text.replace(/^#[ \t]+(.*)$/gim, '<h1 class="text-lg font-semibold text-slate-900 mt-4 mb-2 pb-1 border-b border-slate-200">$1</h1>');

    // Bold (**text**) diubah menjadi teks mengalir natural tanpa cetak tebal hitam
    text = text.replace(/\*\*(.*?)\*\*/g, '<span class="font-medium text-slate-800">$1</span>');
    text = text.replace(/__(.*?)__/g, '<span class="font-medium text-slate-800">$1</span>');

    text = text.replace(/(^|[^\*])\*([^\*\n]+)\*([^\*]|$)/g, '$1<em class="italic text-slate-700">$2</em>$3');
    text = text.replace(/(^|[^_])_([^_\n]+)_([^_]|$)/g, '$1<em class="italic text-slate-700">$2</em>$3');

    text = text.replace(/^[ \t]*[\*\-\+][ \t]+(.*)$/gim, '<li class="my-1">$1</li>');
    text = text.replace(/^[ \t]*(\d+)\.[ \t]+(.*)$/gim, '<li class="my-1" value="$1">$2</li>');

    text = text.replace(/(<li class="my-1"[^>]*>[\s\S]*?<\/li>\s*)+/gi, function(match) {
        if (match.includes('value="')) {
            return '<ol class="list-decimal pl-5 my-2 space-y-1">' + match + '</ol>';
        }
        return '<ul class="list-disc pl-5 my-2 space-y-1">' + match + '</ul>';
    });

    text = text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener" class="text-blue-600 font-medium hover:underline inline-flex items-center gap-1">$1 <span class="iconify" data-icon="lucide:external-link"></span></a>');
    text = text.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 bg-slate-100 text-slate-800 rounded text-xs font-mono border border-slate-200">$1</code>');
    text = text.replace(/\n\n/g, '<div class="h-2"></div>');
    text = text.replace(/\n/g, '<br>');

    return text;
}

function appendMessageElement(type, content, sources, messageId = null, time = null, senderName = null, feedback = null, quickOptions = [], chartDataParam = null) {
    const isVisitor = type === 'visitor';
    const isOfficer = type === 'officer';
    const formattedTime = time || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

    const wrapper = document.createElement('div');
    wrapper.className = `flex ${isVisitor ? 'justify-end' : 'justify-start'} chat-msg-wrapper`;
    
    if (messageId) {
        wrapper.id = 'msg-box-' + messageId;
    }

    let canvasId = '';
    let chartData = chartDataParam;

    if (isVisitor) {
        wrapper.innerHTML = `
            <div class="max-w-[88%] sm:max-w-lg">
                <div class="bg-[#005b9f] text-white rounded-2xl sm:rounded-3xl rounded-tr-sm px-4 sm:px-5 py-3 sm:py-3.5 shadow-sm">
                    <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium break-words">${escapeHtml(content)}</p>
                </div>
                <span class="text-[9px] sm:text-[10px] text-slate-400 font-semibold mt-1 block text-right mr-1.5">Anda • ${formattedTime}</span>
            </div>
        `;
    } else if (isOfficer) {
        wrapper.innerHTML = `
            <div class="flex gap-2 sm:gap-3 max-w-[92%] sm:max-w-xl">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl sm:rounded-2xl bg-blue-700 text-white flex items-center justify-center shrink-0 font-black text-xs shadow-sm">
                    ${escapeHtml((senderName || 'P').substring(0, 1))}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="bg-white border-2 border-blue-400 rounded-2xl sm:rounded-3xl rounded-tl-sm p-3.5 sm:p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-1.5 text-xs font-bold text-blue-700 mb-2 pb-1.5 border-b border-blue-100">
                            <div class="flex items-center gap-1.5 truncate">
                                <span class="iconify text-base" data-icon="lucide:user-check"></span>
                                <span class="truncate">${escapeHtml(senderName || 'Petugas BPS Karanganyar')}</span>
                            </div>
                            <button type="button" onclick="copyMessageText(this)" data-content="${escapeHtml(content)}" title="Salin Pesan Petugas" class="btn-copy px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 hover:bg-emerald-50 text-blue-700 hover:text-emerald-700 border border-blue-200 hover:border-emerald-200 transition-all flex items-center gap-1 cursor-pointer shadow-xs shrink-0">
                                <span class="iconify text-xs sm:text-sm" data-icon="lucide:copy"></span>
                                <span class="btn-copy-label">Salin</span>
                            </button>
                        </div>
                        <div class="chat-content-body text-xs sm:text-sm break-words">${formatBotContent(content)}</div>
                    </div>
                    <span class="text-[9px] sm:text-[10px] text-slate-400 font-semibold mt-1 block ml-1.5">Petugas Resmi • ${formattedTime}</span>
                </div>
            </div>
        `;
    } else {
        // Bot / System Message
        let displayContent = content;

        // Ekstraksi data grafik jika terdapat format ```chart
        const chartMatch = content.match(/```chart\s*(\{[\s\S]*?\})\s*```/);
        if (chartMatch) {
            try {
                chartData = JSON.parse(chartMatch[1]);
                displayContent = content.replace(/```chart\s*\{[\s\S]*?\}\s*```/g, '').trim();
            } catch (err) {
                console.error("Gagal memproses data grafik:", err);
            }
        }

        let chartBoxHtml = '';
        if (chartData && chartData.labels && chartData.data) {
            canvasId = 'chart-box-' + Math.random().toString(36).substring(2, 9);
            chartBoxHtml = `
                <div class="mt-3 p-3 sm:p-4 bg-slate-50 border border-slate-200/90 rounded-2xl shadow-xs">
                    <div class="flex items-center justify-between gap-2 mb-2 pb-1.5 border-b border-slate-200">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span class="iconify text-base text-blue-600" data-icon="${chartData.type === 'line' ? 'lucide:trending-up' : 'lucide:bar-chart-2'}"></span>
                            <span class="truncate">${escapeHtml(chartData.title || 'Visualisasi Data BPS Karanganyar')}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-100 text-blue-800 shrink-0">
                            Data BPS
                        </span>
                    </div>
                    <div class="relative h-52 sm:h-60 w-full">
                        <canvas id="${canvasId}"></canvas>
                    </div>
                    ${chartData.description ? `<p class="mt-2 text-[10px] text-slate-500 font-medium italic text-right">${escapeHtml(chartData.description)}</p>` : ''}
                </div>
            `;
        }

        let sourcesHtml = '';
        if (sources && sources.length > 0) {
            sourcesHtml = `
                <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-bold text-slate-700 block mb-1">Rujukan Dokumen Resmi:</span>
                    ${sources.map(s => `<a href="${s.url || '#'}" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 mt-1 font-medium"><span class="iconify text-xs" data-icon="lucide:file-text"></span> ${escapeHtml(s.title)}</a>`).join('')}
                </div>
            `;
        }

        let quickChipsHtml = '';
        if (quickOptions && quickOptions.length > 0) {
            quickChipsHtml = `
                <div class="mt-3 pt-2 border-t border-slate-100 flex flex-wrap gap-1.5">
                    ${quickOptions.map(opt => `<button onclick="sendQuickMessage('${escapeHtml(opt)}')" class="px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors flex items-center gap-1 cursor-pointer"><span>${escapeHtml(opt)}</span></button>`).join('')}
                </div>
            `;
        }

        let feedbackHtml = '';
        if (messageId) {
            feedbackHtml = `
                <div id="feedback-box-${messageId}" class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span class="font-medium">Apakah jawaban ini akurat & membantu?</span>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="submitFeedback(${messageId}, 'helpful')" title="Membantu" class="p-1 rounded hover:bg-slate-100 text-slate-400 hover:text-emerald-600 transition-colors cursor-pointer">
                            <span class="iconify text-sm" data-icon="lucide:thumbs-up"></span>
                        </button>
                        <button type="button" onclick="submitFeedback(${messageId}, 'not_helpful')" title="Kurang Membantu" class="p-1 rounded hover:bg-slate-100 text-slate-400 hover:text-rose-500 transition-colors cursor-pointer">
                            <span class="iconify text-sm" data-icon="lucide:thumbs-down"></span>
                        </button>
                    </div>
                </div>
            `;
        }

        // Clean text content for text-to-speech
        const rawContentSafe = displayContent.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '');

        wrapper.innerHTML = `
            <div class="flex gap-2 sm:gap-3 max-w-2xl">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-xs p-1 mt-0.5">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0 flex-1">
                    <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl rounded-tl-sm p-3.5 sm:p-5 shadow-xs">
                        <div class="flex items-center justify-between gap-1.5 mb-2 pb-2 border-b border-slate-100">
                            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider truncate flex items-center gap-1.5">
                                <span class="iconify text-sm" data-icon="lucide:check-circle-2"></span>
                                Layanan PST BPS Karanganyar
                            </span>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="copyMessageText(this)" data-content="${escapeHtml(rawContentSafe)}" title="Salin Teks Jawaban" class="btn-copy px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-200 transition-all flex items-center gap-1 cursor-pointer shadow-xs">
                                    <span class="iconify text-xs sm:text-sm" data-icon="lucide:copy"></span>
                                    <span class="btn-copy-label">Salin</span>
                                </button>
                                <button type="button" onclick="speakText('${rawContentSafe}', this)" title="Dengarkan Suara (Text-to-Speech)" class="shrink-0 btn-tts px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-700 border border-slate-200 transition-all flex items-center gap-1 cursor-pointer">
                                    <span class="iconify text-xs sm:text-sm" data-icon="lucide:volume-2"></span>
                                    <span>Dengarkan</span>
                                </button>
                            </div>
                        </div>
                        <div class="chat-content-body text-xs sm:text-sm break-words">${formatBotContent(displayContent)}</div>
                        ${chartBoxHtml}
                        ${sourcesHtml}
                        ${quickChipsHtml}
                        ${feedbackHtml}
                    </div>
                    <div class="flex items-center gap-1.5 text-[9px] sm:text-[10px] text-slate-400 font-semibold mt-1 ml-1.5">
                        <span class="inline-flex items-center gap-1 text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 font-semibold">
                            <span class="iconify text-xs text-blue-600" data-icon="lucide:shield-check"></span> Layanan BPS
                        </span>
                        <span>•</span>
                        <span>${formattedTime}</span>
                    </div>
                </div>
            </div>
        `;
    }

    messagesArea.appendChild(wrapper);
    if (canvasId && chartData) {
        renderMessageChart(canvasId, chartData);
    }
    if (window.Iconify && typeof window.Iconify.scan === 'function') {
        window.Iconify.scan(wrapper);
    }
    scrollToBottom();
}

// -------------------------------------------------------------
// VOICE INPUT (SPEECH-TO-TEXT) USING WEB SPEECH API
// -------------------------------------------------------------
// MULTI-LANGUAGE HELPER & SPEECH MAPPING
// -------------------------------------------------------------
function getActiveLanguage() {
    return localStorage.getItem('bps_selected_lang') || 'id';
}

const speechLangMap = {
    'id': 'id-ID',
    'en': 'en-US',
    'ar': 'ar-SA',
    'ja': 'ja-JP',
    'zh-CN': 'zh-CN',
    'zh-TW': 'zh-TW',
    'de': 'de-DE',
    'fr': 'fr-FR',
    'es': 'es-ES',
    'ko': 'ko-KR',
    'ru': 'ru-RU',
    'nl': 'nl-NL',
    'tr': 'tr-TR',
    'pt': 'pt-PT',
    'it': 'it-IT',
    'vi': 'vi-VN',
    'th': 'th-TH',
    'ms': 'ms-MY',
    'jw': 'jv-ID',
    'su': 'su-ID',
    'hi': 'hi-IN',
    'sv': 'sv-SE',
    'cs': 'cs-CZ',
    'el': 'el-GR',
    'hu': 'hu-HU',
    'ro': 'ro-RO',
    'da': 'da-DK',
    'fi': 'fi-FI',
    'no': 'nb-NO',
    'he': 'he-IL',
    'fa': 'fa-IR',
    'ur': 'ur-PK',
    'bn': 'bn-BD',
    'ta': 'ta-IN',
    'te': 'te-IN',
    'my': 'my-MM',
    'km': 'km-KH',
    'lo': 'lo-LA',
    'ne': 'ne-NP',
    'si': 'si-LK',
    'sw': 'sw-KE',
    'af': 'af-ZA',
    'hr': 'hr-HR',
    'sk': 'sk-SK',
    'bg': 'bg-BG',
    'sr': 'sr-RS'
};

function getSpeechLang() {
    const lang = getActiveLanguage();
    return speechLangMap[lang] || (lang.includes('-') ? lang : `${lang}-${lang.toUpperCase()}`);
}

window.addEventListener('bps-language-changed', function(e) {
    const lang = e.detail?.lang || 'id';
    const badge = lang.toUpperCase();
    const chatBadge = document.getElementById('chat-lang-label');
    if (chatBadge) chatBadge.textContent = badge;
    const inputBadge = document.getElementById('chat-input-lang-badge');
    if (inputBadge) inputBadge.textContent = badge;
});

// -------------------------------------------------------------
// VOICE INPUT (SPEECH-TO-TEXT)
// -------------------------------------------------------------
let recognition = null;
let isRecording = false;

if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = getSpeechLang();
    recognition.continuous = false;
    recognition.interimResults = true;

    recognition.onstart = function() {
        isRecording = true;
        const micIcon = document.getElementById('voice-mic-icon');
        const pulseRing = document.getElementById('voice-pulse-ring');
        const voiceBtn = document.getElementById('voice-input-btn');
        if (pulseRing) pulseRing.classList.remove('hidden');
        if (voiceBtn) voiceBtn.className = 'h-12 w-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-400 flex items-center justify-center transition-all shrink-0 cursor-pointer relative';
        if (micIcon) micIcon.setAttribute('data-icon', 'lucide:mic-off');
        chatInput.placeholder = 'Sedang mendengarkan suara Anda... (Bicaralah sekarang)';
    };

    recognition.onresult = function(event) {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            transcript += event.results[i][0].transcript;
        }
        chatInput.value = transcript;
        chatInput.dispatchEvent(new Event('input'));
    };

    recognition.onerror = function(event) {
        console.warn('Speech recognition error:', event.error);
        stopVoiceRecording();
    };

    recognition.onend = function() {
        stopVoiceRecording();
    };
}

function toggleVoiceRecording() {
    if (!recognition) {
        Swal.fire({
            icon: 'info',
            title: 'Fitur Mikrofon',
            text: 'Browser Anda belum mendukung Web Speech Recognition. Disarankan menggunakan Google Chrome atau Microsoft Edge terbaru.',
            confirmButtonColor: '#2563eb'
        });
        return;
    }

    if (isRecording) {
        recognition.stop();
    } else {
        try {
            recognition.lang = getSpeechLang();
            recognition.start();
        } catch (e) {
            console.warn(e);
        }
    }
}

function stopVoiceRecording() {
    isRecording = false;
    const micIcon = document.getElementById('voice-mic-icon');
    const pulseRing = document.getElementById('voice-pulse-ring');
    const voiceBtn = document.getElementById('voice-input-btn');
    if (pulseRing) pulseRing.classList.add('hidden');
    if (voiceBtn) voiceBtn.className = 'h-12 w-12 rounded-2xl bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 border border-slate-300 flex items-center justify-center transition-all shrink-0 cursor-pointer relative group';
    if (micIcon) micIcon.setAttribute('data-icon', 'lucide:mic');
    chatInput.placeholder = 'Ketik pertanyaan Anda atau tekan ikon mikrofon untuk berbicara...';
}

// -------------------------------------------------------------
// TEXT-TO-SPEECH (SPEECH SYNTHESIS)
// -------------------------------------------------------------
let currentUtterance = null;
let activeSpeakerBtn = null;

function speakText(rawText, btnElement) {
    if (!('speechSynthesis' in window)) {
        Swal.fire({
            icon: 'info',
            title: 'Fitur Suara',
            text: 'Perangkat atau browser Anda belum mendukung sintesis suara (Text-to-Speech).',
            confirmButtonColor: '#2563eb'
        });
        return;
    }

    // If currently speaking this message, stop it
    if (window.speechSynthesis.speaking && activeSpeakerBtn === btnElement) {
        window.speechSynthesis.cancel();
        resetSpeakerButton(btnElement);
        return;
    }

    // Cancel any previous speech
    window.speechSynthesis.cancel();
    if (activeSpeakerBtn) {
        resetSpeakerButton(activeSpeakerBtn);
    }

    // Clean Markdown tags, HTML tags, and asterisks for smooth speaking
    let cleanText = rawText
        .replace(/<[^>]*>/g, ' ')
        .replace(/\*\*/g, '')
        .replace(/\*/g, '')
        .replace(/\[([^\]]+)\]\([^)]+\)/g, '$1')
        .replace(/[•\-_]/g, ' ')
        .trim();

    currentUtterance = new SpeechSynthesisUtterance(cleanText);
    currentUtterance.lang = getSpeechLang();
    currentUtterance.rate = 1.0;
    currentUtterance.pitch = 1.0;

    btnElement.innerHTML = `<span class="iconify text-sm text-rose-600 animate-pulse" data-icon="lucide:square"></span> <span class="text-rose-600">Berhenti</span>`;
    activeSpeakerBtn = btnElement;

    currentUtterance.onend = function() {
        resetSpeakerButton(btnElement);
    };

    currentUtterance.onerror = function() {
        resetSpeakerButton(btnElement);
    };

    window.speechSynthesis.speak(currentUtterance);
}

function resetSpeakerButton(btn) {
    if (btn) {
        btn.innerHTML = `<span class="iconify text-sm" data-icon="lucide:volume-2"></span> <span>Dengarkan</span>`;
    }
    activeSpeakerBtn = null;
}

function sendQuickMessage(text) {
    chatInput.value = text;
    chatForm.dispatchEvent(new Event('submit'));
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;

    const qw = document.getElementById('quick-questions-wrapper');
    if (qw) qw.classList.add('hidden');

    const tempMsgId = 'temp-' + Date.now();
    appendMessageElement('visitor', text, [], tempMsgId);

    chatInput.value = '';
    chatInput.style.height = 'auto';
    sendBtn.disabled = true;
    charCounter.textContent = '0/1000';

    typingIndicator.classList.remove('hidden');
    scrollToBottom();

    fetch('{{ route("chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message: text,
            session: visitorSession,
            language: getActiveLanguage()
        })
    })
    .then(r => r.json())
    .then(data => {
        typingIndicator.classList.add('hidden');
        if (data.session) {
            visitorSession = data.session;
            localStorage.setItem('bps_chat_session', visitorSession);
        }

        if (data.status) {
            updateStatusBadge(data.status, data.officer_name);
        }

        // Tautkan id visitor asli dari database agar polling tidak menduplikasinya
        if (data.visitor_message && data.visitor_message.id) {
            seenMessageIds.add(data.visitor_message.id);
            const tempEl = document.getElementById('msg-box-' + tempMsgId);
            if (tempEl) {
                tempEl.id = 'msg-box-' + data.visitor_message.id;
            }
        }

        const botMsg = data.bot_message || (typeof data.reply === 'object' ? data.reply : { content: data.reply, sources: data.sources || [], id: null });
        const replyText = botMsg?.content || (typeof data.reply === 'string' ? data.reply : '');
        const replySources = botMsg?.sources || data.sources || [];
        const replyId = botMsg?.id || null;
        const replyTime = botMsg?.created_at || null;

        if (replyId) {
            seenMessageIds.add(replyId);
        }

        if (replyText) {
            appendMessageElement('bot', replyText, replySources, replyId, replyTime, null, null, data.quick_options || [], botMsg?.chart || data.chart || null);
        }
    })
    .catch(() => {
        typingIndicator.classList.add('hidden');
        appendMessageElement('bot', 'Maaf, terjadi gangguan jaringan saat memproses pertanyaan Anda. Silakan coba kirim ulang pertanyaan.');
    });
});

function updateStatusBadge(status, officerName = null) {
    const pill = document.getElementById('chat-status-pill');
    const waitingBar = document.getElementById('handover-waiting-bar');
    const officerBtn = document.getElementById('btn-request-officer');
    if (!pill) return;

    if (status === 'waiting') {
        // Tanda merah: Menunggu respon / Belum dialihkan dari admin
        pill.className = 'shrink-0 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-rose-500/25 text-rose-200 border border-rose-500/50 flex items-center gap-1.5 shadow-xs';
        pill.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span><span>Menunggu Respon Petugas</span>`;

        if (waitingBar) waitingBar.classList.remove('hidden');

        if (officerBtn) {
            officerBtn.className = 'p-2 sm:px-3.5 sm:py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold border border-rose-700 transition-all flex items-center gap-1.5 active:scale-95 shadow-sm cursor-pointer';
            officerBtn.setAttribute('onclick', 'cancelOfficerHandoff()');
            officerBtn.setAttribute('title', 'Batalkan Antrean Petugas dan Balik ke AI');
            officerBtn.innerHTML = `<span class="iconify text-base text-white" data-icon="lucide:x-circle"></span><span class="hidden sm:inline">Batalkan Antrean</span>`;
        }
    } else if (status === 'handled') {
        // Petugas admin telah mengambil alih
        pill.className = 'shrink-0 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-blue-500/25 text-blue-200 border border-blue-500/50 flex items-center gap-1.5 shadow-xs';
        pill.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span><span>Terhubung: ${escapeHtml(officerName || 'Petugas BPS')}</span>`;

        if (waitingBar) waitingBar.classList.add('hidden');

        if (officerBtn) {
            officerBtn.className = 'p-2 sm:px-3.5 sm:py-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold border border-slate-800 transition-all flex items-center gap-1.5 active:scale-95 shadow-sm cursor-pointer';
            officerBtn.setAttribute('onclick', 'cancelOfficerHandoff()');
            officerBtn.setAttribute('title', 'Kembali ke Chatbot AI');
            officerBtn.innerHTML = `<span class="iconify text-base text-white" data-icon="lucide:bot"></span><span class="hidden sm:inline">Kembali ke AI</span>`;
        }
    } else {
        // Mode normal AI Chatbot
        pill.className = 'shrink-0 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1';
        pill.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span><span>Layanan AI Aktif</span>`;

        if (waitingBar) waitingBar.classList.add('hidden');

        if (officerBtn) {
            officerBtn.className = 'p-2 sm:px-3.5 sm:py-2 rounded-xl bg-[#f7941d] hover:bg-[#e07e0c] text-white text-xs font-bold border border-amber-600 transition-all flex items-center gap-1.5 active:scale-95 shadow-sm cursor-pointer';
            officerBtn.setAttribute('onclick', 'requestOfficerHandoff()');
            officerBtn.setAttribute('title', 'Hubungkan ke Petugas');
            officerBtn.innerHTML = `<span class="iconify text-base text-white" data-icon="lucide:headset"></span><span class="hidden sm:inline">Hubungi Petugas</span>`;
        }
    }
    if (window.Iconify && typeof window.Iconify.scan === 'function') {
        window.Iconify.scan(pill);
        if (officerBtn) window.Iconify.scan(officerBtn);
    }
}

function requestOfficerHandoff() {
    Swal.fire({
        title: 'Hubungi Petugas BPS?',
        text: 'Percakapan Anda akan dialihkan ke antrean petugas BPS Karanganyar untuk ditanggapi langsung pada jam kerja (Senin–Jumat, 08.00–15.30 WIB).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#005b9f',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hubungkan Petugas',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("chat.request-officer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ session: visitorSession })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    updateStatusBadge(data.status);
                }
                if (data.message) {
                    appendMessageElement('bot', data.message.content, [], data.message.id, data.message.created_at);
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Permintaan Diteruskan',
                    text: 'Anda telah masuk ke antrean petugas BPS Karanganyar. Status saat ini menunggu konfirmasi dan respon petugas.',
                    confirmButtonColor: '#005b9f'
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengirim permintaan ke petugas. Silakan coba lagi.',
                    confirmButtonColor: '#005b9f'
                });
            });
        }
    });
}

function cancelOfficerHandoff() {
    Swal.fire({
        title: 'Kembali ke Chatbot AI?',
        text: 'Antrean panggilan ke petugas akan dibatalkan dan Anda dapat langsung bertanya kembali dengan Asisten AI BPS Karanganyar.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#005b9f',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Kembali ke AI',
        cancelButtonText: 'Tetap Antre',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("chat.cancel-officer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ session: visitorSession })
            })
            .then(r => r.json())
            .then(data => {
                updateStatusBadge('active');
                if (data.message) {
                    appendMessageElement('bot', data.message.content, [], data.message.id, data.message.created_at);
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Antrean Dibatalkan',
                    text: 'Anda telah dialihkan kembali ke layanan AI Chatbot BPS Karanganyar.',
                    confirmButtonColor: '#005b9f'
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Membatalkan',
                    text: 'Terjadi kesalahan jaringan saat membatalkan antrean.',
                    confirmButtonColor: '#005b9f'
                });
            });
        }
    });
}

function submitFeedback(messageId, rating) {
    fetch('{{ route("chat.feedback") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message_id: messageId,
            rating: rating
        })
    })
    .then(r => r.json())
    .then(() => {
        const box = document.getElementById('feedback-box-' + messageId);
        if (box) {
            box.innerHTML = `<span class="text-[11px] text-emerald-600 font-bold flex items-center gap-1"><span class="iconify" data-icon="lucide:check-circle"></span> Terima kasih atas penilaian Anda!</span>`;
        }
        Toast.fire({
            icon: 'success',
            title: 'Terima kasih atas penilaian Anda!'
        });
    })
    .catch(() => {});
}

function resetConversation() {
    Swal.fire({
        title: 'Mulai Sesi Percakapan Baru?',
        text: 'Riwayat percakapan saat ini akan ditutup dan Anda dapat memulai topik pertanyaan baru.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Mulai Baru',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.removeItem('bps_chat_session');
            window.location.reload();
        }
    });
}

function renderMessageChart(canvasId, chartData) {
    setTimeout(() => {
        const ctx = document.getElementById(canvasId);
        if (!ctx || typeof Chart === 'undefined') return;

        const isLine = chartData.type === 'line';
        const colorPalette = ['#0284c7', '#0369a1', '#0ea5e9', '#059669', '#10b981', '#f59e0b', '#6366f1'];

        new Chart(ctx, {
            type: chartData.type || 'bar',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: chartData.title || 'Nilai',
                    data: chartData.data || [],
                    borderColor: '#0284c7',
                    backgroundColor: isLine ? 'rgba(2, 132, 199, 0.12)' : (chartData.labels.length > 1 ? chartData.labels.map((_, i) => colorPalette[i % colorPalette.length]) : '#0284c7'),
                    borderWidth: isLine ? 3 : 1,
                    fill: isLine,
                    tension: 0.35,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0284c7',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderRadius: isLine ? 0 : 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 8,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return (context.dataset.label || '') + ': ' + context.parsed.y + (chartData.unit ? ' ' + chartData.unit : '');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#64748b' }
                    },
                    y: {
                        grid: { color: 'rgba(226, 232, 240, 0.8)' },
                        ticks: {
                            font: { size: 10 },
                            color: '#64748b',
                            callback: function(value) {
                                return value + (chartData.unit ? ' ' + chartData.unit : '');
                            }
                        }
                    }
                }
            }
        });
    }, 80);
}

function downloadConsultationPdf() {
    const session = localStorage.getItem('bps_chat_session') || visitorSession;
    if (!session) {
        Swal.fire({
            icon: 'info',
            title: 'Belum Ada Percakapan',
            text: 'Silakan mulai bertanya data statistik terlebih dahulu untuk mengunduh bukti konsultasi.',
            confirmButtonColor: '#0284c7'
        });
        return;
    }
    window.open('{{ route("chat.download-pdf") }}?session=' + encodeURIComponent(session), '_blank');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function copyMessageText(btn) {
    let text = btn.getAttribute('data-content') || '';
    // Clean out chart blocks, icon tags, and excessive asterisks for pure clean text
    let cleanText = text
        .replace(/```chart\s*\{[\s\S]*?\}\s*```/g, '')
        .replace(/\[icon:[a-z0-9\-]+\]/gi, '')
        .replace(/^[ \t]*[\*\-\+][ \t]+/gm, '• ')
        .replace(/\*\*(.*?)\*\*/g, '$1')
        .replace(/<[^>]+>/g, '')
        .trim();

    const doSuccess = () => {
        const label = btn.querySelector('.btn-copy-label');
        const icon = btn.querySelector('.iconify');
        const origClasses = btn.className;

        btn.className = 'btn-copy px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-300 transition-all flex items-center gap-1 cursor-pointer shadow-xs';
        if (label) label.textContent = 'Tersalin!';
        if (icon) icon.setAttribute('data-icon', 'lucide:check');

        Toast.fire({
            icon: 'success',
            title: 'Teks jawaban berhasil disalin!'
        });

        setTimeout(() => {
            btn.className = origClasses;
            if (label) label.textContent = 'Salin';
            if (icon) icon.setAttribute('data-icon', 'lucide:copy');
        }, 2200);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(cleanText).then(doSuccess).catch(() => fallbackCopy(cleanText, doSuccess));
    } else {
        fallbackCopy(cleanText, doSuccess);
    }
}

function fallbackCopy(text, callback) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try {
        document.execCommand('copy');
        if (typeof callback === 'function') callback();
    } catch (e) {
        console.error('Fallback copy failed', e);
    }
    document.body.removeChild(ta);
}

function pollHistory() {
    if (isPollingHistory) return;
    const session = localStorage.getItem('bps_chat_session') || visitorSession;
    if (!session) return;

    isPollingHistory = true;
    fetch('{{ route("chat.messages") }}?session=' + encodeURIComponent(session), {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        isPollingHistory = false;
        if (data.status) {
            updateStatusBadge(data.status, data.officer_name);
        }
        if (Array.isArray(data.messages)) {
            if (data.messages.length > 0) {
                const qw = document.getElementById('quick-questions-wrapper');
                if (qw) qw.classList.add('hidden');
            }

            data.messages.forEach(msg => {
                if (msg.id) {
                    // Jika pesan sudah pernah dirender di DOM, tandai dan lewati
                    if (seenMessageIds.has(msg.id) || document.getElementById('msg-box-' + msg.id)) {
                        seenMessageIds.add(msg.id);
                        return;
                    }

                    // Jika ini pesan visitor dan ada elemen temporary visitor yang cocok, mutasikan id-nya
                    if (msg.sender_type === 'visitor') {
                        const tempEl = messagesArea.querySelector('[id^="msg-box-temp-"]');
                        if (tempEl) {
                            tempEl.id = 'msg-box-' + msg.id;
                            seenMessageIds.add(msg.id);
                            return;
                        }
                    }

                    seenMessageIds.add(msg.id);
                    appendMessageElement(
                        msg.sender_type,
                        msg.content,
                        msg.sources || [],
                        msg.id,
                        msg.created_at,
                        msg.sender_name,
                        msg.feedback,
                        [],
                        msg.chart || null
                    );
                }
            });
        }
    })
    .catch(() => {
        isPollingHistory = false;
    });
}

// Initial history fetch and periodic polling (every 3 seconds)
pollHistory();
setInterval(pollHistory, 3000);

// Auto-populate query if passed via URL ?q=...
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const initialQuery = urlParams.get('q');
    if (initialQuery && initialQuery.trim().length > 0) {
        setTimeout(() => {
            sendQuickMessage(initialQuery.trim());
        }, 300);
    }
});
</script>
@endpush
