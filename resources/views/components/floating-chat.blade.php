{{-- Floating Chat Widget Component (BPS Kabupaten Karanganyar) --}}
<div id="floating-chat-container" class="fixed bottom-5 right-5 z-50 font-sans">

    {{-- Chat Bubble Trigger Button --}}
    <button id="floating-chat-trigger"
            type="button"
            onclick="toggleFloatingChat()"
            aria-label="Buka Layanan PST BPS"
            class="flex items-center gap-2.5 px-4 py-3 bg-[#002b6a] hover:bg-[#003c80] text-white rounded-full shadow-xl shadow-blue-950/30 hover:scale-105 active:scale-95 transition-all duration-300 border-2 border-[#f7941d] group cursor-pointer">
        <div class="relative shrink-0">
            <div class="w-8 h-8 rounded-full bg-white p-1 flex items-center justify-center shadow-sm">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
            </div>
            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#002b6a] animate-pulse"></span>
        </div>
        <div class="text-left pr-1 hidden sm:block">
            <span class="text-[9.5px] text-[#f7941d] block uppercase font-black tracking-wider leading-none">BPS Karanganyar</span>
            <span class="text-xs font-black tracking-tight leading-tight text-white">Konsultasi PST</span>
        </div>
        <span class="sm:hidden text-xs font-bold pr-0.5">PST BPS</span>
        <span class="iconify text-base text-[#f7941d] group-hover:scale-110 transition-transform" data-icon="lucide:message-square-text"></span>
    </button>

    {{-- Floating Chat Modal Window --}}
    <div id="floating-chat-modal"
         class="hidden flex flex-col w-[92vw] sm:w-[390px] h-[520px] max-h-[82vh] bg-white rounded-3xl border border-slate-200 shadow-2xl shadow-slate-900/30 overflow-hidden transition-all duration-300">
        
        {{-- Header (BPS Corporate Navy) --}}
        <div class="px-4 py-3.5 bg-[#002b6a] text-white flex items-center justify-between border-b-2 border-[#f7941d] shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-white p-1 flex items-center justify-center shrink-0 shadow-xs">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold text-white truncate flex items-center gap-1.5">
                        <span>Pelayanan Statistik Terpadu (PST)</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    </h3>
                    <p class="text-[10px] text-blue-200 truncate">BPS Kabupaten Karanganyar</p>
                </div>
            </div>

            <div class="flex items-center gap-1 shrink-0">
                {{-- Expand to Full Chat Page --}}
                <a href="{{ route('chat.index') }}"
                   class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition-colors"
                   title="Buka Layar Penuh">
                    <span class="iconify text-sm" data-icon="lucide:maximize-2"></span>
                </a>
                {{-- Close Button --}}
                <button type="button"
                        onclick="toggleFloatingChat()"
                        class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition-colors cursor-pointer"
                        title="Tutup">
                    <span class="iconify text-base" data-icon="lucide:x"></span>
                </button>
            </div>
        </div>

        {{-- Messages Scroll Area --}}
        <div id="floating-chat-messages" class="flex-1 overflow-y-auto p-3.5 space-y-3 bg-slate-50 text-xs">
            {{-- Welcome bubble --}}
            <div class="flex gap-2 max-w-[90%]">
                <div class="w-6 h-6 rounded-lg bg-white border border-slate-200 p-0.5 flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                    <img src="{{ asset('images/logo-bps.svg') }}" alt="BPS" class="w-full h-full object-contain">
                </div>
                <div class="bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm p-3 shadow-xs text-slate-800 space-y-1.5">
                    <p class="leading-relaxed">
                        Selamat datang di <strong>Layanan Konsultasi Data BPS Kabupaten Karanganyar</strong>. Silakan ketik data yang Anda butuhkan.
                    </p>
                    <div class="pt-1.5 border-t border-slate-100 flex items-center gap-1 text-[9.5px] text-slate-500 font-semibold">
                        <span class="iconify text-xs text-blue-600" data-icon="lucide:check-circle-2"></span>
                        <span>Rujukan Resmi BPS Karanganyar</span>
                    </div>
                </div>
            </div>

            {{-- Quick Chips inside Floating --}}
            <div id="floating-quick-chips" class="pt-1 space-y-1.5 ml-8">
                <div class="flex flex-wrap gap-1">
                    <button type="button" onclick="sendFloatingQuick('Jumlah penduduk Karanganyar 2026')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-bold shadow-xs transition-all cursor-pointer">
                        <span class="iconify text-xs text-blue-600" data-icon="lucide:users"></span>
                        <span>Penduduk 2026</span>
                    </button>
                    <button type="button" onclick="sendFloatingQuick('Angka kemiskinan Karanganyar')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-bold shadow-xs transition-all cursor-pointer">
                        <span class="iconify text-xs text-rose-600" data-icon="lucide:trending-down"></span>
                        <span>Kemiskinan</span>
                    </button>
                    <button type="button" onclick="sendFloatingQuick('Jadwal jam buka PST')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-bold shadow-xs transition-all cursor-pointer">
                        <span class="iconify text-xs text-amber-600" data-icon="lucide:clock"></span>
                        <span>Jam Layanan PST</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div id="floating-typing" class="hidden px-4 py-1.5 bg-slate-50 border-t border-slate-100 text-[10px] text-slate-400 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.2s"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 0.4s"></span>
            <span>Memproses data...</span>
        </div>

        {{-- Input Footer --}}
        <div class="p-2.5 bg-white border-t border-slate-100 shrink-0">
            <form id="floating-chat-form" class="flex items-center gap-2">
                <input type="text"
                       id="floating-chat-input"
                       placeholder="Ketik pertanyaan data statistik..."
                       maxlength="500"
                       class="flex-1 px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <button type="submit"
                        id="floating-send-btn"
                        class="w-8 h-8 rounded-xl bg-[#005b9f] hover:bg-[#04325e] active:scale-95 text-white flex items-center justify-center shrink-0 shadow-xs transition-all cursor-pointer disabled:opacity-40"
                        disabled>
                    <span class="iconify text-sm" data-icon="lucide:send"></span>
                </button>
            </form>
            <div class="flex items-center justify-between mt-1 px-1 text-[9.5px] text-slate-400">
                <span>Rujukan resmi BPS Karanganyar</span>
                <a href="{{ route('chat.index') }}" class="text-blue-600 hover:underline font-bold">Layar Penuh &rarr;</a>
            </div>
        </div>

    </div>
