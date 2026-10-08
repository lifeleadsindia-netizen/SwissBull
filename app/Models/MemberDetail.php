<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'file_read' => 'array',
        'pepe_wallet' => 'float',
        'p2p_wallet' => 'float',
        'trading_wallet' => 'float',
    ];

    public function whatsappReferrals()
    {
        return $this->hasMany(WhatsappReferral::class, 'member_id', 'memberid');
    }

    /**
     * Relationship: Member has many staking details (Package/Activation source).
     */
    public function stakingDetails()
    {
        return $this->hasMany(StakingDetail::class, 'memberid', 'memberid');
    }

    /**
     * Relationship: Member's latest staking detail.
     */
    public function latestStakingDetail()
    {
        return $this->hasOne(StakingDetail::class, 'memberid', 'memberid')->latestOfMany('created_at');
    }

    /**
     * Relationship: Member has many package details (Preserved for other modules).
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
        return (float) ($this->attributes['p2p_wallet'] ?? $this->attributes['trading_wallet'] ?? 0.00);
    }

    /**
     * Mutator: Setting Trading Wallet balance directly to P2P Wallet.
     */
    public function setTradingWalletAttribute($value): void
    {
        $this->attributes['p2p_wallet'] = (float) $value;
    }

    /**
     * Accessor: Default lock days from active setting.
     */
    public function getTradingWalletLockDaysAttribute(): int
    {
        return TradingWalletSetting::getDefaultLockDays();
    }

    /**
     * Accessor: Default maximum withdrawal percentage from active setting.
     */
    public function getTradingWalletWithdrawalPercentAttribute(): float
    {
        return TradingWalletSetting::getDefaultWithdrawalPercent();
    }

    /**
     * Accessor: Dynamic locked until timestamp.
     */
    public function getTradingWalletLockedUntilAttribute(): ?Carbon
    {
        return $this->tradingWalletLockedUntil();
    }

    /**
     * Get the latest unlock timestamp across member's staking details and package details.
     */
    public function tradingWalletLockedUntil(): ?Carbon
    {
        $latest = null;

        $stakings = $this->relationLoaded('stakingDetails')
            ? $this->stakingDetails
            : $this->stakingDetails()->get();

        foreach ($stakings as $stk) {
            $unl = $stk->locked_until;
            if ($unl && (! $latest || $unl->gt($latest))) {
                $latest = $unl;
            }
        }

        $packages = $this->relationLoaded('packageDetails')
            ? $this->packageDetails
            : $this->packageDetails()->get();

        foreach ($packages as $pkg) {
            $unl = $pkg->locked_until;
            if ($unl && (! $latest || $unl->gt($latest))) {
                $latest = $unl;
            }
        }

        return $latest;
    }

    /**
     * Check if the member's trading wallet is currently locked based on staking_details or package_details.
     */
    public function isTradingWalletLocked(): bool
    {
        $stakings = $this->relationLoaded('stakingDetails')
            ? $this->stakingDetails
            : $this->stakingDetails()->get();

        if ($stakings->contains(function ($stk) {
            return $stk->isLocked();
        })) {
            return true;
        }

        $packages = $this->relationLoaded('packageDetails')
            ? $this->packageDetails
            : $this->packageDetails()->get();

        return $packages->contains(function ($pkg) {
            return $pkg->isLocked();
        });
    }

    /**
     * Get remaining days in the trading wallet lock period.
     */
    public function tradingWalletRemainingLockDays(): int
    {
        $lockedUntil = $this->tradingWalletLockedUntil();
        if (! $lockedUntil || now()->gte($lockedUntil)) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($lockedUntil, false) / 86400);
    }

    /**
     * Calculate maximum withdrawable amount from Trading Wallet (using P2P Wallet balance).
     */
    public function tradingWalletMaxWithdrawable(): float
    {
        if ($this->isTradingWalletLocked()) {
            return 0.00;
        }

        $percent = TradingWalletSetting::getDefaultWithdrawalPercent();
        $balance = (float) ($this->trading_wallet ?? 0.00);
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

        // CONDITION A: Trading Wallet Lock (sourced from staking_details)
        if ($this->isTradingWalletLocked()) {
            $remaining = $this->tradingWalletRemainingLockDays();
            $until = $this->tradingWalletLockedUntil() ? $this->tradingWalletLockedUntil()->format('d M Y') : 'lock expiry';
            $errorMessage = "Trading Wallet is locked for {$remaining} more day(s) (until {$until}). Withdrawal is not allowed during the lock period.";

            return false;
        }

        // Available balance check (sourced from trading_wallet)
        $balance = (float) ($this->trading_wallet ?? 0.00);
        if ($amount > $balance) {
            $errorMessage = 'Requested amount exceeds your available Trading Wallet balance ($'.number_format($balance, 2).').';

            return false;
        }

        // CONDITION B: Maximum Withdrawal Percentage (Post-lock)
        $maxWithdrawable = $this->tradingWalletMaxWithdrawable();
        if ($amount > $maxWithdrawable) {
            $percent = TradingWalletSetting::getDefaultWithdrawalPercent();
            $errorMessage = 'Requested amount ($'.number_format($amount, 2).") exceeds the maximum allowed withdrawal limit of {$percent}% ($".number_format($maxWithdrawable, 2).') from your Trading Wallet.';

            return false;
        }

        return true;
    }

    /**
     * Check if the member is permitted to purchase or apply for a package.
     * Every package operates independently with its own individual returns and expiry.
     * Multiple package purchases are allowed.
     */
    public function canPurchasePackage(?string &$errorMessage = null): bool
    {
        return true;
    }

    /**
     * Apply Trading Wallet Lock and Withdrawal Percentage.
     * Maintains backwards compatibility without altering member_details schema.
     */
    public function applyTradingWalletLock(?int $lockDays = null, ?float $withdrawalPercent = null, ?Carbon $appliedAt = null): void
    {
        // Lock calculation is anchored dynamically to staking_details.created_at + TradingWalletSetting.
    }
}
