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
        if (! Schema::hasTable('package_plans')) {
            Schema::create('package_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('package_range', 50)->unique();
                $table->decimal('min_amount', 15, 2);
                $table->decimal('max_amount', 15, 2)->nullable();
                $table->decimal('trading_wallet_percent', 5, 2)->default(70.00);
                $table->decimal('return_percent', 8, 2)->default(5.00);
                $table->decimal('max_return_percent', 8, 2)->default(200.00);
                $table->unsignedInteger('lock_days')->default(30);
                $table->unsignedInteger('duration_days')->default(1200);
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->timestamps();
            });

            // Seed initial 3 package ranges
            DB::table('package_plans')->insert([
                [
                    'name' => 'Package 1 (50 – 500 USDT)',
                    'package_range' => '50-500',
                    'min_amount' => 50.00,
                    'max_amount' => 500.00,
                    'trading_wallet_percent' => 70.00,
                    'return_percent' => 5.00,
                    'max_return_percent' => 200.00,
                    'lock_days' => 30,
                    'duration_days' => 1200,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Package 2 (600 – 5,000 USDT)',
                    'package_range' => '600-5000',
                    'min_amount' => 600.00,
                    'max_amount' => 5000.00,
                    'trading_wallet_percent' => 70.00,
                    'return_percent' => 7.00,
                    'max_return_percent' => 200.00,
                    'lock_days' => 30,
                    'duration_days' => 1200,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Package 3 (6,000+ USDT)',
                    'package_range' => '6000+',
                    'min_amount' => 6000.00,
                    'max_amount' => null,
                    'trading_wallet_percent' => 70.00,
                    'return_percent' => 10.00,
                    'max_return_percent' => 200.00,
                    'lock_days' => 30,
                    'duration_days' => 1200,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Synchronize trading_wallet column in package_distributions if missing
        if (Schema::hasTable('package_distributions') && ! Schema::hasColumn('package_distributions', 'trading_wallet')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                $table->decimal('trading_wallet', 5, 2)->default(70.00)->after('id');
            });

            // Populate existing row if present
            DB::table('package_distributions')->whereNull('trading_wallet')->orWhere('trading_wallet', 0)->update([
                'trading_wallet' => DB::raw('COALESCE(p2p_wallet, 70.00)'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_plans');

        if (Schema::hasTable('package_distributions') && Schema::hasColumn('package_distributions', 'trading_wallet')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                $table->dropColumn('trading_wallet');
            });
        }
    }
};
