<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('package_details') && Schema::hasColumn('package_details', 'memberid')) {
            DB::statement('ALTER TABLE `package_details` MODIFY `memberid` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('package_details') && Schema::hasColumn('package_details', 'memberid')) {
            DB::statement('ALTER TABLE `package_details` MODIFY `memberid` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL');
        }
    }
};
