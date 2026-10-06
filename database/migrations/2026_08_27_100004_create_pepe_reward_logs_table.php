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
        if (! Schema::hasTable('pepe_reward_logs')) {
            Schema::create('pepe_reward_logs', function (Blueprint $table) {
                $table->id();
                $table->string('member_id');
                $table->string('mobile_number');
                $table->decimal('reward_amount', 15, 2);
                $table->unsignedBigInteger('message_id')->nullable();
                $table->date('reward_date');
                $table->timestamps();

                $table->index('member_id');
                $table->index('reward_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pepe_reward_logs');
    }
};
