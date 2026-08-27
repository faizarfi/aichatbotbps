@extends('layouts.public')

@section('title', 'Survei Kepuasan Masyarakat (SKM) BPS Karanganyar')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    {{-- Header --}}
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-xs font-bold text-amber-800 border border-amber-200">
            <span class="iconify text-sm text-amber-600" data-icon="lucide:star"></span>
            <span>Evaluasi Mutu Pelayanan Publik</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Survei Kepuasan Masyarakat (SKM)
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto">
            Bantu kami mewujudkan pelayanan statistik yang transparan, ramah, dan prima dengan memberikan ulasan jujur atas pengalaman Anda.
        </p>
    </div>

    {{-- Form SKM --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('survei.store') }}" class="space-y-6">
            @csrf

            {{-- Info Responden --}}
            <div class="space-y-4 pb-6 border-b border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">1. Profil Singkat Responden</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Anda (Boleh Dikosongkan / Anonim)</label>
                        <input type="text" name="respondent_name" value="{{ old('respondent_name', auth()->user()->name ?? '') }}" placeholder="Nama / Inisial (Opsional)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Pengguna *</label>
                        <select name="respondent_type" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none">
                            <option value="Mahasiswa / Pelajar">Mahasiswa / Pelajar</option>
                            <option value="Peneliti / Dosen">Peneliti / Dosen</option>
                            <option value="Instansi Pemerintah / OPD">Instansi Pemerintah / OPD</option>
                            <option value="Badan Usaha / Swasta">Badan Usaha / Swasta</option>
                            <option value="Masyarakat Umum">Masyarakat Umum</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Layanan yang Digunakan *</label>
                    <select name="service_used" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="Chatbot & Konsultasi AI 24 Jam">Chatbot & Konsultasi AI 24 Jam</option>
                        <option value="Konsultasi Tatap Muka PST di Kantor">Konsultasi Tatap Muka PST di Kantor</option>
                        <option value="Permintaan Data Mikro & Sektoral">Permintaan Data Mikro & Sektoral</option>
                        <option value="Layanan Pengaduan & Aspirasi">Layanan Pengaduan & Aspirasi</option>
                        <option value="Akses Publikasi & Berita Resmi Statistik">Akses Publikasi & Berita Resmi Statistik</option>
                    </select>
                </div>
            </div>

            {{-- 4 Pilar Penilaian Standar KemenPAN-RB --}}
            <div class="space-y-5 pb-6 border-b border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">2. Penilaian 4 Unsur Mutu Layanan (Skala Bintang 1 - 5)</h3>

                {{-- Pilar 1 --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-900">A. Kesesuaian & Kualitas Data/Informasi</p>
                        <p class="text-[11px] text-slate-500">Kesesuaian data statistik yang diperoleh dengan kebutuhan Anda</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer p-1">
                            <input type="radio" name="quality_score" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-2xl text-slate-300 peer-checked:text-amber-500 hover:text-amber-400 transition-colors">★</span>
                        </label>
                        @endfor
                    </div>
                </div>

                {{-- Pilar 2 --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-900">B. Kecepatan Waktu Respon Pelayanan</p>
                        <p class="text-[11px] text-slate-500">Kecepatan sistem / petugas dalam merespons pertanyaan dan permohonan</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer p-1">
                            <input type="radio" name="speed_score" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-2xl text-slate-300 peer-checked:text-amber-500 hover:text-amber-400 transition-colors">★</span>
                        </label>
                        @endfor
                    </div>
                </div>

                {{-- Pilar 3 --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-900">C. Keramahan & Kejelasan Jawaban</p>
                        <p class="text-[11px] text-slate-500">Sikap ramah, sopan, dan kejelasan penjelasan yang diberikan</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer p-1">
                            <input type="radio" name="friendliness_score" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-2xl text-slate-300 peer-checked:text-amber-500 hover:text-amber-400 transition-colors">★</span>
                        </label>
                        @endfor
                    </div>
                </div>

                {{-- Pilar 4 --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-900">D. Kemudahan Akses & Sarana Layanan</p>
                        <p class="text-[11px] text-slate-500">Kemudahan navigasi website, ruang PST kantor, serta fitur yang tersedia</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer p-1">
                            <input type="radio" name="facility_score" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-2xl text-slate-300 peer-checked:text-amber-500 hover:text-amber-400 transition-colors">★</span>
                        </label>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Saran & Masukan --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">3. Saran & Masukan untuk Peningkatan Layanan (Opsional)</label>
                <textarea name="feedback_text" rows="3" placeholder="Tuliskan saran, kritik, atau apresiasi Anda untuk perbaikan layanan BPS Karanganyar ke depan..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="iconify text-lg" data-icon="lucide:send"></span>
                    <span>Kirim Penilaian Survei Kepuasan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
