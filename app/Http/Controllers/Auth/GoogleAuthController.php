<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Get configured Socialite driver with SSL verification handling for local dev.
     */
    protected function getSocialiteDriver()
    {
        $guzzleClient = new GuzzleClient([
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
            'timeout' => 15,
        ]);

        return Socialite::driver('google')
            ->setHttpClient($guzzleClient)
            ->stateless();
    }

    /**
     * Redirect to Google OAuth provider.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi di .env (Client ID / Secret masih kosong).');
        }

        return $this->getSocialiteDriver()->redirect();
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = $this->getSocialiteDriver()->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Akun Google: ' . $e->getMessage());
        }

        // Check if user already exists with this google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update existing user with google_id and avatar if missing
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'last_login_at' => now(),
            ]);
        } else {
            // Create new general user
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna BPS',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]);
        }

        Auth::login($user, true);

        // Hapus session URL intended agar tidak ada bekas redirect admin
        session()->forget('url.intended');

        // Jika petugas/admin masuk via Google, arahkan ke dashboard admin
        if ($user->isStaff()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang kembali di Panel Petugas BPS, ' . $user->name . '!');
        }

        // Masyarakat umum / guest selalu diarahkan ke Beranda Publik
        return redirect()->route('home')
            ->with('success', 'Selamat datang, ' . $user->name . '! Anda berhasil masuk menggunakan Akun Google.');
    }
}
