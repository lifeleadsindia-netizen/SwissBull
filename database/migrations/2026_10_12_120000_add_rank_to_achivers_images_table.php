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
        if (Schema::hasTable('achivers_images') && ! Schema::hasColumn('achivers_images', 'rank')) {
            Schema::table('achivers_images', function (Blueprint $table) {
                $table->integer('rank')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('achivers_images') && Schema::hasColumn('achivers_images', 'rank')) {
            Schema::table('achivers_images', function (Blueprint $table) {
                $table->dropColumn('rank');
            });
        }
    }
};
