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
        if (Schema::hasTable('staking_details')) {
            Schema::table('staking_details', function (Blueprint $table) {
                if (! Schema::hasColumn('staking_details', 'capping_percent')) {
                    $table->decimal('capping_percent', 8, 2)->default(200.00)->after('rate');
                }
                if (! Schema::hasColumn('staking_details', 'max_amount')) {
                    $table->decimal('max_amount', 15, 2)->default(0.00)->after('capping_percent');
                }
                if (! Schema::hasColumn('staking_details', 'total_earned')) {
                    $table->decimal('total_earned', 15, 2)->default(0.00)->after('max_amount');
                }
                if (! Schema::hasColumn('staking_details', 'txnid')) {
                    $table->string('txnid', 255)->nullable()->after('package');
                }
                if (! Schema::hasColumn('staking_details', 'order_id')) {
                    $table->string('order_id', 100)->nullable()->after('txnid');
                }
                if (! Schema::hasColumn('staking_details', 'activated_at')) {
                    $table->timestamp('activated_at')->nullable()->after('status');
                }
                if (! Schema::hasColumn('staking_details', 'last_roi_at')) {
                    $table->timestamp('last_roi_at')->nullable()->after('activated_at');
                }
                if (! Schema::hasColumn('staking_details', 'deactivated_at')) {
                    $table->timestamp('deactivated_at')->nullable()->after('last_roi_at');
                }
            });

            try {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE staking_details MODIFY total_installments INT(11) NOT NULL DEFAULT 0');
            } catch (\Throwable $e) {
                // Ignore if driver does not support or already modified
            }
        }

        if (Schema::hasTable('staking_incomes')) {
            Schema::table('staking_incomes', function (Blueprint $table) {
                if (! Schema::hasColumn('staking_incomes', 'staking_id')) {
                    $table->unsignedBigInteger('staking_id')->nullable()->after('id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('staking_details')) {
            Schema::table('staking_details', function (Blueprint $table) {
                $columns = ['capping_percent', 'max_amount', 'total_earned', 'txnid', 'order_id', 'activated_at', 'last_roi_at', 'deactivated_at'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('staking_details', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('staking_incomes')) {
            Schema::table('staking_incomes', function (Blueprint $table) {
                if (Schema::hasColumn('staking_incomes', 'staking_id')) {
                    $table->dropColumn('staking_id');
                }
            });
        }
    }
};
