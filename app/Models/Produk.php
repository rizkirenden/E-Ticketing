<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'deskripsi',
        'jenis_aplikasi_id'
    ];

    public function jenisAplikasi()
    {
        return $this->belongsTo(Jenisaplikasi::class, 'jenis_aplikasi_id');
    }
}
