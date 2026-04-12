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
            width: 20%;
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
            width: 5%;
        }

        .data-table th:nth-child(2) {
            width: 12%;
        }

        .data-table th:nth-child(3) {
            width: 12%;
        }

        .data-table th:nth-child(4) {
            width: 15%;
        }

        .data-table th:nth-child(5) {
            width: 12%;
        }

        .data-table th:nth-child(6) {
            width: 8%;
        }

        .data-table th:nth-child(7) {
            width: 12%;
        }

        /* Status Badges untuk Aktivitas */
        .activity-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-create {
            background: #d4edda;
            color: #155724;
        }

        .badge-update {
            background: #cce5ff;
            color: #004085;
        }

        .badge-delete {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-login {
            background: #e2d5f1;
            color: #5a3e85;
        }

        .badge-logout {
            background: #fff3cd;
            color: #856404;
        }

        .badge-whatsapp {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-other {
            background: #e2e3e5;
            color: #383d41;
        }

        /* Module Summary */
        .module-summary {
            margin-bottom: 15px;
            width: 100%;
        }

        .module-summary h3 {
            margin: 0 0 6px 0;
            color: #001D39;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .module-items {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .module-item {
            background: #001D39;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 500;
        }

        /* Additional Stats Table */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }

        .stats-table th {
            background: #e9ecef;
            color: #001D39;
            padding: 4px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 8px;
        }

        .stats-table td {
            padding: 3px 6px;
            border-bottom: 1px solid #dee2e6;
            font-size: 8px;
        }

        .stats-table tr:last-child td {
            border-bottom: none;
        }

        .data-id {
            font-family: monospace;
            background: #e9ecef;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
        }

        .deskripsi-cell {
            word-wrap: break-word;
            line-height: 1.3;
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
                <p class="header-subtitle">E-Ticketing System - Activity Logs - Dicetak: {{ $date }}</p>
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
                    <div class="filter-label">Modul</div>
                    <div class="filter-value">{{ $filters['modul'] ?: 'Semua' }}</div>
                </td>
                <td>
                    <div class="filter-label">Aktivitas</div>
                    <div class="filter-value">{{ $filters['aktivitas'] ?: 'Semua' }}</div>
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
        <table class="summary-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <div class="summary-label">TOTAL LOGS</div>
                    <div class="summary-value total">{{ $statistics['total'] }}</div>
                </td>
                <td>
                    <div class="summary-label">MODUL</div>
                    <div class="summary-value total">{{ $statistics['by_module']->count() }}</div>
                </td>
                <td>
                    <div class="summary-label">AKTIVITAS</div>
                    <div class="summary-value total">{{ $statistics['by_activity']->count() }}</div>
                </td>
                <td>
                    <div class="summary-label">USER</div>
                    <div class="summary-value total">{{ $statistics['by_user']->count() }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Module Summary -->
    <div class="module-summary">
        <h3>MODUL YANG TERLIBAT</h3>
        <div class="module-items">
            @foreach ($statistics['by_module'] as $module => $count)
                <span class="module-item">{{ $module }} ({{ $count }})</span>
            @endforeach
        </div>
    </div>

    <!-- Data Table - Seperti landscape -->
    <table class="data-table" cellspacing="0" cellpadding="0" border="0">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Aktivitas</th>
                <th>Deskripsi</th>
                <th>Modul</th>
                <th>Data ID</th>
                <th>Tanggal & Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
                @php
                    $badgeClass = match ($log->aktivitas) {
                        'CREATE' => 'badge-create',
                        'UPDATE' => 'badge-update',
                        'DELETE' => 'badge-delete',
                        'LOGIN' => 'badge-login',
                        'LOGOUT' => 'badge-logout',
                        'WHATSAPP' => 'badge-whatsapp',
                        default => 'badge-other',
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if ($log->user)
                            <strong>{{ $log->user->nama ?? ($log->user->username ?? 'N/A') }}</strong>
                            <br>
                            <span style="font-size: 7px; color:#999;">{{ $log->user->username ?? '' }}</span>
                        @else
                            <span>System</span>
                        @endif
                    </td>
                    <td>
                        <span class="activity-badge {{ $badgeClass }}">
                            {{ $log->aktivitas }}
                        </span>
                    </td>
                    <td class="deskripsi-cell">{{ $log->deskripsi }}</td>
                    <td><span class="data-id">{{ $log->modul }}</span></td>
                    <td><span class="data-id">{{ $log->data_id ?? '-' }}</span></td>
                    <td>
                        {{ $log->tanggal_aktivitas->format('d/m/Y') }}<br>
                        <span style="font-size: 7px; color:#999;">{{ $log->tanggal_aktivitas->format('H:i:s') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data activity logs
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Berdasarkan Aktivitas -->
    <div class="summary-section">
        <div class="summary-title">RINGKASAN BERDASARKAN AKTIVITAS</div>
        <table class="stats-table" cellspacing="0" cellpadding="0" border="0">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics['by_activity'] as $activity => $count)
                    <tr>
                        <td>{{ $activity }}</td>
                        <td><strong>{{ $count }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Top Users by Activity -->
    <div class="summary-section">
        <div class="summary-title">TOP USER BERDASARKAN AKTIVITAS</div>
        <table class="stats-table" cellspacing="0" cellpadding="0" border="0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Jumlah Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics['by_user']->sortDesc()->take(10) as $user => $count)
                    <tr>
                        <td>{{ $user }}</td>
                        <td><strong>{{ $count }}</strong></td>
                    </tr>
                @endforeach
                @if ($statistics['by_user']->count() == 0)
                    <tr>
                        <td colspan="2" style="text-align: center; color: #999;">Tidak ada data user</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh E-Ticketing System - Activity Logs - © {{ date('Y') }} IT
            DIGITAL</p>
    </div>
</body>

</html>
