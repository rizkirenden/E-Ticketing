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

        /* Filter Section - Seperti landscape */
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

        /* Summary Section - Seperti landscape */
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
            width: 14.28%;
            /* Adjusted for 7 status columns */
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

        /* New status summary colors */
        .summary-value.pending {
            color: #e67700;
            /* Dark orange for pending */
        }

        .summary-value.escalate {
            color: #d73a49;
            /* Red-orange for escalate */
        }

        /* Data Table - Seperti landscape */
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

        /* Atur lebar kolom data table */
        .data-table th:nth-child(1) {
            width: 4%;
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
            width: 13%;
        }

        .data-table th:nth-child(6) {
            width: 13%;
        }

        .data-table th:nth-child(7) {
            width: 8%;
        }

        .data-table th:nth-child(8) {
            width: 10%;
        }

        .data-table th:nth-child(9) {
            width: 10%;
        }

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

        /* New status badge colors */
        .status-pending {
            background: #fff5e6;
            /* Light orange background */
            color: #e67700;
        }

        .status-escalate {
            background: #ffe6e8;
            /* Light red background */
            color: #d73a49;
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
    </style>
</head>

<body>
    <!-- Header dengan Logo di Kanan - Menggunakan Tabel -->
    <table class="header-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="header-left" width="70%">
                <h1 class="header-title">{{ $title }}</h1>
                <p class="header-subtitle">E-Ticketing System - IT DIGITAL - Dicetak: {{ $date }}</p>
            </td>
            <td class="header-right" width="30%">
                <div class="logo">
                    @if (isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo IT DIGITAL" class="logo-img">
                    @else
                        <!-- Fallback jika logo tidak ada -->
                        <div
                            style="width:50px;height:50px;background:#001D39;color:white;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:bold;font-size:16px;margin-left:auto;">
                            IT
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Filter Information - Seperti landscape -->
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

    <!-- Summary Statistics - Seperti landscape -->
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

    <!-- Data Table - Seperti landscape -->
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
