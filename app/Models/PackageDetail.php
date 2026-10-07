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
        'invest_amount' => 'float',
        'trading_wallet_amount' => 'float',
        'return_percent' => 'float',
        'total_earning' => 'float',
        'max_earning' => 'float',
        'max_return_percent' => 'float',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
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
     * Check if this package entry is currently expired.
     */
     public function isExpired(): bool
     {
         if (! $this->expires_at) {
             return false;
         }

         return now()->gt($this->expires_at);
     }

     /**
      * Check if this package entry is active.
      */
     public function isPackageActive(): bool
     {
         if ($this->isExpired()) {
             return false;
         }

         $status = strtolower(trim((string) $this->status));

         return in_array($status, ['active', 'accepted']);
     }

     /**
      * Get remaining days until package expiry.
      */
     public function remainingExpiryDays(): int
     {
         if (! $this->expires_at || $this->isExpired()) {
             return 0;
         }

         return (int) ceil(now()->diffInSeconds($this->expires_at, false) / 86400);
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
