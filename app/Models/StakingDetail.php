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

    protected $guarded = [];

    protected $casts = [
        'invest_amount' => 'float',
        'trading_wallet_amount' => 'float',
        'rate' => 'float',
        'capping_percent' => 'float',
        'max_amount' => 'float',
        'total_earned' => 'float',
        'installments' => 'integer',
        'total_installments' => 'integer',
        'invest_date' => 'datetime',
        'activated_at' => 'datetime',
        'last_roi_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'is_upgrade' => 'boolean',
    ];

    /**
     * Auto-populate default invest_date and trading_wallet_amount if not set.
     */
    protected static function booted(): void
    {
        static::creating(function (StakingDetail $staking) {
            if (empty($staking->invest_date)) {
                $staking->invest_date = now();
            }
            if ((empty($staking->trading_wallet_amount) || (float) $staking->trading_wallet_amount === 0.0) && ! empty($staking->invest_amount)) {
                $plan = PackagePlan::findByRange($staking->package);
                $percent = $plan && (float) $plan->trading_wallet_percent > 0 ? (float) $plan->trading_wallet_percent : 70.0;
                $staking->trading_wallet_amount = round(((float) $staking->invest_amount) * ($percent / 100), 2);
            }
        });
    }

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

    /**
     * Get dynamic daily ROI rate from MonthlyTradingProfitConfiction, active PackagePlan, or PDF tiers.
     * Tiers: $50–500 => 5%, $600–5,000 => 7%, $6,000+ => 10%.
     */
    public function getDailyRate(): float
    {
        $plan = PackagePlan::findByRange($this->package);
        if (! $plan) {
            $plan = PackagePlan::where('min_amount', '<=', (float) $this->invest_amount)
                ->where(function ($q) {
                    $q->whereNull('max_amount')->orWhere('max_amount', '>=', (float) $this->invest_amount);
                })
                ->first();
        }

        if ($plan) {
            $configuredRate = MonthlyTradingProfitConfiction::getRateForPackage($plan->id);
            if ($configuredRate > 0) {
                return $configuredRate;
            }

            if ((float) $plan->return_percent > 0) {
                return (float) $plan->return_percent;
            }
        }

        if ($this->rate > 0) {
            return (float) $this->rate;
        }

        $amt = (float) $this->invest_amount;
        if ($amt >= 6000) {
            return 10.00;
        } elseif ($amt >= 600) {
            return 7.00;
        }

        return 5.00;
    }

    /**
     * Get dynamic capping percentage from MonthlyTradingProfitConfiction, active PackagePlan, or self.
     */
    public function getCappingPercent(): float
    {
        $configuredCapping = MonthlyTradingProfitConfiction::getCappingPercent();
        if ($configuredCapping > 0) {
            return $configuredCapping;
        }

        $plan = PackagePlan::findByRange($this->package);
        if (! $plan) {
            $plan = PackagePlan::where('min_amount', '<=', (float) $this->invest_amount)
                ->where(function ($q) {
                    $q->whereNull('max_amount')->orWhere('max_amount', '>=', (float) $this->invest_amount);
                })
                ->first();
        }

        if ($plan && (float) $plan->max_return_percent > 0) {
            return (float) $plan->max_return_percent;
        }

        return (float) ($this->capping_percent > 0 ? $this->capping_percent : 200.00);
    }

    /**
     * Get 70% Trading Wallet base amount for profit calculations.
     */
    public function getTradingWalletBase(): float
    {
        if ($this->txnid) {
            $pkg = PackageDetail::where('txnid', $this->txnid)->first();
            if ($pkg && (float) $pkg->trading_wallet_amount > 0) {
                return (float) $pkg->trading_wallet_amount;
            }
        }

        return round((float) $this->invest_amount * 0.70, 2);
    }

    /**
     * Get dynamic maximum ROI amount based on package amount and dynamic capping percent.
     */
    public function getMaxRoiAmount(): float
    {
        $capPercent = $this->getCappingPercent();

        return round((float) $this->invest_amount * ($capPercent / 100), 2);
    }

    /**
     * Get total ROI earned by this package.
     */
    public function getTotalEarned(): float
    {
        $sum = (float) $this->incomes()->sum('amount');
        $column = (float) ($this->total_earned ?? 0.0);

        return round(max($sum, $column), 2);
    }

    /**
     * Get remaining ROI eligible before reaching the cap.
     */
    public function remainingRoi(): float
    {
        $max = $this->getMaxRoiAmount();
        $earned = $this->getTotalEarned();

        return max(0.00, round($max - $earned, 2));
    }

    /**
     * Check if package has reached or exceeded its ROI cap.
     */
    public function isCapped(): bool
    {
        return $this->remainingRoi() <= 0.00;
    }

    /**
     * Check if package is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Deactivate package upon reaching capping.
     */
    public function deactivate(): self
    {
        $this->status = 'Deactive';
        $this->deactivated_at = $this->deactivated_at ?? now();
        $this->save();

        return $this;
    }
}
