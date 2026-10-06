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
        if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'pepe_wallet')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->decimal('pepe_wallet', 15, 2)->default(0)->after('wallet');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_details') && Schema::hasColumn('member_details', 'pepe_wallet')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->dropColumn('pepe_wallet');
            });
        }
    }
};
