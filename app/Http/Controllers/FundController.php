<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\WalletTransfer;
use Illuminate\Http\Request;

class FundController extends Controller
{
    // public function importfund()
    // {
    //     $memberid = session('MEMBER_ID');
    //     $result['data'] = MemberDetail::where('memberid', $memberid)->first();
    //     return view('member.fund.import-fund')->with($result);
    // }

    public function importfund()
    {
        $memberid = session('MEMBER_ID');
        $country = session('country');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['country'] = Country::where('name', $country)->first();
        $result['addfundData'] = ImportFund::where([['memberid', $memberid], ['added_by', 'User']])->orderBy('created_at', 'desc')->get();

        return view('member.fund.import-flt')->with($result);
    }

    public function impfundhis(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['addfundData'] = ImportFund::where([['memberid', $memberid], ['added_by', 'User']])->orderBy('created_at', 'desc')->get();

        return view('member.fund.import-fund-history')->with($result);
    }

    public function addFund(Request $request)
    {

        $memberid = $request->post('memberid');
        $txnid = $request->post('txnid');
        $amount = $request->post('amount');
        $data = MemberDetail::where('memberid', $memberid)->first();

        $var = new ImportFund;
        $var->memberid = $memberid;
        $var->orderid = 'OD'.time();
        $var->amount = $amount;
        $var->txnid = $txnid;
        $var->type = 'Add';
        $var->mode = 'Online';
        $var->status = 'Approved';
        $var->added_by = 'User';
        $var->wallet_type = 'Wallet';
        $var->save();

        $wallet = $data->p2p_wallet;
        $data->p2p_wallet += $amount;
        $data->save();
        p2pwalletTransfer($memberid, $amount, 'debit', $wallet, 'Fund Added', ''.$amount.' USDT added to wallet');
        session()->flash('successMsg', 'Your requested funds have been imported successfully.');
    }

    public function p2pwallet(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $memberid = $result['data']['memberid'];
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.p2p-wallet')->with($result);
    }

    public function p2pHistory(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $memberid = $result['data']['memberid'];
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.p2p-history')->with($result);
    }

    public function fundTransfer()
    {
        $result['data'] = MemberDetail::find(session('MEMBER_ID'));
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.fund_transfer')->with($result);
    }

    public function fundTrans(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'member' => 'required',
        ]);
        $memberid = strtoupper($request->post('member'));
        $val_id = MemberDetail::where('memberid', $memberid)->first();
        if ($val_id && $val_id->status != 'Blocked') {

            $sendData = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
            $senderid = $sendData->memberid;
            $var = MemberDetail::where('memberid', $senderid)->first();
            $txn_password = $request->post('txn_password');
            $amount = $request->post('amount');
            if ($amount < 0) {
                session()->flash('failedMsg', 'Negative value is not allowed');

                return redirect()->back();
            }

            if ($val_id->status == 'Deactive') {
                session()->flash('failedMsg', 'Entered memberid is deactive. Amount cannot be transferred to deactive account');

                return redirect()->back();
            }

            if ($var->status == 'Deactive') {
                session()->flash('failedMsg', 'Your account status is deactive. Please contact system admin');

                return redirect()->back();
            }

            if ($amount > $var['wallet']) {
                session()->flash('failedMsg', 'You do not have enough balance to transfer this amount ');

                return redirect()->back();
            }
            if ($memberid == $senderid) {
                session()->flash('otpMsg', 'You can not transfer money to your own account');

                return redirect()->back();
            } else {

                // sending Money
                $qry = MemberDetail::where('memberid', $memberid)->first();
                $wallet = $qry->wallet;
                $qry->wallet += $amount;
                $qry->save();
                p2pwalletTransfer($memberid, $amount, 'debit', $wallet, 'Fund Transfer', '$ '.$amount.' has been transferred to your p2p wallet by '.$senderid.'');

                $query = MemberDetail::where('memberid', $senderid)->first();
                $senderwallet = $query->wallet;
                $query->wallet -= $amount;
                $query->save();

                p2pwalletTransfer($senderid, $amount, 'credit', $senderwallet, 'Fund Transfer', '$ '.$amount.' has been transferred by you to '.$memberid.' p2p wallet');

                // $trans = new P2pFundTransfers();
                // $trans->senderid = $senderid;
                // $trans->amount = $amount;
                // $trans->deduction = 0;
                // $trans->net_amount = $amount;
                // $trans->receiverid = $memberid;
                // $trans->save();

                session()->flash('successMsg', 'Amount has been transferred successfully');

                return redirect()->back();
            }
        } else {
            session()->flash('failedMsg', 'Entered Memberid is either blocked or unavailable');

            return redirect()->back();
        }
    }
}
