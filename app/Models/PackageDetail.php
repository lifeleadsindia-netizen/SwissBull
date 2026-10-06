<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PackageDetail extends Model
{
    protected $table = 'package_details';

    protected $guarded = [];

    protected $attributes = [
        'lock_days' => 0,
    ];

    protected $casts = [
        'package_value' => 'float',
        'lock_days' => 'integer',
        'lock_applied_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    /**
     * Relationship: Package Detail belongs to Member Detail.
     */
    public function member()
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }

    /**
     * Check if this package entry is currently locked.
     */
    public function isLocked(): bool
    {
        if (! $this->locked_until) {
            return false;
        }

        return now()->lt($this->locked_until);
    }

    /**
     * Get remaining days in this package entry's lock period.
     */
    public function remainingLockDays(): int
    {
        if (! $this->isLocked()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->locked_until, false) / 86400);
    }

    /**
     * Apply Lock Period to this package entry.
     */
    public function applyLock(?int $lockDays = null, ?Carbon $appliedAt = null): void
    {
        $setting = TradingWalletSetting::getActiveSetting();
        $lockDays = $lockDays !== null ? $lockDays : $setting->lock_days;
        $appliedAt = $appliedAt ?? now();

        $this->lock_days = $lockDays;
        $this->lock_applied_at = $appliedAt;
        $this->locked_until = $lockDays > 0 ? $appliedAt->copy()->addDays($lockDays) : null;
    }
}
