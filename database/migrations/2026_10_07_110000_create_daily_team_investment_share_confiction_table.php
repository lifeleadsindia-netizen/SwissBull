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
        if (! Schema::hasTable('daily_team_investment_share_confiction')) {
            Schema::create('daily_team_investment_share_confiction', function (Blueprint $table) {
                $table->id();
                $table->decimal('level_1_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_1_directs')->default(4);
                $table->decimal('level_2_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_2_directs')->default(2);
                $table->decimal('level_3_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_3_directs')->default(2);
                $table->decimal('level_4_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_4_directs')->default(2);
                $table->decimal('level_5_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_5_directs')->default(2);
                $table->decimal('level_6_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_6_directs')->default(2);
                $table->decimal('level_7_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_7_directs')->default(2);
                $table->decimal('level_8_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_8_directs')->default(2);
                $table->decimal('level_9_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_9_directs')->default(2);
                $table->decimal('level_10_rate', 8, 2)->default(1.00);
                $table->unsignedInteger('level_10_directs')->default(2);
                $table->timestamps();
            });

            // Seed initial row with configured default rates and direct requirements
            DB::table('daily_team_investment_share_confiction')->insert([
                'id' => 1,
                'level_1_rate' => 1.00,
                'level_1_directs' => 4,
                'level_2_rate' => 1.00,
                'level_2_directs' => 2,
                'level_3_rate' => 1.00,
                'level_3_directs' => 2,
                'level_4_rate' => 1.00,
                'level_4_directs' => 2,
                'level_5_rate' => 1.00,
                'level_5_directs' => 2,
                'level_6_rate' => 1.00,
                'level_6_directs' => 2,
                'level_7_rate' => 1.00,
                'level_7_directs' => 2,
                'level_8_rate' => 1.00,
                'level_8_directs' => 2,
                'level_9_rate' => 1.00,
                'level_9_directs' => 2,
                'level_10_rate' => 1.00,
                'level_10_directs' => 2,
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
        Schema::dropIfExists('daily_team_investment_share_confiction');
    }
};
