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
     * Get activation timestamp directly from staking_details.created_at.
     */
    public function getActivationDateAttribute(): ?Carbon
    {
        return $this->created_at ? Carbon::parse($this->created_at) : null;
    }

    /**
     * Calculate unlock date/time based ONLY on staking_details.created_at + package_distributions.lock_days.
     */
    public function getLockedUntilAttribute(): ?Carbon
    {
        if (! $this->created_at) {
            return null;
        }

        $lockDays = PackageDistribution::getDefaultLockDays();

        return Carbon::parse($this->created_at)->copy()->addDays(max(0, $lockDays));
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

    /**
     * Check if staking is active.
     */
    public function isPackageActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check if staking is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'Expired' || $this->status === 'Deactive';
    }
}
