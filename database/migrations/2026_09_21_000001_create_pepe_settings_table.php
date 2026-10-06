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
        if (! Schema::hasTable('pepe_settings')) {
            Schema::create('pepe_settings', function (Blueprint $table) {
                $table->id();
                $table->string('contract_address', 64)->default('0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00');
                $table->string('token_symbol', 20)->default('PEPE');
                $table->string('token_name', 100)->default('PEPE BEP-20');
                $table->unsignedTinyInteger('token_decimals')->default(18);
                $table->unsignedInteger('chain_id')->default(56);
                $table->string('network_name', 100)->default('BNB Smart Chain (BEP20)');
                $table->string('rpc_url', 255)->default('https://bsc-dataseed.binance.org/');
                $table->string('explorer_url', 255)->default('https://bscscan.com');
                $table->string('disbursement_wallet', 64)->nullable();
                $table->text('disbursement_key')->nullable();
                $table->unsignedInteger('gas_limit')->default(150000);
                $table->decimal('min_redeem', 15, 2)->default(1.00);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pepe_settings');
    }
};
