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
        if (Schema::hasTable('member_details')) {
            Schema::table('member_details', function (Blueprint $table) {
                if (! Schema::hasColumn('member_details', 'trading_wallet_lock_days')) {
                    $table->unsignedInteger('trading_wallet_lock_days')->default(0)->after('trading_wallet');
                }
                if (! Schema::hasColumn('member_details', 'trading_wallet_locked_until')) {
                    $table->timestamp('trading_wallet_locked_until')->nullable()->after('trading_wallet_lock_days');
                }
                if (! Schema::hasColumn('member_details', 'trading_wallet_withdrawal_percent')) {
                    $table->decimal('trading_wallet_withdrawal_percent', 5, 2)->default(100.00)->after('trading_wallet_locked_until');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_details')) {
            Schema::table('member_details', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('member_details', 'trading_wallet_lock_days')) {
                    $columnsToDrop[] = 'trading_wallet_lock_days';
                }
                if (Schema::hasColumn('member_details', 'trading_wallet_locked_until')) {
                    $columnsToDrop[] = 'trading_wallet_locked_until';
                }
                if (Schema::hasColumn('member_details', 'trading_wallet_withdrawal_percent')) {
                    $columnsToDrop[] = 'trading_wallet_withdrawal_percent';
                }
                if (! empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
