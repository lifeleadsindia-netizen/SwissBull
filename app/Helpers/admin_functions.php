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
        + totalAdminSingleLegIncome()
        + totalAdminTeamWithdrawalCommissionIncome();

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
