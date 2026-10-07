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
        if (! Schema::hasTable('monthly_trading_profit_confiction')) {
            Schema::create('monthly_trading_profit_confiction', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id')->nullable()->index();
                $table->decimal('rate', 8, 2)->default(0.00);
                $table->decimal('rate_percent', 8, 2)->default(0.00);
                $table->decimal('capping_percent', 8, 2)->default(0.00);
                $table->decimal('package_1_rate', 8, 2)->nullable()->default(0.00);
                $table->decimal('package_2_rate', 8, 2)->nullable()->default(0.00);
                $table->decimal('package_3_rate', 8, 2)->nullable()->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_trading_profit_confiction');
    }
};
