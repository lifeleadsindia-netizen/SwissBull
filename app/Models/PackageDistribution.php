<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDistribution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_distributions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trading_wallet',
        'referral_bonus',
        'team_trading_profit',
        'team_performance_bonus',
        'hero_of_the_month',
        'lock_days',
        'status',
        'capping',
        'package_1_rate',
        'package_2_rate',
        'package_3_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trading_wallet' => 'decimal:2',
        'referral_bonus' => 'decimal:2',
        'team_trading_profit' => 'decimal:2',
        'team_performance_bonus' => 'decimal:2',
        'hero_of_the_month' => 'decimal:2',
        'lock_days' => 'integer',
        'status' => 'string',
        'capping' => 'float',
        'package_1_rate' => 'float',
        'package_2_rate' => 'float',
        'package_3_rate' => 'float',
    ];

    public function getP2pWalletAttribute(): float
    {
        return (float) ($this->attributes['trading_wallet'] ?? (PackagePlan::value('trading_wallet_percent') ?? 0.0));
    }

    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $setting = static::create([
                'trading_wallet' => (float) (PackagePlan::value('trading_wallet_percent') ?? 0.0),
                'hero_of_the_month' => 0.0,
                'lock_days' => (int) (PackagePlan::value('lock_days') ?? 0),
                'status' => 'on',
                'capping' => (float) (PackagePlan::value('max_return_percent') ?? 0.0),
                'package_1_rate' => (float) (PackagePlan::where('id', 1)->value('return_percent') ?? 0.0),
                'package_2_rate' => (float) (PackagePlan::where('id', 2)->value('return_percent') ?? 0.0),
                'package_3_rate' => (float) (PackagePlan::where('id', 3)->value('return_percent') ?? 0.0),
            ]);
        }

        return $setting;
    }

    public static function getDefaultLockDays(): int
    {
        $setting = static::first();

        return (int) ($setting?->lock_days ?? (PackagePlan::value('lock_days') ?? 0));
    }

    /**
     * Obsolete: Default maximum withdrawal percentage (Feature disabled: Max Withdrawal Percentage removed).
     * Retained as safe fallback returning 100.00 without querying database column.
     */
    public static function getDefaultWithdrawalPercent(): float
    {
        return 100.00;
    }

    public static function getStatus(): string
    {
        return strtolower((string) (static::getActiveSetting()->status ?? 'on'));
    }

    public function isOn(): bool
    {
        $status = strtolower((string) ($this->status ?? 'on'));

        return $status === 'on' || $status === 'lock';
    }

    public function isOff(): bool
    {
        $status = strtolower((string) ($this->status ?? 'on'));

        return $status === 'off' || $status === 'unlock';
    }

    public function isLocked(): bool
    {
        return $this->isOn();
    }

    public function isUnlocked(): bool
    {
        return $this->isOff();
    }

    public function getCappingPercentAttribute(): float
    {
        return (float) ($this->attributes['capping'] ?? $this->attributes['capping_percent'] ?? (PackagePlan::value('max_return_percent') ?? 0.0));
    }

    public static function getCapping(): float
    {
        $setting = static::first();
        if ($setting && isset($setting->capping) && $setting->capping !== null) {
            return (float) $setting->capping;
        }

        $planCapping = PackagePlan::whereNotNull('max_return_percent')->value('max_return_percent');

        return $planCapping !== null ? (float) $planCapping : 0.0;
    }

    public static function getCappingPercent(): float
    {
        return static::getCapping();
    }

    public static function getRateForPackage(int $packageId): float
    {
        $plan = PackagePlan::find($packageId);
        if ($plan && (float) $plan->return_percent > 0) {
            return (float) $plan->return_percent;
        }

        $setting = static::first();
        if ($setting) {
            $field = 'package_'.$packageId.'_rate';
            if (isset($setting->{$field}) && (float) $setting->{$field} > 0) {
                return (float) $setting->{$field};
            }
        }

        return (float) (PackagePlan::where('id', $packageId)->value('return_percent') ?? 0.0);
    }

    public static function getDistributionConfig(): array
    {
        $config = static::getActiveSetting();

        $walletPercent = (float) ($config->p2p_wallet ?? $config->trading_wallet ?? (PackagePlan::value('trading_wallet_percent') ?? 0.0));

        return [
            'p2p_wallet' => $walletPercent,
            'trading_wallet' => $walletPercent,
            'referral_bonus' => (float) ($config->referral_bonus ?? 0.0),
            'team_trading_profit' => (float) ($config->team_trading_profit ?? 0.0),
            'team_performance_bonus' => (float) ($config->team_performance_bonus ?? 0.0),
            'hero_of_the_month' => (float) ($config->hero_of_the_month ?? 0.0),
            'lock_days' => (int) ($config->lock_days ?? 0),
            'status' => (string) ($config->status ?? 'on'),
            'capping' => (float) ($config->capping ?? (PackagePlan::value('max_return_percent') ?? 0.0)),
            'capping_percent' => (float) ($config->capping ?? (PackagePlan::value('max_return_percent') ?? 0.0)),
        ];
    }
}
