@extends('layouts.admin')

@section('title', 'Percakapan Pengunjung')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Ruang Percakapan & Antrean Live</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau interaksi masyarakat dengan chatbot dan tanggapi permintaan bantuan langsung.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Auto-Refresh Aktif
            </span>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        @php
        $currentStatus = request('status', 'all');
        $tabs = [
            'all' => ['label' => 'Semua', 'count' => $counts['all'], 'icon' => 'lucide:layers'],
            'waiting' => ['label' => 'Menunggu Petugas', 'count' => $counts['waiting'], 'icon' => 'lucide:clock', 'color' => 'bg-amber-500 text-slate-900'],
            'handled' => ['label' => 'Sedang Ditangani', 'count' => $counts['handled'], 'icon' => 'lucide:user-check', 'color' => 'bg-blue-500 text-white'],
            'bot' => ['label' => 'Otomatis Bot', 'count' => $counts['bot'], 'icon' => 'lucide:bot', 'color' => 'bg-slate-200 text-slate-700'],
            'closed' => ['label' => 'Selesai', 'count' => $counts['closed'], 'icon' => 'lucide:check-circle', 'color' => 'bg-emerald-200 text-emerald-800'],
        ];
        @endphp

        @foreach($tabs as $key => $tab)
        <a href="{{ route('admin.conversations.index', ['status' => $key]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $currentStatus === $key ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="iconify text-base" data-icon="{{ $tab['icon'] }}"></span>
            <span>{{ $tab['label'] }}</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $tab['color'] ?? 'bg-slate-700 text-white' }}">
                {{ $tab['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- Search Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.conversations.index') }}" class="flex gap-3">
            <input type="hidden" name="status" value="{{ $currentStatus }}">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="Cari ID percakapan atau nama pengunjung...">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2">
                <span class="iconify" data-icon="lucide:search"></span> Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.conversations.index', ['status' => $currentStatus]) }}" class="px-4 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-medium transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Conversations Grid/List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($conversations as $conv)
        @php
        $statusInfo = [
            'waiting' => ['label' => 'Menunggu Petugas', 'class' => 'bg-amber-100 text-amber-800 border-amber-300', 'badge' => 'animate-pulse'],
            'handled' => ['label' => 'Ditangani Petugas', 'class' => 'bg-blue-100 text-blue-800 border-blue-300', 'badge' => ''],
            'bot' => ['label' => 'Ditangani Bot', 'class' => 'bg-slate-100 text-slate-700 border-slate-300', 'badge' => ''],
            'closed' => ['label' => 'Ditutup', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'badge' => ''],
        ];
        $st = $statusInfo[$conv->status] ?? $statusInfo['bot'];
        $lastMsg = $conv->messages->first();
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between group">
            <div>
                {{-- Top Card Info --}}
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm">
                            <span class="iconify text-lg text-blue-600" data-icon="{{ $conv->channel === 'whatsapp' ? 'lucide:phone' : 'lucide:globe' }}"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 leading-none truncate max-w-[160px]">{{ $conv->visitor_name }}</h4>
                            <span class="text-[11px] text-slate-400 font-mono">UUID: {{ substr($conv->public_id, 0, 8) }}...</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $st['class'] }} {{ $st['badge'] }}">
                        {{ $st['label'] }}
                    </span>
                </div>

                {{-- Last Message Preview --}}
                <div class="bg-slate-50 rounded-xl p-3 mb-4">
                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                        @if($lastMsg)
                            <span class="font-semibold text-slate-800">{{ $lastMsg->sender_type === 'visitor' ? 'Pengunjung:' : ($lastMsg->sender_type === 'officer' ? 'Petugas:' : 'Bot:') }}</span>
                            {{ $lastMsg->content }}
                        @else
                            <span class="italic text-slate-400">Belum ada pesan</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Bottom Footer --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <div class="flex items-center gap-1.5">
                    <span class="iconify" data-icon="lucide:message-square"></span>
                    <span>{{ $conv->messages_count }} pesan</span>
                    <span class="mx-1">•</span>
                    <span>{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : $conv->created_at->diffForHumans() }}</span>
                </div>
                <a href="{{ route('admin.conversations.show', $conv) }}" class="inline-flex items-center gap-1 font-semibold text-blue-600 hover:text-blue-800 transition-colors group-hover:translate-x-0.5 transform">
                    Buka Chat <span class="iconify" data-icon="lucide:chevron-right"></span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400">
            <span class="iconify text-5xl mx-auto mb-3 text-slate-300" data-icon="lucide:message-square-off"></span>
            <p class="text-base font-semibold text-slate-600">Tidak ada percakapan pada kategori ini.</p>
            <p class="text-xs text-slate-400 mt-1">Percakapan baru dari pengunjung website akan muncul di sini secara otomatis.</p>
        </div>
        @endforelse
    </div>

    @if($conversations->hasPages())
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        {{ $conversations->links() }}
    </div>
    @endif
</div>
@endsection
