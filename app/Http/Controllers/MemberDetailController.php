<?php

namespace App\Http\Controllers;

use App\Models\AchiversImage;
use App\Models\BusinessPlanDocument;
use App\Models\Country;
use App\Models\DashMessage;
use App\Models\MemberDetail;
use App\Models\MemberVideo;
use App\Models\PackageDetail;
use App\Models\PackagePlan;
use App\Models\PepeRewardLog;
use App\Models\PepeSetting;
use App\Models\PromotionBanner;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use App\Models\UplineMember;
use App\Models\WhatsappReferral;
use App\Models\WithdrawalRequest;
use App\Services\PepeRewardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable as GlobalThrowable;

class MemberDetailController extends Controller
{
    public function dashboard(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $country = session('country');
        $result['country'] = Country::where('name', $country)->first();
        $result['data'] = MemberDetail::where([['memberid', $memberid], ['country', $country]])->first();
        $result['dashMsg'] = DashMessage::orderby('rank', 'asc')->get();
        $result['achieverImages'] = AchiversImage::orderBy('id', 'desc')->get();

        // WhatsApp Referral Promotion Statistics
        $today = now()->toDateString();
        $waTodayCount = WhatsappReferral::where('member_id', $memberid)
            ->whereDate('created_at', $today)
            ->count();
        $result['waTodayCount'] = $waTodayCount;
        $result['waTodayStatus'] = $waTodayCount >= PepeRewardService::DAILY_MESSAGE_LIMIT
            ? 'Completed (10/10)'
            : ($waTodayCount > 0 ? 'Available ('.$waTodayCount.'/10)' : 'Available (0/10)');
        $result['waTotalReferrals'] = WhatsappReferral::where('member_id', $memberid)->count();
        $result['waDirectRegCount'] = PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_REGISTRATION)->count();
        $result['waDirectActCount'] = PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_ACTIVATION)->count();
        $waTotalPepe = PepeRewardService::getTotalEarned($memberid);
        $waTotalRedeemed = (float) WithdrawalRequest::where('memberid', $memberid)
            ->where('type', 'Airdrop Withdrawal')
            ->where('status', 'Approved')
            ->sum('gross_amount');
        $pepeWalletBalance = (float) ($result['data']->pepe_wallet ?? 0);
        $waPromoAvailable = max(0, $waTotalPepe - $waTotalRedeemed);

        // Current PEPE wallet balance is the actual balance remaining in member's pepe_wallet
        $waAvailablePepe = $pepeWalletBalance;
        // If pepe_wallet column has not yet been initialized for this member but promo tokens are available, sync them
        if ($pepeWalletBalance <= 0 && $waPromoAvailable > 0 && $waTotalRedeemed == 0) {
            $waAvailablePepe = $waPromoAvailable;
            if ($result['data']) {
                $result['data']->pepe_wallet = $waPromoAvailable;
                $result['data']->save();
            }
        }

        $result['waTotalPepe'] = $waTotalPepe;
        $result['waTotalRedeemed'] = $waTotalRedeemed;
        $result['pepeWalletBalance'] = $waAvailablePepe;
        $result['waAvailablePepe'] = $waAvailablePepe;
        $lastWa = WhatsappReferral::where('member_id', $memberid)->latest('created_at')->first();
        $result['waLastDate'] = $lastWa ? $lastWa->created_at : null;
        $result['countries'] = Country::whereNotNull('phonecode')->where('phonecode', '!=', '')->orderBy('nicename', 'asc')->get();
        $result['pepeSettings'] = PepeSetting::getSettings();

        // Phase 2 + Phase 3: Package, Staking & Trading Wallet Lock Information (Backend Computed)
        $member = $result['data'];

        $activePackages = PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->latest('created_at')
            ->get();
        $activePackage = $activePackages->first();
        $result['activePackage'] = $activePackage;

        $stakings = StakingDetail::where('memberid', $memberid)
            ->latest('created_at')
            ->get();
        $activeStaking = $stakings->where('status', 'Active')->first() ?? $stakings->first();
        $result['activeStaking'] = $activeStaking;

        // 1. Investment calculations
        $totalInvestQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('invest_amount');
        if ($totalInvestQuery <= 0 && $stakings->count() > 0) {
            $totalInvestQuery = (float) $stakings->where('status', 'Active')->sum('invest_amount');
            if ($totalInvestQuery <= 0) {
                $totalInvestQuery = (float) $stakings->sum('invest_amount');
            }
        }
        $totalInvestment = $totalInvestQuery > 0 ? $totalInvestQuery : (float) ($member->self_biz ?? 0);
        $result['totalInvestment'] = $totalInvestment;

        $activePackageInvestAmount = (float) ($activePackage->invest_amount ?? ($activePackage->package_value ?? ($activeStaking ? $activeStaking->invest_amount : $totalInvestment)));
        $result['activePackageInvestAmount'] = $activePackageInvestAmount;

        // 2. Trading Wallet balance (sourced strictly from member's trading_wallet)
        $tradingWalletBalance = (float) ($member->trading_wallet ?? 0.00);
        $result['tradingWalletBalance'] = $tradingWalletBalance;

        // 3. Dynamic Admin-configured Package Plan and Return %
        $packagePlan = null;
        if ($activePackage && $activePackage->package_range) {
            $packagePlan = PackagePlan::findByRange($activePackage->package_range);
        }
        if (! $packagePlan && $activeStaking && $activeStaking->package) {
            $packagePlan = PackagePlan::findByRange($activeStaking->package);
        }
        if (! $packagePlan && $totalInvestment > 0) {
            $packagePlan = PackagePlan::where('min_amount', '<=', $totalInvestment)
                ->where(function ($q) use ($totalInvestment) {
                    $q->whereNull('max_amount')->orWhere('max_amount', '>=', $totalInvestment);
                })->first();
        }

        $defaultSetting = TradingWalletSetting::getActiveSetting();
        $defaultLockDays = (int) ($defaultSetting->lock_days ?? 30);

        // Maximum Return % must come from actual Admin-configured maximum return / capping setting
        $maxReturnPercent = 200.00;
        if ($packagePlan && (float) $packagePlan->max_return_percent > 0) {
            $maxReturnPercent = (float) $packagePlan->max_return_percent;
        } elseif ($activePackage && (float) $activePackage->max_return_percent > 0) {
            $maxReturnPercent = (float) $activePackage->max_return_percent;
        } elseif ($activeStaking && (float) $activeStaking->getCappingPercent() > 0) {
            $maxReturnPercent = (float) $activeStaking->getCappingPercent();
        }
        $result['maxReturnPercent'] = $maxReturnPercent;

        // Daily ROI rate
        $dailyRoiPercent = $packagePlan && (float) $packagePlan->return_percent > 0
            ? (float) $packagePlan->return_percent
            : ($activeStaking ? $activeStaking->getDailyRate() : (float) ($activePackage->return_percent ?? 5.00));
        $result['dailyRoiPercent'] = $dailyRoiPercent;

        // 4. Total Earning & Max Earning limit
        $totalEarnQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('total_earning');
        $stakingEarnSum = (float) StakingDetail::where('memberid', $memberid)->sum('total_earned');
        $totalEarning = round(max($totalEarnQuery, $stakingEarnSum), 2);
        $result['totalPackageEarning'] = $totalEarning;
        $result['totalEarning'] = $totalEarning;

        $maxEarningQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('max_earning');
        if ($maxEarningQuery <= 0 && $stakings->count() > 0) {
            $maxEarningQuery = (float) $stakings->where('status', 'Active')->sum(function ($stk) {
                return $stk->getMaxRoiAmount();
            });
        }
        $maxEarning = $maxEarningQuery > 0
            ? round($maxEarningQuery, 2)
            : ($totalInvestment > 0 ? round($totalInvestment * ($maxReturnPercent / 100), 2) : 0.00);
        $result['maxPackageEarning'] = $maxEarning;
        $result['maxEarning'] = $maxEarning;

        $remainingEligibility = max(0.00, round($maxEarning - $totalEarning, 2));
        $result['remainingEligibility'] = $remainingEligibility;

        // 5. Package Status
        if ($activePackage && $activePackage->isPackageActive()) {
            $packageStatus = 'Active';
        } elseif ($activePackage && $activePackage->isExpired()) {
            $packageStatus = 'Expired';
        } elseif ($activeStaking && $activeStaking->status === 'Active') {
            $packageStatus = 'Active';
        } elseif ($activeStaking && $activeStaking->status === 'Deactive') {
            $packageStatus = 'Capped / Deactivated';
        } elseif ($totalInvestment > 0) {
            $packageStatus = 'Active';
        } else {
            $packageStatus = 'No Active Package';
        }
        $result['packageStatus'] = $packageStatus;

        // 6. Multiple Packages Processing & Package-Wise Lock Tracking
        $packageList = PackageDetail::where('memberid', $memberid)
            ->latest('created_at')
            ->take(10)
            ->get();

        $processedPackages = [];
        $isAnyLocked = false;
        $primaryUnlockTime = null;

        foreach ($packageList as $pkg) {
            $pkgLockDays = $pkg->lock_days > 0 ? (int) $pkg->lock_days : $defaultLockDays;
            $pkgActivatedAt = $pkg->activated_at ? Carbon::parse($pkg->activated_at) : ($pkg->created_at ? Carbon::parse($pkg->created_at) : null);

            $pkgLockedUntil = $pkg->locked_until;
            if (! $pkgLockedUntil && $pkgActivatedAt && $pkgLockDays > 0) {
                $pkgLockedUntil = $pkgActivatedAt->copy()->addDays($pkgLockDays);
            }

            $pkgIsLocked = false;
            $pkgRemainingSeconds = 0;
            $pkgRemainingDays = 0;

            if ($pkgLockedUntil && now()->lt($pkgLockedUntil)) {
                $pkgIsLocked = true;
                $pkgRemainingSeconds = (int) now()->diffInSeconds($pkgLockedUntil, false);
                $pkgRemainingDays = (int) ceil($pkgRemainingSeconds / 86400);

                $isAnyLocked = true;
                if (! $primaryUnlockTime || $pkgLockedUntil->gt($primaryUnlockTime)) {
                    $primaryUnlockTime = $pkgLockedUntil;
                }
            }

            $pkgPlan = PackagePlan::findByRange($pkg->package_range);
            $pkgMaxReturn = $pkgPlan ? (float) $pkgPlan->max_return_percent : (float) ($pkg->max_return_percent ?: $maxReturnPercent);

            $pkg->is_locked = $pkgIsLocked;
            $pkg->lock_status_label = $pkgIsLocked ? 'Fund Locked' : 'Fund Unlocked';
            $pkg->remaining_lock_days = $pkgRemainingDays;
            $pkg->remaining_lock_seconds = $pkgRemainingSeconds;
            $pkg->unlock_datetime = $pkgLockedUntil ? $pkgLockedUntil->format('d M Y, H:i') : null;
            $pkg->unlock_timestamp = $pkgLockedUntil ? $pkgLockedUntil->timestamp * 1000 : null;
            $pkg->computed_max_return_percent = $pkgMaxReturn;

            $processedPackages[] = $pkg;
        }

        // Staking details lock checks
        foreach ($stakings as $stk) {
            if ($stk->isLocked()) {
                $isAnyLocked = true;
                $stkUnlock = $stk->locked_until;
                if ($stkUnlock && (! $primaryUnlockTime || $stkUnlock->gt($primaryUnlockTime))) {
                    $primaryUnlockTime = $stkUnlock;
                }
            }
        }

        $remainingLockSeconds = ($isAnyLocked && $primaryUnlockTime) ? max(0, (int) now()->diffInSeconds($primaryUnlockTime, false)) : 0;
        $remainingLockDays = ($isAnyLocked && $primaryUnlockTime) ? (int) ceil($remainingLockSeconds / 86400) : 0;
        $isFundLocked = $isAnyLocked && ($remainingLockSeconds > 0);

        $lockStatus = $isFundLocked ? 'Fund Locked' : 'Fund Unlocked';
        $unlockDateTimeFormatted = ($isFundLocked && $primaryUnlockTime) ? $primaryUnlockTime->format('d M Y, H:i') : null;
        $unlockTimestampMs = ($isFundLocked && $primaryUnlockTime) ? $primaryUnlockTime->timestamp * 1000 : null;

        $result['isFundLocked'] = $isFundLocked;
        $result['lockStatus'] = $lockStatus;
        $result['unlockDateTime'] = $unlockDateTimeFormatted;
        $result['unlockTimestamp'] = $unlockTimestampMs;
        $result['remainingLockDays'] = $remainingLockDays;
        $result['remainingLockSeconds'] = $remainingLockSeconds;
        $result['fundLockTitle'] = $lockStatus;
        $result['fundUnlocksInText'] = $isFundLocked ? "Fund Unlocks In {$remainingLockDays} Days" : 'Fund Unlocked';
        $result['fundLockReturnMessage'] = $isFundLocked
            ? 'Fund Locked, You are eligible for total return of '.number_format($maxReturnPercent, 0).'% Returns'
            : 'You are eligible for total return of '.number_format($maxReturnPercent, 0).'% Returns';
        $result['packageInvestments'] = collect($processedPackages);

        return view('member.dashboard')->with($result);
    }

    /**
     * API Endpoint: Get real-time Trading Wallet Lock & Financial Status for Member Dashboard.
     */
    public function getTradingWalletStatus(Request $request)
    {
        $memberid = session('MEMBER_ID') ?? $request->get('memberid');
        if (! $memberid) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized or session expired.',
            ], 401);
        }

        $member = MemberDetail::where('memberid', $memberid)->first();
        if (! $member) {
            return response()->json([
                'status' => false,
                'message' => 'Member not found.',
            ], 404);
        }

        $activePackages = PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->latest('created_at')
            ->get();
        $activePackage = $activePackages->first();

        $stakings = StakingDetail::where('memberid', $memberid)->latest('created_at')->get();
        $activeStaking = $stakings->where('status', 'Active')->first() ?? $stakings->first();

        $totalInvestQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('invest_amount');
        if ($totalInvestQuery <= 0 && $stakings->count() > 0) {
            $totalInvestQuery = (float) $stakings->where('status', 'Active')->sum('invest_amount');
            if ($totalInvestQuery <= 0) {
                $totalInvestQuery = (float) $stakings->sum('invest_amount');
            }
        }
        $totalInvestment = $totalInvestQuery > 0 ? $totalInvestQuery : (float) ($member->self_biz ?? 0);

        $tradingWalletBalance = (float) ($member->trading_wallet ?? 0.00);

        $packagePlan = null;
        if ($activePackage && $activePackage->package_range) {
            $packagePlan = PackagePlan::findByRange($activePackage->package_range);
        }
        if (! $packagePlan && $activeStaking && $activeStaking->package) {
            $packagePlan = PackagePlan::findByRange($activeStaking->package);
        }
        if (! $packagePlan && $totalInvestment > 0) {
            $packagePlan = PackagePlan::where('min_amount', '<=', $totalInvestment)
                ->where(function ($q) use ($totalInvestment) {
                    $q->whereNull('max_amount')->orWhere('max_amount', '>=', $totalInvestment);
                })->first();
        }

        $defaultSetting = TradingWalletSetting::getActiveSetting();
        $defaultLockDays = (int) ($defaultSetting->lock_days ?? 30);

        $maxReturnPercent = 200.00;
        if ($packagePlan && (float) $packagePlan->max_return_percent > 0) {
            $maxReturnPercent = (float) $packagePlan->max_return_percent;
        } elseif ($activePackage && (float) $activePackage->max_return_percent > 0) {
            $maxReturnPercent = (float) $activePackage->max_return_percent;
        } elseif ($activeStaking && (float) $activeStaking->getCappingPercent() > 0) {
            $maxReturnPercent = (float) $activeStaking->getCappingPercent();
        }

        $totalEarnQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('total_earning');
        $stakingEarnSum = (float) StakingDetail::where('memberid', $memberid)->sum('total_earned');
        $totalEarning = round(max($totalEarnQuery, $stakingEarnSum), 2);

        $maxEarningQuery = (float) PackageDetail::where('memberid', $memberid)
            ->whereIn('status', ['Active', 'Accepted'])
            ->sum('max_earning');
        if ($maxEarningQuery <= 0 && $stakings->count() > 0) {
            $maxEarningQuery = (float) $stakings->where('status', 'Active')->sum(function ($stk) {
                return $stk->getMaxRoiAmount();
            });
        }
        $maxEarning = $maxEarningQuery > 0
            ? round($maxEarningQuery, 2)
            : ($totalInvestment > 0 ? round($totalInvestment * ($maxReturnPercent / 100), 2) : 0.00);

        $remainingEligibility = max(0.00, round($maxEarning - $totalEarning, 2));

        if ($activePackage && $activePackage->isPackageActive()) {
            $packageStatus = 'Active';
        } elseif ($activePackage && $activePackage->isExpired()) {
            $packageStatus = 'Expired';
        } elseif ($activeStaking && $activeStaking->status === 'Active') {
            $packageStatus = 'Active';
        } elseif ($activeStaking && $activeStaking->status === 'Deactive') {
            $packageStatus = 'Capped / Deactivated';
        } elseif ($totalInvestment > 0) {
            $packageStatus = 'Active';
        } else {
            $packageStatus = 'No Active Package';
        }

        $packageList = PackageDetail::where('memberid', $memberid)->latest('created_at')->take(10)->get();
        $processedPackages = [];
        $isAnyLocked = false;
        $primaryUnlockTime = null;

        foreach ($packageList as $pkg) {
            $pkgLockDays = $pkg->lock_days > 0 ? (int) $pkg->lock_days : $defaultLockDays;
            $pkgActivatedAt = $pkg->activated_at ? Carbon::parse($pkg->activated_at) : ($pkg->created_at ? Carbon::parse($pkg->created_at) : null);
            $pkgLockedUntil = $pkg->locked_until;
            if (! $pkgLockedUntil && $pkgActivatedAt && $pkgLockDays > 0) {
                $pkgLockedUntil = $pkgActivatedAt->copy()->addDays($pkgLockDays);
            }

            $pkgIsLocked = false;
            $pkgRemainingSeconds = 0;
            $pkgRemainingDays = 0;

            if ($pkgLockedUntil && now()->lt($pkgLockedUntil)) {
                $pkgIsLocked = true;
                $pkgRemainingSeconds = (int) now()->diffInSeconds($pkgLockedUntil, false);
                $pkgRemainingDays = (int) ceil($pkgRemainingSeconds / 86400);

                $isAnyLocked = true;
                if (! $primaryUnlockTime || $pkgLockedUntil->gt($primaryUnlockTime)) {
                    $primaryUnlockTime = $pkgLockedUntil;
                }
            }

            $pkgPlan = PackagePlan::findByRange($pkg->package_range);
            $pkgMaxReturn = $pkgPlan ? (float) $pkgPlan->max_return_percent : (float) ($pkg->max_return_percent ?: $maxReturnPercent);

            $processedPackages[] = [
                'id' => $pkg->id,
                'package_range' => $pkg->package_range,
                'invest_amount' => (float) ($pkg->invest_amount ?? $pkg->package_value),
                'trading_wallet_amount' => (float) ($pkg->trading_wallet_amount ?? (($pkg->invest_amount ?? $pkg->package_value) * 0.70)),
                'is_locked' => $pkgIsLocked,
                'lock_status_label' => $pkgIsLocked ? 'Fund Locked' : 'Fund Unlocked',
                'remaining_lock_days' => $pkgRemainingDays,
                'remaining_lock_seconds' => $pkgRemainingSeconds,
                'unlock_datetime' => $pkgLockedUntil ? $pkgLockedUntil->format('d M Y, H:i') : null,
                'unlock_timestamp' => $pkgLockedUntil ? $pkgLockedUntil->timestamp * 1000 : null,
                'max_return_percent' => $pkgMaxReturn,
                'max_earning' => (float) ($pkg->max_earning ?? 0.0),
                'total_earning' => (float) ($pkg->total_earning ?? 0.0),
                'status' => $pkg->status,
            ];
        }

        foreach ($stakings as $stk) {
            if ($stk->isLocked()) {
                $isAnyLocked = true;
                $stkUnlock = $stk->locked_until;
                if ($stkUnlock && (! $primaryUnlockTime || $stkUnlock->gt($primaryUnlockTime))) {
                    $primaryUnlockTime = $stkUnlock;
                }
            }
        }

        $remainingLockSeconds = ($isAnyLocked && $primaryUnlockTime) ? max(0, (int) now()->diffInSeconds($primaryUnlockTime, false)) : 0;
        $remainingLockDays = ($isAnyLocked && $primaryUnlockTime) ? (int) ceil($remainingLockSeconds / 86400) : 0;
        $isFundLocked = $isAnyLocked && ($remainingLockSeconds > 0);

        return response()->json([
            'status' => true,
            'data' => [
                'investment' => $totalInvestment,
                'trading_wallet' => $tradingWalletBalance,
                'total_earning' => $totalEarning,
                'max_earning' => $maxEarning,
                'remaining_eligibility' => $remainingEligibility,
                'package_status' => $packageStatus,
                'is_locked' => $isFundLocked,
                'lock_status' => $isFundLocked ? 'Fund Locked' : 'Fund Unlocked',
                'unlock_datetime' => ($isFundLocked && $primaryUnlockTime) ? $primaryUnlockTime->format('d M Y, H:i') : null,
                'unlock_timestamp' => ($isFundLocked && $primaryUnlockTime) ? $primaryUnlockTime->timestamp * 1000 : null,
                'remaining_lock_days' => $remainingLockDays,
                'remaining_lock_seconds' => $remainingLockSeconds,
                'maximum_return_percent' => $maxReturnPercent,
                'fund_lock_title' => $isFundLocked ? 'Fund Locked' : 'Fund Unlocked',
                'fund_unlocks_in_text' => $isFundLocked ? "Fund Unlocks In {$remainingLockDays} Days" : 'Fund Unlocked',
                'fund_lock_return_message' => $isFundLocked
                    ? 'Fund Locked, You are eligible for total return of '.number_format($maxReturnPercent, 0).'% Returns'
                    : 'You are eligible for total return of '.number_format($maxReturnPercent, 0).'% Returns',
                'packages' => $processedPackages,
            ],
        ]);
    }

    public function tradingDashboard(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $country = session('country');
        $result['country'] = Country::where('name', $country)->first();
        $result['data'] = MemberDetail::where([['memberid', $memberid], ['country', $country]])->first();

        return view('member.trading-dashboard')->with($result);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('address');
        $request->session()->forget('MEMBER_ID');
        $request->session()->forget('country');
        $request->session()->forget('userid');
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('mobile');

        return redirect('/');
    }

    public function memforgetPassword()
    {
        return view('member.forget-password');
    }

    public function insertPdata(Request $request)
    {
        // print_r($request->post());
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required',
            'mobile' => 'required',
            // 'dob' => 'required',
            // 'gender' => 'required',
            'email' => 'required|email',
        ]);

        $id = session('MEMBER_ID');
        $var = MemberDetail::where('memberid', $id)->first();
        $var->name = $request->post('name');
        $var->mobile = $request->post('mobile');
        // $var->dob = $request->post('dob');
        // $var->gender = $request->post('gender');
        $var->email = $request->post('email');
        if ($request->hasfile('profile_image')) {
            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                session()->flash('failedMsg', 'Allowed image type is jpg, jpeg, png. Please change image type');

                return redirect()->back();
            }
            $filename = time().'.'.$extension;
            $file->move(public_path('uploads'), $filename);
            $var->profile_image = $filename;
        }
        $var->profile_status = 'Updated';
        $var->save();

        session()->flash('successMsg', 'Profile Details have been updated');

        return redirect()->back();
    }

    public function profile(Request $request)
    {
        $id = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $id)->first();

        return view('member.profile.profile')->with($result);
    }

    public function createUser()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.profile.create-user')->with($result);
    }

    public function password()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.profile.security')->with($result);
    }

    public function geneology()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['directs'] = MemberDetail::where('sponsorid', $memberid)->get();

        return view('member.team.geneology')->with($result);
    }

    public function ViewGeneology($id)
    {
        $data = MemberDetail::where('id', $id)->orWhere('memberid', $id)->first();

        if (! $data) {
            return redirect('member/team/geneology');
        }

        $result['data'] = $data;
        $result['directs'] = MemberDetail::where('sponsorid', $data->memberid)->get();

        return view('member.team.view-geneology')->with($result);
    }

    public function directteam()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['directData'] = MemberDetail::where('sponsorid', $result['data']['memberid'])->get();

        return view('member.team.direct-team')->with($result);
    }

    public function leveldetails(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.team.level-directs')->with($result);
    }

    public function lelMemDetails($level)
    {
        $uplines = 'upline_'.$level;
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $ids = UplineMember::where($uplines, $memberid)->pluck('memberid');
        $result['users'] = MemberDetail::whereIn('memberid', $ids)->get();
        $result['levelNo'] = $level;

        return view('member.team.level-members-details')->with($result);
    }

    public function getMember(Request $request)
    {
        $memberid = $request->post('memberid');
        if ($memberid != '') {
            $data = MemberDetail::where('memberid', $memberid)->first();
            if ($data) {
                return response()->json([
                    'code' => 1,
                    'data' => '<span class="text-success">Member Name :'.$data['name'].'</span>',
                    'name' => '<span >'.$data['name'].' wants to lend</span>',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'data' => '<span class="text-danger">No Data Found with this Id</span>',
                ]);
            }
        } else {
            return response()->json([
                'code' => 0,
                'data' => '',
            ]);
        }
    }

    public function getSponname(Request $request)
    {
        $sponsorid = $request->post('sponsorid');
        if ($sponsorid != '') {
            $data = MemberDetail::where('memberid', $sponsorid)->first();
            if ($data) {
                // <span class="text-danger">This Sponsor Id is not active. Please change sponsor id</span>
                if ($data['status'] == 'Temp') {
                    return response()->json([
                        'code' => 0,
                        'data' => '<span class="text-success">Sponsor Name :'.$data['name'].'</span>',
                    ]);
                } elseif ($data['status'] == 'Deactive') {
                    return response()->json([
                        'code' => 0,
                        'data' => '<span class="text-danger">This Sponsor Id is Deactive. Please change sponsor id</span>',
                    ]);
                } elseif ($data['status'] == 'Active') {
                    return response()->json([
                        'code' => 1,
                        'data' => '<span class="text-success">Sponsor Name :'.$data['name'].'</span>',
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 1,
                    'data' => '<span class="text-danger">No Data Found with this Id</span>',
                ]);
            }
        } else {
            return response()->json([
                'code' => 1,
                'data' => '',
            ]);
        }
    }

    public function sendRegisterOtp(Request $request)
    {
        $email = $request->post('email');

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'code' => 0,
                'data' => '<span class="text-danger">Please enter a valid email address</span>',
            ]);
        }

        try {
            // Check if email already exists
            $existingUser = MemberDetail::where('email', $email)->first();
            if ($existingUser) {
                return response()->json([
                    'code' => 0,
                    'data' => '<span class="text-danger">This email is already registered. Please use a different email address.</span>',
                ]);
            }

            $otp = rand(100000, 999999);
            $request->session()->put('register_otp', $otp);
            $request->session()->put('register_email', $email);
            $request->session()->put('register_otp_time', time());

            $mailData = [
                'otp' => $otp,
            ];
            $user['to'] = $email;
            $fromAddress = config('mail.from.address', 'support@mathwallet.live');
            $fromName = config('mail.from.name', config('detailsApp.name', 'Math Wallet'));

            Mail::send([
                'html' => 'member.mails.register-otp',
                'text' => 'member.mails.register-otp-text',
            ], $mailData, function ($message) use ($user, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName);
                $message->replyTo($fromAddress, $fromName);
                $message->to($user['to']);
                $message->subject('Verify Your Email - '.config('detailsApp.name'));
            });

            return response()->json([
                'code' => 1,
                'data' => '<span class="text-success">OTP has been sent to your email. Please check your inbox.</span>',
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ]);
        } catch (GlobalThrowable $e) {
            Log::error('sendRegisterOtp failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            $errorText = app()->environment('local')
                ? 'Server error: '.e($e->getMessage())
                : 'Failed to send OTP. Please try again.';

            return response()->json([
                'code' => 0,
                'data' => '<span class="text-danger">'.$errorText.'</span>',
            ]);
        }
    }

    public function verifyRegisterOtp(Request $request)
    {
        $enteredOtp = $request->post('otp');
        $sessionOtp = session()->get('register_otp');
        $sessionEmail = session()->get('register_email');
        $otpTime = session()->get('register_otp_time');

        if (! $sessionOtp || ! $sessionEmail || ! $otpTime) {
            return response()->json([
                'code' => 0,
                'data' => '<span class="text-danger">OTP session expired. Please request a new OTP.</span>',
            ]);
        }

        // Check if OTP is expired (10 minutes)
        if (time() - $otpTime > 600) {
            session()->forget(['register_otp', 'register_email', 'register_otp_time']);

            return response()->json([
                'code' => 0,
                'data' => '<span class="text-danger">OTP has expired. Please request a new OTP.</span>',
            ]);
        }

        if ($enteredOtp == $sessionOtp) {
            // Mark email as verified
            session()->put('email_verified', true);
            session()->put('verified_email', $sessionEmail);

            return response()->json([
                'code' => 1,
                'data' => '<span class="text-success">Email verified successfully! You can now complete your registration.</span>',
                'message' => 'Email verified successfully',
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'data' => '<span class="text-danger">Invalid OTP. Please try again.</span>',
            ]);
        }
    }

    /**
     * Display Promotional Banners gallery page (Dynamic Banners).
     */
    public function promotionalBanners(Request $request)
    {
        AdminMediaController::ensureBootstrapped();

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();
        $data = $member;

        $dbBanners = PromotionBanner::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $banners = [];
        foreach ($dbBanners as $b) {
            $banners[] = [
                'id' => $b->id,
                'filename' => basename($b->image_path),
                'title' => $b->title,
                'tag' => $b->tag ?: 'Math Wallet Official',
                'url' => $b->image_url,
                'download_url' => route('member.promotional-banners.download', ['filename' => $b->id]),
                'external_link' => $b->external_link,
            ];
        }

        $baseUrl = $request->getSchemeAndHttpHost();
        $referralLink = $baseUrl.'/member/register/'.($member->memberid ?? '');

        return view('member.promotional-banners', compact('data', 'member', 'banners', 'referralLink'));
    }

    /**
     * Download Promotional Banner image with attachment headers.
     */
    public function downloadBanner(string $filename)
    {
        $cleanFilename = basename($filename);

        $banner = PromotionBanner::where('id', $filename)
            ->orWhere('image_path', $cleanFilename)
            ->first();

        if ($banner) {
            $candidates = [
                public_path('uploads/banners/'.$banner->image_path),
                public_path('uassets/mw_banners/'.$banner->image_path),
                public_path('uploads/'.$banner->image_path),
            ];
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    return response()->download($cand, basename($cand));
                }
            }
        }

        $fallback = public_path('uassets/mw_banners/'.$cleanFilename);
        if (file_exists($fallback)) {
            return response()->download($fallback, $cleanFilename);
        }

        abort(404, 'Banner image not found.');
    }

    /**
     * Display Business Plan PDF presentation page (Dynamic Multilingual PDFs).
     */
    public function businessPlanPdf(Request $request)
    {
        AdminMediaController::ensureBootstrapped();

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();
        $data = $member;

        $dbPdfs = BusinessPlanDocument::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $pdfs = [];
        foreach ($dbPdfs as $pdf) {
            $pdfs[] = [
                'id' => $pdf->id,
                'filename' => $pdf->file_path,
                'language' => $pdf->language,
                'native_language' => $pdf->native_language ?: $pdf->language,
                'flag' => $pdf->flag ?: '📄',
                'flag_code' => $pdf->flag_code ?: 'global',
                'badge' => $pdf->badge ?: 'Official Edition',
                'badge_color' => $pdf->badge_color ?: '#3b82f6',
                'gradient' => $pdf->gradient ?: 'linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.05) 100%)',
                'border_color' => $pdf->border_color ?: 'rgba(59, 130, 246, 0.3)',
                'icon_color' => $pdf->icon_color ?: '#60a5fa',
                'title' => $pdf->title,
                'description' => $pdf->description,
                'size' => $pdf->file_size ?: 'PDF',
                'pages_hint' => $pdf->pages_hint ?: 'Full Pitch Deck',
                'url' => $pdf->file_url,
                'download_url' => route('member.business-plan-pdf.download', ['filename' => $pdf->id]),
            ];
        }

        $baseUrl = $request->getSchemeAndHttpHost();
        $referralLink = $baseUrl.'/member/register/'.($member->memberid ?? '');

        return view('member.business-plan-pdf', compact('data', 'member', 'pdfs', 'referralLink'));
    }

    /**
     * Download Business Plan PDF file with attachment headers.
     */
    public function downloadPdf(string $filename)
    {
        $cleanFilename = basename($filename);

        $doc = BusinessPlanDocument::where('id', $filename)
            ->orWhere('file_path', $cleanFilename)
            ->first();

        if ($doc && $doc->disk_path && file_exists($doc->disk_path)) {
            return response()->download($doc->disk_path, basename($doc->disk_path));
        }

        $fallback = public_path('uassets/mw_pdf/'.$cleanFilename);
        if (file_exists($fallback)) {
            return response()->download($fallback, $cleanFilename);
        }

        abort(404, 'Business plan PDF not found.');
    }

    /**
     * Display Plan Video presentation page (Dynamic Multi-Video).
     */
    public function planVideo(Request $request)
    {
        AdminMediaController::ensureBootstrapped();

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();
        $data = $member;
        $baseUrl = $request->getSchemeAndHttpHost();
        $referralLink = $baseUrl.'/member/register/'.($member->memberid ?? '');

        $query = MemberVideo::where('video_type', 'plan')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('tag', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        $allVideos = (clone $query)->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        $videos = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        $selectedVideo = null;
        if ($request->filled('v')) {
            $selectedVideo = $allVideos->firstWhere('id', (int) $request->input('v'));
        }
        if (! $selectedVideo) {
            $selectedVideo = $allVideos->first();
        }

        $video = null;
        if ($selectedVideo) {
            $video = [
                'id' => $selectedVideo->id,
                'filename' => $selectedVideo->video_file ?: 'Video Stream',
                'title' => $selectedVideo->title,
                'tag' => $selectedVideo->tag ?: 'Official Presentation',
                'duration' => $selectedVideo->duration ?: 'Full Video',
                'size' => $selectedVideo->duration ?: 'HD Video',
                'format' => $selectedVideo->isYouTube() ? 'YouTube Video' : 'MP4 Video',
                'url' => $selectedVideo->video_play_url,
                'embed_url' => $selectedVideo->embed_url,
                'is_youtube' => $selectedVideo->isYouTube(),
                'download_url' => route('member.plan-video.download', ['filename' => $selectedVideo->id]),
                'description' => $selectedVideo->description,
                'can_download' => $selectedVideo->canDownload(),
                'thumbnail_url' => $selectedVideo->thumbnail_url,
            ];
        }

        return view('member.plan-video', compact('data', 'member', 'video', 'videos', 'selectedVideo', 'allVideos', 'referralLink'));
    }

    /**
     * Download Plan Video file with attachment headers.
     */
    public function downloadVideo(string $filename)
    {
        $cleanFilename = basename($filename);

        $video = MemberVideo::where('video_type', 'plan')
            ->where(function ($q) use ($filename, $cleanFilename) {
                $q->where('id', $filename)->orWhere('video_file', $cleanFilename);
            })
            ->first();

        if ($video && $video->disk_path && file_exists($video->disk_path)) {
            return response()->download($video->disk_path, basename($video->disk_path));
        }

        $fallback = public_path('uassets/mw_plan_video/'.$cleanFilename);
        if (file_exists($fallback)) {
            return response()->download($fallback, 'Math_Wallet_Business_Plan.mp4');
        }

        abort(404, 'Plan video file not found.');
    }

    /**
     * Display Tutorial Video page (Dynamic Multi-Video).
     */
    public function tutorialVideo(Request $request)
    {
        AdminMediaController::ensureBootstrapped();

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();
        $data = $member;
        $baseUrl = $request->getSchemeAndHttpHost();
        $referralLink = $baseUrl.'/member/register/'.($member->memberid ?? '');

        $query = MemberVideo::where('video_type', 'tutorial')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('tag', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        $allVideos = (clone $query)->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        $videos = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        $selectedVideo = null;
        if ($request->filled('v')) {
            $selectedVideo = $allVideos->firstWhere('id', (int) $request->input('v'));
        }
        if (! $selectedVideo) {
            $selectedVideo = $allVideos->first();
        }

        $video = null;
        if ($selectedVideo) {
            $video = [
                'id' => $selectedVideo->id,
                'filename' => $selectedVideo->video_file ?: 'Video Stream',
                'title' => $selectedVideo->title,
                'tag' => $selectedVideo->tag ?: 'Official Tutorial',
                'duration' => $selectedVideo->duration ?: 'Step-by-Step Guide',
                'size' => $selectedVideo->duration ?: 'HD Guide',
                'format' => $selectedVideo->isYouTube() ? 'YouTube Video' : 'MP4 Video',
                'url' => $selectedVideo->video_play_url,
                'embed_url' => $selectedVideo->embed_url,
                'is_youtube' => $selectedVideo->isYouTube(),
                'download_url' => route('member.tutorial-video.download', ['filename' => $selectedVideo->id]),
                'description' => $selectedVideo->description,
                'can_download' => $selectedVideo->canDownload(),
                'thumbnail_url' => $selectedVideo->thumbnail_url,
            ];
        }

        return view('member.tutorial-video', compact('data', 'member', 'video', 'videos', 'selectedVideo', 'allVideos', 'referralLink'));
    }

    /**
     * Download Tutorial Video file with attachment headers.
     */
    public function downloadTutorialVideo(string $filename)
    {
        $cleanFilename = basename($filename);

        $video = MemberVideo::where('video_type', 'tutorial')
            ->where(function ($q) use ($filename, $cleanFilename) {
                $q->where('id', $filename)->orWhere('video_file', $cleanFilename);
            })
            ->first();

        if ($video && $video->disk_path && file_exists($video->disk_path)) {
            return response()->download($video->disk_path, basename($video->disk_path));
        }

        $fallback = public_path('uassets/mw_Tutorial_video/'.$cleanFilename);
        if (file_exists($fallback)) {
            return response()->download($fallback, 'Math_Wallet_Tutorial_Guide.mp4');
        }

        abort(404, 'Tutorial video file not found.');
    }

    public function singlrLegDetails()
    {
        $memberid = session('MEMBER_ID');

        $data = MemberDetail::where('memberid', $memberid)->first();

        if (! $data || empty($data->activated_at)) {
            $totalMembers = 0;
            $membersDetails = collect();
            $totalActiveMembers = 0;
        } else {
            $activated_at = $data->activated_at;
            $memberDbId = $data->id;

            // Details — Active members first (sorted by activation), then Inactive members (sorted by registration)
            $membersDetails = MemberDetail::where('id', '!=', $memberDbId)
                ->where('memberid', '!=', $memberid)
                ->where(function ($q) use ($activated_at) {
                    $q->where(function ($subActive) use ($activated_at) {
                        $subActive->where('status', 'Active')
                            ->whereNotNull('activated_at')
                            ->where('activated_at', '>=', $activated_at);
                    })->orWhere(function ($subInactive) use ($activated_at) {
                        $subInactive->where('status', '!=', 'Active')
                            ->where(function ($dateQ) use ($activated_at) {
                                $dateQ->where('created_at', '>=', $activated_at)
                                    ->orWhere(function ($actQ) use ($activated_at) {
                                        $actQ->whereNotNull('activated_at')
                                            ->where('activated_at', '>=', $activated_at);
                                    });
                            });
                    });
                })
                ->orderByRaw("
                    CASE
                        WHEN status = 'Active' THEN 0
                        ELSE 1
                    END ASC
                ")
                ->orderByRaw("
                    CASE
                        WHEN status = 'Active' THEN activated_at
                        ELSE created_at
                    END ASC
                ")
                ->get();

            $totalActiveMembers = $membersDetails->where('status', 'Active')->count();
            $totalMembers = $membersDetails->count();
        }

        return view(
            'member.single-leg-details',
            compact('data', 'totalMembers', 'membersDetails', 'totalActiveMembers')
        );
    }

    public function businessPlanText(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();
        $data = $member;

        $baseUrl = $request->getSchemeAndHttpHost();
        $referralLink = $baseUrl.'/member/register/'.($member->memberid ?? '');

        return view('member.business-plan-text', compact('data', 'member', 'referralLink'));
    }
}
