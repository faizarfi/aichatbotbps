@extends('layouts.public')

@section('title', 'Peta Tematik 17 Kecamatan Kabupaten Karanganyar')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 rounded-3xl p-6 sm:p-10 border border-blue-200/90 shadow-sm relative overflow-hidden">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white text-xs font-bold text-blue-800 border border-blue-200 shadow-xs mb-4">
                <span class="iconify text-sm text-blue-600" data-icon="lucide:map-pin"></span>
                <span>Data Geospasial & Statistik Sektoral</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                Eksplorasi Data 17 Kecamatan Kabupaten Karanganyar
            </h1>
            <p class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed">
                Jelajahi profil kependudukan, luas wilayah, kepadatan, dan potensi sektor unggulan di setiap kecamatan se-Kabupaten Karanganyar bersumber dari data resmi BPS.
            </p>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Total Penduduk</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:users"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($totalPopulation, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Jiwa (17 Kecamatan)</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Luas Wilayah</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:map"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($totalArea, 2, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Kilometer Persegi (km²)</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Kepadatan Rata-rata</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:activity"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($avgDensity, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Jiwa per km²</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Desa & Kelurahan</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <span class="iconify text-base" data-icon="lucide:home"></span>
                </span>
            </div>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ $totalVillages }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Desa dan Kelurahan</p>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Urutkan:</span>
            <button type="button" onclick="sortDistricts('name')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200" data-sort="name">
                Nama (A-Z)
            </button>
            <button type="button" onclick="sortDistricts('population')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-sort="population">
                Penduduk Terbanyak
            </button>
            <button type="button" onclick="sortDistricts('density')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-sort="density">
                Kepadatan Tertinggi
            </button>
            <button type="button" onclick="sortDistricts('area')" class="btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-sort="area">
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
                   class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
        </div>
    </div>

    {{-- Grid 17 Kecamatan Cards --}}
    <div id="districts-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($districts as $d)
        <div class="district-card bg-white rounded-2xl border border-slate-200/90 hover:border-blue-300 shadow-sm hover:shadow-md transition-all p-5 flex flex-col justify-between group"
             data-name="{{ strtolower($d->name) }}"
             data-population="{{ $d->population }}"
             data-density="{{ $d->density }}"
             data-area="{{ $d->area_sqkm }}">
            <div>
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-slate-400">KODE: {{ $d->code }}</span>
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-700 transition-colors">
                            Kecamatan {{ $d->name }}
                        </h3>
                        <p class="text-xs text-slate-500">Ibu kota: <strong class="text-slate-700">{{ $d->capital_name }}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shrink-0">
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
                        <span class="iconify text-emerald-600 text-sm" data-icon="lucide:sparkles"></span>
                        <span>Potensi & Sektor Unggulan:</span>
                    </p>
                    <p class="text-xs text-slate-600 bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl">
                        {{ $d->featured_sector }}
                    </p>
                </div>

                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ $d->description }}
                </p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('chat.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <span class="iconify text-sm" data-icon="lucide:message-square"></span>
                    <span>Tanya Data Kecamatan Ini</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
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
    
    // Update button states
    document.querySelectorAll('.btn-sort').forEach(btn => {
        if (btn.dataset.sort === by) {
            btn.className = 'btn-sort px-3.5 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200';
        } else {
            btn.className = 'btn-sort px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200';
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
