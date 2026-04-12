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
            width: 25%;
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

        /* Data Table */
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

        /* Atur lebar kolom data table (7 kolom) */
        .data-table th:nth-child(1) {
            width: 5%;
        }

        .data-table th:nth-child(2) {
            width: 12%;
        }

        .data-table th:nth-child(3) {
            width: 10%;
        }

        .data-table th:nth-child(4) {
            width: 28%;
        }

        .data-table th:nth-child(5) {
            width: 10%;
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

        .data-id {
            font-family: monospace;
            background: #e9ecef;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
        }

        .user-name {
            font-weight: 600;
            color: #001D39;
        }

        .user-username {
            font-size: 7px;
            color: #6c757d;
        }

        /* Stats Additional - Ringkasan Aktivitas & Top User */
        .stats-additional {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            width: 100%;
        }

        .stats-box {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 10px;
        }

        .stats-box-title {
            color: #001D39;
            font-size: 9px;
            font-weight: 600;
            margin-bottom: 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #dee2e6;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stats-box-item {
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            margin-bottom: 3px;
        }

        .stats-box-item span:first-child {
            color: #6c757d;
        }

        .stats-box-item span:last-child {
            font-weight: 600;
            color: #001D39;
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
                <td width="20%">
                    <div class="filter-label">Pencarian</div>
                    <div class="filter-value">{{ $filters['search'] ?: 'Semua' }}</div>
                </td>
                <td width="20%">
                    <div class="filter-label">Modul</div>
                    <div class="filter-value">{{ $filters['modul'] ?: 'Semua' }}</div>
                </td>
                <td width="20%">
                    <div class="filter-label">Aktivitas</div>
                    <div class="filter-value">{{ $filters['aktivitas'] ?: 'Semua' }}</div>
                </td>
                <td width="20%">
                    <div class="filter-label">Tgl Awal</div>
                    <div class="filter-value">{{ $filters['tanggal_awal'] ?: 'Semua' }}</div>
                </td>
                <td width="20%">
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

    <!-- Data Table -->
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
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        @if ($log->user)
                            <div class="user-name">{{ $log->user->nama ?? ($log->user->username ?? 'N/A') }}</div>
                            <div class="user-username">{{ $log->user->username ?? '' }}</div>
                        @else
                            <span>System</span>
                        @endif
                    </td>
                    <td>
                        <span class="activity-badge {{ $badgeClass }}">
                            {{ $log->aktivitas }}
                        </span>
                    </td>
                    <td>{{ Str::limit($log->deskripsi, 60) }}</td>
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

    <!-- Ringkasan Aktivitas dan Top User -->
    <div class="stats-additional">
        <div class="stats-box">
            <div class="stats-box-title">RINGKASAN AKTIVITAS</div>
            @foreach ($statistics['by_activity'] as $activity => $count)
                <div class="stats-box-item">
                    <span>{{ $activity }}</span>
                    <span>{{ $count }}</span>
                </div>
            @endforeach
        </div>
        <div class="stats-box">
            <div class="stats-box-title">TOP USER</div>
            @foreach ($statistics['by_user']->sortDesc()->take(5) as $user => $count)
                <div class="stats-box-item">
                    <span>{{ Str::limit($user, 20) }}</span>
                    <span>{{ $count }}</span>
                </div>
            @endforeach
            @if ($statistics['by_user']->count() == 0)
                <div class="stats-box-item">
                    <span>Tidak ada data</span>
                    <span>0</span>
                </div>
            @endif
        </div>
        <div class="stats-box">
            <div class="stats-box-title">TOP MODUL</div>
            @foreach ($statistics['by_module']->sortDesc()->take(5) as $module => $count)
                <div class="stats-box-item">
                    <span>{{ Str::limit($module, 20) }}</span>
                    <span>{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh E-Ticketing System - Activity Logs - © {{ date('Y') }} IT
            DIGITAL</p>
    </div>
</body>

</html>
