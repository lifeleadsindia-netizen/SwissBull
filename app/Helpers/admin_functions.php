<?php

use App\Models\Country;
use App\Models\DailyIncome;
use App\Models\DirectIncome;
use App\Models\HeroOfTheMonthReward;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PartnershipIncome;
use App\Models\PepeRewardLog;
use App\Models\RoiLevelIncome;
use App\Models\SingleLegIncome;
use App\Models\StakingIncome;
use App\Models\TicketText;
use App\Models\WhatsappReferral;
use App\Models\WithdrawalIncome;
use App\Models\WithdrawalRequest;

function totalMembersadmin()
{
    $count = MemberDetail::all();

    return count($count);
}

function totalActiveMembers()
{
    $count = MemberDetail::where('status', 'Active')->get();

    return count($count);
}

function totalTempMembers()
{
    $count = MemberDetail::where('status', 'Temp')->count();

    return $count;
}

function totalblockedMembers()
{
    $count = MemberDetail::where('status', 'Blocked')->count();

    return $count;
}

function totalFundWalletBalance()
{
    $sum = MemberDetail::sum('p2p_wallet');

    return $sum;
}

function totalTradingWalletBalance()
{
    $sum = MemberDetail::sum('trading_wallet');

    return $sum;
}

function todaysWithdrawal()
{
    $sum = WithdrawalRequest::where('status', 'Approved')->whereDate('payment_date', date('Y-m-d'))->sum('gross_amount');

    return $sum;
}

function totalWithdrawal()
{
    $sum = WithdrawalRequest::where('status', 'Approved')->sum('gross_amount');

    return $sum;
}

function AdmUnread($ticketid)
{
    $count = TicketText::where([['ticket_id', $ticketid], ['written_by', 'Admin'], ['status', 'Unread']])->count();

    return $count;
}

function userUnread($ticketid)
{
    $count = TicketText::where([['ticket_id', $ticketid], ['written_by', 'Member'], ['status', 'Unread']])->count();

    return $count;
}

