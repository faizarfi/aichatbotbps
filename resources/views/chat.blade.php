@extends('layouts.public')

@section('title', 'Chatbot Layanan Statistik 24 Jam')
@section('meta_description', 'Konsultasikan data statistik resmi, informasi publikasi, jadwal PST, dan tata cara pengaduan BPS Kabupaten Karanganyar.')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-6 py-4 sm:py-8">
    {{-- Chat Box Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-200/60 overflow-hidden flex flex-col h-[calc(100dvh-125px)] sm:h-[calc(100vh-175px)] min-h-[480px]">

        {{-- Chat Header --}}
        <div class="px-4 sm:px-6 py-3.5 sm:py-4.5 border-b border-slate-100 bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 flex items-center justify-between text-white shadow-lg">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-md">
                        <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm sm:text-base font-black text-white tracking-tight truncate">Asisten Statistik BPS</h2>
                        <span id="chat-status-pill" class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                            Bot Aktif
                        </span>
                    </div>
                    <p class="text-[11px] sm:text-xs text-blue-200 truncate mt-0.5 font-medium">BPS Kabupaten Karanganyar • 24 Jam</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <button id="btn-request-officer" onclick="requestOfficerHandoff()" class="px-3 sm:px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2 active:scale-95 shadow-sm" title="Hubungkan ke Petugas">
                    <span class="iconify text-base text-cyan-300" data-icon="lucide:user-headset"></span>
                    <span class="hidden sm:inline">Hubungi Petugas</span>
                </button>
                <button onclick="resetConversation()" class="p-2 sm:px-3 sm:py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-1.5 active:scale-95" title="Mulai Sesi Baru">
                    <span class="iconify text-base" data-icon="lucide:refresh-cw"></span>
                    <span class="hidden md:inline">Mulai Baru</span>
                </button>
            </div>
        </div>

        {{-- Messages Scroll Area --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 sm:space-y-5 bg-gradient-to-b from-slate-50/80 to-slate-100/40">

            {{-- Bot Welcome Bubble --}}
            <div class="flex gap-3 max-w-2xl">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm p-1 mt-0.5">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0 space-y-1">
                    <div class="bg-white border border-slate-200/90 rounded-3xl rounded-tl-sm p-4 sm:p-5 shadow-sm text-slate-800 space-y-3">
                        <p class="text-xs sm:text-sm leading-relaxed">
                            Halo! Selamat datang di <strong>Layanan Informasi BPS Kabupaten Karanganyar</strong>.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Saya dapat membantu Anda mencari informasi publikasi resmi (seperti <em>Karanganyar Dalam Angka</em>), data kependudukan, angka kemiskinan, PDRB, jadwal konsultasi Pelayanan Statistik Terpadu (PST), hingga pengaduan layanan.
                        </p>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold ml-2 block">Asisten AI • BPS Karanganyar</span>
                </div>
            </div>

            {{-- Quick Topic Chips --}}
            <div id="quick-questions-wrapper" class="ml-11 sm:ml-12 pt-1 space-y-2">
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Topik Populer Cepat:</p>
                <div class="flex flex-wrap gap-2">
                    <button onclick="sendQuickMessage('Cara memperoleh data statistik BPS Karanganyar')" class="px-3.5 py-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-98">
                        <span>📊 Cara Memperoleh Data</span>
                    </button>
                    <button onclick="sendQuickMessage('Jadwal dan jam buka operasional PST')" class="px-3.5 py-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-98">
                        <span>🕒 Jadwal Layanan PST</span>
                    </button>
                    <button onclick="sendQuickMessage('Publikasi Karanganyar Dalam Angka (KDA)')" class="px-3.5 py-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-98">
                        <span>📖 Karanganyar Dalam Angka</span>
                    </button>
                    <button onclick="sendQuickMessage('Data kemiskinan dan garis kemiskinan Karanganyar')" class="px-3.5 py-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-98">
                        <span>📉 Angka Kemiskinan</span>
                    </button>
                    <button onclick="sendQuickMessage('Alamat kantor dan kontak BPS Karanganyar')" class="px-3.5 py-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-98">
                        <span>📍 Alamat & Kontak Resmi</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div id="typing-indicator" class="hidden px-4 sm:px-6 py-2.5 bg-slate-50 border-t border-slate-100">
            <div class="flex items-center gap-2.5 text-slate-400">
                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <span class="iconify text-xs" data-icon="lucide:bot"></span>
                </div>
                <div class="flex items-center gap-1.5 bg-white border border-slate-200 px-3 py-1.5 rounded-full shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 150ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 300ms;"></span>
                    <span class="text-[11px] font-semibold text-slate-600 ml-1">Menyiapkan jawaban data...</span>
                </div>
            </div>
        </div>

        {{-- Input Bar --}}
        <div class="p-3 sm:p-5 border-t border-slate-100 bg-white shadow-inner">
            <form id="public-chat-form" class="flex items-end gap-2.5">
                @csrf
                <div class="flex-1 relative">
                    <textarea id="public-chat-input"
                              rows="1"
                              maxlength="1000"
                              placeholder="Ketik pertanyaan Anda seputar data statistik Karanganyar..."
                              class="w-full px-4 py-3 pr-14 rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none transition-all shadow-sm leading-relaxed"
                              style="max-height: 120px;"></textarea>
                    <span id="chat-char-counter" class="absolute bottom-3 right-3 text-[10px] text-slate-400 font-mono">0/1000</span>
                </div>
                <button type="submit" id="public-send-btn"
                        class="h-12 w-12 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-95 text-white flex items-center justify-center transition-all shrink-0 shadow-lg shadow-blue-600/30 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                        disabled>
                    <span class="iconify text-xl" data-icon="lucide:send"></span>
                </button>
            </form>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-3 pt-2.5 text-[11px] text-slate-400 border-t border-slate-100">
                <span class="flex items-center gap-1.5">
                    <span class="iconify text-emerald-600 text-sm shrink-0" data-icon="lucide:shield-check"></span>
                    <span>Jawaban bersumber dari data dan publikasi resmi BPS Kabupaten Karanganyar.</span>
                </span>
                <div class="flex items-center gap-3">
                    <a href="{{ route('aduan.create') }}" class="text-blue-600 font-bold hover:underline flex items-center gap-1">
                        <span class="iconify" data-icon="lucide:ticket"></span> Buat Aduan Layanan
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
<script>
const messagesArea = document.getElementById('chat-messages');
const chatForm = document.getElementById('public-chat-form');
const chatInput = document.getElementById('public-chat-input');
const sendBtn = document.getElementById('public-send-btn');
const typingIndicator = document.getElementById('typing-indicator');
const charCounter = document.getElementById('chat-char-counter');
const statusPill = document.getElementById('chat-status-pill');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

// Load message history & start live polling
function pollHistory() {
    if (!visitorSession) return;

    fetch('{{ route("chat.messages") }}?session=' + encodeURIComponent(visitorSession))
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                updateStatusBadge(data.status, data.officer_name);
            }

            if (data.messages && data.messages.length > 0) {
                const currentCount = messagesArea.querySelectorAll('.chat-msg-wrapper').length;
                if (data.messages.length > currentCount) {
                    const qw = document.getElementById('quick-questions-wrapper');
                    if (qw) qw.classList.add('hidden');

                    renderAllMessages(data.messages);
                }
            }
        })
        .catch(() => {});
}

function updateStatusBadge(status, officerName) {
    if (!statusPill) return;
    if (status === 'waiting') {
        statusPill.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/30 animate-pulse';
        statusPill.textContent = 'Menunggu Petugas';
    } else if (status === 'handled') {
        statusPill.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/30';
        statusPill.textContent = officerName ? 'Petugas: ' + officerName : 'Terhubung Petugas';
    } else if (status === 'closed') {
        statusPill.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-400/20 text-slate-300 border border-slate-400/30';
        statusPill.textContent = 'Sesi Selesai';
    } else {
        statusPill.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30';
        statusPill.textContent = 'Bot Aktif';
    }
}

function renderAllMessages(messages) {
    messagesArea.querySelectorAll('.chat-msg-wrapper').forEach(e => e.remove());

    messages.forEach(msg => {
        appendMessageElement(msg.sender_type, msg.content, msg.sources, msg.id, msg.created_at, msg.sender_name, msg.feedback);
    });

    scrollToBottom();
}

function formatBotContent(raw) {
    if (!raw) return '';
    
    // First escape HTML
    let text = escapeHtml(raw);
    
    // Bold: **text**
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>');
    
    // Italic: *text*
    text = text.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');
    
    // Markdown Links: [title](url)
    text = text.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener" class="text-blue-600 font-bold hover:underline inline-flex items-center gap-1">$1 <span class="iconify" data-icon="lucide:external-link"></span></a>');
    
    // Preserve newlines
    text = text.replace(/\n/g, '<br>');
    
    return text;
}

function appendMessageElement(type, content, sources, messageId = null, time = null, senderName = null, feedback = null, quickOptions = []) {
    const isVisitor = type === 'visitor';
    const isOfficer = type === 'officer';
    const formattedTime = time || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

    const wrapper = document.createElement('div');
    wrapper.className = `flex ${isVisitor ? 'justify-end' : 'justify-start'} chat-msg-wrapper`;

    if (isVisitor) {
        wrapper.innerHTML = `
            <div class="max-w-lg">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-3xl rounded-tr-sm px-4 sm:px-5 py-3 sm:py-3.5 shadow-md">
                    <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium">${escapeHtml(content)}</p>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block text-right mr-2">Anda • ${formattedTime}</span>
            </div>
        `;
    } else if (isOfficer) {
        wrapper.innerHTML = `
            <div class="flex gap-3 max-w-xl">
                <div class="w-8 h-8 rounded-2xl bg-blue-700 text-white flex items-center justify-center shrink-0 font-black text-xs shadow-md">
                    ${escapeHtml((senderName || 'P').substring(0, 1))}
                </div>
                <div>
                    <div class="bg-white border-2 border-blue-400 rounded-3xl rounded-tl-sm p-4 sm:p-5 shadow-md">
                        <div class="flex items-center gap-1.5 text-xs font-black text-blue-700 mb-2">
                            <span class="iconify text-base" data-icon="lucide:user-check"></span>
                            <span>${escapeHtml(senderName || 'Petugas BPS Karanganyar')}</span>
                        </div>
                        <div class="text-xs sm:text-sm text-slate-800 leading-relaxed">${formatBotContent(content)}</div>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold mt-1 block ml-2">Petugas Resmi • ${formattedTime}</span>
                </div>
            </div>
        `;
    } else {
        // Bot Message
        let sourcesHtml = '';
        if (sources && sources.length > 0) {
            sourcesHtml = `
                <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                    <span class="font-bold text-slate-700 block mb-1">Rujukan Resmi BPS:</span>
                    ${sources.map(s => `<a href="${s.url || '#'}" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 mt-1 font-medium"><span class="iconify" data-icon="lucide:external-link"></span> ${escapeHtml(s.title)}</a>`).join('')}
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
                <div class="mt-3 flex items-center justify-between pt-2.5 border-t border-slate-100" id="feedback-box-${messageId}">
                    <span class="text-[10px] text-slate-400">Apakah informasi ini membantu?</span>
                    <div class="flex items-center gap-1.5">
                        <button onclick="submitFeedback(${messageId}, 'helpful')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold border ${feedback === 'helpful' ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-emerald-50 hover:text-emerald-700'} transition-all flex items-center gap-1 cursor-pointer">
                            <span>👍 Ya</span>
                        </button>
                        <button onclick="submitFeedback(${messageId}, 'not_helpful')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold border ${feedback === 'not_helpful' ? 'bg-rose-50 text-rose-700 border-rose-300' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-rose-50 hover:text-rose-700'} transition-all flex items-center gap-1 cursor-pointer">
                            <span>👎 Tidak</span>
                        </button>
                    </div>
                </div>
            `;
        }

        wrapper.innerHTML = `
            <div class="flex gap-3 max-w-2xl">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm p-1 mt-0.5">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <div class="bg-white border border-slate-200/90 rounded-3xl rounded-tl-sm p-4 sm:p-5 shadow-sm">
                        <div class="text-xs sm:text-sm text-slate-800 leading-relaxed">${formatBotContent(content)}</div>
                        ${sourcesHtml}
                        ${quickChipsHtml}
                        ${feedbackHtml}
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold mt-1 block ml-2">Bot Asisten • ${formattedTime}</span>
                </div>
            </div>
        `;
    }

    messagesArea.appendChild(wrapper);
    scrollToBottom();
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

    appendMessageElement('visitor', text);

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
            session: visitorSession
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

        const botMsg = data.bot_message || (typeof data.reply === 'object' ? data.reply : { content: data.reply, sources: data.sources || [], id: null });
        const replyText = botMsg?.content || (typeof data.reply === 'string' ? data.reply : '');
        const replySources = botMsg?.sources || data.sources || [];
        const replyId = botMsg?.id || null;
        const replyTime = botMsg?.created_at || null;

        if (replyText) {
            appendMessageElement('bot', replyText, replySources, replyId, replyTime, null, null, data.quick_options || []);
        }
    })
    .catch(() => {
        typingIndicator.classList.add('hidden');
        appendMessageElement('bot', 'Maaf, terjadi gangguan jaringan saat memproses pertanyaan Anda. Silakan coba kirim ulang pertanyaan.');
    });
});

function requestOfficerHandoff() {
    Swal.fire({
        title: 'Hubungi Petugas BPS?',
        text: 'Percakapan Anda akan dialihkan ke antrean petugas BPS Karanganyar untuk ditanggapi langsung pada jam kerja (Senin–Jumat, 08.00–15.30 WIB).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
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
                    appendMessageElement('bot', data.message.content, [], data.message.id);
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Permintaan Diteruskan',
                    text: 'Anda telah masuk ke antrean petugas BPS Karanganyar. Mohon tunggu balasan petugas di halaman ini.',
                    confirmButtonColor: '#2563eb'
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengirim permintaan ke petugas. Silakan coba lagi.',
                    confirmButtonColor: '#2563eb'
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

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initial history fetch and periodic polling (every 3 seconds)
pollHistory();
setInterval(pollHistory, 3000);
</script>
@endpush
