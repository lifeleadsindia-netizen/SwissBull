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
        'status' => 'string',
    ];

    /**
     * Get or create the singleton Trading Wallet settings row.
     */
    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $setting = static::create([
                'lock_days' => 90,
                'withdrawal_percent' => 100.00,
                'status' => 'on',
            ]);
        }

        return $setting;
    }

    /**
     * Get active default lock period in days.
     */
    public static function getDefaultLockDays(): int
    {
        return (int) (static::getActiveSetting()->lock_days ?? 90);
    }

    /**
     * Get active default post-lock maximum withdrawal percentage.
     */
    public static function getDefaultWithdrawalPercent(): float
    {
        return (float) (static::getActiveSetting()->withdrawal_percent ?? 100.00);
    }

    /**
     * Get current status ('on' or 'off').
     */
    public static function getStatus(): string
    {
        return strtolower((string) (static::getActiveSetting()->status ?? 'on'));
    }

    /**
     * Check if status is set to on.
     */
    public function isOn(): bool
    {
        $status = strtolower((string) ($this->status ?? 'on'));

        return $status === 'on' || $status === 'lock';
    }

    /**
     * Check if status is set to off.
     */
    public function isOff(): bool
    {
        $status = strtolower((string) ($this->status ?? 'on'));

        return $status === 'off' || $status === 'unlock';
    }

    /**
     * Check if status is set to on (legacy alias for isLocked).
     */
    public function isLocked(): bool
    {
        return $this->isOn();
    }

    /**
     * Check if status is set to off (legacy alias for isUnlocked).
     */
    public function isUnlocked(): bool
    {
        return $this->isOff();
    }
}
