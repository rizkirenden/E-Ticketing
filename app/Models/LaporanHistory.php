<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanHistory extends Model
{
    protected $table = 'laporan_histories';

    protected $fillable = [
        'laporan_id',
        'old_status',
        'new_status',
        'description',
        'updated_by',
        'changed_at'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }
}
