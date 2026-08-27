@extends('layouts.admin')

@section('title', 'Manajemen Reservasi Konsultasi PST')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Antrean Reservasi Konsultasi PST</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola jadwal tatap muka pengunjung dan verifikasi permohonan konsultasi statistik.</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-slate-200">
        <a href="{{ route('admin.reservations.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-amber-700 hover:bg-amber-50 border border-amber-200' }}">
            Menunggu ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }}">
            Disetujui ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'completed' ? 'bg-blue-700 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Selesai ({{ $counts['completed'] }})
        </a>
    </div>

    {{-- Table List --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Kode Booking</th>
                        <th class="py-3.5 px-4">Pengunjung / Pemohon</th>
                        <th class="py-3.5 px-4">Topik Konsultasi</th>
                        <th class="py-3.5 px-4">Jadwal Tanggal & Sesi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservations as $r)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                            {{ $r->booking_code }}
                        </td>
                        <td class="py-3.5 px-4">
                            <p class="font-bold text-slate-900">{{ $r->visitor_name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $r->visitor_email }} • {{ $r->visitor_phone }}</p>
                            @if($r->institution)
                            <p class="text-[11px] text-blue-600 font-medium">{{ $r->institution }}</p>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            {{ $r->topic_category }}
                        </td>
                        <td class="py-3.5 px-4">
                            <p class="font-bold text-slate-900">{{ $r->reservation_date->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $r->time_slot }}</p>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($r->status === 'approved')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Disetujui</span>
                            @elseif($r->status === 'completed')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Selesai</span>
                            @elseif($r->status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Dibatalkan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">Menunggu</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('admin.reservations.show', $r) }}" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs inline-flex items-center gap-1 border border-blue-200">
                                <span>Detail</span>
                                <span class="iconify text-sm" data-icon="lucide:chevron-right"></span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400">Belum ada reservasi konsultasi yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservations->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $reservations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
