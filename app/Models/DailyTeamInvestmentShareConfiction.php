<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTeamInvestmentShareConfiction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daily_team_investment_share_confiction';

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
        'level_1_directs' => 'integer',
        'level_2_rate' => 'float',
        'level_2_directs' => 'integer',
        'level_3_rate' => 'float',
        'level_3_directs' => 'integer',
        'level_4_rate' => 'float',
        'level_4_directs' => 'integer',
        'level_5_rate' => 'float',
        'level_5_directs' => 'integer',
        'level_6_rate' => 'float',
        'level_6_directs' => 'integer',
        'level_7_rate' => 'float',
        'level_7_directs' => 'integer',
        'level_8_rate' => 'float',
        'level_8_directs' => 'integer',
        'level_9_rate' => 'float',
        'level_9_directs' => 'integer',
        'level_10_rate' => 'float',
        'level_10_directs' => 'integer',
    ];

    /**
     * Calculate the direct referrals chain for 10 levels given a level 1 requirement.
     * Formula: Level X = Level 1 + ((X - 1) * 2)
     *
     * @return array<int, int>
     */
    public static function calculateDirectsChain(int $level1Directs): array
    {
        $chain = [];
        for ($i = 1; $i <= 10; $i++) {
            $chain[$i] = $level1Directs + (($i - 1) * 2);
        }

        return $chain;
    }

    /**
     * Get or create active singleton configuration.
     */
    public static function getActiveSetting(): self
    {
        $setting = static::first();
        if (! $setting) {
            $chain = static::calculateDirectsChain(4);
            $setting = static::create([
                'level_1_rate' => 1.00,
                'level_1_directs' => $chain[1],
                'level_2_rate' => 1.00,
                'level_2_directs' => $chain[2],
                'level_3_rate' => 1.00,
                'level_3_directs' => $chain[3],
                'level_4_rate' => 1.00,
                'level_4_directs' => $chain[4],
                'level_5_rate' => 1.00,
                'level_5_directs' => $chain[5],
                'level_6_rate' => 1.00,
                'level_6_directs' => $chain[6],
                'level_7_rate' => 1.00,
                'level_7_directs' => $chain[7],
                'level_8_rate' => 1.00,
                'level_8_directs' => $chain[8],
                'level_9_rate' => 1.00,
                'level_9_directs' => $chain[9],
                'level_10_rate' => 1.00,
                'level_10_directs' => $chain[10],
            ]);
        }

        return $setting;
    }

    /**
     * Get all 10 level settings as an associative array keyed by level (1..10).
     *
     * @return array<int, array{rate: float, directs: int}>
     */
    public static function getLevelSettings(): array
    {
        $setting = static::getActiveSetting();

        $levels = [];
        $level1Directs = (int) ($setting->level_1_directs ?? 4);
        $chain = static::calculateDirectsChain($level1Directs);

        for ($i = 1; $i <= 10; $i++) {
            $levels[$i] = [
                'rate' => (float) ($setting->{"level_{$i}_rate"} ?? 1.00),
                'directs' => (int) ($setting->{"level_{$i}_directs"} ?? $chain[$i]),
            ];
        }

        return $levels;
    }

    /**
     * Get configured rate for a specific level.
     */
    public static function getRateForLevel(int $level): float
    {
        $settings = static::getLevelSettings();

        return $settings[$level]['rate'] ?? 0.00;
    }

    /**
     * Get configured direct referral requirement for a specific level.
     */
    public static function getDirectsForLevel(int $level): int
    {
        $settings = static::getLevelSettings();

        return $settings[$level]['directs'] ?? 0;
    }
}
