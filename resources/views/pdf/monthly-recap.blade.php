<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Bulanan - {{ $bulan_indonesia }} {{ $tahun }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            width: 100%;
            background: white;
        }

        /* Header dengan Logo di Kanan - Menggunakan Tabel */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            margin: 0 0 12px 0;
            border-bottom: 1.5px solid #001D39;
        }

        .header-table td {
            border: 0;
            padding: 0;
            vertical-align: middle;
        }

        .header-left {
            text-align: left;
        }

        .header-right {
            text-align: right;
        }

        .header-title {
            color: #001D39;
            margin: 0;
            font-size: 18px;
            line-height: 1.3;
        }

        .header-subtitle {
            margin: 3px 0 0 0;
            color: #666;
            font-size: 9px;
            line-height: 1.2;
        }

        .logo {
            width: 50px;
            height: 50px;
            display: inline-block;
            vertical-align: middle;
        }

        .logo-img {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 15px;
            width: 100%;
            padding: 0;
        }

        .filter-section h3 {
            margin: 0 0 6px 0;
            color: #001D39;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .filter-table td {
            border: 0;
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }

        .filter-table td:last-child {
            padding-right: 0;
        }

        .filter-label {
            font-size: 7px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
            margin-bottom: 1px;
        }

        .filter-value {
            font-size: 9px;
            font-weight: 600;
            color: #001D39;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 18px;
            width: 100%;
            padding: 0;
        }

        .summary-title {
            font-size: 11px;
            font-weight: 600;
            color: #495057;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .summary-table td {
            border: 0;
            padding: 3px 0;
            vertical-align: top;
            text-align: center;
        }

        .summary-label {
            font-size: 8px;
            color: #6c757d;
            white-space: nowrap;
            text-align: center;
            margin-bottom: 1px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            text-align: center;
        }

        .summary-value.total {
            color: #001D39;
        }

        .summary-value.open {
            color: #856404;
        }

        .summary-value.process {
            color: #004085;
        }

        .summary-value.done {
            color: #155724;
        }

        .summary-value.reject {
            color: #721c24;
        }

        .summary-value.pending {
            color: #e67700;
        }

        .summary-value.escalate {
            color: #d73a49;
        }

        /* Stats Container untuk Ringkasan Eksekutif */
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 8px;
            flex: 1;
            min-width: 80px;
            text-align: center;
            border-radius: 4px;
        }

        .stat-card .number {
            font-size: 18px;
            font-weight: bold;
            color: #001D39;
        }

        .stat-card .label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        /* Section Title */
        .section-title {
            background: #001D39;
            color: white;
            padding: 6px 10px;
            margin: 15px 0 10px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
            table-layout: fixed;
        }

        .data-table thead tr {
            background: #001D39;
            color: white;
        }

        .data-table th {
            padding: 5px 3px;
            text-align: left;
            font-weight: 600;
            border: 0;
            font-size: 8px;
        }

        .data-table td {
            padding: 4px 3px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            border: 0;
            word-wrap: break-word;
            font-size: 8px;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-open {
            background: #fff3cd;
            color: #856404;
        }

        .status-process {
            background: #cce5ff;
            color: #004085;
        }

        .status-done {
            background: #d4edda;
            color: #155724;
        }

        .status-reject {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff5e6;
            color: #e67700;
        }

        .status-escalate {
            background: #ffe6e8;
            color: #d73a49;
        }

        /* Progress Bar */
        .progress-bar-container {
            background: #e0e0e0;
            height: 4px;
            margin-top: 3px;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar {
            background: #001D39;
            height: 4px;
            border-radius: 2px;
        }

        /* Text alignment */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding: 6px 0 3px 0;
            width: 100%;
        }

        .footer p {
            margin: 1px 0;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- Header dengan Logo di Kanan - Menggunakan Tabel -->
    <table class="header-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="header-left" width="70%">
                <h1 class="header-title">REKAP BULANAN TIKET DUKUNGAN</h1>
                <p class="header-subtitle">
                    Periode: {{ $bulan_indonesia }} {{ $tahun }} |
                    E-Ticketing System - IT DIGITAL |
                    Dicetak: {{ $generate_date->format('d/m/Y H:i:s') }}
                </p>
            </td>
            <td class="header-right" width="30%">
                <div class="logo">
                    @php
                        $logoPath = public_path('assets/logo4.png');
                        $logoBase64 = null;
                        if (file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoBase64 = 'data:image/png;base64,' . $logoData;
                        }
                    @endphp
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo IT DIGITAL" class="logo-img">
                    @else
                        <div
                            style="width:50px;height:50px;background:#001D39;color:white;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:bold;font-size:16px;margin-left:auto;">
                            IT
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Filter Information -->
    <div class="filter-section">
        <h3>INFORMASI REKAP</h3>
        <table class="filter-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="filter-label">Periode</div>
                    <div class="filter-value">{{ $bulan_indonesia }} {{ $tahun }}</div>
                </td>
                <td>
                    <div class="filter-label">Tanggal Awal</div>
                    <div class="filter-value">{{ $start_date->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="filter-label">Tanggal Akhir</div>
                    <div class="filter-value">{{ $end_date->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="filter-label">Total Hari</div>
                    <div class="filter-value">{{ $jumlah_hari ?? $start_date->daysInMonth }} hari</div>
                </td>
                <td>
                    <div class="filter-label">Generate Oleh</div>
                    <div class="filter-value">{{ $generated_by }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Eksekutif -->
    <div class="filter-section">
        <h3>RINGKASAN EKSEKUTIF</h3>
        <table class="filter-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="filter-label">Total Laporan</div>
                    <div class="filter-value" style="font-size: 14px;">{{ number_format($total_laporan) }} tiket</div>
                </td>
                <td>
                    <div class="filter-label">Rata-rata per Hari</div>
                    <div class="filter-value" style="font-size: 14px;">{{ number_format($rata_per_hari, 1) }}
                        % tiket/hari</div>
                </td>
                @if ($avg_completion_time > 0)
                    <td>
                        <div class="filter-label">Rata-rata Resolusi</div>
                        <div class="filter-value" style="font-size: 14px;">{{ $avg_completion_time }} jam</div>
                    </td>
                @endif
                <td>
                    <div class="filter-label">Completion Rate</div>
                    <div class="filter-value" style="font-size: 14px;">{{ $stats['completion_rate'] ?? 0 }}%</div>
                </td>
                <td>
                    <div class="filter-label">Aplikasi Terlibat</div>
                    <div class="filter-value" style="font-size: 14px;">{{ count($stat_per_aplikasi) }} aplikasi</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics - Semua Status -->
    <div class="summary-section">
        <div class="summary-title">STATISTIK LAPORAN PER STATUS</div>
        <table class="summary-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="summary-label">TOTAL</div>
                    <div class="summary-value total">{{ number_format($total_laporan) }}</div>
                </td>
                <td>
                    <div class="summary-label">OPEN</div>
                    <div class="summary-value open">{{ number_format($stats['open'] ?? 0) }}</div>
                </td>
                <td>
                    <div class="summary-label">PROCESS</div>
                    <div class="summary-value process">{{ number_format($stats['process'] ?? 0) }}</div>
                </td>
                <td>
                    <div class="summary-label">PENDING</div>
                    <div class="summary-value pending">{{ number_format($stats['pending'] ?? 0) }}</div>
                </td>
                <td>
                    <div class="summary-label">ESCALATE</div>
                    <div class="summary-value escalate">{{ number_format($stats['escalate'] ?? 0) }}</div>
                </td>
                <td>
                    <div class="summary-label">DONE</div>
                    <div class="summary-value done">{{ number_format($stats['done'] ?? 0) }}</div>
                </td>
                <td>
                    <div class="summary-label">REJECT</div>
                    <div class="summary-value reject">{{ number_format($stats['reject'] ?? 0) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">📋 DETAIL STATUS LAPORAN</div>
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Status</th>
                <th width="20%" class="text-right">Jumlah</th>
                <th width="25%" class="text-right">Persentase</th>
                <th width="30%">Progress</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($stat_per_status as $status => $jumlah)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($status) }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($jumlah, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>{{ $total_laporan > 0 ? number_format(($jumlah / $total_laporan) * 100, 1, ',', '.') : 0 }}%</strong>
                    </td>
                    <td>
                        <div class="progress-bar-container">
                            <div class="progress-bar"
                                style="width: {{ $total_laporan > 0 ? ($jumlah / $total_laporan) * 100 : 0 }}%;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f0f0f0; font-weight: bold;">
                <td colspan="2" class="text-right">TOTAL</td>
                <td class="text-right"><strong>{{ number_format($total_laporan, 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>100%</strong></td>
                <td>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: 100%; background: #001D39;"></div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Statistik per Aplikasi -->
    <div class="section-title">📱 STATISTIK PER APLIKASI</div>
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="55%">Nama Aplikasi</th>
                <th width="20%">Jumlah Laporan</th>
                <th width="20%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($stat_per_aplikasi as $aplikasi => $jumlah)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $aplikasi }}</td>
                    <td class="text-right"><strong>{{ number_format($jumlah) }}</strong></td>
                    <td class="text-right">
                        {{ $total_laporan > 0 ? round(($jumlah / $total_laporan) * 100, 1) : 0 }}%
                        <div class="progress-bar-container">
                            <div class="progress-bar"
                                style="width: {{ $total_laporan > 0 ? ($jumlah / $total_laporan) * 100 : 0 }}%;">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top 10 Kantor -->
    <div class="section-title">🏢 TOP 10 KANTOR DENGAN LAPORAN TERBANYAK</div>
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="55%">Nama Kantor</th>
                <th width="20%">Jumlah Laporan</th>
                <th width="20%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach (array_slice($stat_per_kantor, 0, 10, true) as $kantor => $jumlah)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $kantor }}</td>
                    <td class="text-right"><strong>{{ number_format($jumlah) }}</strong></td>
                    <td class="text-right">
                        {{ $total_laporan > 0 ? round(($jumlah / $total_laporan) * 100, 1) : 0 }}%
                        <div class="progress-bar-container">
                            <div class="progress-bar"
                                style="width: {{ $total_laporan > 0 ? ($jumlah / $total_laporan) * 100 : 0 }}%;">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Trend Harian -->
    <div class="section-title">📈 TREND HARIAN</div>
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Tanggal</th>
                <th width="60%">Jumlah Laporan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @php $maxJumlah = max($stat_per_hari) ?: 1; @endphp
            @foreach ($stat_per_hari as $tanggal => $jumlah)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</strong>
                        <span
                            style="color: #666; font-size: 7px;">({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l') }})</span>
                    </td>
                    <td>
                        <strong>{{ number_format($jumlah) }}</strong> tiket
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ ($jumlah / $maxJumlah) * 100 }}%;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 20 Laporan Terbaru -->
    @if (count($laporans) > 0)
        <div class="section-title">📋 20 LAPORAN TERBARU</div>
        <table class="data-table" cellspacing="0" cellpadding="0" border="0">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Ticket</th>
                    <th width="10%">Tanggal</th>
                    <th width="12%">Pelapor</th>
                    <th width="10%">No. HP</th>
                    <th width="12%">Kantor</th>
                    <th width="12%">Aplikasi</th>
                    <th width="8%">Status</th>
                    <th width="10%">Tgl Selesai</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($laporans as $laporan)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td><strong>{{ $laporan->nomor_ticket }}</strong></td>
                        <td>{{ $laporan->tanggal_laporan->format('d/m/Y') }}</td>
                        <td>{{ $laporan->nama_pelapor }}</td>
                        <td>{{ $laporan->no_handphone }}</td>
                        <td>{{ $laporan->kantor->nama_cabang ?? '-' }}</td>
                        <td>{{ $laporan->jenisAplikasi->jenis_aplikasi ?? '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($laporan->status) }}">
                                {{ strtoupper($laporan->status) }}
                            </span>
                        </td>
                        <td>{{ $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh E-Ticketing System - © {{ date('Y') }} IT DIGITAL</p>
        <p>* Laporan ini berisi rekap lengkap semua status laporan pada periode {{ $bulan_indonesia }}
            {{ $tahun }}</p>
    </div>
</body>

</html>