function totalLevelIn()
{
    $sum = LevelIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalDirectIn()
{
    $sum = DirectIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalRoiIn()
{
    $sum = DailyIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminDirectIncome()
{
    return totalDirectIn();
}

function totalAdminHeroOfTheMonthIncome()
{
    $sum = HeroOfTheMonthReward::where('status', 'Paid')->sum('prize_amount');

    return $sum;
}

function totalAdminMonthlyTradingProfit()
{
    return totalAdminRoiIncome();
}

function totalAdminReferralBonus()
{
    return totalAdminDirectIncome();
}

function totalAdminTeamTradingProfit()
{
    return totalAdminStakingLevelIncome();
}

function totalAdminDailyTeamInvestmentShare()
{
    return totalAdminLevelIncome();
}

function totalIn()
{
    $sum = totalAdminRoiIncome()
        + totalAdminDirectIncome()
        + totalAdminStakingLevelIncome()
        + totalAdminLevelIncome()
        + totalAdminHeroOfTheMonthIncome()
        + totalAdminPartnershipIncome()
        + totalAdminSingleLegIncome();

    return $sum;
}

function totalAdminRoiIncome()
{
    $sum = StakingIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminStakingLevelIncome()
{
    $sum = RoiLevelIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminLevelIncome()
{
    $sum = LevelIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminSingleLegIncome()
{
    $sum = SingleLegIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminPartnershipIncome()
{
    $sum = PartnershipIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalAdminTeamWithdrawalCommissionIncome()
{
    $sum = WithdrawalIncome::where('status', 'Paid')->sum('amount');

    return $sum;
}

function totalWalletBalance()
{
    $sum = MemberDetail::sum('wallet');

    return $sum;
}

function totalPEPEWalletBalance()
{
    $sum = MemberDetail::sum('pepe_wallet');

    return $sum;
}

// function totalP2PBalance()
// {
//     $sum = MemberDetail::where('status', 'Active')->sum('p2p_wallet');
//     return $sum;
// }

// function newSupportRequests()
// {
//     $count = SupportTicket::where('support_status', 'New')->count();
//     return $count;
// }

function todayUpgrade()
{
    $var = PackageDetail::where([['package_type', 'Upgradation'], ['status', 'Accepted']])->whereDate('created_at', date('Y-m-d'))->sum('package_value');

    return $var;
}

function todayActivation()
{
    $var = PackageDetail::where([['package_type', 'Activation'], ['status', 'Accepted']])->whereDate('created_at', date('Y-m-d'))->sum('package_value');

    return $var;
}

function totalAvtivation()
{
    $var = PackageDetail::where([['package_type', 'Activation'], ['status', 'Accepted']])->sum('package_value');

    return $var;
}

function totalUpgrade()
{
    $var = PackageDetail::where([['package_type', 'Upgradation'], ['status', 'Accepted']])->sum('package_value');

    return $var;
}

function totalgrossAmount()
{
    $count = WithdrawalRequest::where('type', 'USDT')->sum('gross_amount');

    return $count;
}

function totalnetAmount()
{
    $count = WithdrawalRequest::where('type', 'USDT')->sum('net_amount');

    return $count;
}

function totaldeductions()
{
    $count = WithdrawalRequest::where('type', 'USDT')->sum('service_charge');

    return $count;
}

function pendingwithrawalreq()
{
    $count = WithdrawalRequest::where('status', 'Pending')->count();

    return $count;
}

function Cancelwithrawalreq()
{
    $count = WithdrawalRequest::where('status', 'Cancelled')->count();

    return $count;
}

function Approvwithrawalreq()
{
    $count = WithdrawalRequest::where('status', 'Approved')->count();

    return $count;
}

function TotalInvestment()
{
    $sum = PackageDetail::where('status', 'Accepted')->sum('package_value');

    return $sum;
}

function totalInc()
{
    $sum = totalDepositInc() + totalWithdrawalInc();

    return $sum;
}

function totalDepositInc()
{
    $var = LevelIncome::where([['status', 'Paid'], ['type', 'Deposit']])->sum('amount');

    return $var;
}

function totalWithdrawalInc()
{
    $var = LevelIncome::where([['status', 'Paid'], ['type', 'Withdrawal']])->sum('amount');

    return $var;
}

function getCurrencySymbol($memberid)
{
    $country = MemberDetail::where('memberid', $memberid)->first()->country;
    $currency = Country::where('name', $country)->first();

    return $currency->symbol;
}

function todayPEPEWithdrawal()
{
    $sum = PepeRewardLog::whereDate('created_at', date('Y-m-d'))->sum('reward_amount');
    if ($sum <= 0) {
        $sum = WhatsappReferral::where('status', 'Completed')->whereDate('created_at', date('Y-m-d'))->sum('reward_amount');
    }

    return $sum;
}

function totalPEPEWithdrawal()
{
    $sum = PepeRewardLog::sum('reward_amount');
    if ($sum <= 0) {
        $sum = WhatsappReferral::where('status', 'Completed')->sum('reward_amount');
    }

    return $sum;
}

function adminIncomeOverviewChartData($month = null, $year = null)
{
    if (! $month) {
        $month = date('m');
    }
    if (! $year) {
        $year = date('Y');
    }

    $daysInMonth = (int) date('t', strtotime("$year-$month-01"));
    $monthName = date('M', strtotime("$year-$month-01"));

    $checkpoints = [
        ['day' => 1, 'start' => 1, 'end' => 4, 'label' => "1 $monthName"],
        ['day' => 5, 'start' => 5, 'end' => 9, 'label' => "5 $monthName"],
        ['day' => 10, 'start' => 10, 'end' => 14, 'label' => "10 $monthName"],
        ['day' => 15, 'start' => 15, 'end' => 19, 'label' => "15 $monthName"],
        ['day' => 20, 'start' => 20, 'end' => 24, 'label' => "20 $monthName"],
        ['day' => 25, 'start' => 25, 'end' => 29, 'label' => "25 $monthName"],
        ['day' => min(31, $daysInMonth), 'start' => 30, 'end' => $daysInMonth, 'label' => "$daysInMonth $monthName"],
    ];

    $groups = [];
    $maxVal = 0;
    $peakIndex = 4;
    $peakAmount = 0;

    foreach ($checkpoints as $idx => $cp) {
        $startDate = sprintf('%04d-%02d-%02d 00:00:00', $year, $month, $cp['start']);
        $endDate = sprintf('%04d-%02d-%02d 23:59:59', $year, $month, $cp['end']);

        $bar1 = (float) LevelIncome::where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $bar2 = (float) StakingIncome::where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        if ($bar2 <= 0) {
            $bar2 = (float) DailyIncome::where('status', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        }

        $bar3 = (float) DirectIncome::where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount')
            + (float) RoiLevelIncome::where('status', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount')
            + (float) PartnershipIncome::where('status', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

        $totalGroup = $bar1 + $bar2 + $bar3;
        if ($totalGroup > $maxVal) {
            $maxVal = $totalGroup;
            $peakIndex = $idx;
            $peakAmount = $totalGroup;
        }

        $groups[] = [
            'label' => $cp['label'],
            'bar1' => $bar1,
            'bar2' => $bar2,
            'bar3' => $bar3,
            'total' => $totalGroup,
        ];
    }

    if ($maxVal <= 0) {
        $yMax = 100;
        $peakIndex = 4;
        $peakAmount = 0;
        $peakLabel = $checkpoints[$peakIndex]['label'];
    } else {
        if ($maxVal <= 5) {
            $yMax = 10;
        } elseif ($maxVal <= 25) {
            $yMax = 30;
        } elseif ($maxVal <= 50) {
            $yMax = 60;
        } elseif ($maxVal <= 100) {
            $yMax = 120;
        } elseif ($maxVal <= 300) {
            $yMax = 300;
        } else {
            $yMax = ceil($maxVal / 100) * 100;
        }
        $peakLabel = $checkpoints[$peakIndex]['label'];
    }

    return [
        'groups' => $groups,
        'yMax' => $yMax,
        'yMid' => round($yMax * 0.66, 0),
        'yLow' => round($yMax * 0.33, 0),
        'peakIndex' => $peakIndex,
        'peakAmount' => $peakAmount,
        'peakLabel' => $peakLabel,
        'hasData' => ($maxVal > 0),
    ];
}
