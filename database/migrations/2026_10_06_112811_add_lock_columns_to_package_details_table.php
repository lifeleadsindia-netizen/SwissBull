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
            if (! Schema::hasColumn('package_details', 'lock_days')) {
                $table->integer('lock_days')->default(0)->after('status');
            }
            if (! Schema::hasColumn('package_details', 'lock_applied_at')) {
                $table->timestamp('lock_applied_at')->nullable()->after('lock_days');
            }
            if (! Schema::hasColumn('package_details', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('lock_applied_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_details', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('package_details', 'locked_until')) {
                $cols[] = 'locked_until';
            }
            if (Schema::hasColumn('package_details', 'lock_applied_at')) {
                $cols[] = 'lock_applied_at';
            }
            if (Schema::hasColumn('package_details', 'lock_days')) {
                $cols[] = 'lock_days';
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
