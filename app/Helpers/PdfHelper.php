<?php

if (!function_exists('formatJSONForPDF')) {
    function formatJSONForPDF($data) {
        if (!$data) return '<span class="json-null">null</span>';

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Simple formatting for PDF
        return '<pre style="margin:0; font-family: monospace; font-size: 11px;">' . htmlspecialchars($json) . '</pre>';
    }
}
