<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyTradingProfitConfiction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_distributions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'capping' => 'float',
        'package_1_rate' => 'float',
        'package_2_rate' => 'float',
        'package_3_rate' => 'float',
    ];

    /**
     * Get rate for a specific package ID.
     */
    public static function getRateForPackage(int $packageId): float
    {
        return PackageDistribution::getRateForPackage($packageId);
    }

    /**
     * Get active Monthly Trading Profit Capping.
     */
    public static function getCapping(): float
    {
        return PackageDistribution::getCapping();
    }

    /**
     * Get active Monthly Trading Profit Capping (legacy alias).
     */
    public static function getCappingPercent(): float
    {
        return static::getCapping();
    }
}
