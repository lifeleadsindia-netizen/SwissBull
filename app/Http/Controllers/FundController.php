<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use App\Models\WalletTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $result['packagePlans'] = PackagePlan::where('status', 'Active')->orderBy('min_amount', 'asc')->get();

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
        $validator = Validator::make($request->all(), [
            'memberid' => 'required',
            'amount' => 'required|numeric|gte:1',
            'txnid' => 'required',
        ], [
            'memberid.required' => 'Member ID is required.',
            'amount.required' => 'Please enter deposit amount.',
            'amount.numeric' => 'Deposit amount must be a valid number.',
            'amount.gte' => 'Minimum deposit amount is 1 USDT.',
            'txnid.required' => 'Transaction ID is required.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            session()->flash('FailedMsg', $validator->errors()->first());

            return redirect()->back();
        }

        $amount = (float) $request->input('amount');
        $txnid = trim((string) $request->input('txnid'));
        $memberid = trim((string) $request->input('memberid'));

        // Check for duplicate transaction ID to prevent double-crediting
        $alreadyProcessed = ImportFund::where('txnid', $txnid)->exists();

        if ($alreadyProcessed) {
            $msg = 'This transaction ID has already been processed.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                ], 422);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        }

        $orderId = 'OD'.time().rand(10, 99);

        try {
            DB::transaction(function () use (
                $memberid,
                $amount,
                $txnid,
                $orderId
            ) {
                $member = MemberDetail::where('memberid', $memberid)->lockForUpdate()->first();
                if (! $member) {
                    throw new \InvalidArgumentException('Invalid Member ID.');
                }

                // 1. Create Deposit Record
                $importFund = new ImportFund;
                $importFund->memberid = $memberid;
                $importFund->orderid = $orderId;
                $importFund->package = null;
                $importFund->amount = $amount;
                $importFund->txnid = $txnid;
                $importFund->type = 'Add';
                $importFund->mode = 'Online';
                $importFund->status = 'Approved';
                $importFund->added_by = 'User';
                $importFund->wallet_type = 'Wallet';
                $importFund->save();

                // 2. Add 100% to Member P2P Wallet
                $oldWallet = (float) $member->p2p_wallet;
                $member->p2p_wallet = $oldWallet + $amount;
                $member->save();

                // 3. Record Wallet Ledger Entry
                p2pwalletTransfer(
                    $memberid,
                    $amount,
                    'credit',
                    $oldWallet,
                    'Fund Added',
                    "{$amount} USDT deposit: 100% allocated to P2P Wallet"
                );
            });
        } catch (\InvalidArgumentException $e) {
            $msg = $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        } catch (\Throwable $e) {
            $msg = 'An error occurred while processing your deposit: '.$e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 500);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        }

        session()->flash('successMsg', 'Your requested funds have been imported successfully.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Your requested funds have been imported successfully.',
            ]);
        }

        return redirect()->back();
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