</div>

<script>
let isFloatingOpen = false;
const floatingTrigger = document.getElementById('floating-chat-trigger');
const floatingModal = document.getElementById('floating-chat-modal');
const floatingMessages = document.getElementById('floating-chat-messages');
const floatingForm = document.getElementById('floating-chat-form');
const floatingInput = document.getElementById('floating-chat-input');
const floatingSendBtn = document.getElementById('floating-send-btn');
const floatingTyping = document.getElementById('floating-typing');

function toggleFloatingChat() {
    isFloatingOpen = !isFloatingOpen;
    if (isFloatingOpen) {
        floatingTrigger.classList.add('hidden');
        floatingModal.classList.remove('hidden');
        floatingInput.focus();
        scrollFloatingToBottom();
    } else {
        floatingModal.classList.add('hidden');
        floatingTrigger.classList.remove('hidden');
    }
}

floatingInput?.addEventListener('input', function() {
    floatingSendBtn.disabled = this.value.trim().length === 0;
});

function scrollFloatingToBottom() {
    if (floatingMessages) {
        floatingMessages.scrollTop = floatingMessages.scrollHeight;
    }
}

function sendFloatingQuick(text) {
    if (!floatingInput) return;
    floatingInput.value = text;
    floatingSendBtn.disabled = false;
    floatingForm.dispatchEvent(new Event('submit'));
}

floatingForm?.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = floatingInput.value.trim();
    if (!text) return;

    // Sembunyikan quick chips
    const chips = document.getElementById('floating-quick-chips');
    if (chips) chips.classList.add('hidden');

    // Tampilkan pesan pengguna
    appendFloatingMessage('user', text);
    floatingInput.value = '';
    floatingSendBtn.disabled = true;

    // Tampilkan loading typing
    floatingTyping.classList.remove('hidden');
    scrollFloatingToBottom();

    // Pastikan session sinkron dengan localStorage
    let session = localStorage.getItem('bps_chat_session');
    if (!session) {
        session = 'bps_' + Math.random().toString(36).substring(2, 15) + '_' + Date.now();
        localStorage.setItem('bps_chat_session', session);
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('{{ route("chat.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message: text,
            session: session
        })
    })
    .then(r => r.json())
    .then(data => {
        floatingTyping.classList.add('hidden');
        if (data.session) {
            localStorage.setItem('bps_chat_session', data.session);
        }

        const botMsg = data.bot_message || (typeof data.reply === 'object' ? data.reply : { content: data.reply });
        let replyText = botMsg?.content || (typeof data.reply === 'string' ? data.reply : 'Terima kasih atas pertanyaan Anda.');
        
        // Bersihkan blok chart untuk tampilan mini di widget
        replyText = replyText.replace(/```chart\s*\{[\s\S]*?\}\s*```/g, '').trim();

        appendFloatingMessage('bot', replyText);
    })
    .catch(() => {
        floatingTyping.classList.add('hidden');
        appendFloatingMessage('bot', 'Maaf, terjadi kendala saat memproses jawaban. Silakan coba kembali.');
    });
});

