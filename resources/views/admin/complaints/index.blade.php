@extends('layouts.admin')

@section('title', 'Daftar Aduan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Aduan & Layanan Tiket</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola dan tindak lanjuti laporan keluhan atau masukan masyarakat terhadap layanan BPS.</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        @php
        $currentStatus = request('status', 'all');
        $tabs = [
            'all' => ['label' => 'Semua', 'count' => $counts['all'], 'icon' => 'lucide:layers'],
            'new' => ['label' => 'Baru', 'count' => $counts['new'], 'icon' => 'lucide:sparkles', 'color' => 'bg-red-500 text-white'],
            'verified' => ['label' => 'Diverifikasi', 'count' => $counts['verified'], 'icon' => 'lucide:check-circle-2', 'color' => 'bg-blue-500 text-white'],
            'processing' => ['label' => 'Diproses', 'count' => $counts['processing'], 'icon' => 'lucide:clock', 'color' => 'bg-amber-500 text-slate-900'],
            'resolved' => ['label' => 'Selesai', 'count' => $counts['resolved'], 'icon' => 'lucide:badge-check', 'color' => 'bg-emerald-500 text-white'],
            'rejected' => ['label' => 'Ditolak', 'count' => $counts['rejected'], 'icon' => 'lucide:x-circle', 'color' => 'bg-slate-400 text-white'],
        ];
        @endphp

        @foreach($tabs as $key => $tab)
        <a href="{{ route('admin.complaints.index', ['status' => $key, 'priority' => request('priority')]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $currentStatus === $key ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="iconify text-base" data-icon="{{ $tab['icon'] }}"></span>
            <span>{{ $tab['label'] }}</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $tab['color'] ?? 'bg-slate-700 text-white' }}">
                {{ $tab['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- Search and Priority Filter --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="hidden" name="status" value="{{ $currentStatus }}">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="Cari Nomor Tiket (ADU-...), nama pelapor, atau uraian...">
            </div>
            <div class="flex gap-2">
                <select name="priority" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="all">Semua Prioritas</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (Urgent)</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shrink-0">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Complaints Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nomor Tiket</th>
                        <th class="px-6 py-4">Pelapor & Kategori</th>
                        <th class="px-6 py-4">Uraian Aduan</th>
                        <th class="px-6 py-4 text-center">Prioritas</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $comp)
                    @php
                    $statusBadge = [
                        'new' => 'bg-red-50 text-red-700 border-red-200',
                        'verified' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-slate-100 text-slate-600 border-slate-200',
                    ];
                    $statusLabels = [
                        'new' => 'Baru',
                        'verified' => 'Diverifikasi',
                        'processing' => 'Diproses',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ];
                    $priorityBadge = [
                        'low' => 'bg-slate-100 text-slate-600',
                        'normal' => 'bg-blue-50 text-blue-700',
                        'high' => 'bg-red-50 text-red-700 font-bold',
                    ];
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-blue-600 text-sm block">{{ $comp->ticket_number }}</span>
                            <span class="text-xs text-slate-400 mt-0.5 block">{{ $comp->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <h4 class="font-semibold text-slate-900">{{ $comp->reporter_name }}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 capitalize mt-1">
                                {{ $comp->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <p class="text-slate-700 line-clamp-2 text-xs leading-relaxed">{{ $comp->description }}</p>
                            @if($comp->attachments->count() > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] text-blue-600 mt-1 font-medium">
                                <span class="iconify" data-icon="lucide:paperclip"></span> {{ $comp->attachments->count() }} Lampiran
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $priorityBadge[$comp->priority] ?? 'bg-slate-100 text-slate-600' }} capitalize">
                                {{ $comp->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusBadge[$comp->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusLabels[$comp->status] ?? $comp->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.complaints.show', $comp) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-colors">
                                <span>Detail</span>
                                <span class="iconify" data-icon="lucide:chevron-right"></span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <span class="iconify text-4xl mx-auto mb-2 text-slate-300" data-icon="lucide:ticket"></span>
                            Tidak ada aduan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $complaints->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
