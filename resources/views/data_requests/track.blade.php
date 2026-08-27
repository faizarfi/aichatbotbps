@extends('layouts.public')

@section('title', 'Lacak Permohonan Data Statistik & ROMANTIK')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold text-emerald-800 border border-emerald-200">
            <span class="iconify text-sm text-emerald-600" data-icon="lucide:search"></span>
            <span>Pelacak Permohonan Data</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Lacak Status Permohonan Data
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto">
            Pantau progres verifikasi, penyiapan data, dan unduh dataset hasil olahan resmi dari BPS Karanganyar.
        </p>
    </div>

    {{-- Search Form --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="GET" action="{{ route('layanan-data.track') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="iconify text-base" data-icon="lucide:database"></span>
                </div>
                <input type="text" 
                       name="ticket" 
                       value="{{ request('ticket') }}" 
                       required 
                       placeholder="Contoh: REQ-DATA-202608-001" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 uppercase font-mono font-bold outline-none">
            </div>
            <button type="submit" class="py-3 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-sm flex items-center justify-center gap-2 cursor-pointer transition-all">
                <span class="iconify text-base" data-icon="lucide:search"></span>
                <span>Lacak Progres</span>
            </button>
        </form>
    </div>

    {{-- Search Result --}}
    @if(request('ticket'))
        @if($dataRequest)
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs font-mono font-bold text-emerald-600 block">{{ $dataRequest->ticket_number }}</span>
                    <h3 class="text-lg font-black text-slate-900">{{ $dataRequest->research_title }}</h3>
                    <p class="text-xs text-slate-500">{{ $dataRequest->applicant_name }} • {{ $dataRequest->institution_name }}</p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold self-start sm:self-auto {{ $dataRequest->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : ($dataRequest->status === 'reviewing' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                    Status: {{ $dataRequest->status_label }}
                </span>
            </div>

            {{-- Progress Tracker Steps --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    <div class="space-y-1">
                        <div class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold mx-auto flex items-center justify-center text-xs">✓</div>
                        <p class="font-bold text-slate-800 text-[11px]">Diajukan</p>
                    </div>
                    <div class="space-y-1">
                        <div class="w-7 h-7 rounded-full {{ in_array($dataRequest->status, ['reviewing', 'approved', 'ready']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold mx-auto flex items-center justify-center text-xs">2</div>
                        <p class="font-bold {{ in_array($dataRequest->status, ['reviewing', 'approved', 'ready']) ? 'text-slate-800' : 'text-slate-400' }} text-[11px]">Ditinjau</p>
                    </div>
                    <div class="space-y-1">
                        <div class="w-7 h-7 rounded-full {{ in_array($dataRequest->status, ['approved', 'ready']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold mx-auto flex items-center justify-center text-xs">3</div>
                        <p class="font-bold {{ in_array($dataRequest->status, ['approved', 'ready']) ? 'text-slate-800' : 'text-slate-400' }} text-[11px]">Disiapkan</p>
                    </div>
                    <div class="space-y-1">
                        <div class="w-7 h-7 rounded-full {{ $dataRequest->status === 'ready' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} font-bold mx-auto flex items-center justify-center text-xs">4</div>
                        <p class="font-bold {{ $dataRequest->status === 'ready' ? 'text-emerald-700' : 'text-slate-400' }} text-[11px]">Siap Diunduh</p>
                    </div>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 text-xs space-y-1">
                <span class="text-slate-400 font-semibold block mb-0.5">Rincian Variabel Data yang Diminta:</span>
                <p class="text-slate-700 leading-relaxed">{{ $dataRequest->data_description }}</p>
            </div>

            @if($dataRequest->officer_notes)
            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-xs">
                <span class="text-blue-800 font-bold block mb-0.5">Catatan Petugas BPS:</span>
                <p class="text-blue-900">{{ $dataRequest->officer_notes }}</p>
            </div>
            @endif

            {{-- Download Result File Button if Ready --}}
            @if($dataRequest->status === 'ready' && $dataRequest->result_file_path)
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Berkas Data Siap Diunduh:</span>
                    <p class="text-xs text-slate-700 font-medium">{{ $dataRequest->result_filename }}</p>
                </div>
                <a href="{{ route('layanan-data.download', $dataRequest) }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-sm flex items-center gap-2 transition-all shrink-0">
                    <span class="iconify text-base" data-icon="lucide:download"></span>
                    <span>Unduh Dataset Resmi</span>
                </a>
            </div>
            @endif
        </div>
        @else
        <div class="p-8 rounded-3xl bg-white border border-slate-200 text-center space-y-3">
            <span class="iconify text-4xl text-rose-500 mx-auto" data-icon="lucide:alert-circle"></span>
            <h3 class="text-base font-bold text-slate-800">Nomor Registrasi Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Nomor registrasi <strong>{{ request('ticket') }}</strong> tidak terdaftar. Mohon periksa kembali nomor permohonan data Anda.</p>
        </div>
        @endif
    @endif
</div>
@endsection
