@extends('layouts.public')

@section('title', 'Peta Tematik & Statistik 17 Kecamatan Kabupaten Karanganyar')
@section('meta_description', 'Profil demografi, luas wilayah, kepadatan penduduk, dan potensi unggulan 17 kecamatan di Kabupaten Karanganyar resmi BPS.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">
    {{-- Header Banner (BPS Corporate Navy) --}}
    <div class="bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white rounded-3xl p-6 sm:p-10 border-b-4 border-[#f7941d] shadow-md relative overflow-hidden">
        <div class="max-w-3xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20 mb-3">
                <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:map-pin"></span>
                <span>Data Statistik Spasial & Kewilayahan BPS</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                Statistik & Profil 17 Kecamatan Kabupaten Karanganyar
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-blue-100 leading-relaxed max-w-2xl">
                Jelajahi data agregat kependudukan, luas wilayah administratif, kepadatan, dan komparasi potensi sektor unggulan di 17 kecamatan se-Kabupaten Karanganyar berdasarkan publikasi <em>Kecamatan Dalam Angka</em>.
            </p>
        </div>
    </div>

    {{-- 4 Stat Cards Beridentitas Resmi BPS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Penduduk --}}
        <div class="bg-white rounded-2xl border border-slate-200 border-t-4 border-t-[#005b9f] p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Penduduk</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#005b9f] flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:users"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($totalPopulation, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Jiwa (17 Kecamatan)</p>
        </div>

        {{-- Luas Wilayah --}}
        <div class="bg-white rounded-2xl border border-slate-200 border-t-4 border-t-[#00a651] p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Luas Wilayah</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-[#00a651] flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:map"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($totalArea, 2, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Kilometer Persegi (km²)</p>
        </div>

        {{-- Kepadatan Rata-rata --}}
        <div class="bg-white rounded-2xl border border-slate-200 border-t-4 border-t-[#f7941d] p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kepadatan Rata-rata</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-[#f7941d] flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:activity"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($avgDensity, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Jiwa per km²</p>
        </div>

        {{-- Desa & Kelurahan --}}
        <div class="bg-white rounded-2xl border border-slate-200 border-t-4 border-t-[#04325e] p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Wilayah Administrasi</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-[#04325e] flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:building-2"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ $totalVillages }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Desa dan Kelurahan</p>
        </div>
    </div>

    {{-- Komparasi Indikator Statistik Wilayah 17 Kecamatan --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-[#005b9f] text-xs font-bold border border-blue-200 mb-2">
                    <span class="iconify text-sm" data-icon="lucide:scale"></span>
                    <span>Analisis Komparasi Wilayah</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Komparasi Indikator Statistik Antar-Kecamatan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Bandingkan data kependudukan, luas wilayah, kepadatan, dan karakteristik sektoral antar 2 kecamatan secara berdampingan.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="randomCompare()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold border border-slate-300 transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:shuffle"></span>
                    <span>Acak Perbandingan</span>
                </button>
            </div>
        </div>

        {{-- Dropdown Pemilihan 2 Kecamatan (BPS Blue vs BPS Orange) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-blue-50/70 border border-[#005b9f]/30 space-y-2">
                <label for="compare-select-a" class="block text-xs font-extrabold text-[#005b9f] uppercase tracking-wider">
                    Pilih Wilayah A (Kecamatan 1):
                </label>
                <select id="compare-select-a" onchange="updateComparison()" class="w-full px-3.5 py-2.5 rounded-xl border border-blue-300 bg-white text-slate-900 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-[#005b9f] outline-none">
                    @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ $d->name === 'Colomadu' ? 'selected' : '' }}>
                        Kecamatan {{ $d->name }} ({{ number_format($d->population, 0, ',', '.') }} Jiwa)
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/70 border border-[#f7941d]/30 space-y-2">
                <label for="compare-select-b" class="block text-xs font-extrabold text-[#ea580c] uppercase tracking-wider">
                    Pilih Wilayah B (Kecamatan 2):
                </label>
                <select id="compare-select-b" onchange="updateComparison()" class="w-full px-3.5 py-2.5 rounded-xl border border-amber-300 bg-white text-slate-900 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-[#f7941d] outline-none">
                    @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ $d->name === 'Tawangmangu' ? 'selected' : '' }}>
                        Kecamatan {{ $d->name }} ({{ number_format($d->population, 0, ',', '.') }} Jiwa)
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Comparison Result Container --}}
        <div id="comparison-result" class="space-y-4">
            {{-- Dynamic Content via JavaScript --}}
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Urutkan:</span>
            <button type="button" onclick="sortDistricts('name')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#005b9f] text-white shadow-xs" data-sort="name">
                Nama (A-Z)
            </button>
            <button type="button" onclick="sortDistricts('population')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200" data-sort="population">
                Penduduk Terbanyak
            </button>
            <button type="button" onclick="sortDistricts('density')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200" data-sort="density">
                Kepadatan Tertinggi
            </button>
            <button type="button" onclick="sortDistricts('area')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200" data-sort="area">
                Wilayah Terluas
            </button>
        </div>
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <span class="iconify text-base" data-icon="lucide:search"></span>
            </div>
            <input type="text" 
                   id="search-input" 
                   oninput="filterDistricts(this.value)" 
                   placeholder="Cari kecamatan (cth: Tawangmangu)..." 
                   class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#005b9f] outline-none transition-all">
        </div>
    </div>

    {{-- Grid 17 Kecamatan Cards Beridentitas Resmi BPS --}}
    <div id="districts-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($districts as $d)
        <div class="district-card bg-white rounded-2xl border border-slate-200 hover:border-[#005b9f] shadow-sm hover:shadow-md transition-all p-5 flex flex-col justify-between group"
             data-name="{{ strtolower($d->name) }}"
             data-population="{{ $d->population }}"
             data-density="{{ $d->density }}"
             data-area="{{ $d->area_sqkm }}">
            <div>
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-slate-400">KODE WILAYAH: {{ $d->code }}</span>
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-[#005b9f] transition-colors">
                            Kecamatan {{ $d->name }}
                        </h3>
                        <p class="text-xs text-slate-500">Ibu kota: <strong class="text-slate-700">{{ $d->capital_name }}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-[#005b9f] border border-blue-200 shrink-0">
                        {{ $d->villages_count }} Desa/Kel
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-100 my-3 text-center">
                    <div class="p-1.5 rounded-lg bg-slate-50">
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Penduduk</p>
                        <p class="text-xs font-black text-slate-800 mt-0.5">{{ number_format($d->population, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-1.5 rounded-lg bg-slate-50">
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Luas (km²)</p>
                        <p class="text-xs font-black text-slate-800 mt-0.5">{{ number_format($d->area_sqkm, 1, ',', '.') }}</p>
                    </div>
                    <div class="p-1.5 rounded-lg bg-slate-50">
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Kepadatan</p>
                        <p class="text-xs font-black text-slate-800 mt-0.5">{{ number_format($d->density, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-1.5 mb-3">
                    <p class="text-[11px] font-bold text-slate-700 flex items-center gap-1.5">
                        <span class="iconify text-[#00a651] text-sm" data-icon="lucide:award"></span>
                        <span>Potensi & Sektor Unggulan:</span>
                    </p>
                    <p class="text-xs text-slate-700 bg-emerald-50/70 border border-emerald-200 p-2.5 rounded-xl font-medium">
                        {{ $d->featured_sector }}
                    </p>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    {{ $d->description }}
                </p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-[10px] text-slate-400">Sumber: BPS Karanganyar</span>
                <a href="{{ route('chat.index') }}" class="font-bold text-[#005b9f] hover:text-[#04325e] flex items-center gap-1">
                    <span class="iconify text-sm" data-icon="lucide:message-square"></span>
                    <span>Konsultasi Data Ini</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
const districtsData = @json($districts);

function updateComparison() {
    const selA = document.getElementById('compare-select-a');
    const selB = document.getElementById('compare-select-b');
    const container = document.getElementById('comparison-result');
    if (!selA || !selB || !container) return;

    const idA = parseInt(selA.value);
    const idB = parseInt(selB.value);

    const distA = districtsData.find(d => d.id === idA) || districtsData[0];
    const distB = districtsData.find(d => d.id === idB) || districtsData[1];

    const popDiff = distA.population - distB.population;
    const popLeader = popDiff >= 0 ? distA.name : distB.name;
    const popPctA = Math.round((distA.population / (distA.population + distB.population)) * 100);
    const popPctB = 100 - popPctA;

    const areaDiff = (distA.area_sqkm - distB.area_sqkm).toFixed(2);
    const areaLeader = distA.area_sqkm >= distB.area_sqkm ? distA.name : distB.name;

    const densityDiff = distA.density - distB.density;
    const densityLeader = distA.density >= distB.density ? distA.name : distB.name;

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Card Kecamatan A (BPS Blue) --}}
            <div class="p-5 rounded-2xl bg-blue-50/60 border-2 border-[#005b9f]/40 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold text-[#005b9f] uppercase tracking-wider">Wilayah A</span>
                        <h3 class="text-xl font-black text-slate-900">Kecamatan ${distA.name}</h3>
                        <p class="text-xs text-slate-500">Ibu kota: <strong class="text-slate-700">${distA.capital_name}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#005b9f] text-white">
                        ${distA.villages_count} Desa/Kel
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-2 py-3 border-y border-blue-200 text-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Penduduk</p>
                        <p class="text-sm font-black text-[#04325e]">${distA.population.toLocaleString('id-ID')} Jiwa</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Luas Wilayah</p>
                        <p class="text-sm font-black text-[#04325e]">${parseFloat(distA.area_sqkm).toFixed(2)} km²</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Kepadatan</p>
                        <p class="text-sm font-black text-[#04325e]">${distA.density.toLocaleString('id-ID')} /km²</p>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-[#005b9f] mb-1 flex items-center gap-1">
                        <span class="iconify text-[#00a651]" data-icon="lucide:award"></span> Sektor Unggulan:
                    </p>
                    <p class="text-xs text-slate-700 bg-white p-3 rounded-xl border border-blue-200 leading-relaxed font-medium">
                        ${distA.featured_sector}
                    </p>
                </div>
            </div>

            {{-- Card Kecamatan B (BPS Orange) --}}
            <div class="p-5 rounded-2xl bg-amber-50/60 border-2 border-[#f7941d]/40 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold text-[#ea580c] uppercase tracking-wider">Wilayah B</span>
                        <h3 class="text-xl font-black text-slate-900">Kecamatan ${distB.name}</h3>
                        <p class="text-xs text-slate-500">Ibu kota: <strong class="text-slate-700">${distB.capital_name}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#f7941d] text-white">
                        ${distB.villages_count} Desa/Kel
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-2 py-3 border-y border-amber-200 text-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Penduduk</p>
                        <p class="text-sm font-black text-amber-950">${distB.population.toLocaleString('id-ID')} Jiwa</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Luas Wilayah</p>
                        <p class="text-sm font-black text-amber-950">${parseFloat(distB.area_sqkm).toFixed(2)} km²</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase">Kepadatan</p>
                        <p class="text-sm font-black text-amber-950">${distB.density.toLocaleString('id-ID')} /km²</p>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-[#ea580c] mb-1 flex items-center gap-1">
                        <span class="iconify text-[#00a651]" data-icon="lucide:award"></span> Sektor Unggulan:
                    </p>
                    <p class="text-xs text-slate-700 bg-white p-3 rounded-xl border border-amber-200 leading-relaxed font-medium">
                        ${distB.featured_sector}
                    </p>
                </div>
            </div>
        </div>

        {{-- Visual Ratio Bar & Highlight Summary --}}
        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                <span class="text-[#005b9f]">Rasio Penduduk: <strong>${distA.name} (${popPctA}%)</strong></span>
                <span class="text-slate-600">Selisih: <strong>${Math.abs(popDiff).toLocaleString('id-ID')} Jiwa</strong> (${popLeader} lebih banyak)</span>
                <span class="text-[#ea580c]"><strong>${distB.name} (${popPctB}%)</strong></span>
            </div>
            <div class="w-full h-3.5 rounded-full bg-slate-200 overflow-hidden flex shadow-inner">
                <div class="bg-[#005b9f] h-full transition-all duration-500" style="width: ${popPctA}%"></div>
                <div class="bg-[#f7941d] h-full transition-all duration-500" style="width: ${popPctB}%"></div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#00a651]"></span>
                    <span>Wilayah terluas: <strong>Kecamatan ${areaLeader}</strong> (selisih ${Math.abs(areaDiff)} km²)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#f7941d]"></span>
                    <span>Wilayah terpadat: <strong>Kecamatan ${densityLeader}</strong> (selisih ${Math.abs(densityDiff)} jiwa/km²)</span>
                </div>
                <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-1 text-[#005b9f] hover:text-[#04325e] font-extrabold text-xs">
                    <span>Konsultasi Data Kecamatan</span> &rarr;
                </a>
            </div>
        </div>
    `;
}

function randomCompare() {
    if (!districtsData || districtsData.length < 2) return;
    const selA = document.getElementById('compare-select-a');
    const selB = document.getElementById('compare-select-b');
    
    let idxA = Math.floor(Math.random() * districtsData.length);
    let idxB = Math.floor(Math.random() * districtsData.length);
    while (idxB === idxA) {
        idxB = Math.floor(Math.random() * districtsData.length);
    }
    
    selA.value = districtsData[idxA].id;
    selB.value = districtsData[idxB].id;
    updateComparison();
}

document.addEventListener('DOMContentLoaded', function() {
    updateComparison();
});

function filterDistricts(query) {
    const q = query.toLowerCase().trim();
    const cards = document.querySelectorAll('.district-card');
    cards.forEach(c => {
        const name = c.dataset.name;
        if (!q || name.includes(q)) {
            c.style.display = 'flex';
        } else {
            c.style.display = 'none';
        }
    });
}

function sortDistricts(by) {
    const container = document.getElementById('districts-container');
    const cards = Array.from(document.querySelectorAll('.district-card'));
    
    document.querySelectorAll('.btn-sort').forEach(btn => {
        if (btn.dataset.sort === by) {
            btn.className = 'btn-sort px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#005b9f] text-white shadow-xs';
        } else {
            btn.className = 'btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200';
        }
    });

    cards.sort((a, b) => {
        if (by === 'name') {
            return a.dataset.name.localeCompare(b.dataset.name);
        } else if (by === 'population') {
            return parseInt(b.dataset.population) - parseInt(a.dataset.population);
        } else if (by === 'density') {
            return parseInt(b.dataset.density) - parseInt(a.dataset.density);
        } else if (by === 'area') {
            return parseFloat(b.dataset.area) - parseFloat(a.dataset.area);
        }
        return 0;
    });

    cards.forEach(c => container.appendChild(c));
}
</script>
@endpush
@endsection
