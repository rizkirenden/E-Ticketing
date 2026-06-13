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
        Schema::table('role_permissions', function (Blueprint $table) {
            // Cek apakah kolom can_excel sudah ada
            if (!Schema::hasColumn('role_permissions', 'can_excel')) {
                $table->boolean('can_excel')->default(false)->after('can_wa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            // Cek apakah kolom can_excel ada sebelum dihapus
            if (Schema::hasColumn('role_permissions', 'can_excel')) {
                $table->dropColumn('can_excel');
            }
        });
    }
};
