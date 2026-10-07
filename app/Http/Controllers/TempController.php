<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PartnershipDetail;
use App\Models\PartnershipIncome;
use App\Models\SingleLegIncome;
use App\Services\StakingRoiService;
use Illuminate\Http\Request;

class TempController extends Controller
{
    public function dailyIncomeDis(Request $request, StakingRoiService $service)
    {
        $targetDate = $request->input('date');
        $result = $service->processDailyRoi($targetDate);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'data' => $result,
            ]);
        }

        return 'Daily ROI distribution processed successfully: '.json_encode($result);
    }

    public function singleLegIncome()
    {
        $levels = [
            1 => ['staking' => 0,      'commision' => 2,       'team' => 25,       'rank_status' => 0],
            2 => ['staking' => 0,      'commision' => 5,       'team' => 100,      'rank_status' => 1],
            3 => ['staking' => 0,      'commision' => 9,       'team' => 300,      'rank_status' => 2],
            4 => ['staking' => 20,     'commision' => 18,      'team' => 750,      'rank_status' => 3],
            5 => ['staking' => 50,     'commision' => 40,      'team' => 1750,     'rank_status' => 4],
            6 => ['staking' => 120,    'commision' => 100,     'team' => 4750,     'rank_status' => 5],
            7 => ['staking' => 270,    'commision' => 250,     'team' => 11250,    'rank_status' => 6],
            8 => ['staking' => 570,    'commision' => 500,     'team' => 26250,    'rank_status' => 7],
            9 => ['staking' => 1170,   'commision' => 1000,    'team' => 66250,    'rank_status' => 8],
            10 => ['staking' => 2370,   'commision' => 2000,    'team' => 166250,   'rank_status' => 9],
            11 => ['staking' => 4870,   'commision' => 5000,    'team' => 366250,   'rank_status' => 10],
            12 => ['staking' => 9870,   'commision' => 10000,   'team' => 766250,   'rank_status' => 11],
            13 => ['staking' => 17870,  'commision' => 15000,   'team' => 1366250,  'rank_status' => 12],
            14 => ['staking' => 27870,  'commision' => 20000,   'team' => 2166250,  'rank_status' => 13],
            15 => ['staking' => 52870,  'commision' => 50000,   'team' => 3166250,  'rank_status' => 14],
        ];

        foreach ($levels as $level => $data) {

            $members = MemberDetail::where([['status', 'Active'], ['rank_status', $data['rank_status']], ['self_biz', '>=', $data['staking']]])->get();
            foreach ($members as $value) {
                $memberid = $value->memberid;
                $self_biz = $value->self_biz;
                $activated_at = $value->activated_at;

                $totalMembers = MemberDetail::where([['id', '!=', $value->id], ['activated_at', '>=', $activated_at], ['status', 'Active']])->count();

                if ($totalMembers >= $data['team']) {
                    $level = $level;
                    $amount = $data['commision'];

                    $alreadyPaid = SingleLegIncome::where([
                        ['memberid', $memberid],
                        ['level', $level],
                    ])->exists();
                    if ($amount > 0 && ! $alreadyPaid) {

                        $insert = new SingleLegIncome;
                        $insert->memberid = $memberid;
                        $insert->level = $level;
                        $insert->team = $totalMembers;
                        $insert->amount = $amount;
                        $insert->staking = $self_biz;
                        $insert->status = 'Paid';
                        $insert->save();

                        $value->rank_status += 1;
                        $wallet = $value->wallet;
                        $value->wallet += $amount;
                        $value->save();

                        walletTransfer($memberid, $amount, 'debit', $wallet, 'Single Leg Income', 'Single Leg Income Amount Added into wallet.');
                    }
                }
            }
        }
    }

    public function dailyPartTeamBizUpdate()
    {
        $update = MemberDetail::where('status', '!=', 'Temp')->update(['part_daily_team_biz' => 0, 'daily_team_biz' => 0]);
    }

    public function partnershipIncomeDis()
    {
        $stakings = PartnershipDetail::where('status', 'Active')->get();
        foreach ($stakings as $value) {
            $memberid = $value->memberid;
            $rank = $value->rank;
            $achieved = $value->achieved;
            $capping = $value->capping;
            $installment = $value->installments + 1;
            $rate = $value->rate;
            $invest_id = $value->id;

            // $achieved = PartnershipIncome::where([['status', 'Paid'], ['memberid', $memberid], ['invest_id', $invest_id]])->sum('amount');
            if ($achieved >= $capping) {
                $value->status = 'Deactive';
                $value->save();
            }

            $memUpdate = MemberDetail::where('memberid', $memberid)->first();
            $stack_dailyTeamBiz = $memUpdate->daily_team_biz;
            $part_dailyTeamBiz = $memUpdate->part_daily_team_biz;
            $dailyTeamBiz = $stack_dailyTeamBiz + $part_dailyTeamBiz;

            if ($dailyTeamBiz <= 0) {
                continue;
            }
            $_amount = $dailyTeamBiz / 100 * $rate;
            $balance = $capping - $achieved;
            if ($balance <= $_amount) {
                $amount = $balance;
            } else {
                $amount = $_amount;
            }

            if ($amount > 0) {

                $update = PartnershipDetail::where('id', $invest_id)->first();
                $update->installments += 1;
                $update->achieved = $achieved + $amount;
                if ($balance <= $_amount) {
                    $update->status = 'Deactive';
                }
                $update->save();

                $qry = new PartnershipIncome;
                $qry->date = date('Y-m-d');
                $qry->invest_id = $invest_id;
                $qry->memberid = $memberid;
                $qry->total_investment = $value->invest_amount;
                $qry->rate = $rate;
                $qry->rank = $rank;
                $qry->daily_team_biz = $dailyTeamBiz;
                $qry->achieved = $update->achieved;
                $qry->amount = $amount;
                $qry->installment = $installment;
                $qry->status = 'Paid';
                $qry->save();

                $wallet = $memUpdate->wallet;
                $memUpdate->wallet += $amount;
                $memUpdate->save();

                walletTransfer($memberid, $amount, 'debit', $wallet, 'Partnership Income', 'Partnership Income amount has been transfered into wallet.');
            } else {
                $value->status = 'Deactive';
                $value->save();
            }
        }
    }

    //  use for testing purpose only
    public function addTempMember($Sponsorid)
    {
        for ($i = 1; $i <= 50; $i++) {

            $memberid = userId();

            $var = new MemberDetail;
            $var->memberid = $memberid;
            $var->member_wallet = 'xyz';
            $var->sponsorid = $Sponsorid;
            $var->name = 'test'.$i;
            $var->email = 'test'.$i.'@gmail.com';
            $var->mobile = '0123456789';
            $var->phonecode = '+91';
            $var->country = 'India';
            $var->status = 'Temp';
            $var->p2p_wallet = 10000;
            $var->status = 'Active';
            $var->activated_at = now();
            $var->activation_amount = 30;
            $var->save();
            updateDownline($Sponsorid);
            updateUpline($Sponsorid, $memberid);
            team_update($Sponsorid);

            $new = new PackageDetail;
            $new->memberid = $memberid;
            $new->package_type = 'Account Activation';
            $new->package_value = 30;
            $new->payment_mode = 'Fund Wallet';
            $new->txnid = 'A/'.date('YmdHis');
            $new->status = 'Accepted';
            $new->save();

            $Sponsorid = $var->memberid;

            sleep(1);

        }
    }
}
