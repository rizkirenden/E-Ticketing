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
        Schema::create('laporan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade');
            $table->string('old_status', 20);
            $table->string('new_status', 20);
            $table->text('description')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            // Add indexes for better performance
            $table->index('laporan_id');
            $table->index('changed_at');
            $table->index(['laporan_id', 'changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_histories');
    }
};
