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
        if (Schema::hasTable('pepe_reward_logs')) {
            Schema::table('pepe_reward_logs', function (Blueprint $table) {
                if (! Schema::hasColumn('pepe_reward_logs', 'reward_type')) {
                    $table->string('reward_type', 50)->default('message')->after('reward_amount');
                    $table->index('reward_type');
                }
                if (! Schema::hasColumn('pepe_reward_logs', 'referred_member_id')) {
                    $table->string('referred_member_id', 100)->nullable()->after('reward_type');
                    $table->index('referred_member_id');
                }
                if (! Schema::hasColumn('pepe_reward_logs', 'description')) {
                    $table->string('description', 255)->nullable()->after('referred_member_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pepe_reward_logs')) {
            Schema::table('pepe_reward_logs', function (Blueprint $table) {
                if (Schema::hasColumn('pepe_reward_logs', 'reward_type')) {
                    $table->dropColumn('reward_type');
                }
                if (Schema::hasColumn('pepe_reward_logs', 'referred_member_id')) {
                    $table->dropColumn('referred_member_id');
                }
                if (Schema::hasColumn('pepe_reward_logs', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};
