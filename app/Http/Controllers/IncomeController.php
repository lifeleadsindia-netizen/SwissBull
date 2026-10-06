<?php

namespace App\Http\Controllers;

use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\RoiLevelIncome;
use App\Models\SingleLegIncome;
use App\Models\StakingIncome;

class IncomeController extends Controller
{
    public function roiIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['rData'] = StakingIncome::where([['memberid', $result['data']['memberid']], ['status', 'Paid']])->orderby('created_at', 'desc')->get();

        return view('member.income.roi-incomes')->with($result);
    }

    public function stakingLevelIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['sData'] = RoiLevelIncome::where([['memberid', $result['data']['memberid']], ['status', 'Paid']])->orderby('created_at', 'desc')->get();

        return view('member.income.staking-level-incomes')->with($result);
    }

    public function levelIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['lData'] = LevelIncome::where('memberid', $result['data']['memberid'])->orderby('created_at', 'desc')->get();

        return view('member.income.level-incomes')->with($result);
    }

    public function singleLegIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['sData'] = SingleLegIncome::where([['memberid', $result['data']['memberid']], ['status', 'Paid']])->orderby('created_at', 'desc')->get();

        return view('member.income.singleleg-incomes')->with($result);
    }

    public function partnershipIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['pData'] = DirectIncome::where([['memberid', $result['data']['memberid']], ['status', 'Paid']])->orderby('created_at', 'desc')->get();

        return view('member.income.partnership-incomes')->with($result);
    }

    public function withdrawalCommissionIncome()
    {
        $result['data'] = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
        $result['wData'] = DirectIncome::where([['memberid', $result['data']['memberid']], ['status', 'Paid']])->orderby('created_at', 'desc')->get();

        return view('member.income.withdrawal-Commission')->with($result);
    }
}
