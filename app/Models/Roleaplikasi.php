<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roleaplikasi extends Model
{
    protected $table = 'role_aplikasis';

    protected $fillable = [
        'role_id',
        'jenis_aplikasi_id'
    ];

    /**
     * Get the role that owns the role aplikasi.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the jenis aplikasi that owns the role aplikasi.
     */
    public function jenisAplikasi()
    {
        return $this->belongsTo(Jenisaplikasi::class, 'jenis_aplikasi_id');
    }
}
