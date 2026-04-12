<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'nomor_ticket',
        'nama_pelapor',
        'kantor_id',
        'jenis_aplikasi_id',
        'kode_produk_id',
        'no_handphone',
        'kronologi',
        'lampiran',
        'solusi',
        'status',
        'pending_deskripsi',
        'escalate_deskripsi',
        'tanggal_laporan',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_laporan' => 'datetime',
        'tanggal_selesai' => 'datetime'
    ];

    /**
     * Get the kantor that owns the laporan.
     */
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    /**
     * Get the jenis aplikasi that owns the laporan.
     */
    public function jenisAplikasi()
    {
        return $this->belongsTo(Jenisaplikasi::class, 'jenis_aplikasi_id');
    }

    // Relationship with produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk_id');
    }

    /**
     * Get lampiran as array
     */
    public function getLampiranArrayAttribute()
    {
        return json_decode($this->lampiran, true) ?? [];
    }

    /**
     * Generate nomor ticket otomatis dengan format:
     * {kode_jenis_aplikasi}-{kode_produk}-TCK-{DDMMYYYY}-{nomor_urut}
     *
     * Contoh: APPS1-PRD001-TCK-25122024-0001
     */
    public static function generateNomorTicket($jenisAplikasiId, $kodeProdukId)
    {
        // Get jenis aplikasi
        $jenisAplikasi = Jenisaplikasi::find($jenisAplikasiId);
        $kodeJenisAplikasi = $jenisAplikasi ? $jenisAplikasi->kode_jenis_aplikasi : 'APPS';

        // Get produk
        $produk = Produk::find($kodeProdukId);
        $kodeProduk = $produk ? $produk->kode_produk : 'PRD';

        // Format tanggal: DDMMYYYY (contoh: 25122024)
        $tanggal = now()->format('dmY');

        // Prefix format: {kode_jenis_aplikasi}-{kode_produk}-TCK-{tanggal}-
        $prefix = strtoupper($kodeJenisAplikasi) . '-' . strtoupper($kodeProduk) . '-TCK-' . $tanggal . '-';

        // Get last ticket number for today with same prefix
        $lastTicket = self::where('nomor_ticket', 'LIKE', $prefix . '%')
            ->whereDate('created_at', today())
            ->latest()
            ->first();

        if ($lastTicket) {
            // Extract last number from ticket (last 4 digits)
            $lastNumber = intval(substr($lastTicket->nomor_ticket, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Alternative format: {kode_jenis_aplikasi}-{kode_produk}-TCK-{YYYYMMDD}-{nomor_urut}
     * Contoh: APPS1-PRD001-TCK-20241225-0001
     */
    public static function generateNomorTicketAlt($jenisAplikasiId, $kodeProdukId)
    {
        // Get jenis aplikasi
        $jenisAplikasi = Jenisaplikasi::find($jenisAplikasiId);
        $kodeJenisAplikasi = $jenisAplikasi ? $jenisAplikasi->kode_jenis_aplikasi : 'APPS';

        // Get produk
        $produk = Produk::find($kodeProdukId);
        $kodeProduk = $produk ? $produk->kode_produk : 'PRD';

        // Format tanggal: YYYYMMDD
        $tanggal = now()->format('Ymd');

        // Prefix format: {kode_jenis_aplikasi}-{kode_produk}-TCK-{tanggal}-
        $prefix = strtoupper($kodeJenisAplikasi) . '-' . strtoupper($kodeProduk) . '-TCK-' . $tanggal . '-';

        // Count tickets for today with same prefix
        $todayCount = self::where('nomor_ticket', 'LIKE', $prefix . '%')
            ->whereDate('created_at', today())
            ->count();

        $newNumber = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Format dengan nomor urut berdasarkan kombinasi aplikasi dan produk
     * Contoh: APPS1-PRD001-TCK-20241225-0001
     */
    public static function generateNomorTicketWithSequence($jenisAplikasiId, $kodeProdukId)
    {
        // Get jenis aplikasi
        $jenisAplikasi = Jenisaplikasi::find($jenisAplikasiId);
        $kodeJenisAplikasi = $jenisAplikasi ? $jenisAplikasi->kode_jenis_aplikasi : 'APPS';

        // Get produk
        $produk = Produk::find($kodeProdukId);
        $kodeProduk = $produk ? $produk->kode_produk : 'PRD';

        // Format tanggal: YYYYMMDD
        $tanggal = now()->format('Ymd');

        // Base prefix
        $basePrefix = strtoupper($kodeJenisAplikasi) . '-' . strtoupper($kodeProduk) . '-TCK-' . $tanggal;

        // Get the highest sequence number for today with this specific prefix
        $lastTicket = self::where('nomor_ticket', 'LIKE', $basePrefix . '%')
            ->whereDate('created_at', today())
            ->orderBy('nomor_ticket', 'desc')
            ->first();

        if ($lastTicket) {
            // Extract the sequence number from the last ticket
            $parts = explode('-', $lastTicket->nomor_ticket);
            $lastSequence = intval(end($parts));
            $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0001';
        }

        return $basePrefix . '-' . $newSequence;
    }
}
