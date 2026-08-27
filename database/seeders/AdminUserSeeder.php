<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Buat akun admin awal.
     * Password diambil dari environment agar tidak hardcoded di source code.
     */
    public function run(): void
    {
        $email = trim(env('ADMIN_EMAIL', '')) ?: 'admin@bps-karanganyar.local';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => env('ADMIN_PASSWORD', 'password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
