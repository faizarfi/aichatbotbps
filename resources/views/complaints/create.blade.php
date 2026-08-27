@extends('layouts.public')

@section('title', 'Sampaikan Pengaduan Layanan')
@section('meta_description', 'Formulir resmi penyampaian pengaduan, kritik, dan saran pelayanan Badan Pusat Statistik Kabupaten Karanganyar.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-14">

    {{-- Page Header --}}
    <div class="mb-8 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black mb-3 border border-blue-200/80 shadow-sm">
            <span class="iconify text-base" data-icon="lucide:shield-alert"></span>
            <span>Saluran Pengaduan Resmi Masyarakat</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Formulir Pengaduan Layanan</h1>
        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xl">
            Sampaikan keluhan, aspirasi, atau masukan Anda terkait pelayanan BPS Kabupaten Karanganyar. Setiap laporan akan mendapatkan <strong>Nomor Tiket Resmi</strong> untuk pelacakan.
        </p>
    </div>

    {{-- Success Notification Banner --}}
    @if(session('success'))
    <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 shadow-lg shadow-emerald-500/5">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md">
                <span class="iconify text-2xl" data-icon="lucide:check-circle-2"></span>
            </div>
            <div class="space-y-1">
                <p class="text-base font-black text-emerald-900">Aduan Anda Berhasil Dikirim!</p>
                <p class="text-xs sm:text-sm text-emerald-800">
                    Nomor Tiket Pelacakan: <span class="font-mono font-black text-sm bg-white px-2.5 py-1 rounded-xl border border-emerald-300 text-emerald-800 ml-1">{{ session('ticket_number') }}</span>
                </p>
                <p class="text-xs text-emerald-700 pt-1">Simpan nomor tiket ini untuk memantau perkembangan tindak lanjut dari petugas BPS.</p>
                <div class="pt-3">
                    <a href="{{ route('status-aduan', ['ticket' => session('ticket_number')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                        <span class="iconify" data-icon="lucide:search"></span>
                        <span>Pantau Status Aduan Ini</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 p-6 sm:p-10">
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
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                           placeholder="Contoh: Budi Santoso">
                </div>
                @error('reporter_name') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Kontak Pelapor --}}
            <div>
                <label for="reporter_contact" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Nomor WhatsApp / Telepon / Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <span class="iconify text-lg" data-icon="lucide:phone-call"></span>
                    </span>
                    <input type="text" id="reporter_contact" name="reporter_contact" value="{{ old('reporter_contact', auth()->user()->phone_number ?? auth()->user()->email ?? '') }}" required
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                           placeholder="Contoh: 08123456789 atau email@domain.com">
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400 flex items-center gap-1">
                    <span class="iconify text-blue-600 text-sm shrink-0" data-icon="lucide:lock"></span>
                    <span>Data kontak dienkripsi dan hanya dapat diakses oleh petugas resmi penanganan aduan.</span>
                </p>
                @error('reporter_contact') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori Aduan --}}
            <div>
                <label for="category" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Kategori Pelayanan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="category" name="category" required
                            class="w-full px-4 py-3.5 rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white font-medium cursor-pointer">
                        <option value="">-- Pilih Kategori Pengaduan --</option>
                        <option value="pelayanan" {{ old('category') === 'pelayanan' ? 'selected' : '' }}>Pelayanan & Fasilitas PST</option>
                        <option value="data" {{ old('category') === 'data' ? 'selected' : '' }}>Kualitas Data & Publikasi</option>
                        <option value="website" {{ old('category') === 'website' ? 'selected' : '' }}>Website & Aplikasi Layanan</option>
                        <option value="sdm" {{ old('category') === 'sdm' ? 'selected' : '' }}>Petugas & Tenaga Lapangan</option>
                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                @error('category') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Uraian Aduan --}}
            <div>
                <label for="description" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Uraian Detail Pengaduan <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                          class="w-full px-4 py-3.5 rounded-2xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none leading-relaxed"
                          placeholder="Jelaskan kronologi, waktu, kendala, atau saran yang ingin Anda sampaikan secara lengkap...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Lampiran Berkas --}}
            <div>
                <label for="attachments" class="block text-xs sm:text-sm font-extrabold text-slate-800 mb-2">
                    Lampiran Bukti Pendukung (Opsional)
                </label>
                <div class="p-4 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                    <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-colors cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-400">Format yang didukung: PDF, JPG, PNG. Maksimal 2 MB per file.</p>
                </div>
                @error('attachments.*') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Persetujuan & Kebijakan Privasi --}}
            <div class="bg-blue-50/60 rounded-2xl p-4 sm:p-5 border border-blue-100">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" required
                           class="h-5 w-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500 mt-0.5 shrink-0 cursor-pointer">
                    <span class="text-xs text-slate-600 leading-relaxed font-medium">
                        Saya menyatakan data yang disampaikan adalah benar dan menyetujui data kontak saya digunakan untuk keperluan tindak lanjut aduan sesuai
                        <a href="{{ route('kebijakan-privasi') }}" class="text-blue-700 font-bold hover:underline">Kebijakan Privasi BPS</a>.
                    </span>
                </label>
                @error('consent') <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-98 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-blue-600/25 flex items-center justify-center gap-2.5">
                <span class="iconify text-xl" data-icon="lucide:send"></span>
                <span>Kirim Pengaduan Resmi</span>
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
        <p class="text-xs sm:text-sm text-slate-600 mb-3">Nomor Tiket Pengaduan Anda adalah:</p>
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 font-mono font-black text-xl tracking-wider mb-3 select-all">
            {{ session('ticket_number') }}
        </div>
        <p class="text-xs text-slate-400">Simpan nomor tiket ini untuk memantau status perkembangan tindak lanjut dari petugas.</p>
    `,
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
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
