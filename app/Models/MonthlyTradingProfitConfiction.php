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
    protected $table = 'monthly_trading_profit_confiction';

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
        'package_id' => 'integer',
        'rate' => 'float',
        'rate_percent' => 'float',
        'capping_percent' => 'float',
        'package_1_rate' => 'float',
        'package_2_rate' => 'float',
        'package_3_rate' => 'float',
    ];

    /**
     * Relationship: Associated package plan.
     */
    public function packagePlan(): BelongsTo
    {
        return $this->belongsTo(PackagePlan::class, 'package_id');
    }

    /**
     * Get rate for a specific package ID.
     */
    public static function getRateForPackage(int $packageId): float
    {
        $record = static::where('package_id', $packageId)->first();
        if ($record) {
            return (float) ($record->rate ?? $record->rate_percent ?? 0.00);
        }

        return 0.00;
    }

    /**
     * Get active Monthly Trading Profit Capping (%).
     */
    public static function getCappingPercent(): float
    {
        $capping = static::whereNotNull('capping_percent')->value('capping_percent');

        return $capping !== null ? (float) $capping : 0.00;
    }
}
