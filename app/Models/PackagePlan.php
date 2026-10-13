<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePlan extends Model
{
    protected $table = 'package_plans';

    protected $guarded = [];

    protected $casts = [
        'min_amount' => 'float',
        'max_amount' => 'float',
        'trading_wallet_percent' => 'float',
        'return_percent' => 'float',
        'max_return_percent' => 'float',
        'lock_days' => 'integer',
        'duration_days' => 'integer',
    ];

    /**
     * Scope: only active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Find plan by package_range string.
     */
    public static function findByRange(?string $range): ?self
    {
        if ($range === null || trim($range) === '') {
            return null;
        }

        $clean = strtolower(trim(str_replace([' ', '-', '_'], '', $range)));
        $normalized = match ($clean) {
            '50500', 'package1', 'pkg1' => '50-500',
            '6005000', 'package2', 'pkg2' => '600-5000',
            '6000+', '6000plus', '6000andabove', 'package3', 'pkg3' => '6000+',
            default => trim($range),
        };

        $plan = static::where('package_range', $normalized)->first();
        if ($plan) {
            return $plan;
        }

        return static::where('name', 'LIKE', '%'.$range.'%')->first();
    }

    /**
     * Find plan matching an investment amount.
     */
    public static function findForAmount(float $amount): ?self
    {
        return static::where('min_amount', '<=', $amount)
            ->where(function ($query) use ($amount) {
                $query->whereNull('max_amount')
                    ->orWhere('max_amount', '>=', $amount);
            })
            ->first();
    }

    /**
     * Validate if amount is within this package plan's range.
     */
    public function isValidAmount(float $amount): bool
    {
        if ($amount < (float) $this->min_amount) {
            return false;
        }

        if ($this->max_amount !== null && $amount > (float) $this->max_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate trading wallet allocation for a given deposit amount.
     */
    public function calculateTradingWalletAmount(float $amount): float
    {
        $percent = $this->trading_wallet_percent > 0
            ? (float) $this->trading_wallet_percent
            : (float) (PackageDistribution::getActiveSetting()->trading_wallet ?? 0);

        return round($amount * ($percent / 100), 2);
    }

    /**
     * Calculate maximum earning limit for a given deposit amount.
     */
    public function calculateMaxEarning(float $amount): float
    {
        $percent = $this->max_return_percent > 0
            ? (float) $this->max_return_percent
            : (float) PackageDistribution::getCapping();

        return round($amount * ($percent / 100), 2);
    }

    /**
     * Get the dynamic display range string formatted from current min_amount and max_amount.
     */
    public function getDisplayRangeAttribute(): string
    {
        $min = (float) $this->min_amount == (int) $this->min_amount
            ? (int) $this->min_amount
            : (float) $this->min_amount;

        if ($this->max_amount !== null && $this->max_amount !== '' && (float) $this->max_amount > 0) {
            $max = (float) $this->max_amount == (int) $this->max_amount
                ? (int) $this->max_amount
                : (float) $this->max_amount;

            return "{$min}–{$max} USDT";
        }

        return "{$min}+ USDT";
    }
}
