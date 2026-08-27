@extends('layouts.public')

@section('title', 'Booking Konsultasi Tatap Muka PST')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    {{-- Header --}}
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-xs font-bold text-blue-800 border border-blue-200">
            <span class="iconify text-sm text-blue-600" data-icon="lucide:calendar-clock"></span>
            <span>Pelayanan Tatap Muka Langsung</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Reservasi Konsultasi Statistik Offline
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto">
            Jadwalkan kunjungan Anda ke Ruang Pelayanan Statistik Terpadu (PST) Kantor BPS Kabupaten Karanganyar tanpa perlu antre lama.
        </p>
    </div>

    {{-- Lokasi Info Card --}}
    <div class="bg-gradient-to-r from-blue-50 to-sky-50 p-4 rounded-2xl border border-blue-200 text-xs text-slate-700 flex items-start gap-3">
        <span class="iconify text-xl text-blue-600 shrink-0 mt-0.5" data-icon="lucide:map-pin"></span>
        <div>
            <p class="font-bold text-slate-900">Lokasi Pelayanan PST:</p>
            <p>Kantor BPS Kabupaten Karanganyar, Jl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar (Hari Kerja: Senin – Jumat, 08.00 – 15.30 WIB)</p>
        </div>
    </div>

    {{-- Form Booking --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('reservasi.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="visitor_name" value="{{ old('visitor_name', auth()->user()->name ?? '') }}" required placeholder="Contoh: Muhammad Ilham" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('visitor_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                    <input type="email" name="visitor_email" value="{{ old('visitor_email', auth()->user()->email ?? '') }}" required placeholder="email@gmail.com" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('visitor_email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP *</label>
                    <input type="text" name="visitor_phone" value="{{ old('visitor_phone') }}" required placeholder="081234567890" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('visitor_phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Asal Instansi / Universitas (Opsional)</label>
                    <input type="text" name="institution" value="{{ old('institution') }}" placeholder="Contoh: UNS / UMS / Dinas Kominfo" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Topik Konsultasi *</label>
                    <select name="topic_category" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Pilih Topik Konsultasi</option>
                        <option value="Konsultasi Skripsi / Tugas Akhir">Konsultasi Skripsi / Tugas Akhir</option>
                        <option value="Data Sosial & Kemiskinan">Data Sosial & Kemiskinan</option>
                        <option value="Data Pertanian & Perkebunan">Data Pertanian & Perkebunan</option>
                        <option value="Data Ekonomi & PDRB">Data Ekonomi & PDRB</option>
                        <option value="Rekomendasi Statistik Sektoral (ROMANTIK)">Rekomendasi Statistik Sektoral (ROMANTIK)</option>
                        <option value="Layanan Perpustakaan Cetak BPS">Layanan Perpustakaan Cetak BPS</option>
                        <option value="Konsultasi Statistik Umum">Konsultasi Statistik Umum</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rencana Tanggal Kunjungan *</label>
                    <input type="date" name="reservation_date" min="{{ date('Y-m-d') }}" value="{{ old('reservation_date', date('Y-m-d', strtotime('+1 day'))) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilihan Sesi Waktu Pelayanan *</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50/70 cursor-pointer flex items-center gap-2.5 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                        <input type="radio" name="time_slot" value="08.30 - 10.00 WIB" required class="text-blue-600">
                        <div>
                            <p class="text-xs font-bold">Sesi 1 (Pagi)</p>
                            <p class="text-[11px] text-slate-500">08.30 - 10.00 WIB</p>
                        </div>
                    </label>
                    <label class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50/70 cursor-pointer flex items-center gap-2.5 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                        <input type="radio" name="time_slot" value="10.30 - 12.00 WIB" class="text-blue-600">
                        <div>
                            <p class="text-xs font-bold">Sesi 2 (Siang)</p>
                            <p class="text-[11px] text-slate-500">10.30 - 12.00 WIB</p>
                        </div>
                    </label>
                    <label class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50/70 cursor-pointer flex items-center gap-2.5 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700">
                        <input type="radio" name="time_slot" value="13.00 - 14.30 WIB" class="text-blue-600">
                        <div>
                            <p class="text-xs font-bold">Sesi 3 (Sore)</p>
                            <p class="text-[11px] text-slate-500">13.00 - 14.30 WIB</p>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rincian Data / Hal yang Ingin Dikonsultasikan *</label>
                <textarea name="consultation_purpose" rows="3" required placeholder="Jelaskan kebutuhan data atau topik konsultasi statistik Anda secara singkat..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="iconify text-lg" data-icon="lucide:check-circle"></span>
                    <span>Ajukan Reservasi & Buat Tiket Digital</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
