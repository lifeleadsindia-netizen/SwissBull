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
        if (! Schema::hasTable('hero_of_the_month_rewards')) {
            Schema::create('hero_of_the_month_rewards', function (Blueprint $table) {
                $table->id();
                $table->string('month', 7)->index(); // YYYY-MM
                $table->string('memberid', 50)->index();
                $table->decimal('direct_business', 15, 2)->default(0.00);
                $table->decimal('total_pool', 15, 2)->default(0.00);
                $table->decimal('pool_percentage', 5, 2)->default(2.00);
                $table->unsignedInteger('total_winners')->default(1);
                $table->decimal('prize_amount', 15, 2)->default(0.00);
                $table->string('status', 20)->default('Paid');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_of_the_month_rewards');
    }
};
