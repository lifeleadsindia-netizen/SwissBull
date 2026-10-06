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
        if (! Schema::hasTable('whatsapp_referrals')) {
            Schema::create('whatsapp_referrals', function (Blueprint $table) {
                $table->id();
                $table->string('member_id');
                $table->string('mobile_number');
                $table->unsignedBigInteger('message_id')->nullable();
                $table->decimal('reward_amount', 15, 2)->default(1000.00);
                $table->string('status')->default('Completed');
                $table->boolean('reward_given')->default(true);
                $table->timestamps();

                $table->index('member_id');
                $table->index('mobile_number');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_referrals');
    }
};
