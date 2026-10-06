<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 1. Add pepe_wallet to member_details if not exists
if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'pepe_wallet')) {
    Schema::table('member_details', function (Blueprint $table) {
        $table->decimal('pepe_wallet', 15, 2)->default(0)->after('wallet');
    });
    echo "Added pepe_wallet column to member_details.\n";
} else {
    echo "pepe_wallet column already exists or member_details missing.\n";
}

// 2. Create whatsapp_referral_messages
if (! Schema::hasTable('whatsapp_referral_messages')) {
    Schema::create('whatsapp_referral_messages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('status')->default('Active'); // Active, Inactive
        $table->string('apply_to')->default('All Members'); // All Members, Specific Members
        $table->text('target_member_ids')->nullable(); // JSON or comma-separated member IDs
        $table->timestamps();
    });
    echo "Created whatsapp_referral_messages table.\n";
} else {
    echo "whatsapp_referral_messages table already exists.\n";
}

// 3. Create whatsapp_referrals
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
    echo "Created whatsapp_referrals table.\n";
} else {
    echo "whatsapp_referrals table already exists.\n";
}

// 4. Create pepe_reward_logs
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
    echo "Created pepe_reward_logs table.\n";
} else {
    echo "pepe_reward_logs table already exists.\n";
}
