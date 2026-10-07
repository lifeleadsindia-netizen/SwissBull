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
        Schema::table('package_details', function (Blueprint $table) {
            if (! Schema::hasColumn('package_details', 'package_range')) {
                $table->string('package_range', 50)->nullable()->after('package_type');
            }
            if (! Schema::hasColumn('package_details', 'invest_amount')) {
                $table->decimal('invest_amount', 15, 2)->nullable()->after('package_value');
            }
            if (! Schema::hasColumn('package_details', 'trading_wallet_amount')) {
                $table->decimal('trading_wallet_amount', 15, 2)->default(0.00)->after('invest_amount');
            }
            if (! Schema::hasColumn('package_details', 'activated_at')) {
                $table->datetime('activated_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('package_details', 'expires_at')) {
                $table->datetime('expires_at')->nullable()->after('activated_at');
            }
            if (! Schema::hasColumn('package_details', 'return_percent')) {
                $table->decimal('return_percent', 8, 2)->default(0.00)->after('expires_at');
            }
            if (! Schema::hasColumn('package_details', 'total_earning')) {
                $table->decimal('total_earning', 15, 2)->default(0.00)->after('return_percent');
            }
            if (! Schema::hasColumn('package_details', 'max_earning')) {
                $table->decimal('max_earning', 15, 2)->default(0.00)->after('total_earning');
            }
            if (! Schema::hasColumn('package_details', 'max_return_percent')) {
                $table->decimal('max_return_percent', 8, 2)->default(200.00)->after('max_earning');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_details', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach ([
                'package_range',
                'invest_amount',
                'trading_wallet_amount',
                'activated_at',
                'expires_at',
                'return_percent',
                'total_earning',
                'max_earning',
                'max_return_percent',
            ] as $column) {
                if (Schema::hasColumn('package_details', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
