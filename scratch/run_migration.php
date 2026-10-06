<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('member_details') && ! Schema::hasColumn('member_details', 'file_read')) {
    Schema::table('member_details', function (Blueprint $table) {
        $table->json('file_read')->nullable();
    });
    echo "Added file_read column to member_details successfully.\n";
} else {
    echo "Column file_read already exists or member_details table does not exist.\n";
}

if (! Schema::hasTable('notifications')) {
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // 'All Users' or 'Specific Member'
        $table->string('memberid')->nullable();
        $table->string('title');
        $table->text('message');
        $table->timestamps();
    });
    echo "Created notifications table successfully.\n";
} else {
    echo "notifications table already exists.\n";
}
