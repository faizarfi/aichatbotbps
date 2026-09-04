@extends('layouts.public')

@section('title', 'Saluran Resmi Pengaduan Pelayanan BPS Kabupaten Karanganyar')
@section('meta_description', 'Formulir resmi penyampaian pengaduan, kritik, dan aspirasi pelayanan publik Badan Pusat Statistik Kabupaten Karanganyar.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">

    {{-- Page Header Banner (BPS Corporate Navy) --}}
    <div class="bg-gradient-to-br from-[#002b6a] via-[#003c80] to-[#043277] text-white rounded-3xl p-6 sm:p-8 border-b-4 border-[#f7941d] shadow-md relative overflow-hidden">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-slate-100 border border-white/20 mb-3">
                <span class="iconify text-sm text-[#f7941d]" data-icon="lucide:shield-check"></span>
                <span>Zona Integritas • Bebas Korupsi & Melayani (WBK/WBBM)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Formulir Pengaduan Pelayanan Publik</h1>
            <p class="mt-2 text-xs sm:text-sm text-blue-100 leading-relaxed max-w-xl">
                Sampaikan keluhan, kritik, atau aspirasi Anda terkait layanan BPS Kabupaten Karanganyar. Setiap laporan diproses secara transparan dengan <strong>Nomor Tiket Resmi</strong>.
            </p>
        </div>
    </div>

    {{-- Success Notification Banner --}}
    @if(session('success'))
    <div class="p-6 rounded-3xl bg-emerald-50 border-2 border-[#00a651] shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-[#00a651] text-white flex items-center justify-center shrink-0 shadow-sm">
                <span class="iconify text-2xl" data-icon="lucide:check-circle-2"></span>
            </div>
            <div class="space-y-1">
                <p class="text-base font-black text-slate-900">Pengaduan Anda Berhasil Diterima!</p>
                <p class="text-xs sm:text-sm text-slate-700">
                    Nomor Tiket Resmi: <span class="font-mono font-black text-sm bg-white px-2.5 py-1 rounded-xl border border-emerald-300 text-[#00a651] ml-1">{{ session('ticket_number') }}</span>
                </p>
                <p class="text-xs text-slate-600 pt-1">Simpan nomor tiket ini untuk memantau proses tindak lanjut dari petugas BPS Karanganyar.</p>
                <div class="pt-3">
                    <a href="{{ route('status-aduan', ['ticket' => session('ticket_number')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#005b9f] hover:bg-[#04325e] text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <span class="iconify" data-icon="lucide:search"></span>
                        <span>Pantau Status Aduan Ini</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
        <form method="POST" action="{{ route('aduan.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="reporter_name" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Nama Lengkap Pelapor <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <span class="iconify text-lg" data-icon="lucide:user"></span>
                    </span>
                    <input type="text" id="reporter_name" name="reporter_name" value="{{ old('reporter_name', auth()->user()->name ?? '') }}" required
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-[#005b9f] focus:border-[#005b9f] outline-none transition-all"
                           placeholder="Contoh: Budi Santoso">
                </div>
                @error('reporter_name') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Kontak Pelapor --}}
            <div>
                <label for="reporter_contact" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Nomor Telepon / WhatsApp / Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <span class="iconify text-lg" data-icon="lucide:phone-call"></span>
                    </span>
                    <input type="text" id="reporter_contact" name="reporter_contact" value="{{ old('reporter_contact', auth()->user()->phone_number ?? auth()->user()->email ?? '') }}" required
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-[#005b9f] focus:border-[#005b9f] outline-none transition-all"
                           placeholder="Contoh: 08123456789 atau email@domain.com">
                </div>
                <p class="mt-1.5 text-[11px] text-slate-500 flex items-center gap-1">
                    <span class="iconify text-[#005b9f] text-sm shrink-0" data-icon="lucide:lock"></span>
                    <span>Identitas dan kontak dilindungi undang-undang serta hanya digunakan untuk koordinasi tindak lanjut aduan.</span>
                </p>
                @error('reporter_contact') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori Aduan --}}
            <div>
                <label for="category" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Kategori Pelayanan BPS <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="category" name="category" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-[#005b9f] focus:border-[#005b9f] outline-none transition-all bg-white font-medium cursor-pointer">
                        <option value="">-- Pilih Kategori Pengaduan --</option>
                        <option value="pelayanan" {{ old('category') === 'pelayanan' ? 'selected' : '' }}>Pelayanan & Fasilitas Pelayanan Statistik Terpadu (PST)</option>
                        <option value="data" {{ old('category') === 'data' ? 'selected' : '' }}>Kualitas Data, Rekomendasi & Publikasi Statistik</option>
                        <option value="website" {{ old('category') === 'website' ? 'selected' : '' }}>Website, Aplikasi & Portal Layanan Online</option>
                        <option value="sdm" {{ old('category') === 'sdm' ? 'selected' : '' }}>Petugas Pelayanan / Mitra Lapangan Sensus & Survei</option>
                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                @error('category') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Uraian Aduan --}}
            <div>
                <label for="description" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Uraian Detail Pengaduan / Aspirasi <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                          class="w-full px-4 py-3 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-[#005b9f] focus:border-[#005b9f] outline-none transition-all resize-none leading-relaxed"
                          placeholder="Jelaskan secara runtut kronologi, waktu kejadian, kendala, atau saran perbaikan yang ingin Anda sampaikan...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Lampiran Berkas --}}
            <div>
                <label for="attachments" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Lampiran Bukti Pendukung (Opsional)
                </label>
                <div class="p-4 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors">
                    <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#005b9f] file:text-white hover:file:bg-[#04325e] transition-colors cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-400">Format yang didukung: PDF, JPG, PNG. Ukuran maksimal 2 MB per berkas.</p>
                </div>
                @error('attachments.*') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Persetujuan & Kebijakan Privasi --}}
            <div class="bg-blue-50/70 rounded-2xl p-4 sm:p-5 border border-blue-200">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" required
                           class="h-5 w-5 rounded-lg border-slate-300 text-[#005b9f] focus:ring-[#005b9f] mt-0.5 shrink-0 cursor-pointer">
                    <span class="text-xs text-slate-700 leading-relaxed font-medium">
                        Saya menyatakan data yang disampaikan adalah benar dan menyetujui data kontak saya digunakan untuk keperluan tindak lanjut aduan sesuai
                        <a href="{{ route('kebijakan-privasi') }}" class="text-[#005b9f] font-bold hover:underline">Kebijakan Privasi BPS</a>.
                    </span>
                </label>
                @error('consent') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Submit Button (BPS Navy & Orange) --}}
            <button type="submit"
                    class="w-full py-3.5 px-6 bg-[#04325e] hover:bg-[#004b87] active:scale-98 text-white text-sm font-black rounded-xl transition-all shadow-md flex items-center justify-center gap-2.5 cursor-pointer border-b-2 border-[#f7941d]">
                <span class="iconify text-xl text-[#f7941d]" data-icon="lucide:send"></span>
                <span>Kirim Pengaduan Resmi ke BPS</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success') && session('ticket_number'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Aduan Berhasil Terkirim!',
    html: `
        <p class="text-xs sm:text-sm text-slate-600 mb-3">Nomor Tiket Pengaduan Resmi Anda adalah:</p>
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-[#04325e] font-mono font-black text-xl tracking-wider mb-3 select-all">
            {{ session('ticket_number') }}
        </div>
        <p class="text-xs text-slate-500">Simpan nomor tiket ini untuk memantau status perkembangan tindak lanjut dari petugas BPS Karanganyar.</p>
    `,
    showCancelButton: true,
    confirmButtonColor: '#005b9f',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Lacak Status Sekarang',
    cancelButtonText: 'Tutup',
    reverseButtons: true
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "{{ route('status-aduan', ['ticket' => session('ticket_number')]) }}";
    }
});
</script>
@endif
@endpush
