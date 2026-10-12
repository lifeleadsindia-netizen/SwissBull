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
        if (Schema::hasTable('package_distributions') && Schema::hasColumn('package_distributions', 'withdrawal_percent')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                $table->dropColumn('withdrawal_percent');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('package_distributions') && ! Schema::hasColumn('package_distributions', 'withdrawal_percent')) {
            Schema::table('package_distributions', function (Blueprint $table) {
                $table->decimal('withdrawal_percent', 8, 2)->default(100.00)->nullable()->after('lock_days');
            });
        }
    }
};
