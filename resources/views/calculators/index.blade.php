@extends('layouts.public')

@section('title', 'Kalkulator Statistik Resmi BPS Kabupaten Karanganyar')
@section('meta_description', 'Kalkulator penyesuaian inflasi IHK resmi BPS dan kalkulator ukuran sampel penelitian ilmiah metode Slovin.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">
    {{-- Header Banner (BPS Corporate Navy) --}}
    <div class="bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white rounded-3xl p-6 sm:p-10 border-b-4 border-[#f7941d] shadow-md relative overflow-hidden">
        <div class="max-w-2xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20 mb-3">
                <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:calculator"></span>
                <span>Instrumen Pelayanan Statistik Terpadu (PST)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                Kalkulator Statistik Interaktif
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-blue-100 leading-relaxed">
                Gunakan alat bantu hitung statistik resmi untuk penyesuaian nilai riil uang berdasarkan Indeks Harga Konsumen (IHK) BPS atau tentukan jumlah sampel minimal penelitian survei Anda secara akurat.
            </p>
        </div>
    </div>

    {{-- Tabs Toolbar --}}
    <div class="flex justify-center">
        <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200 shadow-xs">
            <button type="button" onclick="switchCalcTab('inflation')" id="tab-btn-inflation" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold bg-[#003c80] text-white shadow-xs transition-all flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:trending-up"></span>
                <span>Kalkulator Inflasi (IHK BPS)</span>
            </button>
            <button type="button" onclick="switchCalcTab('sample')" id="tab-btn-sample" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-slate-700 hover:text-slate-900 transition-all flex items-center gap-2">
                <span class="iconify text-base" data-icon="lucide:pie-chart"></span>
                <span>Sampel Penelitian (Slovin)</span>
            </button>
        </div>
    </div>

    {{-- TAB 1: KALKULATOR INFLASI & DAYA BELI --}}
    <div id="tab-inflation" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#005b9f] flex items-center justify-center">
                    <span class="iconify text-xl" data-icon="lucide:trending-up"></span>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900">Hitung Nilai Riil Uang Berdasarkan Inflasi BPS</h2>
                    <p class="text-xs text-slate-500">Berdasarkan data time-series Indeks Harga Konsumen (IHK) resmi BPS (Tahun Dasar 2018 = 100)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nominal Uang (Rupiah) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" id="inf-amount" value="1000000" min="1000" step="1000" oninput="calculateInflation()" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#005b9f] outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Masukkan nominal awal yang ingin dihitung</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Asal / Awal *</label>
                    <select id="inf-start-year" onchange="calculateInflation()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#005b9f] outline-none">
                        <option value="2018">2018 (IHK: 100.00)</option>
                        <option value="2019">2019 (IHK: 103.15)</option>
                        <option value="2020">2020 (IHK: 104.82)</option>
                        <option value="2021">2021 (IHK: 106.50)</option>
                        <option value="2022">2022 (IHK: 112.45)</option>
                        <option value="2023">2023 (IHK: 115.80)</option>
                        <option value="2024">2024 (IHK: 118.95)</option>
                        <option value="2025">2025 (IHK: 122.40)</option>
                        <option value="2026">2026 (IHK: 125.85)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Tujuan / Pembanding *</label>
                    <select id="inf-end-year" onchange="calculateInflation()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#005b9f] outline-none">
                        <option value="2018">2018 (IHK: 100.00)</option>
                        <option value="2019">2019 (IHK: 103.15)</option>
                        <option value="2020">2020 (IHK: 104.82)</option>
                        <option value="2021">2021 (IHK: 106.50)</option>
                        <option value="2022">2022 (IHK: 112.45)</option>
                        <option value="2023">2023 (IHK: 115.80)</option>
                        <option value="2024">2024 (IHK: 118.95)</option>
                        <option value="2025">2025 (IHK: 122.40)</option>
                        <option value="2026" selected>2026 (IHK: 125.85)</option>
                    </select>
                </div>
            </div>

            {{-- Result Banner --}}
            <div class="mt-8 p-6 rounded-2xl bg-blue-50/70 border-2 border-[#005b9f]/30">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left items-center">
                    <div class="space-y-1">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nilai Nominal Awal:</span>
                        <p id="res-nominal-start" class="text-lg font-bold text-slate-800">Rp 1.000.000</p>
                        <span id="res-year-start" class="text-xs text-slate-500">Tahun 2018</span>
                    </div>

                    <div class="text-center p-3 rounded-xl bg-white border border-blue-200 shadow-xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Inflasi Kumulatif Periode</span>
                        <p id="res-cumulative-rate" class="text-xl font-black text-[#005b9f]">+25,85%</p>
                        <span class="text-[11px] text-slate-500">Perubahan IHK</span>
                    </div>

                    <div class="space-y-1 md:text-right">
                        <span class="text-[11px] font-bold text-[#00a651] uppercase tracking-wider">Nilai Setara / Daya Beli:</span>
                        <p id="res-equivalent-value" class="text-2xl font-black text-[#00a651]">Rp 1.258.500</p>
                        <span id="res-year-end" class="text-xs text-[#00a651] font-bold">Pada Tahun 2026</span>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-blue-200 text-xs text-slate-700 leading-relaxed">
                    <p id="res-explanation" class="flex items-start gap-2">
                        <span class="iconify text-base text-[#005b9f] shrink-0 mt-0.5" data-icon="lucide:info"></span>
                        <span><strong>Interpretasi BPS:</strong> Barang atau jasa yang berharga <strong>Rp 1.000.000</strong> pada tahun <strong>2018</strong> memerlukan dana sebesar sekitar <strong>Rp 1.258.500</strong> pada tahun <strong>2026</strong> untuk mempertahankan kuantitas dan kualitas konsumsi yang setara.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: KALKULATOR SAMPEL PENELITIAN (SLOVIN) --}}
    <div id="tab-sample" class="hidden space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#00a651] flex items-center justify-center">
                    <span class="iconify text-xl" data-icon="lucide:pie-chart"></span>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900">Hitung Ukuran Sampel Penelitian Minimal (Rumus Slovin)</h2>
                    <p class="text-xs text-slate-500">Standar metodologi penarikan sampel untuk skripsi, tesis, dan survei statistik masyarakat</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jumlah Total Populasi (N) *</label>
                    <input type="number" id="sample-pop" value="1000" min="1" step="1" oninput="calculateSlovin()" placeholder="Contoh: 1000" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#00a651] outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Total jumlah individu/responden dalam populasi target penelitian</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tingkat Kesalahan / Margin of Error (e) *</label>
                    <select id="sample-error" onchange="calculateSlovin()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#00a651] outline-none">
                        <option value="0.01">1% (Tingkat Kepercayaan 99% - Standar Ketat)</option>
                        <option value="0.05" selected>5% (Tingkat Kepercayaan 95% - Standar Penelitian Akademik / Skripsi)</option>
                        <option value="0.10">10% (Tingkat Kepercayaan 90% - Standar Eksploratif / Lapangan)</option>
                    </select>
                </div>
            </div>

            {{-- Result Card (BPS Green & Navy Theme) --}}
            <div class="mt-8 p-6 rounded-2xl bg-emerald-50/70 border-2 border-[#00a651]/30">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center items-center">
                    <div class="p-3 rounded-xl bg-white border border-emerald-200 shadow-xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Total Populasi (N)</span>
                        <p id="res-pop-val" class="text-xl font-black text-slate-800">1.000</p>
                        <span class="text-[11px] text-slate-500">Responden Target</span>
                    </div>

                    <div class="p-4 rounded-xl bg-[#00a651] text-white shadow-md">
                        <span class="text-[10px] font-bold text-emerald-100 uppercase">Sampel Minimal (n)</span>
                        <p id="res-sample-val" class="text-3xl font-black text-white">286</p>
                        <span class="text-[11px] text-emerald-100 font-bold">Responden Diperlukan</span>
                    </div>

                    <div class="p-3 rounded-xl bg-white border border-emerald-200 shadow-xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Taraf Signifikansi</span>
                        <p id="res-error-val" class="text-xl font-black text-[#00a651]">5%</p>
                        <span class="text-[11px] text-slate-500">Tingkat Keyakinan 95%</span>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-emerald-200 text-xs text-slate-700 space-y-2">
                    <p class="font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="iconify text-base text-[#00a651]" data-icon="lucide:calculator"></span>
                        <span>Metodologi Perhitungan Rumus Slovin:</span>
                    </p>
                    <div class="p-3 rounded-xl bg-white font-mono text-center text-xs text-slate-800 border border-emerald-200 font-bold">
                        n = N / (1 + N × e²)
                    </div>
                    <p id="res-slovin-calc" class="text-[11px] text-slate-600">
                        n = 1000 / (1 + 1000 × 0.05²) = 1000 / (1 + 2.5) = 285.71 → Dibulatkan ke atas menjadi <strong>286 responden</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ihkMap = {
    '2018': 100.00,
    '2019': 103.15,
    '2020': 104.82,
    '2021': 106.50,
    '2022': 112.45,
    '2023': 115.80,
    '2024': 118.95,
    '2025': 122.40,
    '2026': 125.85
};

function switchCalcTab(tab) {
    const tabInf = document.getElementById('tab-inflation');
    const tabSamp = document.getElementById('tab-sample');
    const btnInf = document.getElementById('tab-btn-inflation');
    const btnSamp = document.getElementById('tab-btn-sample');

    if (tab === 'inflation') {
        tabInf.classList.remove('hidden');
        tabSamp.classList.add('hidden');
        btnInf.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold bg-[#005b9f] text-white shadow-xs transition-all flex items-center gap-2';
        btnSamp.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-slate-700 hover:text-slate-900 transition-all flex items-center gap-2';
    } else {
        tabInf.classList.add('hidden');
        tabSamp.classList.remove('hidden');
        btnSamp.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold bg-[#005b9f] text-white shadow-xs transition-all flex items-center gap-2';
        btnInf.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-slate-700 hover:text-slate-900 transition-all flex items-center gap-2';
    }
}

function calculateInflation() {
    const amount = parseFloat(document.getElementById('inf-amount').value) || 0;
    const startYear = document.getElementById('inf-start-year').value;
    const endYear = document.getElementById('inf-end-year').value;

    const startIhk = ihkMap[startYear];
    const endIhk = ihkMap[endYear];

    const ratio = endIhk / startIhk;
    const equivalentValue = amount * ratio;
    const cumulativeRate = ((endIhk - startIhk) / startIhk) * 100;

    document.getElementById('res-nominal-start').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    document.getElementById('res-year-start').textContent = 'Tahun ' + startYear;
    
    document.getElementById('res-cumulative-rate').textContent = (cumulativeRate >= 0 ? '+' : '') + cumulativeRate.toFixed(2) + '%';
    document.getElementById('res-equivalent-value').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(equivalentValue));
    document.getElementById('res-explanation').innerHTML = `<span class="iconify text-base text-[#005b9f] shrink-0 mt-0.5" data-icon="lucide:info"></span><span><strong>Interpretasi BPS:</strong> Nilai barang/jasa sebesar <strong>Rp ${new Intl.NumberFormat('id-ID').format(amount)}</strong> pada tahun <strong>${startYear}</strong> setara daya belinya dengan <strong>Rp ${new Intl.NumberFormat('id-ID').format(Math.round(equivalentValue))}</strong> pada tahun <strong>${endYear}</strong>.</span>`;
}

function calculateSlovin() {
    const N = parseFloat(document.getElementById('sample-pop').value) || 0;
    const e = parseFloat(document.getElementById('sample-error').value);

    if (N <= 0) return;

    const denominator = 1 + (N * (e * e));
    const n = Math.ceil(N / denominator);

    document.getElementById('res-pop-val').textContent = new Intl.NumberFormat('id-ID').format(N);
    document.getElementById('res-sample-val').textContent = new Intl.NumberFormat('id-ID').format(n);
    document.getElementById('res-error-val').textContent = (e * 100) + '%';

    document.getElementById('res-slovin-calc').innerHTML = `n = ${N} / (1 + ${N} × ${e}²) = ${N} / ${denominator.toFixed(4)} = ${(N/denominator).toFixed(2)} → Dibulatkan ke atas menjadi <strong>${n} responden</strong>.`;
}

document.addEventListener('DOMContentLoaded', () => {
    calculateInflation();
    calculateSlovin();
});
</script>
@endpush
@endsection