function replaceIconsAndFilterEmojisMini(text) {
    if (!text) return '';
    let res = text.replace(/\[icon:([a-z0-9\-]+)\]/gi, '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:$1"></span>');
    const emojiMap = {
        '📊': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:bar-chart-2"></span>',
        '📈': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:trending-up"></span>',
        '📉': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:trending-down"></span>',
        '📌': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:bookmark"></span>',
        '🛣️': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:route"></span>',
        '🛣': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:route"></span>',
        'ℹ️': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:info"></span>',
        'ℹ': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:info"></span>',
        '🏛️': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:landmark"></span>',
        '🏛': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:landmark"></span>',
        '📍': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:map-pin"></span>',
        '📅': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:calendar"></span>',
        '📞': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:phone"></span>',
        '✉️': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:mail"></span>',
        '✉': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:mail"></span>',
        '🔗': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:external-link"></span>',
        '🔍': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:search"></span>',
        '💡': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:lightbulb"></span>',
        '🧠': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:sparkles"></span>',
        '⚠️': '<span class="iconify text-amber-600 inline-block align-middle mr-1" data-icon="lucide:alert-circle"></span>',
        '🌾': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:wheat"></span>',
        '👥': '<span class="iconify text-blue-600 inline-block align-middle mr-1" data-icon="lucide:users"></span>',
    };
    for (const [emoji, iconHtml] of Object.entries(emojiMap)) {
        res = res.split(emoji).join(iconHtml);
    }
    return res.replace(/[\u{1F300}-\u{1F6FF}\u{1F900}-\u{1F9FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/gu, '');
}

function appendFloatingMessage(sender, text) {
    const isUser = sender === 'user';
    const div = document.createElement('div');
    div.className = `flex ${isUser ? 'justify-end' : 'justify-start'} gap-2 max-w-[92%] ${isUser ? 'ml-auto' : 'mr-auto'}`;

    if (isUser) {
        div.innerHTML = `
            <div class="bg-[#005b9f] text-white rounded-2xl rounded-tr-sm p-2.5 text-xs font-medium shadow-xs leading-relaxed break-words">
                ${escapeHtmlMini(text)}
            </div>
        `;
    } else {
        let cleanedText = typeof replaceIconsAndFilterEmojisMini === 'function' 
            ? replaceIconsAndFilterEmojisMini(text) 
            : (typeof replaceIconsAndFilterEmojis === 'function' ? replaceIconsAndFilterEmojis(text) : text);
        
        let formattedText = cleanedText;
        if (typeof marked !== 'undefined' && typeof marked.parse === 'function') {
            try {
                const renderer = new marked.Renderer();
                renderer.strong = function(token) {
                    const t = typeof token === 'object' ? token.text : token;
                    return '<span class="font-medium text-slate-800">' + t + '</span>';
                };
                marked.use({ renderer });
                formattedText = marked.parse(cleanedText, { breaks: true, gfm: true });
            } catch (e) {
                formattedText = escapeHtmlMini(cleanedText).replace(/\n/g, '<br>');
            }
        } else {
            // Simple robust fallback
            formattedText = cleanedText
                .replace(/^###[ \t]+(.*)$/gim, '<h3 class="font-semibold text-blue-800 text-xs mt-2 mb-1">$1</h3>')
                .replace(/^####[ \t]+(.*)$/gim, '<h4 class="font-semibold text-slate-800 text-xs mt-1.5 mb-0.5">$1</h4>')
                .replace(/\*\*(.*?)\*\*/g, '<span class="font-medium text-slate-800">$1</span>')
                .replace(/^[ \t]*[\*\-\+][ \t]+(.*)$/gim, '<li class="ml-3 list-disc text-slate-700 my-0.5">$1</li>')
                .replace(/\n/g, '<br>');
        }

        div.innerHTML = `
            <div class="w-6 h-6 rounded-lg bg-white border border-slate-200 p-0.5 flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="BPS" class="w-full h-full object-contain">
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm p-3 text-xs text-slate-800 shadow-xs space-y-1 leading-relaxed break-words">
                <div class="chat-content-body text-xs">${formattedText}</div>
                <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400">
                    <span class="text-blue-600 font-bold">Layanan BPS</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="copyFloatingText(this)" data-content="${escapeHtmlMini(text)}" class="text-slate-500 hover:text-emerald-700 font-semibold flex items-center gap-0.5 cursor-pointer">
                            <span class="iconify text-xs" data-icon="lucide:copy"></span>
                            <span class="btn-copy-label">Salin</span>
                        </button>
                        <a href="{{ route('chat.index') }}" class="text-blue-600 hover:underline font-semibold">Detail &rarr;</a>
                    </div>
                </div>
            </div>
        `;
    }

    floatingMessages.appendChild(div);
    if (window.Iconify && typeof window.Iconify.scan === 'function') {
        window.Iconify.scan(div);
    }
    scrollFloatingToBottom();
}

function copyFloatingText(btn) {
    let text = btn.getAttribute('data-content') || '';
    let cleanText = text
        .replace(/```chart\s*\{[\s\S]*?\}\s*```/g, '')
        .replace(/\[icon:[a-z0-9\-]+\]/gi, '')
        .replace(/^[ \t]*[\*\-\+][ \t]+/gm, '• ')
        .replace(/\*\*(.*?)\*\*/g, '$1')
        .trim();

    const label = btn.querySelector('.btn-copy-label');
    const icon = btn.querySelector('.iconify');
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(cleanText).then(() => {
            if (label) label.textContent = 'Tersalin!';
            if (icon) icon.setAttribute('data-icon', 'lucide:check');
            setTimeout(() => {
                if (label) label.textContent = 'Salin';
                if (icon) icon.setAttribute('data-icon', 'lucide:copy');
            }, 2000);
        });
    }
}

function escapeHtmlMini(str) {
    if (!str) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}
</script>
