<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralBonusConfiction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referral_bonus_confiction';

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
                'level_2_rate' => 3.00,
                'level_3_rate' => 2.00,
            ]);
        }

        return $setting;
    }

    /**
     * Get level rates as an associative array keyed by level (1 => rate, 2 => rate, 3 => rate).
     *
     * @return array<int, float>
     */
    public static function getLevelRates(): array
    {
        $setting = static::getActiveSetting();

        return [
            1 => (float) ($setting->level_1_rate ?? 5.00),
            2 => (float) ($setting->level_2_rate ?? 3.00),
            3 => (float) ($setting->level_3_rate ?? 2.00),
        ];
    }
}
