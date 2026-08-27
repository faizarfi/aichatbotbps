@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi PDF')

@section('content')
<div class="space-y-6">
    {{-- Header Banner --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-xs font-bold text-blue-800 border border-blue-200 mb-3">
                <span class="iconify text-sm text-blue-600" data-icon="lucide:file-text"></span>
                <span>Dokumen Resmi PST</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Laporan Rekapitulasi Layanan</h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                Ekspor dan cetak laporan resmi rekapitulasi percakapan chatbot, konsultasi data, dan tiket pengaduan masyarakat berformat PDF standar Badan Pusat Statistik.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('admin.reports.preview', ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type]) }}" 
               target="_blank" 
               class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition-all border border-slate-300 flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:eye"></span>
                <span>Pratinjau PDF</span>
            </a>
            <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type]) }}" 
               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold transition-all shadow-md shadow-blue-500/20 flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:download"></span>
                <span>Unduh File PDF</span>
            </a>
        </div>
    </div>

    {{-- Filter Periode Form --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6">
        <h3 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
            <span class="iconify text-blue-600 text-lg" data-icon="lucide:filter"></span>
            <span>Filter Rentang Waktu Laporan</span>
        </h3>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Data</label>
                <select name="type" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Layanan (Lengkap)</option>
                    <option value="complaints" {{ $type === 'complaints' ? 'selected' : '' }}>Khusus Pengaduan Layanan</option>
                    <option value="conversations" {{ $type === 'conversations' ? 'selected' : '' }}>Khusus Percakapan Chat</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <span class="iconify text-base" data-icon="lucide:refresh-cw"></span>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Ringkasan Statistik Laporan Terpilih --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-400">Total Percakapan</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $conversationStats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Dalam rentang periode terpilih</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-400">Total Tiket Pengaduan</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $complaintStats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Aduan masuk dari masyarakat</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <span class="text-xs font-semibold text-emerald-600">Aduan Diselesaikan</span>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $complaintStats['resolved'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Tiket telah tuntas ditangani</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <span class="text-xs font-semibold text-blue-600">Tingkat Kepuasan</span>
            <p class="text-2xl font-black text-blue-600 mt-2">{{ $satisfactionRate }}%</p>
            <p class="text-xs text-slate-500 mt-1">{{ $feedbackHelpful }} dari {{ $feedbackTotal }} responden puas</p>
        </div>
    </div>

    {{-- Data Rekapitulasi Aduan --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <span class="iconify text-lg" data-icon="lucide:ticket"></span>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Daftar Rekapitulasi Pengaduan</h3>
                    <p class="text-xs text-slate-500">{{ $complaints->count() }} tiket ditemukan pada periode ini</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <th class="py-3 px-4">No. Tiket</th>
                        <th class="py-3 px-4">Nama Pelapor</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Deskripsi Singkat</th>
                        <th class="py-3 px-4">Tanggal Masuk</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $c)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-blue-600">{{ $c->ticket_number }}</td>
                        <td class="py-3 px-4 font-semibold text-slate-900">{{ $c->reporter_name }}</td>
                        <td class="py-3 px-4 capitalize text-slate-600">{{ $c->category }}</td>
                        <td class="py-3 px-4 text-slate-500 max-w-xs truncate">{{ $c->description }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $c->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($c->status === 'resolved')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Selesai</span>
                            @elseif($c->status === 'processing')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Diproses</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">Baru</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400">Tidak ada pengaduan pada rentang tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
