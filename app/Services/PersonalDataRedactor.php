<?php

namespace App\Services;

class PersonalDataRedactor
{
    /**
     * Samarkan NIK, nomor telepon, dan email dari teks publik.
     */
    public static function redact(string $text): string
    {
        // 1. Samarkan NIK (16 digit angka)
        $text = preg_replace('/\b(\d{6})\d{6}(\d{4})\b/', '$1******$2', $text);

        // 2. Samarkan nomor telepon (10-13 digit diawali 08 atau +62)
        $text = preg_replace('/(\+?62|08)(\d{2,3})\d{4,6}(\d{2,3})/', '$1$2****$3', $text);

        // 3. Samarkan email
        $text = preg_replace_callback('/([a-zA-Z0-9_.+-]+)@([a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)/', function ($matches) {
            $user = $matches[1];
            $domain = $matches[2];
            $maskedUser = strlen($user) > 2 ? substr($user, 0, 2) . '***' : $user . '***';
            return $maskedUser . '@' . $domain;
        }, $text);

        return $text;
    }
}
