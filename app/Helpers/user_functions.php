<?php

use App\Console\Commands\DailyTeamInvestmentShare;
use App\Models\Country;
use App\Models\DirectIncome;
use App\Models\HeroOfTheMonthReward;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\PartnershipIncome;
use App\Models\RoiLevelIncome;
use App\Models\SingleLegIncome;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use App\Models\UplineMember;
use App\Models\WithdrawalIncome;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Schema;

function userId()
{
    $userid = 'ST' . rand(1000000, 9999999);
    $member = MemberDetail::where('memberid', $userid)->count();
    if ($member > 0) {
        userId();
    } else {
        return $userid;
    }
}

function getName($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();
    return $member ? $member->name : ($memberid ?? 'N/A');
}

function getMobile($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();
    return $member ? $member->mobile : '';
}

function getMobileCode($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();
    return $member ? $member->phonecode : '';
}

function updateDownline($sponsorid)
{
    if ($sponsorid != 'Root') {
        $var = MemberDetail::where('memberid', $sponsorid)->first();
        $var->downline += 1;
        $var->save();
    }
}

function sponsorBizUpdate($sponsorid, $amount)
{
    if ($sponsorid != 'Root') {
        $var = MemberDetail::where('memberid', $sponsorid)->first();
        $var->direct_biz += $amount;
        $var->save();
    }
}

function updateUpline($sponsorid, $memberid)
{
    if ($memberid != 'ST1234567') {
        $pool = UplineMember::where('memberid', $sponsorid)->first();
        $var = new UplineMember;
        $var->memberid = $memberid;
        $var->upline_1 = $pool->memberid;
        $var->upline_2 = $pool->upline_1;
        $var->upline_3 = $pool->upline_2;
        $var->upline_4 = $pool->upline_3;
        $var->upline_5 = $pool->upline_4;
        $var->upline_6 = $pool->upline_5;
        $var->upline_7 = $pool->upline_6;
        $var->upline_8 = $pool->upline_7;
        $var->upline_9 = $pool->upline_8;
        $var->upline_10 = $pool->upline_9;
        $var->save();
    }
}

function totalsingleLevel($memberid, $level)
{
    $upline = 'upline_' . $level;
    $var = UplineMember::where($upline, $memberid)->count();
    return $var;
}

function team_update($sponsorid)
{
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if (empty($sponsorid) || strtoupper($sponsorid) === 'ROOT') {
            break;
        }
        $query = MemberDetail::where('memberid', $sponsorid)->first();
        if (!$query) {
            break;
        }
        $query->team += 1;
        $query->save();
        $sponsorid = $query->sponsorid;
    }
}

function team_biz_update($sponsorid, $value)
{
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if (empty($sponsorid) || strtoupper($sponsorid) === 'ROOT') {
            break;
        }
        $query = MemberDetail::where('memberid', $sponsorid)->first();
        if (!$query) {
            break;
        }
        $query->team_biz += $value;
        $query->daily_team_biz += $value;
        $query->save();
        $sponsorid = $query->sponsorid;
    }
}

function part_team_biz_update($sponsorid, $value)
{
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if (empty($sponsorid) || strtoupper($sponsorid) === 'ROOT') {
            break;
        }
        $query = MemberDetail::where('memberid', $sponsorid)->first();
        if (!$query) {
            break;
        }
        $query->partnership_team_biz += $value;
        $query->part_daily_team_biz += $value;
        $query->save();
        $sponsorid = $query->sponsorid;
    }
}

