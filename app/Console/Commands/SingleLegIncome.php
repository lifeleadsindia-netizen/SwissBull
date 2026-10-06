<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\SingleLegIncome as ModelsSingleLegIncome;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:single-leg-income')]
#[Description('Command description')]
class SingleLegIncome extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
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

                    $alreadyPaid = ModelsSingleLegIncome::where([
                        ['memberid', $memberid],
                        ['level', $level],
                    ])->exists();
                    if ($amount > 0 && ! $alreadyPaid) {

                        $insert = new ModelsSingleLegIncome;
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
}
