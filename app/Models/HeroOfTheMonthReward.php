<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroOfTheMonthReward extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hero_of_the_month_rewards';

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
        'direct_business' => 'float',
        'total_pool' => 'float',
        'pool_percentage' => 'float',
        'total_winners' => 'integer',
        'prize_amount' => 'float',
    ];

    /**
     * Associated member detail.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
