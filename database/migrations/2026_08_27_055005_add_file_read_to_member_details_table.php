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
        if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'file_read')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->json('file_read')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_details') && Schema::hasColumn('member_details', 'file_read')) {
            Schema::table('member_details', function (Blueprint $table) {
                $table->dropColumn('file_read');
            });
        }
    }
};
