<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenisaplikasi extends Model
{
    protected $table = 'jenis_aplikasis';

    protected $fillable = [
        'kode_jenis_aplikasi',
        'jenis_aplikasi',
        'deskripsi'
    ];

    /**
     * Get the laporans for the aplikasi.
     */
    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'jenis_aplikasi_id');
    }

    public function produks()
    {
        return $this->hasMany(Produk::class, 'jenis_aplikasi_id');
    }
}
