<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi Pelayanan Statistik Terpadu BPS Karanganyar</title>
    <style>
        @page {
            margin: 1.8cm 1.5cm 1.8cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #1e293b;
        }
        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-instansi {
            text-align: center;
            line-height: 1.2;
        }
        .kop-instansi h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
        }
        .kop-instansi h1 {
            font-size: 15pt;
            font-weight: 900;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            color: #1d4ed8;
            letter-spacing: 0.5px;
        }
        .kop-instansi p {
            font-size: 8.5pt;
            color: #475569;
            margin: 4px 0 0 0;
        }
        .kop-line {
            border-top: 2.5px solid #0f172a;
            border-bottom: 0.8px solid #0f172a;
            height: 3px;
            margin: 6px 0 16px 0;
        }
        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .report-title h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
            text-decoration: underline;
        }
        .report-title p {
            font-size: 9pt;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        /* Summary Box */
        .summary-grid {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .summary-grid td {
            padding: 8px 10px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            text-align: center;
        }
        .summary-grid .label {
            font-size: 7.5pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 2px;
        }
        .summary-grid .value {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a8a;
        }
        /* Table Styling */
        .section-header {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 14px 0 6px 0;
            padding-bottom: 2px;
            border-bottom: 1.5px solid #cbd5e1;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 8.5pt;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .data-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        /* Badges */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-new { background-color: #fee2e2; color: #991b1b; }
        .badge-proc { background-color: #fef3c7; color: #92400e; }
        .badge-done { background-color: #d1fae5; color: #065f46; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .ttd-table td {
            vertical-align: top;
            font-size: 9.5pt;
        }
        .ttd-box {
            text-align: center;
            width: 250px;
            float: right;
        }
        .ttd-space {
            height: 60px;
        }
        .footer-note {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 30px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI BPS KARANGANYAR -->
    <table class="kop-table">
        <tr>
            <td style="width: 70px; text-align: left;">
                <img src="{{ public_path('images/logo-bps.png') }}" alt="Logo BPS" style="width: 60px; height: auto;" onerror="this.style.display='none'">
            </td>
            <td class="kop-instansi">
                <h2>BADAN PUSAT STATISTIK</h2>
                <h1>KABUPATEN KARANGANYAR</h1>
                <p>Jl. Lawu No. 202B, Badran Asri, Cangakan, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57712<br>
                Telepon: (0271) 495035 | Email: bps3313@bps.go.id | Website: karanganyarkab.bps.go.id</p>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        <h3>Laporan Rekapitulasi Pelayanan Statistik Terpadu (PST)</h3>
        <p>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong></p>
    </div>

    <!-- RINGKASAN STATISTIK EKSEKUTIF -->
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="label">Total Percakapan</div>
                <div class="value">{{ $conversationStats['total'] }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Tiket Pengaduan</div>
                <div class="value">{{ $complaintStats['total'] }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Aduan Selesai</div>
                <div class="value" style="color: #059669;">{{ $complaintStats['resolved'] }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Kepuasan Layanan</div>
                <div class="value" style="color: #2563eb;">{{ $satisfactionRate }}%</div>
            </td>
        </tr>
    </table>

    <!-- TABEL REKAPITULASI PENGADUAN LAYANAN -->
    <div class="section-header">I. Rekapitulasi Pengaduan Masyarakat Masuk</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 18%;">No. Tiket</th>
                <th style="width: 18%;">Nama Pelapor</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 30%;">Ringkasan Masalah</th>
                <th style="width: 14%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $idx => $comp)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td style="font-weight: bold; font-family: monospace;">{{ $comp->ticket_number }}</td>
                <td>{{ $comp->reporter_name }}</td>
                <td style="text-transform: capitalize;">{{ $comp->category }}</td>
                <td>{{ \Illuminate\Support\Str::limit($comp->description, 70) }}</td>
                <td style="text-align: center;">
                    @if($comp->status === 'resolved')
                        <span class="badge badge-done">Selesai</span>
                    @elseif($comp->status === 'processing')
                        <span class="badge badge-proc">Diproses</span>
                    @elseif($comp->status === 'new')
                        <span class="badge badge-new">Baru</span>
                    @else
                        <span class="badge badge-blue">{{ $comp->status }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #94a3b8; padding: 12px;">Tidak ada data pengaduan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TABEL REKAPITULASI SESI PERCAKAPAN KONSULTASI -->
    <div class="section-header">II. Rekapitulasi Sesi Konsultasi & Chatbot PST</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Nama Pengunjung</th>
                <th style="width: 25%;">Waktu Masuk</th>
                <th style="width: 25%;">Penanganan</th>
                <th style="width: 20%; text-align: center;">Status Sesi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($conversations->take(15) as $idx => $conv)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $conv->visitor_name ?? 'Masyarakat Umum' }}</td>
                <td>{{ $conv->created_at->translatedFormat('d M Y, H:i') }} WIB</td>
                <td>{{ $conv->assignedOfficer ? $conv->assignedOfficer->name : 'Otomatis oleh Sistem PST' }}</td>
                <td style="text-align: center;">
                    @if($conv->status === 'closed')
                        <span class="badge badge-done">Selesai</span>
                    @elseif($conv->status === 'waiting')
                        <span class="badge badge-proc">Menunggu</span>
                    @elseif($conv->status === 'handled')
                        <span class="badge badge-blue">Petugas</span>
                    @else
                        <span class="badge">{{ $conv->status }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 12px;">Tidak ada data percakapan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PENGESAHAN / TANDA TANGAN RESMI -->
    <table class="ttd-table">
        <tr>
            <td style="width: 50%;">
                <p style="font-size: 8.5pt; color: #64748b;">
                    Dicetak secara otomatis oleh:<br>
                    <strong>Sistem Informasi PST BPS Karanganyar</strong><br>
                    Waktu Cetak: {{ $generatedAt }}<br>
                    Operator: {{ $generatedBy }}
                </p>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="ttd-box">
                    <p>Karanganyar, {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}<br>
                    <strong>Kepala Badan Pusat Statistik<br>Kabupaten Karanganyar</strong></p>
                    <div class="ttd-space"></div>
                    <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">....................................................</p>
                    <p style="font-size: 8.5pt; color: #475569; margin: 0;">NIP. ........................................</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen resmi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar • Halaman 1 dari 1
    </div>

</body>
</html>
