<?php

use App\Models\Country;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PartnershipIncome;
use App\Models\RoiLevelIncome;
use App\Models\SingleLegIncome;
use App\Models\StakingIncome;
use App\Models\UplineMember;
use App\Models\WithdrawalIncome;
use App\Models\WithdrawalRequest;

function userId()
{
    $userid = 'MW'.rand(1000000, 9999999);
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

    return $member->name;
}

function getMobile($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();

    return $member->mobile;
}

function getMobileCode($memberid)
{
    $member = MemberDetail::where('memberid', $memberid)->first();

    return $member->phonecode;
}

function updateDownline($sponsorid)
{
    if ($sponsorid != 'Root') {
        $var = MemberDetail::where('memberid', $sponsorid)->first();
        $var->downline += 1;
        $var->save();
    }
}

function updateUpline($sponsorid, $memberid)
{
    if ($memberid != 'MW1234567') {
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
    $upline = 'upline_'.$level;
    $var = UplineMember::where($upline, $memberid)->count();

    return $var;
}

function team_update($sponsorid)
{

    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if ($sponsorid != 'Root') {
            $query = MemberDetail::where('memberid', $sponsorid)->first();
            $query->team += 1;
            $query->save();
            $sponsorid = $query->sponsorid;
        } else {
            break;
        }
    }
}

function team_biz_update($sponsorid, $value)
{
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if ($sponsorid != 'Root') {
            $query = MemberDetail::where('memberid', $sponsorid)->first();
            $query->team_biz += $value;
            $query->daily_team_biz += $value;
            $query->save();
            $sponsorid = $query->sponsorid;
        } else {
            break;
        }
    }
}

function part_team_biz_update($sponsorid, $value)
{
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        if ($sponsorid != 'Root') {
            $query = MemberDetail::where('memberid', $sponsorid)->first();
            $query->partnership_team_biz += $value;
            $query->part_daily_team_biz += $value;
            $query->save();
            $sponsorid = $query->sponsorid;
        } else {
            break;
        }
    }
}

function totalMemberRoiIncome($memberid)
{
    $sum = StakingIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');

    return $sum;
}

function totalMemberStakingLevelIncome($memberid)
{
    $sum = RoiLevelIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');

    return $sum;
}

function totalMemberLevelIncome($memberid)
{
    $sum = LevelIncome::where([['memberid', $memberid], ['status', 'Paid']])->sum('amount');

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

function totalIncome($memberid)
{
    $sum = totalMemberRoiIncome($memberid) + totalMemberStakingLevelIncome($memberid) + totalMemberLevelIncome($memberid) + totalMemberSingleLegIncome($memberid) + totalMemberPartnershipIncome($memberid) + totalMemberTeamWithdrawalCommissionIncome($memberid);

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
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved'], ['type', 'USDT']])->whereDate('created_at', date('Y-m-d'))->sum('gross_amount');

    return $var;
}

function totalWithdrawals($memberid)
{
    $var = WithdrawalRequest::where([['memberid', $memberid], ['status', 'Approved'], ['type', 'USDT']])->sum('gross_amount');

    return number_format($var, 2);
}

function totalLevelMembers($memberid)
{
    $total = 0;
    $limit = 11;
    for ($i = 1; $i < $limit; $i++) {
        $upline = 'upline_'.$i;
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
