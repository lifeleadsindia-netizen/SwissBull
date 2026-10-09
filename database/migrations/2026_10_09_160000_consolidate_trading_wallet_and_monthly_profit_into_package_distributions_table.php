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
        // 1. Add consolidated columns to package_distributions table
        if (Schema::hasTable('package_distributions')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                if (! Schema::hasColumn('package_distributions', 'lock_days')) {
                    $table->integer('lock_days')->default(90)->after('hero_of_the_month');
                }
                if (! Schema::hasColumn('package_distributions', 'withdrawal_percent')) {
                    $table->decimal('withdrawal_percent', 8, 2)->default(100.00)->after('lock_days');
                }
                if (! Schema::hasColumn('package_distributions', 'status')) {
                    $table->string('status', 20)->default('on')->after('withdrawal_percent');
                }
                if (! Schema::hasColumn('package_distributions', 'capping')) {
                    $table->decimal('capping', 8, 2)->default(200.00)->after('status');
                }
                if (! Schema::hasColumn('package_distributions', 'package_1_rate')) {
                    $table->decimal('package_1_rate', 8, 2)->default(5.00)->after('capping');
                }
                if (! Schema::hasColumn('package_distributions', 'package_2_rate')) {
                    $table->decimal('package_2_rate', 8, 2)->default(7.00)->after('package_1_rate');
                }
                if (! Schema::hasColumn('package_distributions', 'package_3_rate')) {
                    $table->decimal('package_3_rate', 8, 2)->default(11.00)->after('package_2_rate');
                }
            });
        }

        // 2. Migrate existing values safely into package_distributions
        $updates = [];

        // Migrate from trading_wallet_settings
        if (Schema::hasTable('trading_wallet_settings')) {
            $twSetting = DB::table('trading_wallet_settings')->first();
            if ($twSetting) {
                $updates['lock_days'] = $twSetting->lock_days ?? 90;
                $updates['withdrawal_percent'] = $twSetting->withdrawal_percent ?? 100.00;
                $updates['status'] = strtolower((string) ($twSetting->status ?? 'on'));
            }
        }

        // Migrate from monthly_trading_profit_confiction and package_plans
        if (Schema::hasTable('monthly_trading_profit_confiction')) {
            $capping = DB::table('monthly_trading_profit_confiction')->where('capping_percent', '>', 0)->value('capping_percent');
            if (! $capping && Schema::hasTable('package_plans')) {
                $capping = DB::table('package_plans')->where('max_return_percent', '>', 0)->value('max_return_percent');
            }
            $updates['capping'] = $capping ?: 200.00;

            $p1 = DB::table('monthly_trading_profit_confiction')->where('package_id', 1)->value('rate');
            $p2 = DB::table('monthly_trading_profit_confiction')->where('package_id', 2)->value('rate');
            $p3 = DB::table('monthly_trading_profit_confiction')->where('package_id', 3)->value('rate');

            if (! $p1 && Schema::hasTable('package_plans')) {
                $p1 = DB::table('package_plans')->where('id', 1)->value('return_percent');
            }
            if (! $p2 && Schema::hasTable('package_plans')) {
                $p2 = DB::table('package_plans')->where('id', 2)->value('return_percent');
            }
            if (! $p3 && Schema::hasTable('package_plans')) {
                $p3 = DB::table('package_plans')->where('id', 3)->value('return_percent');
            }

            $updates['package_1_rate'] = $p1 ?: 5.00;
            $updates['package_2_rate'] = $p2 ?: 7.00;
            $updates['package_3_rate'] = $p3 ?: 11.00;
        }

        $dist = DB::table('package_distributions')->first();
        if ($dist) {
            DB::table('package_distributions')->where('id', $dist->id)->update($updates);
        } else {
            DB::table('package_distributions')->insert(array_merge([
                'p2p_wallet' => 70.00,
                'trading_wallet' => 70.00,
                'hero_of_the_month' => 2.00,
                'created_at' => now(),
                'updated_at' => now(),
            ], $updates));
        }

        // 3. Verify data exists in package_distributions before removing redundant tables
        $verified = DB::table('package_distributions')->first();
        if ($verified && isset($verified->lock_days) && (isset($verified->capping) || isset($verified->capping_percent))) {
            Schema::dropIfExists('trading_wallet_settings');
            Schema::dropIfExists('monthly_trading_profit_confiction');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create trading_wallet_settings if dropped
        if (! Schema::hasTable('trading_wallet_settings')) {
            Schema::create('trading_wallet_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('lock_days')->default(90);
                $table->decimal('withdrawal_percent', 8, 2)->default(100.00);
                $table->enum('status', ['on', 'off'])->default('on');
                $table->timestamps();
            });

            $dist = DB::table('package_distributions')->first();
            if ($dist) {
                DB::table('trading_wallet_settings')->insert([
                    'lock_days' => $dist->lock_days ?? 90,
                    'withdrawal_percent' => $dist->withdrawal_percent ?? 100.00,
                    'status' => in_array($dist->status ?? 'on', ['on', 'off']) ? $dist->status : 'on',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Re-create monthly_trading_profit_confiction if dropped
        if (! Schema::hasTable('monthly_trading_profit_confiction')) {
            Schema::create('monthly_trading_profit_confiction', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id')->nullable()->index();
                $table->decimal('rate', 8, 2)->default(0.00);
                $table->decimal('rate_percent', 8, 2)->default(0.00);
                $table->decimal('capping_percent', 8, 2)->default(200.00);
                $table->decimal('package_1_rate', 8, 2)->nullable()->default(5.00);
                $table->decimal('package_2_rate', 8, 2)->nullable()->default(7.00);
                $table->decimal('package_3_rate', 8, 2)->nullable()->default(11.00);
                $table->timestamps();
            });
        }

        // Drop consolidated columns from package_distributions
        if (Schema::hasTable('package_distributions')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                $cols = ['lock_days', 'withdrawal_percent', 'status', 'capping_percent', 'package_1_rate', 'package_2_rate', 'package_3_rate'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('package_distributions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
