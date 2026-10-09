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
        Schema::table('direct_incomes', function (Blueprint $table) {
            if (! Schema::hasColumn('direct_incomes', 'level')) {
                $table->integer('level')->nullable()->after('memberid');
            }
            if (! Schema::hasColumn('direct_incomes', 'rate')) {
                $table->decimal('rate', 5, 2)->nullable()->default(0.00)->after('package');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direct_incomes', function (Blueprint $table) {
            if (Schema::hasColumn('direct_incomes', 'rate')) {
                $table->dropColumn('rate');
            }
            if (Schema::hasColumn('direct_incomes', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
