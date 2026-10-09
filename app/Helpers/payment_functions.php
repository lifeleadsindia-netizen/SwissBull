<?php

use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\ReferralBonusConfiction;
use App\Models\StakingDetail;
use App\Models\WalletTransfer;
use App\Models\WithdrawalIncome;
use App\Models\WithdrawalRequest;

function walletTransfer($memberid, $amount, $type, $wallet, $walletType, $text)
{
    if ($type == 'debit') {
        $ins = new WalletTransfer;
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->debit = $amount;
        $ins->balance = $wallet + $amount;
        $ins->particular = $text;
        $ins->save();
    } elseif ($type == 'credit') {
        $ins = new WalletTransfer;
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->credit = $amount;
        $ins->balance = $wallet - $amount;
        $ins->particular = $text;
        $ins->save();
    }
}

function p2pwalletTransfer($memberid, $amount, $type, $p2pwallet, $walletType, $text)
{
    if ($type == 'debit') {
        $ins = new WalletTransfer;
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->debit = $amount;
        $ins->balance = $p2pwallet + $amount;
        $ins->particular = $text;
        $ins->save();
    } elseif ($type == 'credit') {
        $ins = new WalletTransfer;
        $ins->memberid = $memberid;
        $ins->walletType = $walletType;
        $ins->credit = $amount;
        $ins->balance = $p2pwallet - $amount;
        $ins->particular = $text;
        $ins->save();
    }
}

function levelRate($level)
{
    return ($level >= 1 && $level <= 10) ? 1.00 : 0.00;
}

function withdrawalIncome($sponsorid, $memberid, $name, $id, $type)
{
    if ($sponsorid != 'Root') {

        $var = WithdrawalRequest::find($id);
        $gross_amount = $var->gross_amount;
        $service_charge = $var->gross_amount / 10;
        $limit = 6;

        for ($i = 1; $i < $limit; $i++) {
            $level = $i;
            $rate = 2;
            $amount = $gross_amount * $rate / 100;

            if ($sponsorid != 'Root') {
                $var = MemberDetail::where('memberid', $sponsorid)->first();
                $status = $var->status;
                $downline = $var->downline;

                if ($amount > 0 && $status == 'Active') {

                    $insert = new WithdrawalIncome;
                    $insert->memberid = $sponsorid;
                    $insert->level = $level;
                    $insert->level_id = $memberid;
                    $insert->amount = $amount;
                    $insert->downline = $downline;
                    $insert->rate = $rate;
                    $insert->withdrawal_charge = $service_charge;
                    $insert->withdrawal_amount = $gross_amount;
                    $insert->name = $name;
                    $insert->type = $type;

                    if ($level == 1 && $downline >= 5 || $level == 2 && $downline >= 10 || $level == 3 && $downline >= 15 || $level == 4 && $downline >= 20 || $level == 5 && $downline >= 25) {

                        $insert->status = 'Paid';
                        $wallet = $var->wallet;
                        $var->wallet += $amount;
                        $var->save();

                        walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Withdrawal Commission', ' '.$i.' Withdrawal Commission Amount Added into wallet.');
                        $insert->status = 'Paid';
                    } else {
                        $insert->status = 'Flushed';
                    }
                    $insert->save();
                }
                $inc = MemberDetail::where('memberid', $sponsorid)->first();
                $sponsorid = $inc['sponsorid'];
            } else {
                break;
            }
        }
    }
}

function capping($memberid)
{
    $var = MemberDetail::where('memberid', $memberid)->first();
    $biz = $var['self_biz'];
    $downline = $var->downline;

    if ($downline >= 1) {
        $cap = $biz * 4;
    } else {
        $cap = $biz * 2;
    }

    return $cap;
}

function stakingCapping($memberid, $staking_id)
{
    $staking = StakingDetail::where('id', $staking_id)->first();
    if ($staking) {
        return $staking->getMaxRoiAmount();
    }

    $sum = StakingDetail::where([['memberid', $memberid], ['id', '<=', $staking_id]])->sum('invest_amount');

    return $sum * 2;
}

function Income3xachieved($memberid)
{
    $var = MemberDetail::where([['memberid', $memberid], ['status', '!=', 'Temp']])->first();
    if ($var) {
        $date = $var['activated_at'];
        $level = LevelIncome::where([['status', 'Paid'], ['memberid', $memberid], ['created_at', '>=', $date]])->sum('amount');
        // $daily = StakingIncome::where([['status', 'Paid'], ['memberid', $memberid], ['created_at', '>=', $date]])->sum('amount');

        $totalAmount = $level;
    } else {
        $totalAmount = 0;
    }

    return $totalAmount;
}

function directIncome($sponsorid, $memberid, $name, $_amount, $type = 'Direct Income', $levelPercentages = [])
{
    if ($sponsorid != 'Root') {
        if (empty($levelPercentages)) {
            $levelPercentages = ReferralBonusConfiction::getLevelRates();
        }

        foreach ($levelPercentages as $level => $rate) {
            $amount = $_amount * $rate / 100;

            if ($sponsorid != 'Root') {
                $var = MemberDetail::where('memberid', $sponsorid)->first();
                if (! $var) {
                    break;
                }
                $status = $var->status;

                if ($amount > 0 && $status == 'Active') {
                    $insert = new DirectIncome;
                    $insert->memberid = $sponsorid;
                    $insert->package = $_amount;
                    $insert->level = $level;
                    $insert->rate = $rate;
                    $insert->amount = $amount;
                    $insert->activatingid = $memberid;
                    $insert->name = $name;
                    $insert->status = 'Paid';
                    $insert->type = $type;
                    $insert->save();

                    $wallet = $var->wallet;
                    $var->wallet += $amount;
                    $var->save();

                    walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Direct Income', ' '.$level.' Direct Income Amount Added into wallet.');
                }

                $inc = MemberDetail::where('memberid', $sponsorid)->first();
                $sponsorid = $inc ? $inc->sponsorid : 'Root';
            } else {
                break;
            }
        }
    }
}
