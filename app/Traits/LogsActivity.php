<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log activity ke database
     *
     * @param string $aktivitas
     * @param string $deskripsi
     * @param string $modul
     * @param int|null $dataId
     * @param array|null $dataSebelum
     * @param array|null $dataSesudah
     * @return void
     */
    protected function logActivity($aktivitas, $deskripsi, $modul, $dataId = null, $dataSebelum = null, $dataSesudah = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'aktivitas' => $aktivitas,
                'deskripsi' => $deskripsi,
                'modul' => $modul,
                'data_id' => $dataId,
                'data_sebelum' => $dataSebelum ? json_encode($dataSebelum) : null,
                'data_sesudah' => $dataSesudah ? json_encode($dataSesudah) : null,
                'tanggal_aktivitas' => now()
            ]);
        } catch (\Exception $e) {
            // Log error tapi jangan sampai mengganggu proses utama
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Log create activity
     *
     * @param string $modul
     * @param int $dataId
     * @param string $deskripsi
     * @param array $dataSesudah
     * @return void
     */
    protected function logCreate($modul, $dataId, $deskripsi, $dataSesudah)
    {
        $this->logActivity(
            'CREATE',
            $deskripsi,
            $modul,
            $dataId,
            null,
            $this->filterSensitiveData($dataSesudah)
        );
    }

    /**
     * Log update activity
     *
     * @param string $modul
     * @param int $dataId
     * @param string $deskripsi
     * @param array $dataSebelum
     * @param array $dataSesudah
     * @return void
     */
    protected function logUpdate($modul, $dataId, $deskripsi, $dataSebelum, $dataSesudah)
    {
        // Filter hanya field yang berubah
        $changes = $this->getChangedFields($dataSebelum, $dataSesudah);

        if (empty($changes)) {
            return;
        }

        $this->logActivity(
            'UPDATE',
            $deskripsi . ' (' . implode(', ', array_keys($changes)) . ')',
            $modul,
            $dataId,
            $this->filterSensitiveData($changes['old'] ?? $dataSebelum),
            $this->filterSensitiveData($changes['new'] ?? $dataSesudah)
        );
    }

    /**
     * Log delete activity
     *
     * @param string $modul
     * @param int $dataId
     * @param string $deskripsi
     * @param array $dataSebelum
     * @return void
     */
    protected function logDelete($modul, $dataId, $deskripsi, $dataSebelum)
    {
        $this->logActivity(
            'DELETE',
            $deskripsi,
            $modul,
            $dataId,
            $this->filterSensitiveData($dataSebelum),
            null
        );
    }

    /**
     * Log login activity
     *
     * @param int $userId
     * @param string $username
     * @param string $ip
     * @return void
     */
    protected function logLogin($userId, $username, $ip)
    {
        $this->logActivity(
            'LOGIN',
            "User {$username} berhasil login dari IP {$ip}",
            'AUTH',
            $userId,
            null,
            ['ip' => $ip, 'login_time' => now()->toDateTimeString()]
        );
    }

    /**
     * Log logout activity
     *
     * @param int $userId
     * @param string $username
     * @param string $ip
     * @return void
     */
    protected function logLogout($userId, $username, $ip)
    {
        $this->logActivity(
            'LOGOUT',
            "User {$username} logout dari IP {$ip}",
            'AUTH',
            $userId
        );
    }

    /**
     * Log failed login activity
     *
     * @param string $username
     * @param string $ip
     * @param string $reason
     * @return void
     */
    protected function logFailedLogin($username, $ip, $reason)
    {
        $this->logActivity(
            'LOGIN_FAILED',
            "Gagal login untuk username {$username} dari IP {$ip}. Alasan: {$reason}",
            'AUTH',
            null,
            ['username' => $username, 'ip' => $ip]
        );
    }

    /**
     * Get changed fields between two arrays
     *
     * @param array $old
     * @param array $new
     * @return array
     */
    protected function getChangedFields($old, $new)
    {
        $oldArray = is_array($old) ? $old : (array)$old;
        $newArray = is_array($new) ? $new : (array)$new;

        $changes = [];
        $oldChanges = [];
        $newChanges = [];

        foreach ($newArray as $key => $value) {
            if (!isset($oldArray[$key]) || $oldArray[$key] != $value) {
                $oldChanges[$key] = $oldArray[$key] ?? null;
                $newChanges[$key] = $value;
            }
        }

        // Cek field yang dihapus
        foreach ($oldArray as $key => $value) {
            if (!isset($newArray[$key])) {
                $oldChanges[$key] = $value;
                $newChanges[$key] = null;
            }
        }

        return [
            'old' => $oldChanges,
            'new' => $newChanges
        ];
    }

    /**
     * Filter sensitive data before logging
     *
     * @param array|null $data
     * @return array|null
     */
    protected function filterSensitiveData($data)
    {
        if (!$data) {
            return null;
        }

        $sensitiveFields = ['password', 'password_confirmation', 'remember_token', 'api_token'];
        $filtered = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $sensitiveFields)) {
                $filtered[$key] = '[REDACTED]';
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
}
