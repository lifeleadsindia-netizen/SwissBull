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
        if (Schema::hasTable('package_distributions')) {
            if (Schema::hasColumn('package_distributions', 'capping_percent') && ! Schema::hasColumn('package_distributions', 'capping')) {
                DB::statement('ALTER TABLE `package_distributions` CHANGE COLUMN `capping_percent` `capping` DECIMAL(8,2) NOT NULL DEFAULT 200.00');
            } elseif (! Schema::hasColumn('package_distributions', 'capping')) {
                Schema::table('package_distributions', function (Blueprint $table) {
                    $table->decimal('capping', 8, 2)->default(200.00)->after('status');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('package_distributions')) {
            if (Schema::hasColumn('package_distributions', 'capping') && ! Schema::hasColumn('package_distributions', 'capping_percent')) {
                DB::statement('ALTER TABLE `package_distributions` CHANGE COLUMN `capping` `capping_percent` DECIMAL(8,2) NOT NULL DEFAULT 200.00');
            }
        }
    }
};
