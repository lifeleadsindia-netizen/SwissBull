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
        if (Schema::hasTable('pepe_settings')) {
            Schema::table('pepe_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('pepe_settings', 'claim_contract_address')) {
                    $table->string('claim_contract_address', 64)->nullable()->after('contract_address');
                }
                if (! Schema::hasColumn('pepe_settings', 'claim_contract_abi')) {
                    $table->longText('claim_contract_abi')->nullable()->after('token_abi');
                }
                if (! Schema::hasColumn('pepe_settings', 'signer_public_address')) {
                    $table->string('signer_public_address', 64)->nullable()->after('disbursement_wallet');
                }
                if (! Schema::hasColumn('pepe_settings', 'signer_private_key')) {
                    $table->text('signer_private_key')->nullable()->after('disbursement_key');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pepe_settings')) {
            Schema::table('pepe_settings', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('pepe_settings', 'claim_contract_address')) {
                    $columns[] = 'claim_contract_address';
                }
                if (Schema::hasColumn('pepe_settings', 'claim_contract_abi')) {
                    $columns[] = 'claim_contract_abi';
                }
                if (Schema::hasColumn('pepe_settings', 'signer_public_address')) {
                    $columns[] = 'signer_public_address';
                }
                if (Schema::hasColumn('pepe_settings', 'signer_private_key')) {
                    $columns[] = 'signer_private_key';
                }
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
