@extends('layouts.admin')

@section('title', 'Telaah Permohonan Data ' . $dataRequest->ticket_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.data-requests.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <span class="iconify text-base" data-icon="lucide:arrow-left"></span>
            <span>Kembali ke Daftar Permohonan Data</span>
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $dataRequest->status === 'ready' ? 'bg-emerald-100 text-emerald-800' : ($dataRequest->status === 'reviewing' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
            Status: {{ $dataRequest->status_label }}
        </span>
    </div>

    {{-- Info Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-bold text-blue-600 block">NO. REGISTRASI: {{ $dataRequest->ticket_number }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">{{ $dataRequest->research_title }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pemohon: <strong class="text-slate-800">{{ $dataRequest->applicant_name }}</strong> ({{ $dataRequest->institution_name }})</p>
            </div>
            <div class="text-right text-xs text-slate-400">
                <span>Tanggal Diajukan:</span>
                <p class="font-bold text-slate-700">{{ $dataRequest->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-slate-400 font-semibold block mb-1">Kategori Pemohon:</span>
                <p class="text-sm font-black text-slate-900 uppercase">{{ $dataRequest->applicant_type }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-slate-400 font-semibold block mb-1">Kontak Pemohon:</span>
                <p class="font-bold text-slate-900">{{ $dataRequest->applicant_phone }}</p>
                <p class="text-slate-500">{{ $dataRequest->applicant_email }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-slate-400 font-semibold block mb-1">Tujuan Penggunaan:</span>
                <p class="font-semibold text-slate-800">{{ $dataRequest->purpose }}</p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-1">
            <span class="text-slate-400 font-bold uppercase tracking-wider block">Rincian Variabel Data yang Diminta:</span>
            <p class="text-slate-700 pt-1 leading-relaxed whitespace-pre-line">{{ $dataRequest->data_description }}</p>
        </div>

        {{-- Attachment Document --}}
        @if($dataRequest->attachment_path)
        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                    <span class="iconify text-lg" data-icon="lucide:file-text"></span>
                </span>
                <div>
                    <p class="text-xs font-bold text-slate-900">{{ $dataRequest->attachment_filename }}</p>
                    <p class="text-[11px] text-blue-700">Surat Pengantar / Proposal Penelitian Pemohon</p>
                </div>
            </div>
            <a href="{{ route('admin.data-requests.download', $dataRequest) }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-blue-700 font-bold text-xs border border-blue-300 shadow-xs flex items-center gap-1.5">
                <span class="iconify text-base" data-icon="lucide:download"></span>
                <span>Unduh Berkas</span>
            </a>
        </div>
        @endif

        {{-- Form Tindak Lanjut & Upload Hasil Data --}}
        <div class="pt-6 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="iconify text-base text-emerald-600" data-icon="lucide:check-square"></span>
                <span>Tindak Lanjut & Unggah Dataset Hasil Olahan BPS</span>
            </h3>
            <form method="POST" action="{{ route('admin.data-requests.status', $dataRequest) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ubah Status *</label>
                        <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="submitted" {{ $dataRequest->status === 'submitted' ? 'selected' : '' }}>Pengajuan Diterima</option>
                            <option value="reviewing" {{ $dataRequest->status === 'reviewing' ? 'selected' : '' }}>Sedang Ditelaah / Disiapkan Petugas</option>
                            <option value="ready" {{ $dataRequest->status === 'ready' ? 'selected' : '' }}>Selesai (Dataset Siap Diunduh Pemohon)</option>
                            <option value="rejected" {{ $dataRequest->status === 'rejected' ? 'selected' : '' }}>Ditolak (Syarat Tidak Lengkap / Data Konfidensial)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Dataset Hasil Olahan BPS (Excel/PDF/ZIP)</label>
                        <input type="file" name="result_file" accept=".xlsx,.xls,.csv,.pdf,.zip,.rar" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Jawaban Resmi Petugas BPS untuk Pemohon</label>
                    <textarea name="officer_notes" rows="3" placeholder="Contoh: Permohonan data telah disetujui. Terlampir dataset PDRB ADHK Karanganyar periode 2019-2024..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">{{ old('officer_notes', $dataRequest->officer_notes) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="py-2.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-sm cursor-pointer transition-all flex items-center gap-2">
                        <span class="iconify text-base" data-icon="lucide:save"></span>
                        <span>Simpan & Kirimkan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
