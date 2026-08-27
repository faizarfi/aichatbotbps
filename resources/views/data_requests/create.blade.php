@extends('layouts.public')

@section('title', 'Layanan Permintaan Data Mikro & Rekomendasi Statistik (ROMANTIK)')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">
    {{-- Header --}}
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold text-emerald-800 border border-emerald-200">
            <span class="iconify text-sm text-emerald-600" data-icon="lucide:database"></span>
            <span>Pelayanan Data & Rekomendasi Statistik</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Permohonan Data Mikro & Rekomendasi Statistik
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto">
            Fasilitas resmi pengajuan data sektoral, data hasil survei/sensus mikro, serta rekomendasi kegiatan statistik (ROMANTIK) bagi akademisi, peneliti, dinas pemerintah, dan swasta.
        </p>
    </div>

    {{-- Form Pengajuan Data --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('layanan-data.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Pemohon / Peneliti *</label>
                    <input type="text" name="applicant_name" value="{{ old('applicant_name', auth()->user()->name ?? '') }}" required placeholder="Nama Lengkap" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('applicant_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Pemohon *</label>
                    <select name="applicant_type" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="mahasiswa">Mahasiswa (Skripsi / Tesis / Riset Kuliah)</option>
                        <option value="pemerintah">Pemerintah Daerah / OPD / Dinas</option>
                        <option value="peneliti">Lembaga Riset / Peneliti Independen</option>
                        <option value="swasta">Badan Usaha / Perusahaan Swasta</option>
                        <option value="umum">Masyarakat Umum / LSM</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email Aktif *</label>
                    <input type="email" name="applicant_email" value="{{ old('applicant_email', auth()->user()->email ?? '') }}" required placeholder="email@instansi.ac.id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP *</label>
                    <input type="text" name="applicant_phone" value="{{ old('applicant_phone') }}" required placeholder="081234567890" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Universitas / Instansi Asal *</label>
                <input type="text" name="institution_name" value="{{ old('institution_name') }}" required placeholder="Contoh: Fakultas Ekonomi Bisnis Universitas Sebelas Maret (UNS)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Kegiatan / Topik Riset Penelitian *</label>
                <input type="text" name="research_title" value="{{ old('research_title') }}" required placeholder="Contoh: Analisis Pengaruh Tingkat Pendidikan terhadap Pertumbuhan Ekonomi di Karanganyar" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rincian Variabel Data yang Dibutuhkan *</label>
                <textarea name="data_description" rows="3" required placeholder="Sebutkan variabel data secara spesifik (contoh: Data PDRB ADHK Karanganyar 2019-2024 per kecamatan, data susenas mikro kemiskinan, dll.)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tujuan Penggunaan Data *</label>
                <input type="text" name="purpose" value="{{ old('purpose') }}" required placeholder="Contoh: Keperluan Penulisan Skripsi S1 / Kajian Kebijakan Daerah" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Surat Pengantar / Proposal Penelitian (Opsional)</label>
                <div class="p-4 rounded-2xl bg-slate-50 border border-dashed border-slate-300">
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400 mt-2">Format yang didukung: PDF, JPG, PNG (Maksimal 5 MB).</p>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="iconify text-lg" data-icon="lucide:send"></span>
                    <span>Kirim Permohonan Data Statistik</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
