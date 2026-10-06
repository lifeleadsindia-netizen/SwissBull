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
        if (Schema::hasTable('pepe_settings') && ! Schema::hasColumn('pepe_settings', 'token_abi')) {
            Schema::table('pepe_settings', function (Blueprint $table) {
                $table->longText('token_abi')->nullable()->after('contract_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pepe_settings') && Schema::hasColumn('pepe_settings', 'token_abi')) {
            Schema::table('pepe_settings', function (Blueprint $table) {
                $table->dropColumn('token_abi');
            });
        }
    }
};
