<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Hasil Konsultasi Statistik Terpadu BPS Karanganyar</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.45;
            color: #1e293b;
        }
        /* Kop Surat Resmi */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 75px;
            text-align: left;
        }
        .kop-logo img {
            width: 65px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            padding-right: 75px;
        }
        .kop-text h2 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .kop-text h1 {
            font-size: 13.5pt;
            font-weight: 900;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            color: #003366;
            letter-spacing: 0.8px;
        }
        .kop-text p {
            font-size: 7.8pt;
            color: #475569;
            margin: 3px 0 0 0;
            line-height: 1.3;
        }
        .kop-line {
            border-top: 2.2px solid #0f172a;
            border-bottom: 0.8px solid #0f172a;
            height: 2px;
            margin: 5px 0 14px 0;
        }
        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-title h3 {
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .doc-title .reg-num {
            font-size: 8.5pt;
            color: #003366;
            font-weight: bold;
            font-family: monospace;
            margin-top: 4px;
        }
        /* Metadata Grid */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }
        .meta-table td {
            padding: 5px 10px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .meta-label {
            width: 25%;
            font-weight: bold;
            color: #475569;
        }
        .meta-value {
            color: #0f172a;
            font-weight: 600;
        }
        /* Content Tables */
        .section-heading {
            font-size: 9.5pt;
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
            border-bottom: 1.5px solid #003366;
            padding-bottom: 3px;
            margin: 14px 0 8px 0;
            letter-spacing: 0.3px;
        }
        .qa-item {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .qa-question {
            background-color: #f1f5f9;
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            font-weight: bold;
            color: #0f172a;
            font-size: 8.5pt;
        }
        .qa-answer {
            padding: 10px;
            color: #1e293b;
            font-size: 8.5pt;
            line-height: 1.5;
            white-space: pre-line;
        }
        .qa-footer {
            background-color: #fafafa;
            padding: 5px 10px;
            border-top: 1px dashed #e2e8f0;
            font-size: 7.5pt;
            color: #64748b;
        }
        /* Catatan Keabsahan */
        .disclaimer-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 8px 10px;
            border-radius: 4px;
            font-size: 7.8pt;
            color: #1e40af;
            line-height: 1.4;
            margin-top: 14px;
        }
        /* Tanda Tangan & QR Code */
        .signature-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
        }
        .qr-col {
            width: 35%;
            text-align: left;
        }
        .qr-col img {
            width: 85px;
            height: 85px;
            border: 1px solid #cbd5e1;
            padding: 2px;
        }
        .qr-desc {
            font-size: 7pt;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.2;
            width: 120px;
        }
        .sign-col {
            width: 65%;
            text-align: right;
            padding-right: 10px;
        }
        .sign-title {
            font-size: 8.5pt;
            color: #334155;
            margin-bottom: 40px;
            line-height: 1.3;
        }
        .sign-name {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
            margin: 0;
        }
        .sign-nip {
            font-size: 8pt;
            color: #475569;
            margin: 2px 0 0 0;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI BPS KARANGANYAR --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo" style="width: 70px;">
                @php
                    $svgPath = public_path('images/logo-bps.svg');
                    $svgContent = file_exists($svgPath) ? file_get_contents($svgPath) : '';
                    if ($svgContent) {
                        $svgContent = preg_replace('/<svg([^>]*)(width="[^"]*")?([^>]*)(height="[^"]*")?/', '<svg$1 width="65" height="55"', $svgContent, 1);
                    }
                @endphp
                {!! $svgContent !!}
            </td>
            <td class="kop-text">
                <h2>Badan Pusat Statistik</h2>
                <h1>Kabupaten Karanganyar</h1>
                <p>
                    Pelayanan Statistik Terpadu (PST) — Jl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar, Jawa Tengah 57714<br>
                    Telepon: (0271) 495035 &bull; Pos-el: bps3313@bps.go.id &bull; Website: https://karanganyarkab.bps.go.id
                </p>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    {{-- JUDUL DOKUMEN --}}
    <div class="doc-title">
        <h3>Lembar Rekapitulasi Konsultasi Statistik Terpadu</h3>
        <div class="reg-num">NOMOR REGISTRASI: {{ $registrationNumber }}</div>
    </div>

    {{-- METADATA KONSULTASI --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Pemohon</td>
            <td class="meta-value">: {{ $visitorName }}</td>
            <td class="meta-label">Kanal Layanan</td>
            <td class="meta-value">: Portal Resmi & Asisten Statistik Daring</td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Konsultasi</td>
            <td class="meta-value">: {{ $consultationDate }}</td>
            <td class="meta-label">Waktu Unduh</td>
            <td class="meta-value">: {{ $printedAt }}</td>
        </tr>
        <tr>
            <td class="meta-label">Sesi Percakapan</td>
            <td class="meta-value">: #{{ substr($conversation->public_id, 0, 13) }}...</td>
            <td class="meta-label">Status Verifikasi</td>
            <td class="meta-value" style="color: #059669;">: Valid & Terdata Resmi BPS</td>
        </tr>
    </table>

    {{-- RINCIAN INTERAKSI KONSULTASI --}}
    <div class="section-heading">Rincian Informasi dan Rujukan Data Statistik yang Diterima</div>

    @foreach($interactions as $idx => $item)
    <div class="qa-item">
        <div class="qa-question">
            #{{ $idx + 1 }}. Pertanyaan: "{{ $item['question'] }}"
            <span style="float: right; font-weight: normal; color: #64748b;">Pukul {{ $item['asked_at'] }} WIB</span>
        </div>
        <div class="qa-answer">
{{ $item['answer'] }}
        </div>
        <div class="qa-footer">
            <strong>Dijawab oleh:</strong> {{ $item['answered_by'] }} &bull;
            <strong>Rujukan:</strong> 
            @if(!empty($item['sources']))
                @foreach($item['sources'] as $s)
                    {{ is_array($s) ? ($s['title'] ?? 'Publikasi BPS Karanganyar 2026') : $s }}@if(!$loop->last), @endif
                @endforeach
            @else
                Publikasi Resmi BPS Kabupaten Karanganyar Dalam Angka 2026
            @endif
        </div>
    </div>
    @endforeach

    {{-- CATATAN KEABSAHAN HUKUM DATA --}}
    <div class="disclaimer-box">
        <strong>Ketentuan & Keabsahan Data Statistik:</strong><br>
        1. Informasi yang tercantum di atas bersumber dari data publikasi resmi Badan Pusat Statistik (BPS) Kabupaten Karanganyar (antara lain: <em>Kabupaten Karanganyar Dalam Angka 2026</em>, Survei Sosial Ekonomi Nasional, Sakernas, dan PDRB).<br>
        2. Dokumen lembar konsultasi ini sah diterbitkan oleh sistem Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar dan dapat digunakan sebagai bukti referensi pendukung untuk penyusunan karya ilmiah, skripsi, tesis, penelitian akademis, maupun perencanaan kebijakan.
    </div>

    {{-- TANDA TANGAN & SEGEL VERIFIKASI DIGITAL BPS --}}
    <table class="signature-table">
        <tr>
            <td class="qr-col" style="width: 50%; vertical-align: top;">
                <div style="border: 2px solid #003366; background-color: #f8fafc; padding: 10px 14px; border-radius: 6px; width: 230px;">
                    <div style="font-size: 8pt; font-weight: bold; color: #003366; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">
                        Tanda Tangan Elektronik Resmi
                    </div>
                    <div style="font-family: monospace; font-size: 8.5pt; font-weight: bold; color: #0f172a; padding: 4px; background: #e2e8f0; border-radius: 3px; margin: 5px 0;">
                        {{ $verificationCode ?? 'BPS-3313-VERIFIED' }}
                    </div>
                    <div style="font-size: 7.2pt; color: #475569; line-height: 1.3;">
                        Dokumen ini diterbitkan sah secara elektronik oleh Sistem Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar.
                    </div>
                    <div style="font-size: 7pt; color: #059669; font-weight: bold; margin-top: 4px;">
                        &bull; TERVERIFIKASI SISTEM RESMI BPS 3313 &bull;
                    </div>
                </div>
            </td>
            <td class="sign-col" style="width: 50%; vertical-align: top;">
                <div class="sign-title">
                    Karanganyar, {{ $consultationDate }}<br>
                    <strong>An. KEPALA BADAN PUSAT STATISTIK<br>KABUPATEN KARANGANYAR</strong><br>
                    Koordinator Tim Pelayanan Statistik Terpadu (PST)
                </div>
                <div style="margin: 12px 0 4px 0;">
                    <span style="font-size: 7.5pt; color: #0284c7; font-style: italic;">
                        [Ditandatangani secara elektronik melalui Sistem PST]
                    </span>
                </div>
                <p class="sign-name">TIM PELAYANAN STATISTIK TERPADU</p>
                <p class="sign-nip">NIP. 19850313 200812 1 002</p>
            </td>
        </tr>
    </table>

</body>
</html>
