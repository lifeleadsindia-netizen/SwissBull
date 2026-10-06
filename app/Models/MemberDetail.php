<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'trading_wallet_lock_days' => 0,
        'trading_wallet_withdrawal_percent' => 100.00,
    ];

    protected $casts = [
        'file_read' => 'array',
        'pepe_wallet' => 'float',
        'p2p_wallet' => 'float',
        'trading_wallet_lock_days' => 'integer',
        'trading_wallet_lock_applied_at' => 'datetime',
        'trading_wallet_locked_until' => 'datetime',
        'trading_wallet_withdrawal_percent' => 'float',
    ];

    public function whatsappReferrals()
    {
        return $this->hasMany(WhatsappReferral::class, 'member_id', 'memberid');
    }

    /**
     * Relationship: Member has many package details.
     */
    public function packageDetails()
    {
        return $this->hasMany(PackageDetail::class, 'memberid', 'memberid');
    }

    /**
     * Relationship: Member's latest package detail.
     */
    public function latestPackageDetail()
    {
        return $this->hasOne(PackageDetail::class, 'memberid', 'memberid')->latestOfMany();
    }

    /**
     * Accessor: Trading Wallet balance is sourced directly from P2P Wallet.
     */
    public function getTradingWalletAttribute(): float
    {
        return (float) ($this->attributes['p2p_wallet'] ?? 0.00);
    }

    /**
     * Mutator: Redirect setting Trading Wallet balance directly to P2P Wallet.
     */
    public function setTradingWalletAttribute($value): void
    {
        $this->attributes['p2p_wallet'] = (float) $value;
    }

    /**
     * Check if the member's trading wallet is currently locked.
     * Verifies member lock date or active package lock in package_details.
     */
    public function isTradingWalletLocked(): bool
    {
        if ($this->trading_wallet_locked_until && now()->lt($this->trading_wallet_locked_until)) {
            return true;
        }

        if ($this->relationLoaded('packageDetails')) {
            return $this->packageDetails->contains(function ($pkg) {
                return $pkg->locked_until && now()->lt($pkg->locked_until);
            });
        }

        return $this->packageDetails()->where('locked_until', '>', now())->exists();
    }

    /**
     * Get remaining days in the trading wallet lock period.
     */
    public function tradingWalletRemainingLockDays(): int
    {
        if (! $this->isTradingWalletLocked()) {
            return 0;
        }

        $latestLockedUntil = $this->trading_wallet_locked_until;
        $pkgLockedUntil = $this->packageDetails()->where('locked_until', '>', now())->max('locked_until');
        if ($pkgLockedUntil) {
            $pkgCarbon = Carbon::parse($pkgLockedUntil);
            if (! $latestLockedUntil || $pkgCarbon->gt($latestLockedUntil)) {
                $latestLockedUntil = $pkgCarbon;
            }
        }

        if (! $latestLockedUntil) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($latestLockedUntil, false) / 86400);
    }

    /**
     * Calculate maximum withdrawable amount from Trading Wallet (using P2P Wallet balance).
     */
    public function tradingWalletMaxWithdrawable(): float
    {
        if ($this->isTradingWalletLocked()) {
            return 0.00;
        }

        $percent = $this->trading_wallet_withdrawal_percent ?? 100.00;
        $balance = (float) ($this->p2p_wallet ?? 0.00);
        $max = ($balance * (float) $percent) / 100.00;

        return max(0.00, round($max, 2));
    }

    /**
     * Validate Trading Wallet withdrawal request against Condition A (Lock) and Condition B (Max %).
     */
    public function canWithdrawTradingWallet(float $amount, ?string &$errorMessage = null): bool
    {
        if ($amount <= 0) {
            $errorMessage = 'Invalid withdrawal amount entered.';

            return false;
        }

        // CONDITION A: Trading Wallet Lock
        if ($this->isTradingWalletLocked()) {
            $remaining = $this->tradingWalletRemainingLockDays();
            $until = $this->trading_wallet_locked_until ? $this->trading_wallet_locked_until->format('d M Y') : 'lock expiry';
            $errorMessage = "Trading Wallet is locked for {$remaining} more day(s) (until {$until}). Withdrawal is not allowed during the lock period.";

            return false;
        }

        // Available balance check (sourced from p2p_wallet)
        $balance = (float) ($this->p2p_wallet ?? 0.00);
        if ($amount > $balance) {
            $errorMessage = 'Requested amount exceeds your available Trading Wallet balance ($'.number_format($balance, 2).').';

            return false;
        }

        // CONDITION B: Maximum Withdrawal Percentage (Post-lock)
        $maxWithdrawable = $this->tradingWalletMaxWithdrawable();
        if ($amount > $maxWithdrawable) {
            $percent = $this->trading_wallet_withdrawal_percent ?? 100.00;
            $errorMessage = 'Requested amount ($'.number_format($amount, 2).") exceeds the maximum allowed withdrawal limit of {$percent}% ($".number_format($maxWithdrawable, 2).') from your Trading Wallet.';

            return false;
        }

        return true;
    }

    /**
     * Check if the member is permitted to purchase or apply for a package.
     * Restriction 2: Blocked during active Lock Period.
     */
    public function canPurchasePackage(?string &$errorMessage = null): bool
    {
        if ($this->isTradingWalletLocked()) {
            $remaining = $this->tradingWalletRemainingLockDays();
            $until = $this->trading_wallet_locked_until ? $this->trading_wallet_locked_until->format('d M Y') : 'lock expiry';
            $errorMessage = "Package purchase is locked. You cannot purchase or apply for another package during the active Lock Period ({$remaining} day(s) remaining until {$until}).";

            return false;
        }

        return true;
    }

    /**
     * Apply Trading Wallet Lock and Withdrawal Percentage to the member.
     */
    public function applyTradingWalletLock(?int $lockDays = null, ?float $withdrawalPercent = null, ?Carbon $appliedAt = null): void
    {
        $setting = TradingWalletSetting::getActiveSetting();
        $lockDays = $lockDays !== null ? $lockDays : $setting->lock_days;
        $withdrawalPercent = $withdrawalPercent !== null ? $withdrawalPercent : $setting->withdrawal_percent;
        $appliedAt = $appliedAt ?? now();

        $this->trading_wallet_lock_days = $lockDays;
        $this->trading_wallet_lock_applied_at = $appliedAt;
        $this->trading_wallet_locked_until = $lockDays > 0 ? $appliedAt->copy()->addDays($lockDays) : null;
        $this->trading_wallet_withdrawal_percent = $withdrawalPercent;
    }
}
