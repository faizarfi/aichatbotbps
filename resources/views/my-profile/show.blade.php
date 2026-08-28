@extends('layouts.public')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-6 py-5 sm:py-12 space-y-5 sm:space-y-6">

    {{-- Header Profil --}}
    <div class="text-center space-y-2 sm:space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-xs font-bold text-blue-800 border border-blue-200">
            <span class="iconify text-sm text-blue-600" data-icon="lucide:user-circle"></span>
            <span>Akun Masyarakat</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto px-2">
            Kelola informasi akun dan lihat riwayat layanan Anda di BPS Kabupaten Karanganyar.
        </p>
    </div>

    {{-- Success / Error Notification --}}
    @if(session('success'))
    <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
        <span class="iconify text-xl text-emerald-600 shrink-0" data-icon="lucide:check-circle-2"></span>
        <p class="text-xs sm:text-sm font-bold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200">
        <div class="flex items-center gap-2 mb-2">
            <span class="iconify text-lg text-rose-600" data-icon="lucide:alert-circle"></span>
            <p class="text-xs font-bold text-rose-800">Terjadi kesalahan:</p>
        </div>
        <ul class="list-disc pl-5 space-y-0.5">
            @foreach($errors->all() as $error)
                <li class="text-xs text-rose-700">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Card Profil Info --}}
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 pb-6 border-b border-slate-100">
            {{-- Avatar --}}
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="Avatar {{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-blue-200 shadow-md" referrerpolicy="no-referrer">
            @else
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center text-3xl font-black shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <div class="text-center sm:text-left flex-1">
                <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $user->name }}</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">{{ $user->email }}</p>
                @if($user->phone_number)
                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1 justify-center sm:justify-start">
                        <span class="iconify text-sm" data-icon="lucide:phone"></span>
                        {{ $user->phone_number }}
                    </p>
                @endif
                <div class="flex flex-wrap items-center gap-2 mt-2 justify-center sm:justify-start">
                    @if($user->google_id)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-blue-50 text-[11px] font-bold text-blue-700 border border-blue-200">
                            <span class="iconify text-sm" data-icon="logos:google-icon"></span>
                            Google
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 border border-slate-200">
                        <span class="iconify text-sm" data-icon="lucide:calendar"></span>
                        Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ringkasan Aktivitas Layanan --}}
        <div class="pt-6">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3">Riwayat Penggunaan Layanan</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 rounded-2xl bg-blue-50/70 border border-blue-100 text-center">
                    <p class="text-2xl font-black text-blue-700">{{ $stats['reservations'] }}</p>
                    <p class="text-[11px] font-bold text-blue-600/80 mt-0.5">Reservasi</p>
                </div>
                <div class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-center">
                    <p class="text-2xl font-black text-emerald-700">{{ $stats['data_requests'] }}</p>
                    <p class="text-[11px] font-bold text-emerald-600/80 mt-0.5">Data Mikro</p>
                </div>
                <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-100 text-center">
                    <p class="text-2xl font-black text-amber-700">{{ $stats['surveys'] }}</p>
                    <p class="text-[11px] font-bold text-amber-600/80 mt-0.5">Survei SKM</p>
                </div>
                <div class="p-3 rounded-2xl bg-rose-50/70 border border-rose-100 text-center">
                    <p class="text-2xl font-black text-rose-700">{{ $stats['complaints'] }}</p>
                    <p class="text-[11px] font-bold text-rose-600/80 mt-0.5">Aduan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Edit Profil --}}
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-8">
        <h3 class="text-sm font-extrabold text-slate-900 mb-1 flex items-center gap-2">
            <span class="iconify text-lg text-blue-600" data-icon="lucide:pen-line"></span>
            Edit Informasi Profil
        </h3>
        <p class="text-xs text-slate-500 mb-5">Perbarui nama, email, atau nomor telepon Anda.</p>

        <form method="POST" action="{{ route('my-profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                       placeholder="081234567890"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('phone_number') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-blue-500/20 flex items-center gap-2 transition-all cursor-pointer">
                    <span class="iconify text-base" data-icon="lucide:save"></span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Zona Bahaya: Hapus Akun --}}
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-rose-200/90 shadow-sm p-4 sm:p-8">
        <h3 class="text-sm font-extrabold text-rose-800 mb-1 flex items-center gap-2">
            <span class="iconify text-lg text-rose-600" data-icon="lucide:trash-2"></span>
            Hapus Akun Permanen
        </h3>
        <p class="text-xs text-slate-500 mb-4">
            Setelah akun dihapus, semua data dan riwayat layanan Anda akan hilang secara permanen dan tidak dapat dipulihkan. Pastikan Anda sudah menyimpan informasi penting sebelum melanjutkan.
        </p>

        <button type="button" id="btn-show-delete" class="px-5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 transition-all flex items-center gap-2 cursor-pointer">
            <span class="iconify text-base" data-icon="lucide:alert-triangle"></span>
            <span>Saya Ingin Menghapus Akun Saya</span>
        </button>

        {{-- Konfirmasi Hapus (tersembunyi, muncul saat tombol diklik) --}}
        <div id="delete-confirmation" class="hidden mt-5 p-5 rounded-2xl bg-rose-50/60 border border-rose-200 space-y-4">
            <div class="p-3 rounded-xl bg-rose-100/80 border border-rose-200 flex items-start gap-3">
                <span class="iconify text-xl text-rose-600 shrink-0 mt-0.5" data-icon="lucide:shield-alert"></span>
                <div>
                    <p class="text-xs font-bold text-rose-900">Peringatan! Tindakan ini tidak dapat dibatalkan.</p>
                    <p class="text-[11px] text-rose-700 mt-0.5">Seluruh data akun, riwayat reservasi, permohonan data, aduan, dan survei yang terkait dengan akun Anda akan dihapus secara permanen.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('my-profile.destroy') }}" class="space-y-3">
                @csrf
                @method('DELETE')

                @if($user->password)
                    {{-- User dengan password: konfirmasi via password --}}
                    <div>
                        <label class="block text-xs font-bold text-rose-800 uppercase tracking-wider mb-1.5">Masukkan Kata Sandi untuk Konfirmasi *</label>
                        <input type="password" name="password" required
                               placeholder="Kata sandi akun Anda"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-rose-300 text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-rose-500 outline-none">
                        @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- User Google OAuth tanpa password: konfirmasi via checkbox --}}
                    <label class="flex items-start gap-3 cursor-pointer p-3 rounded-xl bg-white border border-rose-200">
                        <input type="checkbox" name="confirm_delete" value="1" required
                               class="h-5 w-5 rounded-lg border-rose-300 text-rose-600 focus:ring-rose-500 mt-0.5 shrink-0 cursor-pointer">
                        <span class="text-xs text-rose-800 font-medium leading-relaxed">
                            Saya memahami bahwa tindakan ini bersifat permanen dan saya ingin menghapus akun saya (<strong>{{ $user->email }}</strong>) beserta seluruh data yang terkait.
                        </span>
                    </label>
                    @error('confirm_delete') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                @endif

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white text-xs font-extrabold shadow-lg shadow-rose-500/20 flex items-center gap-2 transition-all cursor-pointer">
                        <span class="iconify text-base" data-icon="lucide:trash-2"></span>
                        <span>Hapus Akun Saya Sekarang</span>
                    </button>
                    <button type="button" id="btn-cancel-delete" class="px-4 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-xs font-bold border border-slate-200 transition-all cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnShow = document.getElementById('btn-show-delete');
    const btnCancel = document.getElementById('btn-cancel-delete');
    const deleteSection = document.getElementById('delete-confirmation');

    if (btnShow && deleteSection) {
        btnShow.addEventListener('click', function() {
            deleteSection.classList.remove('hidden');
            btnShow.classList.add('hidden');
            deleteSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    if (btnCancel && deleteSection && btnShow) {
        btnCancel.addEventListener('click', function() {
            deleteSection.classList.add('hidden');
            btnShow.classList.remove('hidden');
        });
    }
});
</script>
@endpush
