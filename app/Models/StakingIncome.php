<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StakingIncome extends Model
{
    use HasFactory;

    protected $table = 'staking_incomes';

    protected $guarded = [];

    protected $casts = [
        'total_investment' => 'float',
        'rate' => 'float',
        'amount' => 'float',
        'installment' => 'integer',
        'date' => 'date',
    ];

    /**
     * Staking package this income record belongs to.
     */
    public function staking(): BelongsTo
    {
        return $this->belongsTo(StakingDetail::class, 'staking_id', 'id');
    }

    /**
     * Member receiving the income.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