function totalMemberRoiIncome($memberid)
{
    $sum = StakingIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberStakingLevelIncome($memberid)
{
    $sum = LevelIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberLevelIncome($memberid)
{
    $sum = DailyTeamInvestmentShare::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberSingleLegIncome($memberid)
{
    $sum = SingleLegIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberPartnershipIncome($memberid)
{
    $sum = PartnershipIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberTeamWithdrawalCommissionIncome($memberid)
{
    $sum = WithdrawalIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberDirectIncome($memberid)
{
    $sum = DirectIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $sum;
}

function totalMemberHeroOfTheMonthIncome($memberid)
{
    $col = Schema::hasColumn('hero_of_the_month_rewards', 'amount') ? 'amount' : 'prize_amount';
    $sum = HeroOfTheMonthReward::where([['memberid', $memberid], ['status', 'Paid']])->sum($col);

    return (float) $sum;
}

function totalMemberMonthlyTradingProfit($memberid)
{
    return totalMemberRoiIncome($memberid);
}

function totalMemberReferralBonus($memberid)
{
    return totalMemberDirectIncome($memberid);
}

function totalMemberTeamTradingProfit($memberid)
{
    return totalMemberStakingLevelIncome($memberid);
}

function totalMemberDailyTeamInvestmentShare($memberid)
{
    return totalMemberLevelIncome($memberid);
}

function totalIncome($memberid)
{
    $sum = totalMemberRoiIncome($memberid)
        + totalMemberDirectIncome($memberid)
        + totalMemberStakingLevelIncome($memberid)
        + totalMemberLevelIncome($memberid)
        + totalMemberHeroOfTheMonthIncome($memberid)
        + totalMemberPartnershipIncome($memberid)
        + totalMemberSingleLegIncome($memberid);

    return $sum;
}

function totalWithdrawalIncome($memberid)
{
    $var = WithdrawalIncome::where([['memberid', $memberid], ['status', 'Paid'], ['type', 'Withdrawal']])->sum('amount');
    return $var;
}

function totalRoiIncome($memberid)
{
    $var = StakingIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');
    return $var;
}

function todayWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved']])
        ->whereNotIn('type', ['Airdrop Withdrawal', 'Trading Withdrawal'])
        ->where(function ($q) {
            $q->whereDate('payment_date', date('Y-m-d'))
                ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->sum('gross_amount');

    return (float) $var;
}

function totalWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved']])
        ->whereNotIn('type', ['Airdrop Withdrawal', 'Trading Withdrawal'])
        ->sum('gross_amount');

    return (float) $var;
}

function todayTradingWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved'], ['type', 'Trading Withdrawal']])
        ->where(function ($q) {
            $q->whereDate('payment_date', date('Y-m-d'))

                ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->sum('gross_amount');

    return (float) $var;
}


function totalTradingWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved'], ['type', 'Trading Withdrawal']])
        ->sum('gross_amount');

    return (float) $var;
}

function totalLevelMembers($memberid)
{
    $total = 0;
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        $upline = 'upline_' . $i;
        $var = UplineMember::where($upline, $memberid)->count();
        $total += $var;
    }

    return $total;
}

function getSponsorid($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();
    return $member['sponsorid'];
}

function getAllData($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    return $var;
}

function getCountryData($country)
{
    $var = Country::where('name', $country)->first();
    return $var->symbol;
}

function totalEarning($id)
{
    $totalEarning = StakingIncome::where('staking_id', $id)->sum('amount');
    return $totalEarning;
}

function maxEarning($id)
{
    $maxEarning = StakingDetail::find($id);
    $trading_wallet_amount = $maxEarning->trading_wallet_amount;

    $cap = PackageDistribution::find(1);
    $capping = $cap->capping;

    if ($capping == 0) {
        return 'No Limit';
    } else {
        return $trading_wallet_amount * ($capping / 100);
    }
}

function monthlyMaxBiz()
{
    // $start = date('Y-m-01', strtotime('-1 month'));
    // $last  = date('Y-m-t', strtotime('-1 month'));

    $query = MemberDetail::where([['status', 'Active'], ['direct_biz', '>', 0]]);
    // ->whereBetween('created_at', [$start, $last]);

    $maxBiz = (clone $query)->max('direct_biz');

    if ($maxBiz === null) {
        return [
            'memberid' => null,
            'direct_biz' => null,
            'member_count' => 0,
        ];
    }

    $members = (clone $query)
        ->where('direct_biz', $maxBiz)
        ->get(['memberid', 'direct_biz']);

    return [
        'memberid' => $members->pluck('memberid'),
    ];
}
