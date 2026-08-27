@extends('layouts.admin')

@section('title', 'Detail Aduan: ' . $complaint->ticket_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.complaints.index') }}" class="p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors">
                <span class="iconify text-lg" data-icon="lucide:arrow-left"></span>
            </a>
            <div>
                <span class="text-xs font-mono text-slate-400">Nomor Tiket</span>
                <h2 class="text-xl font-bold font-mono text-blue-600 leading-tight">{{ $complaint->ticket_number }}</h2>
            </div>
        </div>

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
        @endphp
        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadge[$complaint->status] ?? '' }}">
            Status: {{ $statusLabels[$complaint->status] ?? $complaint->status }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Complaint Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Informasi Pelapor</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Nama Pelapor</span>
                        <p class="font-semibold text-slate-800">{{ $complaint->reporter_name }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Kontak Pelapor</span>
                        <p class="font-mono text-slate-800">{{ $complaint->reporter_contact }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Kategori Aduan</span>
                        <p class="font-medium text-slate-800 capitalize">{{ $complaint->category }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Prioritas</span>
                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $complaint->priority === 'high' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-700' }} capitalize">
                            {{ $complaint->priority }}
                        </span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400 block mb-2">Uraian Masalah / Pengaduan</span>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $complaint->description }}
                    </div>
                </div>

                {{-- Attachments --}}
                @if($complaint->attachments->count() > 0)
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400 block mb-2">Lampiran Bukti ({{ $complaint->attachments->count() }})</span>
                    <div class="space-y-2">
                        @foreach($complaint->attachments as $att)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center gap-2.5 truncate">
                                <span class="iconify text-lg text-blue-600 shrink-0" data-icon="lucide:file"></span>
                                <div class="truncate">
                                    <p class="text-xs font-semibold text-slate-800 truncate">{{ $att->original_name }}</p>
                                    <span class="text-[10px] text-slate-400">{{ round($att->file_size / 1024, 1) }} KB</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.complaints.download', $att) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1 shrink-0">
                                <span class="iconify" data-icon="lucide:download"></span>
                                <span>Unduh</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Status History / Timeline --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Riwayat Perubahan Status</h3>

                <div class="space-y-4">
                    @forelse($complaint->statusLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mt-1.5 shrink-0"></div>
                        <div class="flex-1 bg-slate-50 p-3.5 rounded-xl border border-slate-200/80">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-slate-800 capitalize">{{ $statusLabels[$log->status] ?? $log->status }}</span>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($log->note)
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $log->note }}</p>
                            @endif
                            <p class="text-[10px] text-slate-400 mt-1">Oleh: {{ $log->changedByUser?->name ?? 'Sistem' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Belum ada riwayat perubahan status.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Update Status Action Card --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Tindak Lanjut Aduan</h3>

                <form action="{{ route('admin.complaints.status', $complaint) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Ubah Status <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="new" {{ $complaint->status === 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="verified" {{ $complaint->status === 'verified' ? 'selected' : '' }}>Diverifikasi</option>
                            <option value="processing" {{ $complaint->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Selesai (Tuntas)</option>
                            <option value="rejected" {{ $complaint->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-xs font-semibold text-slate-700 mb-1.5">Tingkat Prioritas</label>
                        <select id="priority" name="priority"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="low" {{ $complaint->priority === 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="normal" {{ $complaint->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ $complaint->priority === 'high' ? 'selected' : '' }}>Tinggi (Urgent)</option>
                        </select>
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-xs font-semibold text-slate-700 mb-1.5">Petugas Penanggung Jawab</label>
                        <select id="assigned_to" name="assigned_to"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Belum Ditugaskan --</option>
                            @foreach($officers as $officer)
                            <option value="{{ $officer->id }}" {{ $complaint->assigned_to == $officer->id ? 'selected' : '' }}>{{ $officer->name }} ({{ $officer->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="note" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Tindak Lanjut</label>
                        <textarea id="note" name="note" rows="4"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                                  placeholder="Tuliskan catatan proses penanganan atau alasan perubahan status..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all">
                        Perbarui Status Aduan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
