<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            width: 100%;
        }

        /* Header dengan Logo di Kanan - Menggunakan Tabel */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #001D39;
        }

        .header-table td {
            border: 0;
            padding: 5px 0;
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
        }

        .header-subtitle {
            margin: 0;
            color: #666;
            font-size: 10px;
        }

        .header-date {
            font-size: 10px;
            color: #666;
            margin: 0;
        }

        .logo {
            width: 50px;
            height: 50px;
            display: inline-block;
        }

        .logo-img {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 10px;
            width: 100%;
            padding: 0;
        }

        .filter-section h3 {
            margin: 0 0 3px 0;
            color: #001D39;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
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
            padding: 2px 8px 2px 0;
            vertical-align: top;
        }

        .filter-table td:last-child {
            padding-right: 0;
        }

        .filter-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
            margin-bottom: 1px;
        }

        .filter-value {
            font-size: 10px;
            font-weight: 600;
            color: #001D39;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 10px;
            width: 100%;
            padding: 0;
        }

        .summary-title {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.2px;
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
            padding: 2px 0;
            vertical-align: top;
            text-align: center;
            width: 14.28%;
            /* Adjusted for 7 status columns */
        }

        .summary-label {
            font-size: 9px;
            color: #6c757d;
            white-space: nowrap;
            text-align: center;
            margin-bottom: 1px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.1;
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

        /* New status summary colors */
        .summary-value.pending {
            color: #e67700;
            /* Dark orange */
        }

        .summary-value.escalate {
            color: #d73a49;
            /* Red-orange */
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
            table-layout: fixed;
        }

        .data-table thead tr {
            background: #001D39;
            color: white;
        }

        .data-table th {
            padding: 4px 2px;
            text-align: left;
            font-weight: 600;
            border: 0;
            font-size: 9px;
        }

        .data-table td {
            padding: 3px 2px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            border: 0;
            word-wrap: break-word;
            font-size: 9px;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Column widths (9 columns) */
        .data-table th:nth-child(1) {
            width: 3%;
        }

        .data-table th:nth-child(2) {
            width: 12%;
        }

        .data-table th:nth-child(3) {
            width: 12%;
        }

        .data-table th:nth-child(4) {
            width: 10%;
        }

        .data-table th:nth-child(5) {
            width: 14%;
        }

        .data-table th:nth-child(6) {
            width: 14%;
        }

        .data-table th:nth-child(7) {
            width: 7%;
        }

        .data-table th:nth-child(8) {
            width: 9%;
        }

        .data-table th:nth-child(9) {
            width: 9%;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 8px;
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

        /* New status badge colors */
        .status-pending {
            background: #fff5e6;
            color: #e67700;
        }

        .status-escalate {
            background: #ffe6e8;
            color: #d73a49;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding: 5px 0 2px 0;
            width: 100%;
        }

        .footer p {
            margin: 1px 0;
        }
    </style>
</head>

<body>
    <!-- Header dengan Logo di Kanan -->
    <table class="header-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="header-left" width="50%">
                <h1 class="header-title">{{ $title }}</h1>
                <p class="header-subtitle">E-Ticketing System - IT DIGITAL - Dicetak: {{ $date }}</p>
            </td>
            <td class="header-right" width="50%">
                <div style="display: flex; align-items: center; justify-content: flex-end;">
                    <div class="logo">
                        @if (isset($logoBase64) && $logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Logo IT DIGITAL" class="logo-img">
                        @else
                            <div
                                style="width:50px;height:50px;background:#001D39;color:white;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:bold;font-size:16px;">
                                IT
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Filter Information -->
    <div class="filter-section">
        <h3>FILTER YANG DITERAPKAN</h3>
        <table class="filter-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="filter-label">Pencarian</div>
                    <div class="filter-value">{{ $filters['search'] ?: 'Semua' }}</div>
                </td>
                <td>
                    <div class="filter-label">Kantor</div>
                    <div class="filter-value">{{ $filters['kantor'] }}</div>
                </td>
                <td>
                    <div class="filter-label">Aplikasi</div>
                    <div class="filter-value">{{ $filters['aplikasi'] }}</div>
                </td>
                <td>
                    <div class="filter-label">Status</div>
                    <div class="filter-value">{{ $filters['status'] }}</div>
                </td>
                <td>
                    <div class="filter-label">Tgl Awal</div>
                    <div class="filter-value">{{ $filters['tanggal_awal'] ?: 'Semua' }}</div>
                </td>
                <td>
                    <div class="filter-label">Tgl Akhir</div>
                    <div class="filter-value">{{ $filters['tanggal_akhir'] ?: 'Semua' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-section">
        <div class="summary-title">STATISTIK LAPORAN</div>
        @php
            $openCount = $laporans->where('status', 'open')->count();
            $processCount = $laporans->where('status', 'process')->count();
            $doneCount = $laporans->where('status', 'done')->count();
            $rejectCount = $laporans->where('status', 'reject')->count();
            $pendingCount = $laporans->where('status', 'pending')->count();
            $escalateCount = $laporans->where('status', 'escalate')->count();
        @endphp
        <table class="summary-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="summary-label">TOTAL</div>
                    <div class="summary-value total">{{ $total }}</div>
                </td>
                <td>
                    <div class="summary-label">OPEN</div>
                    <div class="summary-value open">{{ $openCount }}</div>
                </td>
                <td>
                    <div class="summary-label">PROCESS</div>
                    <div class="summary-value process">{{ $processCount }}</div>
                </td>
                <td>
                    <div class="summary-label">PENDING</div>
                    <div class="summary-value pending">{{ $pendingCount }}</div>
                </td>
                <td>
                    <div class="summary-label">ESCALATE</div>
                    <div class="summary-value escalate">{{ $escalateCount }}</div>
                </td>
                <td>
                    <div class="summary-label">DONE</div>
                    <div class="summary-value done">{{ $doneCount }}</div>
                </td>
                <td>
                    <div class="summary-label">REJECT</div>
                    <div class="summary-value reject">{{ $rejectCount }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table - 9 Columns -->
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Ticket</th>
                <th>Pelapor</th>
                <th>No. HP</th>
                <th>Kantor</th>
                <th>Aplikasi</th>
                <th>Status</th>
                <th>Tgl Laporan</th>
                <th>Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporans as $index => $laporan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $laporan->nomor_ticket }}</strong></td>
                    <td>{{ $laporan->nama_pelapor }}</td>
                    <td>{{ $laporan->no_handphone }}</td>
                    <td>{{ $laporan->kantor->nama_cabang ?? 'N/A' }}</td>
                    <td>{{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ $laporan->status }}">
                            {{ strtoupper($laporan->status) }}
                        </span>
                    </td>
                    <td>{{ $laporan->tanggal_laporan->format('d/m/Y') }}</td>
                    <td>{{ $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh E-Ticketing System - © {{ date('Y') }} IT DIGITAL</p>
    </div>
</body>

</html>
