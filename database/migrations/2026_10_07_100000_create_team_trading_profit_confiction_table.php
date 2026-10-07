<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('team_trading_profit_confiction')) {
            Schema::create('team_trading_profit_confiction', function (Blueprint $table) {
                $table->id();
                $table->decimal('level_1_rate', 8, 2)->default(5.00);
                $table->decimal('level_2_rate', 8, 2)->default(5.00);
                $table->decimal('level_3_rate', 8, 2)->default(4.00);
                $table->decimal('level_4_rate', 8, 2)->default(4.00);
                $table->decimal('level_5_rate', 8, 2)->default(3.00);
                $table->decimal('level_6_rate', 8, 2)->default(3.00);
                $table->decimal('level_7_rate', 8, 2)->default(2.00);
                $table->decimal('level_8_rate', 8, 2)->default(2.00);
                $table->decimal('level_9_rate', 8, 2)->default(1.00);
                $table->decimal('level_10_rate', 8, 2)->default(1.00);
                $table->timestamps();
            });

            // Seed initial row with configured default rates for all 10 levels
            DB::table('team_trading_profit_confiction')->insert([
                'id' => 1,
                'level_1_rate' => 5.00,
                'level_2_rate' => 5.00,
                'level_3_rate' => 4.00,
                'level_4_rate' => 4.00,
                'level_5_rate' => 3.00,
                'level_6_rate' => 3.00,
                'level_7_rate' => 2.00,
                'level_8_rate' => 2.00,
                'level_9_rate' => 1.00,
                'level_10_rate' => 1.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_trading_profit_confiction');
    }
};
