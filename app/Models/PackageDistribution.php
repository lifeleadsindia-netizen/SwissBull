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
    ];
}
