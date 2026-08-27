@extends('layouts.admin')

@section('title', 'Laporan Survei Kepuasan Masyarakat (IKM / SKM)')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Indeks Kepuasan Masyarakat (IKM)</h1>
            <p class="text-xs sm:text-sm text-slate-500">Evaluasi mutu pelayanan publik PST BPS Karanganyar berbasis standar KemenPAN-RB.</p>
        </div>
        <a href="{{ route('survei.create') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-sm inline-flex items-center gap-2 transition-all self-start sm:self-auto">
            <span class="iconify text-base" data-icon="lucide:external-link"></span>
            <span>Buka Form Survei Publik</span>
        </a>
    </div>

    {{-- 3 Key IKM Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-3xl p-6 shadow-md shadow-amber-500/20 space-y-2">
            <span class="text-xs font-bold text-amber-100 uppercase tracking-wider">Nilai Indeks IKM (Skala 100)</span>
            <p class="text-3xl sm:text-4xl font-black">{{ number_format($ikmScore, 2, ',', '.') }}</p>
            <p class="text-xs text-amber-100 font-medium">Rata-rata Skor: {{ number_format($overallAverage, 2, ',', '.') }} / 5.00</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mutu Kinerja Pelayanan</span>
            <p class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $grade }}</p>
            <p class="text-xs text-slate-500">Kategori Standar PermenPAN-RB No. 14/2017</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Responden SKM</span>
            <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($totalSurveys, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500">Ulasan masyarakat yang masuk</p>
        </div>
    </div>

    {{-- 4 Pilar Evaluasi Detail --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <h2 class="text-sm font-black text-slate-900 mb-5 flex items-center gap-2">
            <span class="iconify text-lg text-amber-500" data-icon="lucide:bar-chart-2"></span>
            <span>Rincian 4 Unsur Mutu Pelayanan (Skala Bintang 1 - 5)</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Pilar 1 --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-700">1. Kesesuaian & Kualitas Data</span>
                    <span class="text-amber-600 font-mono">{{ number_format($avgQuality, 2, ',', '.') }} / 5.0</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-amber-500 h-3 rounded-full transition-all" style="width: {{ ($avgQuality / 5) * 100 }}%"></div>
                </div>
            </div>

            {{-- Pilar 2 --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-700">2. Kecepatan Waktu Respon</span>
                    <span class="text-blue-600 font-mono">{{ number_format($avgSpeed, 2, ',', '.') }} / 5.0</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-blue-500 h-3 rounded-full transition-all" style="width: {{ ($avgSpeed / 5) * 100 }}%"></div>
                </div>
            </div>

            {{-- Pilar 3 --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-700">3. Keramahan Petugas & Sistem</span>
                    <span class="text-emerald-600 font-mono">{{ number_format($avgFriendliness, 2, ',', '.') }} / 5.0</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-emerald-500 h-3 rounded-full transition-all" style="width: {{ ($avgFriendliness / 5) * 100 }}%"></div>
                </div>
            </div>

            {{-- Pilar 4 --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-700">4. Kemudahan Akses & Fasilitas</span>
                    <span class="text-purple-600 font-mono">{{ number_format($avgFacility, 2, ',', '.') }} / 5.0</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-purple-500 h-3 rounded-full transition-all" style="width: {{ ($avgFacility / 5) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Ulasan & Responden --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Ulasan & Feedback Terbaru dari Masyarakat</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Responden</th>
                        <th class="py-3.5 px-4">Layanan</th>
                        <th class="py-3.5 px-4 text-center">Skor Rata-rata</th>
                        <th class="py-3.5 px-4">Saran / Masukan</th>
                        <th class="py-3.5 px-4 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surveys as $s)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4">
                            <p class="font-bold text-slate-900">{{ $s->respondent_name }}</p>
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-100 text-slate-600 mt-0.5">{{ $s->respondent_type }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-700 font-medium">
                            {{ $s->service_used }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800">
                                ★ {{ $s->overall_score }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate italic">
                            {{ $s->feedback_text ?: '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right text-slate-400">
                            {{ $s->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400">Belum ada respon survei kepuasan yang terdata.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($surveys->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $surveys->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
