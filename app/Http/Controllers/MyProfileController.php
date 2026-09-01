<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MyProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna masyarakat.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Hitung riwayat layanan yang pernah diajukan
        $stats = [
            'complaints' => $user->complaints()->count(),
        ];

        return view('my-profile.show', compact('user', 'stats'));
    }

    /**
     * Update profil pengguna (nama, email, nomor HP).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:25'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
        ]);

        // Jika email berubah, reset verifikasi
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->update($validated);

        return redirect()->route('my-profile.show')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Hapus akun pengguna secara permanen.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // User Google OAuth tidak punya password, skip validasi password
        if ($user->password) {
            $request->validate([
                'password' => ['required', 'current_password'],
            ], [
                'password.required' => 'Masukkan kata sandi Anda untuk konfirmasi.',
                'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
            ]);
        } else {
            // Untuk user Google OAuth, cukup konfirmasi via checkbox
            $request->validate([
                'confirm_delete' => ['required', 'accepted'],
            ], [
                'confirm_delete.required' => 'Anda harus mencentang kotak konfirmasi.',
                'confirm_delete.accepted' => 'Anda harus mencentang kotak konfirmasi.',
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan BPS Karanganyar.');
    }
}
