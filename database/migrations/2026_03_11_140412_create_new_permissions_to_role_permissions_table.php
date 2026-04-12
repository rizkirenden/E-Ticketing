<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_new_permissions_to_role_permissions_table.php

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
            $table->boolean('can_wa')->default(false)->after('can_import');
            $table->boolean('can_show')->default(false)->after('can_wa');
            $table->boolean('can_update_status')->default(false)->after('can_show');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn(['can_wa', 'can_show', 'can_update_status']);
        });
    }
};
