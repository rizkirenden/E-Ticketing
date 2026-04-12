<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }

        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 5px 0 0;
        }

        .date {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 10px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8fafc;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin: 5px 0 0;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-create {
            background-color: #10b98120;
            color: #059669;
            border: 1px solid #10b981;
        }

        .badge-update {
            background-color: #3b82f620;
            color: #2563eb;
            border: 1px solid #3b82f6;
        }

        .badge-delete {
            background-color: #ef444420;
            color: #dc2626;
            border: 1px solid #ef4444;
        }

        .badge-login {
            background-color: #8b5cf620;
            color: #7c3aed;
            border: 1px solid #8b5cf6;
        }

        .badge-logout {
            background-color: #f59e0b20;
            color: #d97706;
            border: 1px solid #f59e0b;
        }

        .badge-whatsapp {
            background-color: #25d36620;
            color: #128C7E;
            border: 1px solid #25d366;
        }

        .json-viewer {
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .json-key {
            color: #f472b6;
        }

        .json-value {
            color: #4ade80;
        }

        .json-string {
            color: #fbbf24;
        }

        .json-number {
            color: #60a5fa;
        }

        .json-boolean {
            color: #f87171;
        }

        .json-null {
            color: #9ca3af;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    @php
        // Fungsi helper untuk format JSON di PDF
        function formatJSONForPDF($data)
        {
            if (empty($data)) {
                return '<span class="json-null">null</span>';
            }

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            // Highlight syntax
            $json = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');

            $patterns = [
                '/"([^"]+)"(?=\s*:)/', // keys
                '/:(\s*)"([^"]*)"/', // string values
                '/:(\s*)(\d+)/', // numbers
                '/:(\s*)(true|false)/', // booleans
                '/:(\s*)(null)/', // null
            ];

            $replacements = [
                '<span class="json-key">"$1"</span>',
                ':<span class="json-string"> "$2"</span>',
                ':<span class="json-number"> $2</span>',
                ':<span class="json-boolean"> $2</span>',
                ':<span class="json-null"> $2</span>',
            ];

            return preg_replace($patterns, $replacements, $json);
        }

        // Fungsi untuk mendapatkan warna badge berdasarkan aktivitas
        function getBadgeClass($aktivitas)
        {
            $classes = [
                'CREATE' => 'badge-create',
                'UPDATE' => 'badge-update',
                'DELETE' => 'badge-delete',
                'LOGIN' => 'badge-login',
                'LOGOUT' => 'badge-logout',
                'WHATSAPP' => 'badge-whatsapp',
            ];
            return $classes[$aktivitas] ?? 'badge';
        }

        // Fungsi untuk mendapatkan icon aktivitas
        function getActivityIcon($aktivitas)
        {
            $icons = [
                'CREATE' => '➕',
                'UPDATE' => '✏️',
                'DELETE' => '🗑️',
                'LOGIN' => '🔐',
                'LOGOUT' => '👋',
                'WHATSAPP' => '📱',
            ];
            return $icons[$aktivitas] ?? '•';
        }
    @endphp

    <!-- Header -->
    <div class="header">
        @if ($logoBase64)
            <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
        @endif
        <h1 class="title">{{ $title }}</h1>
        <p class="date">Dicetak pada: {{ $date }}</p>
    </div>

    <!-- Detail Card -->
    <div class="card">
        <h2 class="card-title">Detail Activity Log #{{ $log->id }}</h2>

        <div class="grid-2">
            <div>
                <p class="info-label">ID Log</p>
                <p class="info-value">#{{ $log->id }}</p>
            </div>
            <div>
                <p class="info-label">User</p>
                <p class="info-value">
                    {{ $log->user ? $log->user->nama : 'System' }}
                    @if ($log->user)
                        <br><small style="color: #64748b;">{{ $log->user->username }}</small>
                    @endif
                </p>
            </div>
            <div>
                <p class="info-label">Aktivitas</p>
                <div>
                    <span class="badge {{ getBadgeClass($log->aktivitas) }}">
                        {{ getActivityIcon($log->aktivitas) }} {{ $log->aktivitas }}
                    </span>
                </div>
            </div>
            <div>
                <p class="info-label">Modul</p>
                <p class="info-value">{{ $log->modul }}</p>
            </div>
            <div>
                <p class="info-label">Data ID</p>
                <p class="info-value">{{ $log->data_id ? '#' . $log->data_id : '-' }}</p>
            </div>
            <div>
                <p class="info-label">Tanggal Aktivitas</p>
                <p class="info-value">{{ $log->tanggal_aktivitas->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="card">
        <h2 class="card-title">Deskripsi</h2>
        <p style="margin: 0; padding: 10px; background-color: white; border-radius: 6px;">
            {{ $log->deskripsi }}
        </p>
    </div>

    <!-- Data Sebelum -->
    @if ($log->data_sebelum)
        <div class="card">
            <h2 class="card-title">Data Sebelum</h2>
            <div class="json-viewer">
                {!! formatJSONForPDF($log->data_sebelum) !!}
            </div>
        </div>
    @endif

    <!-- Data Sesudah -->
    @if ($log->data_sesudah)
        <div class="card">
            <h2 class="card-title">Data Sesudah</h2>
            <div class="json-viewer">
                {!! formatJSONForPDF($log->data_sesudah) !!}
            </div>
        </div>
    @endif

    <!-- Metadata -->
    <div style="margin-top: 20px; padding: 10px; background-color: #f1f5f9; border-radius: 6px;">
        <div style="display: flex; gap: 20px; font-size: 11px; color: #64748b;">
            <div>Dibuat: {{ $log->created_at->format('d/m/Y H:i:s') }}</div>
            <div>Diupdate: {{ $log->updated_at->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <!-- Signature -->
    <div class="signature">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Jakarta, {{ now()->format('d F Y') }}</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; font-weight: bold;">IT DIGITAL</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh E-Ticketing System</p>
        <p>© {{ date('Y') }} IT DIGITAL. All rights reserved.</p>
    </div>
</body>

</html>
