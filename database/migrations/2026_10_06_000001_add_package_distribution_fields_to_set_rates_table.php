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
        if (! Schema::hasTable('set_rates')) {
            Schema::create('set_rates', function (Blueprint $table) {
                $table->id();
                $table->decimal('bronze_rate', 10, 2)->default(10.00);
                $table->decimal('silver_rate', 10, 2)->default(20.00);
                $table->decimal('gold_rate', 10, 2)->default(30.00);
                $table->decimal('premium_rate', 10, 2)->default(40.00);
                $table->timestamps();
            });
        }

        Schema::table('set_rates', function (Blueprint $table) {
            // General / base distribution columns
            if (! Schema::hasColumn('set_rates', 'trading_wallet')) {
                $table->decimal('trading_wallet', 8, 2)->default(70.00);
            }
            if (! Schema::hasColumn('set_rates', 'referral_bonus')) {
                $table->decimal('referral_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'team_trading_profit')) {
                $table->decimal('team_trading_profit', 8, 2)->default(8.00);
            }
            if (! Schema::hasColumn('set_rates', 'team_performance_bonus')) {
                $table->decimal('team_performance_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'hero_of_the_month')) {
                $table->decimal('hero_of_the_month', 8, 2)->default(2.00);
            }

            // Bronze package specific columns
            if (! Schema::hasColumn('set_rates', 'bronze_trading_wallet')) {
                $table->decimal('bronze_trading_wallet', 8, 2)->default(70.00);
            }
            if (! Schema::hasColumn('set_rates', 'bronze_referral_bonus')) {
                $table->decimal('bronze_referral_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'bronze_team_trading_profit')) {
                $table->decimal('bronze_team_trading_profit', 8, 2)->default(8.00);
            }
            if (! Schema::hasColumn('set_rates', 'bronze_team_performance_bonus')) {
                $table->decimal('bronze_team_performance_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'bronze_hero_of_the_month')) {
                $table->decimal('bronze_hero_of_the_month', 8, 2)->default(2.00);
            }

            // Silver package specific columns
            if (! Schema::hasColumn('set_rates', 'silver_trading_wallet')) {
                $table->decimal('silver_trading_wallet', 8, 2)->default(70.00);
            }
            if (! Schema::hasColumn('set_rates', 'silver_referral_bonus')) {
                $table->decimal('silver_referral_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'silver_team_trading_profit')) {
                $table->decimal('silver_team_trading_profit', 8, 2)->default(8.00);
            }
            if (! Schema::hasColumn('set_rates', 'silver_team_performance_bonus')) {
                $table->decimal('silver_team_performance_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'silver_hero_of_the_month')) {
                $table->decimal('silver_hero_of_the_month', 8, 2)->default(2.00);
            }

            // Gold package specific columns
            if (! Schema::hasColumn('set_rates', 'gold_trading_wallet')) {
                $table->decimal('gold_trading_wallet', 8, 2)->default(70.00);
            }
            if (! Schema::hasColumn('set_rates', 'gold_referral_bonus')) {
                $table->decimal('gold_referral_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'gold_team_trading_profit')) {
                $table->decimal('gold_team_trading_profit', 8, 2)->default(8.00);
            }
            if (! Schema::hasColumn('set_rates', 'gold_team_performance_bonus')) {
                $table->decimal('gold_team_performance_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'gold_hero_of_the_month')) {
                $table->decimal('gold_hero_of_the_month', 8, 2)->default(2.00);
            }

            // Premium package specific columns
            if (! Schema::hasColumn('set_rates', 'premium_trading_wallet')) {
                $table->decimal('premium_trading_wallet', 8, 2)->default(70.00);
            }
            if (! Schema::hasColumn('set_rates', 'premium_referral_bonus')) {
                $table->decimal('premium_referral_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'premium_team_trading_profit')) {
                $table->decimal('premium_team_trading_profit', 8, 2)->default(8.00);
            }
            if (! Schema::hasColumn('set_rates', 'premium_team_performance_bonus')) {
                $table->decimal('premium_team_performance_bonus', 8, 2)->default(10.00);
            }
            if (! Schema::hasColumn('set_rates', 'premium_hero_of_the_month')) {
                $table->decimal('premium_hero_of_the_month', 8, 2)->default(2.00);
            }
        });

        // Initialize default configuration row if none exists or ensure default values
        $existing = DB::table('set_rates')->find(1);
        if (! $existing) {
            DB::table('set_rates')->insert([
                'id' => 1,
                'bronze_rate' => 10.00,
                'silver_rate' => 20.00,
                'gold_rate' => 30.00,
                'premium_rate' => 40.00,
                'trading_wallet' => 70.00,
                'referral_bonus' => 10.00,
                'team_trading_profit' => 8.00,
                'team_performance_bonus' => 10.00,
                'hero_of_the_month' => 2.00,
                'bronze_trading_wallet' => 70.00,
                'bronze_referral_bonus' => 10.00,
                'bronze_team_trading_profit' => 8.00,
                'bronze_team_performance_bonus' => 10.00,
                'bronze_hero_of_the_month' => 2.00,
                'silver_trading_wallet' => 70.00,
                'silver_referral_bonus' => 10.00,
                'silver_team_trading_profit' => 8.00,
                'silver_team_performance_bonus' => 10.00,
                'silver_hero_of_the_month' => 2.00,
                'gold_trading_wallet' => 70.00,
                'gold_referral_bonus' => 10.00,
                'gold_team_trading_profit' => 8.00,
                'gold_team_performance_bonus' => 10.00,
                'gold_hero_of_the_month' => 2.00,
                'premium_trading_wallet' => 70.00,
                'premium_referral_bonus' => 10.00,
                'premium_team_trading_profit' => 8.00,
                'premium_team_performance_bonus' => 10.00,
                'premium_hero_of_the_month' => 2.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Fill any newly added null/default fields without overwriting existing rates
            $updateData = [];
            $defaults = [
                'trading_wallet' => 70.00,
                'referral_bonus' => 10.00,
                'team_trading_profit' => 8.00,
                'team_performance_bonus' => 10.00,
                'hero_of_the_month' => 2.00,
                'bronze_trading_wallet' => 70.00,
                'bronze_referral_bonus' => 10.00,
                'bronze_team_trading_profit' => 8.00,
                'bronze_team_performance_bonus' => 10.00,
                'bronze_hero_of_the_month' => 2.00,
                'silver_trading_wallet' => 70.00,
                'silver_referral_bonus' => 10.00,
                'silver_team_trading_profit' => 8.00,
                'silver_team_performance_bonus' => 10.00,
                'silver_hero_of_the_month' => 2.00,
                'gold_trading_wallet' => 70.00,
                'gold_referral_bonus' => 10.00,
                'gold_team_trading_profit' => 8.00,
                'gold_team_performance_bonus' => 10.00,
                'gold_hero_of_the_month' => 2.00,
                'premium_trading_wallet' => 70.00,
                'premium_referral_bonus' => 10.00,
                'premium_team_trading_profit' => 8.00,
                'premium_team_performance_bonus' => 10.00,
                'premium_hero_of_the_month' => 2.00,
            ];

            foreach ($defaults as $col => $val) {
                if (! isset($existing->$col) || $existing->$col === null) {
                    $updateData[$col] = $val;
                }
            }

            if (! empty($updateData)) {
                DB::table('set_rates')->where('id', 1)->update($updateData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('set_rates')) {
            Schema::table('set_rates', function (Blueprint $table) {
                $columns = [
                    'trading_wallet', 'referral_bonus', 'team_trading_profit', 'team_performance_bonus', 'hero_of_the_month',
                    'bronze_trading_wallet', 'bronze_referral_bonus', 'bronze_team_trading_profit', 'bronze_team_performance_bonus', 'bronze_hero_of_the_month',
                    'silver_trading_wallet', 'silver_referral_bonus', 'silver_team_trading_profit', 'silver_team_performance_bonus', 'silver_hero_of_the_month',
                    'gold_trading_wallet', 'gold_referral_bonus', 'gold_team_trading_profit', 'gold_team_performance_bonus', 'gold_hero_of_the_month',
                    'premium_trading_wallet', 'premium_referral_bonus', 'premium_team_trading_profit', 'premium_team_performance_bonus', 'premium_hero_of_the_month',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('set_rates', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
