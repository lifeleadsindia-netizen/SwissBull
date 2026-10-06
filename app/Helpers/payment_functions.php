<?php

use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\RoiLevelIncome;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
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

function levelIncome($sponsorid, $memberid, $name, $_amount, $type)
{
    if ($sponsorid != 'Root') {
        $limit = 11;
        for ($i = 1; $i < $limit; $i++) {
            $level = $i;
            $rate = levelRate($level);
            $amount = $_amount * $rate / 100;

            if ($sponsorid != 'Root') {
                $var = MemberDetail::where('memberid', $sponsorid)->first();
                $status = $var->status;
                $downline = $var->downline;

                if ($amount > 0 && $status == 'Active') {

                    if ($type == 'Account Activation') {
                        $insert = new LevelIncome;
                        $insert->package = $_amount;
                    } else {
                        $insert = new RoiLevelIncome;
                        $insert->staking_income = $_amount;
                    }

                    $insert->memberid = $sponsorid;
                    $insert->level = $level;
                    $insert->level_id = $memberid;
                    $insert->amount = $amount;
                    $insert->rate = $rate;
                    $insert->name = $name;
                    $insert->type = $type;

                    if ($level == 1 && $downline >= 0 || $level == 2 && $downline >= 1 || $level == 3 && $downline >= 2 || $level == 4 && $downline >= 3 || $level == 5 && $downline >= 4 || $level == 6 && $downline >= 5 || $level == 7 && $downline >= 6 || $level == 8 && $downline >= 7 || $level == 9 && $downline >= 8 || $level == 10 && $downline >= 9) {

                        $insert->status = 'Paid';
                        $wallet = $var->wallet;
                        $var->wallet += $amount;
                        $var->save();

                        walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Level Income', ' '.$i.'Activation Level Income Amount Added into wallet.');
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

function levelRate($level)
{

    if ($level == 1) {
        $rate = 10;
    } elseif ($level == 2) {
        $rate = 3;
    } elseif ($level == 3) {
        $rate = 2;
    } elseif ($level >= 4 && $level <= 6) {
        $rate = 1;
    } elseif ($level >= 7 && $level <= 10) {
        $rate = 0.5;
    } else {
        $rate = 0;
    }

    return $rate;
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
    $sum = StakingDetail::where([['memberid', $memberid], ['id', '<=', $staking_id]])->sum('invest_amount');
    $cap = $sum * 2;

    return $cap;
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
