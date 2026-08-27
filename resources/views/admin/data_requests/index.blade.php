@extends('layouts.admin')

@section('title', 'Permohonan Data Mikro & Rekomendasi Statistik (ROMANTIK)')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Pengajuan Data Mikro & ROMANTIK</h1>
            <p class="text-xs sm:text-sm text-slate-500">Telaah permohonan data penelitian, verifikasi proposal, dan unggah dataset hasil olahan.</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 pb-2 border-b border-slate-200">
        <a href="{{ route('admin.data-requests.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.data-requests.index', ['status' => 'submitted']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'submitted' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-rose-700 hover:bg-rose-50 border border-rose-200' }}">
            Baru Masuk ({{ $counts['submitted'] }})
        </a>
        <a href="{{ route('admin.data-requests.index', ['status' => 'reviewing']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'reviewing' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-amber-700 hover:bg-amber-50 border border-amber-200' }}">
            Ditinjau ({{ $counts['reviewing'] }})
        </a>
        <a href="{{ route('admin.data-requests.index', ['status' => 'ready']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'ready' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }}">
            Selesai / Siap ({{ $counts['ready'] }})
        </a>
    </div>

    {{-- Table List --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">No. Registrasi</th>
                        <th class="py-3.5 px-4">Pemohon / Instansi</th>
                        <th class="py-3.5 px-4">Judul Penelitian / Kegiatan</th>
                        <th class="py-3.5 px-4">Tanggal Masuk</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $r)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                            {{ $r->ticket_number }}
                        </td>
                        <td class="py-3.5 px-4">
                            <p class="font-bold text-slate-900">{{ $r->applicant_name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $r->institution_name }}</p>
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-100 text-slate-600 mt-0.5">{{ $r->applicant_type }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800 max-w-xs truncate">
                            {{ $r->research_title }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500">
                            {{ $r->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($r->status === 'ready')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Siap Diunduh</span>
                            @elseif($r->status === 'reviewing')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Ditinjau</span>
                            @elseif($r->status === 'rejected')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Ditolak</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 animate-pulse">Baru</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('admin.data-requests.show', $r) }}" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs inline-flex items-center gap-1 border border-blue-200">
                                <span>Telaah</span>
                                <span class="iconify text-sm" data-icon="lucide:chevron-right"></span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400">Belum ada pengajuan permohonan data masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
