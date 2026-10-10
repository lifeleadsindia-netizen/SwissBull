<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Calculate unlock date/time based on matching package detail or activation date + configured lock days.
     */
    public function getLockedUntilAttribute(): ?Carbon
    {
        if (! empty($this->txnid)) {
            $pkg = PackageDetail::where('txnid', $this->txnid)->first();
            if ($pkg && $pkg->locked_until) {
                return Carbon::parse($pkg->locked_until);
            }
        }

        if (! empty($this->order_id)) {
            $pkg = PackageDetail::where('order_id', $this->order_id)->first();
            if ($pkg && $pkg->locked_until) {
                return Carbon::parse($pkg->locked_until);
            }
        }

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
