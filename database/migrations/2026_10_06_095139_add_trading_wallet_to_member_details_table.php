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
        if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'trading_wallet')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->decimal('trading_wallet', 15, 2)->default(0)->after('p2p_wallet');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_details') && Schema::hasColumn('member_details', 'trading_wallet')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->dropColumn('trading_wallet');
            });
        }
    }
};
