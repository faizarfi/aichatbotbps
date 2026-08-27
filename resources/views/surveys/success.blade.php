@extends('layouts.public')

@section('title', 'Terima Kasih atas Penilaian Anda')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-12 sm:py-16 text-center space-y-6">
    <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 border border-emerald-200 mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/10">
        <span class="iconify text-4xl" data-icon="lucide:award"></span>
    </div>

    <div class="space-y-2">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Terima Kasih Banyak!
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Evaluasi dan ulasan Anda telah tercatat dalam sistem <strong>Indeks Kepuasan Masyarakat (IKM)</strong> BPS Kabupaten Karanganyar.
        </p>
    </div>

    {{-- Score Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 text-left space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <span class="text-[11px] text-slate-400 font-bold uppercase">Layanan yang Dinilai:</span>
                <p class="text-xs font-bold text-slate-800">{{ $survey->service_used }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">
                Skor: {{ $survey->overall_score }} / 5.0
            </span>
        </div>

        <div class="grid grid-cols-2 gap-2 text-xs">
            <div class="p-2.5 rounded-xl bg-slate-50">
                <span class="text-slate-400 block text-[10px]">Kualitas Data:</span>
                <strong class="text-slate-800">{{ $survey->quality_score }} / 5 Bintang</strong>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-50">
                <span class="text-slate-400 block text-[10px]">Kecepatan Respon:</span>
                <strong class="text-slate-800">{{ $survey->speed_score }} / 5 Bintang</strong>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-50">
                <span class="text-slate-400 block text-[10px]">Keramahan Petugas:</span>
                <strong class="text-slate-800">{{ $survey->friendliness_score }} / 5 Bintang</strong>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-50">
                <span class="text-slate-400 block text-[10px]">Kemudahan Akses:</span>
                <strong class="text-slate-800">{{ $survey->facility_score }} / 5 Bintang</strong>
            </div>
        </div>

        @if($survey->feedback_text)
        <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100 text-xs">
            <span class="text-blue-700 font-bold block mb-1">Saran & Masukan Anda:</span>
            <p class="text-slate-700 italic">"{{ $survey->feedback_text }}"</p>
        </div>
        @endif
    </div>

    <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
        <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all">
            Kembali ke Beranda
        </a>
        <a href="{{ route('chat.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold border border-slate-300 transition-all">
            Tanya Chatbot
        </a>
    </div>
</div>
@endsection
