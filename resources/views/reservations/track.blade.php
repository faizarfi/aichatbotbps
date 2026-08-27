@extends('layouts.public')

@section('title', 'Cek Status Reservasi Konsultasi PST')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-xs font-bold text-blue-800 border border-blue-200">
            <span class="iconify text-sm text-blue-600" data-icon="lucide:search"></span>
            <span>Pelacakan Jadwal PST</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Lacak Status Tiket Reservasi
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto">
            Masukkan kode booking reservasi Anda untuk memeriksa konfirmasi jadwal dan catatan petugas BPS.
        </p>
    </div>

    {{-- Search Form --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="GET" action="{{ route('reservasi.track') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:ticket"></span>
                </div>
                <input type="text" 
                       name="code" 
                       value="{{ request('code') }}" 
                       required 
                       placeholder="Contoh: PST-BKG-202608-001" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 uppercase font-mono font-bold outline-none">
            </div>
            <button type="submit" class="py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-extrabold shadow-sm flex items-center justify-center gap-2 cursor-pointer transition-all">
                <span class="iconify text-base" data-icon="lucide:search"></span>
                <span>Cari Tiket</span>
            </button>
        </form>
    </div>

    {{-- Search Result --}}
    @if(request('code'))
        @if($reservation)
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs font-mono font-bold text-blue-600 block">{{ $reservation->booking_code }}</span>
                    <h3 class="text-lg font-black text-slate-900">{{ $reservation->visitor_name }}</h3>
                    <p class="text-xs text-slate-500">{{ $reservation->institution ?? 'Masyarakat Umum' }}</p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold self-start sm:self-auto {{ $reservation->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                    Status: {{ $reservation->status_label }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-50">
                    <span class="text-slate-400 block mb-0.5">Jadwal Tanggal:</span>
                    <strong class="text-slate-900 font-bold text-sm">{{ $reservation->reservation_date->translatedFormat('l, d F Y') }}</strong>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50">
                    <span class="text-slate-400 block mb-0.5">Sesi Waktu:</span>
                    <strong class="text-slate-900 font-bold text-sm">{{ $reservation->time_slot }}</strong>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 text-xs">
                <span class="text-slate-400 block mb-0.5">Topik Konsultasi:</span>
                <strong class="text-slate-900 font-bold">{{ $reservation->topic_category }}</strong>
                <p class="text-slate-600 mt-1">{{ $reservation->consultation_purpose }}</p>
            </div>

            @if($reservation->officer_notes)
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <span class="text-amber-800 font-bold block mb-0.5">Catatan Petugas PST:</span>
                <p class="text-amber-900">{{ $reservation->officer_notes }}</p>
            </div>
            @endif

            <div class="pt-2 flex justify-end">
                <a href="{{ route('reservasi.ticket', $reservation->booking_code) }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-1.5">
                    <span class="iconify text-base" data-icon="lucide:printer"></span>
                    <span>Buka Kartu Tiket Digital</span>
                </a>
            </div>
        </div>
        @else
        <div class="p-8 rounded-3xl bg-white border border-slate-200 text-center space-y-3">
            <span class="iconify text-4xl text-rose-500 mx-auto" data-icon="lucide:alert-circle"></span>
            <h3 class="text-base font-bold text-slate-800">Tiket Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Nomor booking <strong>{{ request('code') }}</strong> tidak ditemukan dalam sistem. Pastikan kode yang Anda masukkan sudah benar.</p>
        </div>
        @endif
    @endif
</div>
@endsection
