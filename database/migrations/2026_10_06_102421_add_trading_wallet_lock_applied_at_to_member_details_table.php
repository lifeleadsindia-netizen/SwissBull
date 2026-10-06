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
        if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'trading_wallet_lock_applied_at')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->timestamp('trading_wallet_lock_applied_at')->nullable()->after('trading_wallet_lock_days');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_details') && Schema::hasColumn('member_details', 'trading_wallet_lock_applied_at')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->dropColumn('trading_wallet_lock_applied_at');
            });
        }
    }
};
