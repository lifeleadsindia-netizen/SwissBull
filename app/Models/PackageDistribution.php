<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDistribution extends Model
{
    protected $table = 'package_distributions';

    protected $guarded = [];

    /**
     * Get the package distribution percentage configuration from the database.
     * Database is the source of truth; values can be updated by admin in the future.
     *
     * @return array
     */
    public static function getDistributionConfig(): array
    {
        $config = PackageDistribution::first();

        return [
            'p2p_wallet' => $config ? (float) $config->p2p_wallet : 70.0,
            'referral_bonus' => $config ? (float) $config->referral_bonus : 10.0,
            'team_trading_profit' => $config ? (float) $config->team_trading_profit : 8.0,
            'team_performance_bonus' => $config ? (float) $config->team_performance_bonus : 10.0,
            'hero_of_the_month' => $config ? (float) $config->hero_of_the_month : 2.0,
        ];
    }
}
