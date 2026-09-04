@extends('layouts.public')

@section('title', 'Lacak Status Pengaduan Layanan BPS Karanganyar')
@section('meta_description', 'Pantau perkembangan dan tindak lanjut aduan masyarakat BPS Kabupaten Karanganyar berdasarkan nomor tiket resmi.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">

    {{-- Page Header Banner (BPS Corporate Navy) --}}
    <div class="bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white rounded-3xl p-6 sm:p-8 border-b-4 border-[#f7941d] shadow-md relative overflow-hidden text-center sm:text-left">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20 mb-3">
                <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:search"></span>
                <span>Transparansi Pelayanan Publik BPS</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Pelacakan Status Aduan Resmi</h1>
            <p class="mt-2 text-xs sm:text-sm text-blue-100 leading-relaxed max-w-xl">
                Masukkan nomor tiket resmi (contoh: <strong class="font-mono text-[#f7941d]">ADU-2026-000001</strong>) untuk melihat riwayat proses penanganan oleh petugas BPS Kabupaten Karanganyar.
            </p>
        </div>
    </div>

    {{-- Search Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6">
        <form method="GET" action="{{ route('status-aduan') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <span class="iconify text-xl" data-icon="lucide:ticket"></span>
                    </span>
                    <input type="text" name="ticket" value="{{ request('ticket') }}"
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-[#005b9f] focus:border-[#005b9f] outline-none transition-all font-mono uppercase tracking-wider font-bold"
                           placeholder="Contoh: ADU-2026-000001" required>
                </div>
                <button type="submit"
                        class="py-3 px-6 bg-[#f7941d] hover:bg-[#e07e0c] active:scale-98 text-white text-xs sm:text-sm font-black rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                    <span class="iconify text-lg" data-icon="lucide:search"></span>
                    <span>Lacak Status</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Result Section --}}
    @if(request('ticket'))
        @if(isset($complaint))
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Ticket Header Banner --}}
            <div class="px-6 py-5 bg-[#04325e] text-white border-b-2 border-[#f7941d] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-[#f7941d] tracking-widest block">Nomor Tiket Resmi</span>
                    <span class="text-xl sm:text-2xl font-black font-mono tracking-wider">{{ $complaint->ticket_number }}</span>
                </div>
                @php
                $statusColors = [
                    'new' => 'bg-blue-500/20 text-blue-200 border-blue-400/30',
                    'verified' => 'bg-cyan-500/20 text-cyan-200 border-cyan-400/30',
                    'processing' => 'bg-amber-500/20 text-amber-200 border-amber-400/30',
                    'resolved' => 'bg-emerald-500/20 text-emerald-200 border-emerald-400/30',
                    'rejected' => 'bg-rose-500/20 text-rose-200 border-rose-400/30',
                ];
                $statusLabels = [
                    'new' => 'Aduan Masuk',
                    'verified' => 'Diverifikasi',
                    'processing' => 'Sedang Ditindaklanjuti',
                    'resolved' => 'Selesai Ditangani',
                    'rejected' => 'Ditolak',
                ];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black border self-start sm:self-auto {{ $statusColors[$complaint->status] ?? 'bg-slate-500/20 text-slate-300 border-slate-400/30' }}">
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    <span>{{ $statusLabels[$complaint->status] ?? $complaint->status }}</span>
                </span>
            </div>

            {{-- Ticket Details Grid --}}
            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Kategori Layanan</span>
                        <span class="font-extrabold text-slate-800 capitalize">{{ $complaint->category }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tingkat Prioritas</span>
                        <span class="font-extrabold text-slate-800 capitalize">{{ $complaint->priority }}</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tanggal Diajukan</span>
                        <span class="font-extrabold text-slate-800">{{ $complaint->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Uraian Pengaduan:</span>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $complaint->description }}
                    </div>
                </div>

                {{-- Status Timeline --}}
                @if($complaint->statusLogs->count() > 0)
                <div class="pt-4 border-t border-slate-200">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block mb-4 flex items-center gap-2">
                        <span class="iconify text-[#005b9f]" data-icon="lucide:activity"></span>
                        <span>Riwayat Penanganan Aduan:</span>
                    </span>
                    <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-blue-200">
                        @foreach($complaint->statusLogs->sortByDesc('created_at') as $log)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#005b9f] border-2 border-white shadow-sm"></div>
                            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-black text-slate-900">
                                        {{ $statusLabels[$log->status] ?? $log->status }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-mono">
                                        {{ $log->created_at->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                                @if($log->note)
                                <p class="text-xs text-slate-600 mt-2 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    {{ $log->note }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="bg-white rounded-3xl border border-rose-200 p-8 text-center shadow-xs">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                <span class="iconify text-3xl" data-icon="lucide:search-x"></span>
            </div>
            <h3 class="text-base font-black text-slate-900">Nomor Tiket Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1.5 max-w-sm mx-auto leading-relaxed">
                Mohon periksa kembali nomor tiket yang Anda masukkan. Pastikan sesuai format pengaduan BPS (contoh: <strong class="font-mono">ADU-2026-000001</strong>).
            </p>
        </div>
        @endif
    @endif
</div>
@endsection
