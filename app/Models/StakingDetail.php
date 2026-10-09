<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StakingDetail extends Model
{
    use HasFactory;

    protected $table = 'staking_details';

    /**
     * Relationship to the member.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }

    /**
     * Relationship to staking incomes credited to this package.
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(StakingIncome::class, 'staking_id', 'id');
    }
}
