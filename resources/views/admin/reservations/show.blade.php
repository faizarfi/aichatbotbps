@extends('layouts.admin')

@section('title', 'Detail Reservasi ' . $reservation->booking_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <span class="iconify text-base" data-icon="lucide:arrow-left"></span>
            <span>Kembali ke Daftar Reservasi</span>
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $reservation->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
            Status: {{ $reservation->status_label }}
        </span>
    </div>

    {{-- Info Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-bold text-blue-600 block">KODE BOOKING: {{ $reservation->booking_code }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">{{ $reservation->visitor_name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Asal Instansi / Universitas: <strong class="text-slate-800">{{ $reservation->institution ?? '-' }}</strong></p>
            </div>
            <div class="text-right text-xs text-slate-400">
                <span>Diajukan pada:</span>
                <p class="font-bold text-slate-700">{{ $reservation->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                <span class="text-blue-600 font-semibold block mb-1">Rencana Tanggal:</span>
                <p class="text-sm font-black text-blue-950">{{ $reservation->reservation_date->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                <span class="text-blue-600 font-semibold block mb-1">Sesi Waktu:</span>
                <p class="text-sm font-black text-blue-950">{{ $reservation->time_slot }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-slate-500 font-semibold block mb-1">Kontak Pemohon:</span>
                <p class="font-bold text-slate-900">{{ $reservation->visitor_phone }}</p>
                <p class="text-slate-500">{{ $reservation->visitor_email }}</p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block">Topik Konsultasi:</span>
            <strong class="text-sm font-bold text-slate-900 block">{{ $reservation->topic_category }}</strong>
            <p class="text-slate-600 pt-2 leading-relaxed whitespace-pre-line">{{ $reservation->consultation_purpose }}</p>
        </div>

        {{-- Form Update Status --}}
        <div class="pt-6 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="iconify text-base text-blue-600" data-icon="lucide:settings-2"></span>
                <span>Tindak Lanjut & Verifikasi Petugas PST</span>
            </h3>
            <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ubah Status Reservasi *</label>
                        <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="approved" {{ $reservation->status === 'approved' ? 'selected' : '' }}>Disetujui (Konfirmasi Kunjungan)</option>
                            <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Selesai (Konsultasi Telah Terlaksana)</option>
                            <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Arahan Petugas untuk Pengunjung</label>
                    <textarea name="officer_notes" rows="3" placeholder="Contoh: Jadwal disetujui. Silakan langsung menemui Petugas PST di lantai 1 membawa draft penelitian..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">{{ old('officer_notes', $reservation->officer_notes) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="py-2.5 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-sm cursor-pointer transition-all flex items-center gap-2">
                        <span class="iconify text-base" data-icon="lucide:save"></span>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
