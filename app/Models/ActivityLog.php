<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'modul',
        'data_id',
        'data_sebelum',
        'data_sesudah',
        'tanggal_aktivitas'
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'tanggal_aktivitas' => 'datetime'
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter berdasarkan modul
     */
    public function scopeModul($query, $modul)
    {
        return $query->where('modul', $modul);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeTanggalBetween($query, $start, $end)
    {
        return $query->whereBetween('tanggal_aktivitas', [$start, $end]);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('aktivitas', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('modul', 'like', "%{$search}%")
              ->orWhereHas('user', function($uq) use ($search) {
                  $uq->where('nama', 'like', "%{$search}%")
                     ->orWhere('username', 'like', "%{$search}%");
              });
        });
    }
}
