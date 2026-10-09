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
        'p2p_wallet',
        'trading_wallet',
        'referral_bonus',
        'team_trading_profit',
        'team_performance_bonus',
        'hero_of_the_month',
        'lock_days',
        'withdrawal_percent',
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
        'p2p_wallet' => 'decimal:2',
        'trading_wallet' => 'decimal:2',
        'referral_bonus' => 'decimal:2',
        'team_trading_profit' => 'decimal:2',
        'team_performance_bonus' => 'decimal:2',
        'hero_of_the_month' => 'decimal:2',
        'lock_days' => 'integer',
        'withdrawal_percent' => 'float',
        'status' => 'string',
        'capping' => 'float',
        'package_1_rate' => 'float',
        'package_2_rate' => 'float',
        'package_3_rate' => 'float',
    ];

    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $setting = static::create([
                'p2p_wallet' => 70.00,
                'trading_wallet' => 70.00,
                'hero_of_the_month' => 2.00,
                'lock_days' => 90,
                'withdrawal_percent' => 100.00,
                'status' => 'on',
                'capping' => 200.00,
                'package_1_rate' => 5.00,
                'package_2_rate' => 7.00,
                'package_3_rate' => 11.00,
            ]);
        }

        return $setting;
    }

    public static function getDefaultLockDays(): int
    {
        return (int) (static::getActiveSetting()->lock_days ?? 90);
    }

    public static function getDefaultWithdrawalPercent(): float
    {
        return (float) (static::getActiveSetting()->withdrawal_percent ?? 100.00);
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
        return (float) ($this->attributes['capping'] ?? $this->attributes['capping_percent'] ?? 200.00);
    }

    public static function getCapping(): float
    {
        $setting = static::first();
        if ($setting && (float) ($setting->capping ?? 0) > 0) {
            return (float) $setting->capping;
        }

        $planCapping = PackagePlan::whereNotNull('max_return_percent')->where('max_return_percent', '>', 0)->value('max_return_percent');

        return $planCapping ? (float) $planCapping : 200.00;
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

        return 5.00;
    }

    public static function getDistributionConfig(): array
    {
        $config = static::getActiveSetting();

        $walletPercent = (float) ($config->p2p_wallet ?? $config->trading_wallet ?? 70.0);

        return [
            'p2p_wallet' => $walletPercent,
            'trading_wallet' => $walletPercent,
            'referral_bonus' => (float) ($config->referral_bonus ?? 10.0),
            'team_trading_profit' => (float) ($config->team_trading_profit ?? 8.0),
            'team_performance_bonus' => (float) ($config->team_performance_bonus ?? 10.0),
            'hero_of_the_month' => (float) ($config->hero_of_the_month ?? 2.0),
            'lock_days' => (int) ($config->lock_days ?? 90),
            'withdrawal_percent' => (float) ($config->withdrawal_percent ?? 100.0),
            'status' => (string) ($config->status ?? 'on'),
            'capping' => (float) ($config->capping ?? 200.0),
            'capping_percent' => (float) ($config->capping ?? 200.0),
        ];
    }
}
