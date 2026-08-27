@extends('layouts.public')

@section('title', 'Tiket Reservasi PST BPS Karanganyar - ' . $reservation->booking_code)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    {{-- Success Alert --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3">
        <span class="iconify text-2xl text-emerald-600 shrink-0" data-icon="lucide:check-circle-2"></span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Digital Ticket Card --}}
    <div id="printable-ticket" class="bg-white rounded-3xl border-2 border-dashed border-blue-300 shadow-lg overflow-hidden relative">
        {{-- Top Header Ribbon --}}
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white p-6 sm:p-7 text-center relative">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-xs font-bold text-white border border-white/20 mb-2">
                <span>Pelayanan Statistik Terpadu (PST)</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">KARTU RESERVASI KONSULTASI</h2>
            <p class="text-xs text-blue-100 mt-1">BPS Kabupaten Karanganyar • Jl. Lawu No. 202B</p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            {{-- QR Code & Booking Code Banner --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center sm:text-left">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">KODE BOOKING ANDA:</span>
                    <p class="text-2xl sm:text-3xl font-black font-mono text-blue-700">{{ $reservation->booking_code }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold {{ $reservation->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        Status: {{ $reservation->status_label }}
                    </span>
                </div>
                <div class="shrink-0 bg-white p-3 rounded-xl border border-slate-200 shadow-xs flex flex-col items-center">
                    {{-- QR Code via standard API --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($reservation->booking_code) }}" 
                         alt="QR Code Tiket" 
                         class="w-24 h-24 object-contain">
                    <span class="text-[9px] font-bold text-slate-400 mt-1">SCAN DI KANTOR BPS</span>
                </div>
            </div>

            {{-- Detail Kunjungan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-semibold block mb-0.5">Nama Pengunjung:</span>
                    <strong class="text-slate-900 text-sm font-bold">{{ $reservation->visitor_name }}</strong>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-semibold block mb-0.5">Asal Instansi / Universitas:</span>
                    <strong class="text-slate-900 text-sm font-bold">{{ $reservation->institution ?? '-' }}</strong>
                </div>
                <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-100">
                    <span class="text-blue-600 font-semibold block mb-0.5">Jadwal Tanggal Kunjungan:</span>
                    <strong class="text-blue-900 text-sm font-black">{{ $reservation->reservation_date->translatedFormat('l, d F Y') }}</strong>
                </div>
                <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-100">
                    <span class="text-blue-600 font-semibold block mb-0.5">Sesi Waktu:</span>
                    <strong class="text-blue-900 text-sm font-black">{{ $reservation->time_slot }}</strong>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                <span class="text-slate-400 font-semibold block mb-0.5">Topik Konsultasi:</span>
                <strong class="text-slate-900 font-bold block">{{ $reservation->topic_category }}</strong>
                <p class="text-slate-600 mt-1">{{ $reservation->consultation_purpose }}</p>
            </div>

            @if($reservation->officer_notes)
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <span class="text-amber-800 font-bold block mb-0.5">Catatan Petugas PST BPS:</span>
                <p class="text-amber-900">{{ $reservation->officer_notes }}</p>
            </div>
            @endif

            {{-- Petunjuk Kunjungan --}}
            <div class="text-[11px] text-slate-500 space-y-1 border-t border-slate-100 pt-4">
                <p class="font-bold text-slate-700">📌 Petunjuk Kunjungan PST:</p>
                <p>1. Tunjukkan tiket digital ini (atau cetak fisik) kepada resepsionis / petugas PST saat tiba di kantor BPS.</p>
                <p>2. Datang 10 menit sebelum jadwal sesi waktu yang telah dipilih.</p>
                <p>3. Berpakaian rapi dan sopan.</p>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap items-center justify-center gap-3">
        <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 flex items-center gap-2 cursor-pointer transition-all">
            <span class="iconify text-base" data-icon="lucide:printer"></span>
            <span>Cetak / Simpan PDF Tiket</span>
        </button>
        <a href="{{ route('reservasi.create') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold border border-slate-300 flex items-center gap-2 transition-all">
            <span class="iconify text-base" data-icon="lucide:calendar-plus"></span>
            <span>Buat Reservasi Baru</span>
        </a>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printable-ticket, #printable-ticket * { visibility: visible; }
    #printable-ticket { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
}
</style>
@endsection
