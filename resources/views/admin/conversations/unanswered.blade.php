@extends('layouts.admin')

@section('title', 'Evaluasi Pertanyaan Belum Terjawab')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-xs font-bold text-amber-800 mb-2">
                <span class="iconify text-sm" data-icon="lucide:book-open"></span>
                <span>Peningkatan Basis Data Layanan</span>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Evaluasi Pertanyaan Belum Terjawab (Fallback)</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar pertanyaan masyarakat yang belum memiliki rujukan di basis pengetahuan. Tambahkan artikel baru agar informasi semakin lengkap.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.conversations.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-300 transition-all flex items-center gap-1.5">
                <span class="iconify text-base" data-icon="lucide:arrow-left"></span>
                <span>Semua Percakapan</span>
            </a>
            <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                <span class="iconify text-base" data-icon="lucide:plus-circle"></span>
                <span>Buat Artikel Baru</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                <span class="iconify text-2xl" data-icon="lucide:help-circle"></span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Pertanyaan Fallback</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalFallback, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Perlu ditindaklanjuti</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="iconify text-2xl" data-icon="lucide:check-circle-2"></span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Ketepatan Jawaban</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $accuracyRate }}%</h3>
                <p class="text-[11px] text-emerald-600 font-medium">Pertanyaan terjawab tepat</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                <span class="iconify text-2xl" data-icon="lucide:plus-circle"></span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Tindakan Cepat</p>
                <h3 class="text-sm font-bold text-slate-800">1-Klik Input Artikel</h3>
                <p class="text-[11px] text-slate-400">Otomatis pre-fill judul & soal</p>
            </div>
        </div>
    </div>

    {{-- Search Filter --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.conversations.unanswered') }}" class="flex gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="Cari kata kunci pada riwayat jawaban fallback...">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2">
                <span class="iconify" data-icon="lucide:search"></span> Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.conversations.unanswered') }}" class="px-4 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-medium transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Fallback Question List --}}
    <div class="space-y-4">
        @forelse($fallbackMessages as $item)
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:border-amber-300 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 max-w-3xl">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        Belum Terjawab
                    </span>
                    <span class="text-xs text-slate-400 font-medium">
                        {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                    @if($item->conversation)
                    <span class="text-xs text-slate-400">
                        &bull; Penanya: <strong class="text-slate-700">{{ $item->conversation->visitor_name }}</strong>
                    </span>
                    @endif
                </div>

                {{-- Pertanyaan Pengunjung --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5">
                    <div class="flex items-start gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                            Q
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 leading-relaxed">
                                "{{ $item->question }}"
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Preview Respon Fallback --}}
                <p class="text-xs text-slate-500 line-clamp-1 italic pl-1">
                    Respon Sistem: {{ $item->bot_response }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-row md:flex-col items-center md:items-end gap-2 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                <a href="{{ route('admin.articles.create', ['question' => $item->question, 'title' => 'Data ' . \Illuminate\Support\Str::limit($item->question, 40)]) }}"
                   class="w-full md:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                    <span class="iconify text-base" data-icon="lucide:book-plus"></span>
                    <span>Jadikan Artikel FAQ</span>
                </a>

                @if($item->conversation)
                <a href="{{ route('admin.conversations.show', $item->conversation_id) }}"
                   class="w-full md:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-1">
                    <span class="iconify" data-icon="lucide:external-link"></span>
                    <span>Lihat Chat</span>
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <span class="iconify text-3xl" data-icon="lucide:check-circle-2"></span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak Ada Pertanyaan Belum Terjawab</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Seluruh pertanyaan masyarakat berhasil dijawab dengan baik melalui basis data resmi BPS Kabupaten Karanganyar.
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($fallbackMessages->hasPages())
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        {{ $fallbackMessages->links() }}
    </div>
    @endif
</div>
@endsection
