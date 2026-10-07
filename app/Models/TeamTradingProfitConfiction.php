<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTradingProfitConfiction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_trading_profit_confiction';

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
        'level_1_rate' => 'float',
        'level_2_rate' => 'float',
        'level_3_rate' => 'float',
        'level_4_rate' => 'float',
        'level_5_rate' => 'float',
        'level_6_rate' => 'float',
        'level_7_rate' => 'float',
        'level_8_rate' => 'float',
        'level_9_rate' => 'float',
        'level_10_rate' => 'float',
    ];

    /**
     * Get or create active singleton configuration.
     */
    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $setting = static::create([
                'level_1_rate' => 5.00,
                'level_2_rate' => 5.00,
                'level_3_rate' => 4.00,
                'level_4_rate' => 4.00,
                'level_5_rate' => 3.00,
                'level_6_rate' => 3.00,
                'level_7_rate' => 2.00,
                'level_8_rate' => 2.00,
                'level_9_rate' => 1.00,
                'level_10_rate' => 1.00,
            ]);
        }

        return $setting;
    }

    /**
     * Get all 10 level rates as an associative array keyed by level (1..10).
     *
     * @return array<int, float>
     */
    public static function getLevelRates(): array
    {
        $setting = static::getActiveSetting();

        return [
            1 => (float) ($setting->level_1_rate ?? 5.00),
            2 => (float) ($setting->level_2_rate ?? 5.00),
            3 => (float) ($setting->level_3_rate ?? 4.00),
            4 => (float) ($setting->level_4_rate ?? 4.00),
            5 => (float) ($setting->level_5_rate ?? 3.00),
            6 => (float) ($setting->level_6_rate ?? 3.00),
            7 => (float) ($setting->level_7_rate ?? 2.00),
            8 => (float) ($setting->level_8_rate ?? 2.00),
            9 => (float) ($setting->level_9_rate ?? 1.00),
            10 => (float) ($setting->level_10_rate ?? 1.00),
        ];
    }

    /**
     * Get configured rate for a specific level.
     */
    public static function getRateForLevel(int $level): float
    {
        $rates = static::getLevelRates();

        return $rates[$level] ?? 0.00;
    }
}
