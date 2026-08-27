@extends('layouts.public')

@section('title', 'Kebijakan Privasi')
@section('meta_description', 'Kebijakan privasi dan penggunaan data pada layanan chatbot BPS Karanganyar.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Kebijakan Privasi</h1>

    <div class="prose prose-sm prose-gray max-w-none space-y-6">
        <section>
            <h2 class="text-lg font-semibold text-gray-800">1. Data yang Dikumpulkan</h2>
            <p class="text-sm text-gray-600 leading-relaxed">Layanan ini mengumpulkan data berikut:</p>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1 ml-4">
                <li>Isi percakapan dengan chatbot.</li>
                <li>Nama dan kontak pelapor (untuk aduan).</li>
                <li>Lampiran dokumen (untuk aduan).</li>
                <li>Data teknis sesi (session ID, alamat IP).</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-800">2. Penggunaan Data</h2>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1 ml-4">
                <li>Percakapan digunakan untuk menjawab pertanyaan dan meningkatkan layanan.</li>
                <li>Data aduan digunakan untuk memproses keluhan dan menghubungi pelapor.</li>
                <li>Data kontak pelapor dienkripsi dan hanya dapat diakses oleh petugas berwenang.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-800">3. Layanan AI Eksternal</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                Chatbot menggunakan layanan AI pihak ketiga (OpenRouter) untuk menyusun jawaban.
                Data yang dikirim ke layanan AI hanya berupa pertanyaan umum dan konteks artikel dari basis pengetahuan.
                Data pribadi seperti NIK, nomor telepon, email, dan isi aduan <strong>tidak dikirim</strong> ke layanan AI.
            </p>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-800">4. Penyimpanan dan Retensi</h2>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1 ml-4">
                <li>Riwayat percakapan disimpan untuk peningkatan layanan dan audit.</li>
                <li>Data aduan disimpan sesuai masa retensi yang berlaku.</li>
                <li>Lampiran disimpan di server aplikasi dengan akses terbatas.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-800">5. Keamanan</h2>
            <ul class="text-sm text-gray-600 list-disc list-inside space-y-1 ml-4">
                <li>Data kontak sensitif dienkripsi menggunakan enkripsi aplikasi.</li>
                <li>Akses data dibatasi berdasarkan peran pengguna.</li>
                <li>Komunikasi menggunakan HTTPS pada lingkungan produksi.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-gray-800">6. Kontak</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                Untuk pertanyaan terkait privasi data, hubungi BPS Kabupaten Karanganyar melalui email
                <a href="mailto:bps3313@bps.go.id" class="text-blue-600 hover:underline">bps3313@bps.go.id</a>
                atau telepon <strong>(0271) 495035</strong>.
            </p>
        </section>
    </div>
</div>
@endsection
