@extends('layouts.admin')

@section('title', 'Percakapan: ' . $conversation->visitor_name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Header & Top Controls --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.conversations.index') }}" class="p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors" title="Kembali">
                <span class="iconify text-lg" data-icon="lucide:arrow-left"></span>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-800">{{ $conversation->visitor_name }}</h2>
                    <span id="status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $conversation->status === 'waiting' ? 'bg-amber-100 text-amber-800 border border-amber-300 animate-pulse' : ($conversation->status === 'handled' ? 'bg-blue-100 text-blue-800 border border-blue-300' : ($conversation->status === 'closed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-700 border border-slate-300')) }}">
                        {{ $conversation->status === 'waiting' ? 'Menunggu Petugas' : ($conversation->status === 'handled' ? 'Ditangani Petugas' : ($conversation->status === 'closed' ? 'Ditutup' : 'Ditangani Bot')) }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 font-mono mt-0.5">UUID: {{ $conversation->public_id }} • Kanal: {{ strtoupper($conversation->channel) }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($conversation->status !== 'handled' && $conversation->status !== 'closed')
            <form action="{{ route('admin.conversations.takeover', $conversation) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center gap-1.5">
                    <span class="iconify text-base" data-icon="lucide:user-plus"></span>
                    <span>Ambil Alih Percakapan</span>
                </button>
            </form>
            @endif

            @if($conversation->status !== 'closed')
            <form action="{{ route('admin.conversations.close', $conversation) }}" method="POST" onsubmit="return confirmFormAction(this, 'Tutup Sesi Percakapan?', 'Sesi ini akan ditandai selesai dan bot akan memberikan pesan penutup.', 'Ya, Tutup Sesi')">
                @csrf
                <button type="submit" class="px-3.5 py-2 border border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-slate-600 text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5">
                    <span class="iconify text-base" data-icon="lucide:check-circle"></span>
                    <span>Tutup Sesi</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Chat Room Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col" style="height: 620px;">
        {{-- Live Room Bar --}}
        <div class="px-6 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-700">Live Polling Aktif</span>
                <span class="text-xs text-slate-400">| Sesi Dimulai: {{ $conversation->created_at->format('d M Y, H:i') }}</span>
            </div>
            @if($conversation->assignedOfficer)
            <div class="text-xs text-slate-600 font-medium">
                Ditangani oleh: <span class="font-bold text-blue-600">{{ $conversation->assignedOfficer->name }}</span>
            </div>
            @endif
        </div>

        {{-- Messages Scroll Area --}}
        <div id="admin-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50">
            @foreach($conversation->messages as $msg)
            <div class="flex {{ $msg->sender_type === 'officer' ? 'justify-end' : ($msg->sender_type === 'visitor' ? 'justify-start' : 'justify-start') }} message-item" data-id="{{ $msg->id }}">
                @if($msg->sender_type === 'visitor')
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 shrink-0 font-bold text-xs">
                        P
                    </div>
                    <div>
                        <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                            <p class="text-xs font-semibold text-slate-800 mb-1">Pengunjung</p>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $msg->content }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block ml-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                </div>
                @elseif($msg->sender_type === 'officer')
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="text-right">
                        <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm p-4 shadow-sm">
                            <p class="text-xs font-semibold text-blue-100 mb-1">{{ $msg->sender?->name ?? 'Petugas BPS' }}</p>
                            <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->content }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block mr-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center shrink-0 font-bold text-xs">
                        {{ substr($msg->sender?->name ?? 'P', 0, 1) }}
                    </div>
                </div>
                @else
                {{-- Bot Message --}}
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <span class="iconify text-base" data-icon="lucide:bot"></span>
                    </div>
                    <div>
                        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-semibold text-blue-900">Bot Asisten</p>
                                @if($msg->is_fallback)
                                <span class="px-2 py-0.5 rounded text-[10px] bg-rose-100 text-rose-700 font-semibold">Fallback</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $msg->content }}</p>
                            @if(!empty($msg->knowledge_sources))
                            <div class="mt-2 pt-2 border-t border-blue-100 text-[11px] text-blue-700">
                                <strong>Sumber:</strong>
                                @foreach($msg->knowledge_sources as $src)
                                <span class="inline-block mr-2">• {{ $src['title'] ?? '' }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block ml-1">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Reply Input Area --}}
        <div class="p-4 border-t border-slate-200 bg-white">
            @if($conversation->status === 'closed')
            <div class="p-3 rounded-xl bg-slate-100 text-center text-xs text-slate-500 font-medium">
                Percakapan ini telah ditutup. Buka kembali atau buat sesi baru jika diperlukan.
            </div>
            @else
            <form id="admin-reply-form" class="flex gap-3">
                @csrf
                <div class="flex-1">
                    <textarea id="officer-message-input" rows="2" required maxlength="2000"
                              placeholder="Ketik balasan langsung kepada pengunjung..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none transition-all"></textarea>
                </div>
                <button type="submit" id="officer-send-btn"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2 shrink-0 self-end disabled:opacity-50">
                    <span class="iconify text-lg" data-icon="lucide:send"></span>
                    <span>Kirim</span>
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const conversationId = {{ $conversation->id }};
const messagesContainer = document.getElementById('admin-chat-messages');
const replyForm = document.getElementById('admin-reply-form');
const messageInput = document.getElementById('officer-message-input');
const sendBtn = document.getElementById('officer-send-btn');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function scrollToBottom() {
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}
scrollToBottom();

// Real-time polling messages every 3 seconds
let isPolling = true;
function pollMessages() {
    if (!isPolling) return;

    fetch('{{ route("admin.conversations.messages", $conversation) }}')
        .then(r => r.json())
        .then(data => {
            const currentCount = messagesContainer.querySelectorAll('.message-item').length;
            if (data.messages.length > currentCount) {
                // Re-render new messages
                renderMessages(data.messages);
            }
        })
        .catch(() => {});
}

function renderMessages(messages) {
    messagesContainer.innerHTML = '';
    messages.forEach(msg => {
        const isOfficer = msg.sender_type === 'officer';
        const isVisitor = msg.sender_type === 'visitor';

        const wrapper = document.createElement('div');
        wrapper.className = `flex ${isOfficer ? 'justify-end' : 'justify-start'} message-item`;
        wrapper.dataset.id = msg.id;

        if (isVisitor) {
            wrapper.innerHTML = `
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 shrink-0 font-bold text-xs">P</div>
                    <div>
                        <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                            <p class="text-xs font-semibold text-slate-800 mb-1">Pengunjung</p>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">${escapeHtml(msg.content)}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block ml-1">${msg.created_at}</span>
                    </div>
                </div>
            `;
        } else if (isOfficer) {
            wrapper.innerHTML = `
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="text-right">
                        <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm p-4 shadow-sm">
                            <p class="text-xs font-semibold text-blue-100 mb-1">${escapeHtml(msg.sender_name)}</p>
                            <p class="text-sm leading-relaxed whitespace-pre-line">${escapeHtml(msg.content)}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block mr-1">${msg.created_at}</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center shrink-0 font-bold text-xs">
                        ${escapeHtml(msg.sender_name.substring(0, 1))}
                    </div>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="flex items-start gap-2.5 max-w-xl">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <span class="iconify text-base" data-icon="lucide:bot"></span>
                    </div>
                    <div>
                        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-semibold text-blue-900">Bot Asisten</p>
                                ${msg.is_fallback ? '<span class="px-2 py-0.5 rounded text-[10px] bg-rose-100 text-rose-700 font-semibold">Fallback</span>' : ''}
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">${escapeHtml(msg.content)}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block ml-1">${msg.created_at}</span>
                    </div>
                </div>
            `;
        }
        messagesContainer.appendChild(wrapper);
    });
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

setInterval(pollMessages, 3000);

// Submit officer reply
if (replyForm) {
    replyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (!content) return;

        sendBtn.disabled = true;

        fetch('{{ route("admin.conversations.reply", $conversation) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content: content })
        })
        .then(r => r.json())
        .then(data => {
            messageInput.value = '';
            sendBtn.disabled = false;
            pollMessages();
        })
        .catch(() => {
            alert('Gagal mengirim pesan. Silakan coba lagi.');
            sendBtn.disabled = false;
        });
    });

    // Enter to send
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            replyForm.dispatchEvent(new Event('submit'));
        }
    });
}
</script>
@endpush
