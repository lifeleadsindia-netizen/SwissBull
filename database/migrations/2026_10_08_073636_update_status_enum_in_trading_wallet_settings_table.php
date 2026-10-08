<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert status column to VARCHAR temporarily so data can be safely converted
        DB::statement("ALTER TABLE `trading_wallet_settings` MODIFY COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'on'");

        // 2. Convert existing values: lock -> on, unlock -> off
        DB::statement("UPDATE `trading_wallet_settings` SET `status` = 'on' WHERE `status` = 'lock'");
        DB::statement("UPDATE `trading_wallet_settings` SET `status` = 'off' WHERE `status` = 'unlock'");

        // 3. Alter status column to ENUM('on', 'off') with default 'on'
        DB::statement("ALTER TABLE `trading_wallet_settings` MODIFY COLUMN `status` ENUM('on', 'off') NOT NULL DEFAULT 'on'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `trading_wallet_settings` MODIFY COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'lock'");
        DB::statement("UPDATE `trading_wallet_settings` SET `status` = 'lock' WHERE `status` = 'on'");
        DB::statement("UPDATE `trading_wallet_settings` SET `status` = 'unlock' WHERE `status` = 'off'");
        DB::statement("ALTER TABLE `trading_wallet_settings` MODIFY COLUMN `status` ENUM('lock', 'unlock') NOT NULL DEFAULT 'lock'");
    }
};
