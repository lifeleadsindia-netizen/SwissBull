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
        if (! Schema::hasTable('referral_bonus_confiction')) {
            Schema::create('referral_bonus_confiction', function (Blueprint $table) {
                $table->id();
                $table->decimal('level_1_rate', 8, 2)->default(5.00);
                $table->decimal('level_2_rate', 8, 2)->default(3.00);
                $table->decimal('level_3_rate', 8, 2)->default(2.00);
                $table->timestamps();
            });

            // Seed initial row with default rates: Level 1 (5%), Level 2 (3%), Level 3 (2%)
            DB::table('referral_bonus_confiction')->insert([
                'id' => 1,
                'level_1_rate' => 5.00,
                'level_2_rate' => 3.00,
                'level_3_rate' => 2.00,
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
        Schema::dropIfExists('referral_bonus_confiction');
    }
};
