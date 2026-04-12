<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $table = 'kantors';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'area'
    ];

    /**
     * Get the laporans for the kantor.
     */
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}
