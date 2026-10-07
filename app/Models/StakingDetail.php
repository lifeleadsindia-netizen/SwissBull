<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'is_upgrade' => 'boolean',
    ];

    /**
     * Relationship: Staking detail belongs to a member.
     */
    public function member()
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }

    /**
     * Get the actual activation timestamp from staking_details.created_at (fallback to invest_date).
     */
    public function getActivationDateAttribute(): ?Carbon
    {
        if ($this->created_at) {
            return Carbon::parse($this->created_at);
        }

        if ($this->invest_date) {
            return Carbon::parse($this->invest_date);
        }

        return null;
    }

    /**
     * Calculate unlock date/time based on actual activation date + configured lock days.
     * Formula: Activation Date/Time + Configured Lock Days = Unlock Date/Time
     */
    public function getLockedUntilAttribute(): ?Carbon
    {
        $activation = $this->activation_date;
        if (! $activation) {
            return null;
        }

        $lockDays = TradingWalletSetting::getDefaultLockDays();

        return $lockDays > 0 ? $activation->copy()->addDays($lockDays) : null;
    }

    /**
     * Check if this staking record is currently locked.
     */
    public function isLocked(): bool
    {
        $lockedUntil = $this->locked_until;

        return $lockedUntil ? now()->lt($lockedUntil) : false;
    }

    /**
     * Calculate remaining lock days for this staking record.
     */
    public function remainingLockDays(): int
    {
        if (! $this->isLocked()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->locked_until, false) / 86400);
    }
}
