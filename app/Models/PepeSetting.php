<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PepeSetting extends Model
{
    use HasFactory;

    protected $table = 'pepe_settings';

    protected $fillable = [
        'contract_address',
        'claim_contract_address',
        'token_abi',
        'claim_contract_abi',
        'token_symbol',
        'token_name',
        'token_decimals',
        'chain_id',
        'network_name',
        'rpc_url',
        'explorer_url',
        'disbursement_wallet',
        'disbursement_key',
        'signer_public_address',
        'signer_private_key',
        'gas_limit',
        'min_redeem',
        'is_active',
    ];

    protected $casts = [
        'token_decimals' => 'integer',
        'chain_id' => 'integer',
        'gas_limit' => 'integer',
        'min_redeem' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Default BEP-20 Standard Token ABI JSON string
     */
    public static function getDefaultAbi(): string
    {
        return json_encode([
            [
                'inputs' => [],
                'name' => 'decimals',
                'outputs' => [['internalType' => 'uint8', 'name' => '', 'type' => 'uint8']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'symbol',
                'outputs' => [['internalType' => 'string', 'name' => '', 'type' => 'string']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [['internalType' => 'address', 'name' => 'account', 'type' => 'address']],
                'name' => 'balanceOf',
                'outputs' => [['internalType' => 'uint256', 'name' => '', 'type' => 'uint256']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [
                    ['internalType' => 'address', 'name' => 'to', 'type' => 'address'],
                    ['internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                ],
                'name' => 'transfer',
                'outputs' => [['internalType' => 'bool', 'name' => '', 'type' => 'bool']],
                'stateMutability' => 'nonpayable',
                'type' => 'function',
            ],
            [
                'inputs' => [
                    ['internalType' => 'address', 'name' => 'from', 'type' => 'address'],
                    ['internalType' => 'address', 'name' => 'to', 'type' => 'address'],
                    ['internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                ],
                'name' => 'transferFrom',
                'outputs' => [['internalType' => 'bool', 'name' => '', 'type' => 'bool']],
                'stateMutability' => 'nonpayable',
                'type' => 'function',
            ],
            [
                'inputs' => [
                    ['internalType' => 'address', 'name' => 'spender', 'type' => 'address'],
                    ['internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                ],
                'name' => 'approve',
                'outputs' => [['internalType' => 'bool', 'name' => '', 'type' => 'bool']],
                'stateMutability' => 'nonpayable',
                'type' => 'function',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Default PepeClaimDistributor Smart Contract ABI JSON string
     */
    public static function getDefaultClaimAbi(): string
    {
        return json_encode([
            [
                'inputs' => [
                    ['internalType' => 'address', 'name' => '_token', 'type' => 'address'],
                    ['internalType' => 'address', 'name' => '_signer', 'type' => 'address'],
                ],
                'stateMutability' => 'nonpayable',
                'type' => 'constructor',
            ],
            [
                'anonymous' => false,
                'inputs' => [
                    ['indexed' => true, 'internalType' => 'address', 'name' => 'recipient', 'type' => 'address'],
                    ['indexed' => false, 'internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                    ['indexed' => true, 'internalType' => 'uint256', 'name' => 'nonce', 'type' => 'uint256'],
                    ['indexed' => false, 'internalType' => 'uint256', 'name' => 'timestamp', 'type' => 'uint256'],
                ],
                'name' => 'TokensClaimed',
                'type' => 'event',
            ],
            [
                'inputs' => [
                    ['internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                    ['internalType' => 'uint256', 'name' => 'nonce', 'type' => 'uint256'],
                    ['internalType' => 'uint256', 'name' => 'deadline', 'type' => 'uint256'],
                    ['internalType' => 'bytes', 'name' => 'signature', 'type' => 'bytes'],
                ],
                'name' => 'claim',
                'outputs' => [],
                'stateMutability' => 'nonpayable',
                'type' => 'function',
            ],
            [
                'inputs' => [
                    ['internalType' => 'uint256', 'name' => 'amount', 'type' => 'uint256'],
                ],
                'name' => 'depositTokens',
                'outputs' => [],
                'stateMutability' => 'nonpayable',
                'type' => 'function',
            ],
            [
                'inputs' => [
                    ['internalType' => 'uint256', 'name' => 'nonce', 'type' => 'uint256'],
                ],
                'name' => 'isNonceClaimed',
                'outputs' => [['internalType' => 'bool', 'name' => '', 'type' => 'bool']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'getContractTokenBalance',
                'outputs' => [['internalType' => 'uint256', 'name' => '', 'type' => 'uint256']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'signer',
                'outputs' => [['internalType' => 'address', 'name' => '', 'type' => 'address']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'token',
                'outputs' => [['internalType' => 'address', 'name' => '', 'type' => 'address']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'owner',
                'outputs' => [['internalType' => 'address', 'name' => '', 'type' => 'address']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'paused',
                'outputs' => [['internalType' => 'bool', 'name' => '', 'type' => 'bool']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
            [
                'inputs' => [],
                'name' => 'totalClaimed',
                'outputs' => [['internalType' => 'uint256', 'name' => '', 'type' => 'uint256']],
                'stateMutability' => 'view',
                'type' => 'function',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get effective token ABI
     */
    public function getEffectiveAbi(): string
    {
        return ! empty($this->token_abi) ? $this->token_abi : self::getDefaultAbi();
    }

    /**
     * Get effective Claim Distributor ABI
     */
    public function getEffectiveClaimAbi(): string
    {
        return ! empty($this->claim_contract_abi) ? $this->claim_contract_abi : self::getDefaultClaimAbi();
    }

    /**
     * Get the active settings record or create default record.
     */
    public static function getSettings(): self
    {
        $setting = self::first();

        if (! $setting) {
            $setting = self::create([
                'contract_address' => '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00',
                'claim_contract_address' => null,
                'token_abi' => self::getDefaultAbi(),
                'claim_contract_abi' => self::getDefaultClaimAbi(),
                'token_symbol' => 'PEPE',
                'token_name' => 'PEPE BEP-20',
                'token_decimals' => 18,
                'chain_id' => 56,
                'network_name' => 'BNB Smart Chain (BEP20)',
                'rpc_url' => 'https://bsc-dataseed.binance.org/',
                'explorer_url' => 'https://bscscan.com',
                'disbursement_wallet' => null,
                'disbursement_key' => null,
                'signer_public_address' => '0xA60d48ae1FE0eFc155E6461c5fb0509d4bBf7284',
                'signer_private_key' => '0x75e23491be9f35b67f69fd116717a76bda81c02cf92696ade58e3fe48649bc06',
                'gas_limit' => 180000,
                'min_redeem' => 1.00,
                'is_active' => true,
            ]);
        }

        $dirty = false;
        if (empty($setting->token_abi)) {
            $setting->token_abi = self::getDefaultAbi();
            $dirty = true;
        }
        if (empty($setting->claim_contract_abi)) {
            $setting->claim_contract_abi = self::getDefaultClaimAbi();
            $dirty = true;
        }
        if (empty($setting->signer_private_key)) {
            $setting->signer_public_address = '0xA60d48ae1FE0eFc155E6461c5fb0509d4bBf7284';
            $setting->signer_private_key = '0x75e23491be9f35b67f69fd116717a76bda81c02cf92696ade58e3fe48649bc06';
            $dirty = true;
        }

        if ($dirty) {
            $setting->save();
        }

        return $setting;
    }
}
