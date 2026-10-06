<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingWalletSetting extends Model
{
    protected $table = 'trading_wallet_settings';

    protected $guarded = [];

    protected $casts = [
        'lock_days' => 'integer',
        'withdrawal_percent' => 'float',
    ];

    /**
     * Get or create the singleton Trading Wallet settings row.
     */
    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $setting = static::create([
                'lock_days' => 30,
                'withdrawal_percent' => 100.00,
            ]);
        }

        return $setting;
    }

    /**
     * Get active default lock period in days.
     */
    public static function getDefaultLockDays(): int
    {
        return (int) (static::getActiveSetting()->lock_days ?? 30);
    }

    /**
     * Get active default post-lock maximum withdrawal percentage.
     */
    public static function getDefaultWithdrawalPercent(): float
    {
        return (float) (static::getActiveSetting()->withdrawal_percent ?? 100.00);
    }
}
