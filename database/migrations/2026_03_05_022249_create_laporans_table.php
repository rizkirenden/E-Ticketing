<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_ticket', 30)->unique();
            $table->string('nama_pelapor', 50);
            $table->foreignId('kantor_id')->constrained('kantors')->cascadeOnDelete();
            $table->foreignId('jenis_aplikasi_id')->constrained('jenis_aplikasis')->cascadeOnDelete();
            $table->foreignId('kode_produk_id')->constrained('produks')->cascadeOnDelete();
            $table->string('no_handphone', 20);
            $table->text('kronologi')->nullable();
            $table->json('lampiran')->nullable();
            $table->text('solusi')->nullable();
            $table->enum('status', ['open','process','done','reject','pending','escalate'])->default('open');
            $table->text('pending_deskripsi')->nullable();
            $table->text('escalate_deskripsi')->nullable();
            $table->dateTime('tanggal_laporan');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
