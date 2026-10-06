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
        if (! Schema::hasTable('whatsapp_referral_messages')) {
            Schema::create('whatsapp_referral_messages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('status')->default('Active');
                $table->string('apply_to')->default('All Members');
                $table->text('target_member_ids')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_referral_messages');
    }
};
