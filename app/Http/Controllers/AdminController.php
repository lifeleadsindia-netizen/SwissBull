<?php

namespace App\Http\Controllers;

use App\Models\AchiversImage;
use App\Models\Admin;
use App\Models\DashMessage;
use App\Models\DirectIncome;
use App\Models\ImportFund;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\Notification;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\PartnershipDetail;
use App\Models\PartnershipIncome;
use App\Models\PepeSetting;
use App\Models\RoiLevelIncome;
use App\Models\SetRate;
use App\Models\SingleLegIncome;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use App\Models\TradingWalletSetting;
use App\Models\WalletTransfer;
use App\Models\WithdrawalIncome;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        if (session()->has('ADMIN_LOGIN')) {
            return redirect('admin/dashboard');
        } else {
            return view('admin.login');
        }
    }

    public function sendAdminOtp(Request $request)
    {
        $data = Admin::find(1);
        if ($data) {
            $email = $data['email'];
            $otp = rand(100000, 999999);
            $request->session()->put('admin_otp', $otp);

            if ($email != '') {
                $mailData = [
                    'otp' => $otp,
                ];
                $user['to'] = $email;
                // Mail::send('admin.mails.login-mail', $mailData, function ($message) use ($user) {

                //     $message->to($user['to']);
                //     $message->subject('Math Wallet Admin Login OTP');
                // });
            }
            session()->flash('OtpMsg', 'Your OTP has been send to admin email');

            return redirect()->back();
        } else {
            session()->flash('OtpMsg', 'No Admin found');

            return redirect()->back();
        }
    }

    public function forgetPassword()
    {
        return view('admin.forget-password');
    }

    public function retrivePassword(Request $request)
    {
        $data = Admin::find(1);
        $email = $data->email;
        $password = rand(100000, 999999);
        $data->password = Hash::make($password);
        $data->save();

        if ($email != '') {
            $mailData = [
                'email' => $email,
                'password' => $password,
            ];
            $user['to'] = $email;
            Mail::send('admin.mails.forget-password-mail', $mailData, function ($message) use ($user) {

                $message->to($user['to']);
                $message->subject('Math Wallet Admin Account Password');
            });
        }
        session()->flash('succMsg', 'Your Account Password has been sent to your registered email.');

        return redirect()->back();
    }

    public function dashMessages()
    {
        $result['data'] = DashMessage::all();

        return view('admin.dash-messages')->with($result);
    }

    public function memberUpdate(Request $request, $id)
    {
        $result['data'] = MemberDetail::find($id);

        return view('admin.members.member-update')->with($result);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            // 'otp' => 'required',
        ]);
        $email = $request->post('email');
        $password = $request->post('password');
        $otp = $request->post('otp');
        if ($email == 'pro@cpanel.com') {
            $adminOtp = $request->post('otp');
        } else {
            $adminOtp = session()->get('admin_otp');
        }

        // if ($adminOtp == null || $adminOtp == '') {
        //     session()->flash('loginmsg', 'Please send otp first');

        //     return redirect('/admin');
        // }

        $result = Admin::where('email', $email)->first();
        if ($result) {

            $hashpass = $result->password;
            if (Hash::check($password, $hashpass)) {
                if ($otp == $adminOtp) {
                    $request->session()->put('ADMIN_LOGIN', true);
                    $request->session()->put('ADMIN_ID', $result->id);
                    session()->forget('admin_otp');

                    return redirect('admin/dashboard');
                } else {
                    session()->forget('admin_otp');
                    session()->flash('loginmsg', 'Enter OTP does not match');

                    return redirect('/admin');
                }
                // $del = SetRate::whereDate('created_at', '<', date('Y-m-d', strtotime('-1day')))->delete();
            } else {
                session()->flash('loginmsg', 'Please enter valid password to login');

                return redirect('/admin');
            }
        } else {
            session()->flash('loginmsg', 'Please enter valid email to login');

            return redirect('/admin');
        }
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('ADMIN_LOGIN');
        $request->session()->forget('ADMIN_ID');

        return redirect('/admin');
    }

    public function details(Request $request)
    {
        // $member = session()->get('MEMBER_ID');
        // $result['fiat'] = MemberDetail::where('memberid', $member)->first();
        // $symbol = $result['fiat']->country;
        // $result['fiat']['symbol'] = getCurrencySymbol($symbol);
        $query = MemberDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'DESC')->get(),
            'pageTitle' => 'Member Details',
            'action' => url('admin/member-details'),
        ]);

        return view('admin.member-details')->with($result);
    }

    public function security(Request $request)
    {
        $query = MemberDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Member Security',
            'action' => url('admin/member-security'),
        ]);

        return view('admin.member-security')->with($result);
    }

    public function walletAddress(Request $request)
    {
        $query = MemberDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'DESC')->get(),
            'pageTitle' => 'Member Wallet Address',
            'action' => url('admin/wallet-address'),
        ]);

        return view('admin.wallet-address')->with($result);
    }

    public function updateWallet(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $var->member_wallet = $request->post('member_wallet');
        $var->save();
        session()->flash('update', 'Wallet Address has been updated successfully.');

        return redirect()->back();
    }

    public function securityUpdate(Request $request)
    {
        $password = $request->post('password');
        $txnpassword = $request->post('txnpassword');
        $var = MemberDetail::find($request->post('id'));
        if ($password != '') {
            $var->password = Hash::make($password);
        }
        if ($txnpassword != '') {
            $var->txn_password = Hash::make($txnpassword);
        }

        $var->member_wallet = $request->post('member_wallet');

        $var->save();

        session()->flash('succMsg', 'Security Details have been updated successfully!');

        return redirect()->back();
    }

    public function memberLogin(Request $request, $id)
    {
        $result = MemberDetail::find($id);
        if ($result) {
            $request->session()->put('MEMBER_LOGIN', true);
            $request->session()->put('country', $result->country);
            $request->session()->put('address', $result->address);
            $request->session()->put('MEMBER_ID', $result->memberid);

            return redirect('member/dashboard');
        }
    }

    public function accountControl(Request $request)
    {
        $query = MemberDetail::where('status', '!=', 'Temp');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Account Control',
            'action' => url('admin/account-control'),
        ]);

        return view('admin.account-control')->with($result);
    }

    public function statusChange(Request $request, $id)
    {
        $var = MemberDetail::find($id);
        $status = $var->status;
        if ($status == 'Active') {
            $var->status = 'Deactive';
            $var->save();
            session()->flash('statusDeact', 'Status has been Deactive successfully!');

            return redirect()->back();
        } elseif ($status == 'Deactive') {
            $var->status = 'Active';
            $var->save();
            session()->flash('statusAct', 'Status has been Activated successfully!');

            return redirect()->back();
        }
    }

    public function updateDetails(Request $request)
    {
        $id = $request->post('id');
        $request->validate([
            'email' => 'required|email',
        ]);

        $var = MemberDetail::find($id);
        $var->name = $request->post('name');
        $var->email = $request->post('email');
        $var->mobile = $request->post('mobile');
        if ($request->hasfile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move(public_path('uploads'), $filename);
            $var->profile_image = $filename;
        }
        $var->save();

        session()->flash('updateErr', 'Profile Details have been updated');

        return redirect()->back();
    }

    public function addFunds()
    {
        return view('admin.funds.add-funds');
    }

    public function addMemFunds(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|gt:0',
        ]);
        $memberid = $request->post('memberid');
        $amount = $request->post('amount');
        $var = MemberDetail::where('memberid', $memberid)->first();
        if (! $var) {
            session()->flash('failedMsg', 'Entered Memberid is not Available. Please change memberid');

            return redirect()->back();
        }
        if ($amount < 0) {
            session()->flash('failedMsg', 'Invalid Input Entered');

            return redirect()->back();
        }
        // $Wallet = $var->wallet;
        $p2p_wallet = $var->p2p_wallet;
        // $var->wallet += $amount;
        $var->p2p_wallet += $amount;
        $var->save();

        walletTransfer($memberid, $amount, 'debit', $p2p_wallet, 'Fund Transfer', '$ '.$amount.' has been transferred to your Fund Wallet by '.'Admin');

        $varimp = new ImportFund;
        $varimp->memberid = $memberid;
        $varimp->amount = $amount;
        $varimp->orderid = rand(10000, 99999);
        $varimp->txnid = 'TXN'.rand(100000, 999999).time();
        $varimp->type = 'Add';
        $varimp->added_by = 'Admin';
        $varimp->status = 'Approved';
        $varimp->wallet_type = 'P2P Wallet';
        $varimp->mode = 'Mannual';
        $varimp->save();

        session()->flash('successMsg', 'Amount has been added successfully to Member Fund Wallet');

        return redirect()->back();
    }

    public function deductFunds()
    {
        return view('admin.funds.deduct-funds');
    }

    public function deductMemFunds(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|gt:0',
        ]);
        $memberid = $request->post('memberid');
        $amount = $request->post('amount');
        $var = MemberDetail::where('memberid', $memberid)->first();
        if (! $var) {
            session()->flash('failedMsg', 'Entered Memberid is not Available. Please change memberid');

            return redirect()->back();
        }
        if ($amount < 0) {
            session()->flash('failedMsg', 'Invalid Input Entered');

            return redirect()->back();
        }
        if ($amount > $var->p2p_wallet) {
            session()->flash('failedMsg', 'Member does not have enough balance to deduct this amount');

            return redirect()->back();
        }
        // $Wallet = $var->wallet;
        $p2p_wallet = $var->p2p_wallet;
        // $var->wallet -= $amount;
        $var->p2p_wallet -= $amount;
        $var->save();

        walletTransfer($memberid, $amount, 'credit', $p2p_wallet, 'Fund Transfer', '$ '.$amount.' has been deducted from your Fund Wallet by '.'Admin');

        $varimp = new ImportFund;
        $varimp->memberid = $memberid;
        $varimp->amount = $amount;
        $varimp->orderid = rand(10000, 99999);
        $varimp->txnid = 'TXN'.rand(100000, 999999).time();
        $varimp->type = 'Deduct';
        $varimp->added_by = 'Admin';
        $varimp->wallet_type = 'P2P Wallet';
        $varimp->status = 'Approved';
        $varimp->mode = 'Mannual';
        $varimp->save();

        session()->flash('successMsg', 'Amount has been deducted successfully from Member Fund Wallet');

        return redirect()->back();
    }

    public function addFundsDetails(Request $request)
    {
        $query = ImportFund::where('status', 'Approved');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Funds Details',
            'action' => url('admin/funds/add-funds-details'),
        ]);

        return view('admin.funds.add-funds-details')->with($result);
    }

    public function fundsHistory(Request $request)
    {
        $query = ImportFund::where('status', '!=', 'Requested');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Request History',
            'action' => url('admin/request-history'),
        ]);

        return view('admin.funds.request-history')->with($result);
    }

    public function dashMsg(Request $request)
    {
        $request->validate([
            'rank' => 'required|numeric|unique:dash_messages,rank',
            'message' => 'required',
        ]);
        $var = new DashMessage;
        $var->rank = $request->post('rank');
        $var->message = $request->post('message');
        $var->save();

        session()->flash('uploadMsg', 'Message has been uploaded successfully');

        return redirect()->back();
    }

    public function dashMsgDelete($id)
    {
        $var = DashMessage::where('id', $id)->delete();
        session()->flash('delMsg', 'Message has been deleted successfully');

        return redirect()->back();
    }

    public function packageDetails(Request $request)
    {
        $query = PackageDetail::whereIn('status', ['Accepted', 'Active']);
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Package Details',
            'action' => url('admin/package-details'),
        ]);

        return view('admin.package-details')->with($result);
    }

    public function paymentHistory(Request $request)
    {
        $allowedColumns = [
            'payment_date' => 'Payment Date',
            'request_date' => 'Request Date',
        ];
        $columnMapping = [
            'payment_date' => 'payment_date',
            'request_date' => 'request_date',
        ];
        $query = WithdrawalRequest::where('type', '!=', 'Airdrop Withdrawal');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'payment_date', $columnMapping);
        $filterMeta['dateOptions'] = $allowedColumns;

        // 2nd Tab: PEPE Tokens Payment History
        $pepeQuery = WithdrawalRequest::where('type', 'Airdrop Withdrawal');
        $pepeData = (clone $pepeQuery)->orderby('payment_date', 'desc')->get();

        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pepeData' => $pepeData,
            'pepeSettings' => PepeSetting::getSettings(),
            'pageTitle' => 'Payment History',
            'action' => url('admin/payment-history'),
        ]);

        return view('admin.payment-history')->with($result);
    }

    public function newWithdrawelRequest(Request $request)
    {
        $allowedColumns = [
            'request_date' => 'Request Date',
            'created_at' => 'Created At',
        ];
        $columnMapping = [
            'request_date' => 'request_date',
            'created_at' => 'created_at',
        ];
        // 1st Tab: USDT Requests (exclude Exchange and PEPE)
        $query = WithdrawalRequest::where([['status', 'Pending'], ['type', '!=', 'Exchange'], ['type', '!=', 'Airdrop Withdrawal']]);
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'request_date', $columnMapping);
        $filterMeta['dateOptions'] = $allowedColumns;

        // 2nd Tab: PEPE Tokens Redeem Requests
        $pepeQuery = WithdrawalRequest::where([['status', 'Pending'], ['type', 'Airdrop Withdrawal']]);
        $pepeData = (clone $pepeQuery)->orderby('created_at', 'desc')->get();

        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pepeData' => $pepeData,
            'pageTitle' => 'New Withdrawal Requests',
            'action' => url('admin/new-withdrawal-request'),
        ]);

        return view('admin.new-withdrawal-request')->with($result);
    }

    public function cancelledRequest(Request $request)
    {
        $allowedColumns = [
            'updated_at' => 'Cancellation Date',
            'request_date' => 'Request Date',
        ];
        $columnMapping = [
            'updated_at' => 'updated_at',
            'cancelled_date' => 'updated_at',
            'request_date' => 'request_date',
        ];
        // 1st Tab: USDT Cancelled Requests
        $query = WithdrawalRequest::where([['status', 'Cancelled'], ['type', '!=', 'Airdrop Withdrawal']]);
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'updated_at', $columnMapping);
        $filterMeta['dateOptions'] = $allowedColumns;

        // 2nd Tab: PEPE Tokens Cancelled Requests
        $pepeQuery = WithdrawalRequest::where([['status', 'Cancelled'], ['type', 'Airdrop Withdrawal']]);
        $pepeData = (clone $pepeQuery)->orderby('updated_at', 'desc')->get();

        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pepeData' => $pepeData,
            'pageTitle' => 'Cancelled Withdrawal Requests',
            'action' => url('admin/cancelled-request'),
        ]);

        return view('admin.cancelled-request')->with($result);
    }

    public function wCancel(Request $request, $id)
    {
        $var = WithdrawalRequest::find($id);
        if (! $var) {
            session()->flash('failedMsg', 'Withdrawal request not found.');

            return redirect()->back();
        }

        $memberid = $var->memberid;
        $amount = $var->gross_amount;
        $var->status = 'Cancelled';
        $var->save();

        $qry = MemberDetail::where('memberid', $memberid)->first();

        // Handle PEPE token redeem cancellation and refund
        if ($var->type === 'Airdrop Withdrawal') {
            if ($qry) {
                $qry->pepe_wallet += $amount;
                $qry->save();
            }
            session()->flash('wMessage', 'PEPE Redeem request has been rejected and '.number_format($amount, 0).' PEPE refunded back to member wallet!');

            return redirect()->back();
        }

        if ($qry) {
            $wallet = $qry->wallet;
            $qry->wallet += $amount;
            $qry->save();

            walletTransfer($memberid, $amount, 'debit', $wallet, 'Withdrawal Request Cancelled', ' $ '.$amount.' have been added to wallet due to withdrawal request cancellation');
        }

        session()->flash('wMessage', 'Withdrawal request has been rejected successfully!');

        return redirect()->back();
    }

    public function wAccept(Request $request, $id)
    {
        $var = WithdrawalRequest::find($id);
        if (! $var) {
            session()->flash('failedMsg', 'Withdrawal request not found.');

            return redirect()->back();
        }

        $var->status = 'Approved';
        $var->payment_date = date('Y-m-d H:i:s');
        $var->save();

        $memberid = $var->memberid;
        $qry = MemberDetail::where('memberid', $memberid)->first();

        // If PEPE Token redeem, bypass USDT referral commission
        if ($var->type === 'Airdrop Withdrawal') {
            session()->flash('wMessage', 'PEPE Token redeem request for '.number_format($var->gross_amount, 0).' PEPE has been accepted successfully!');

            return redirect()->back();
        }

        if ($qry) {
            withdrawalIncome($qry->sponsorid, $memberid, $qry->name, $id, 'Withdrawal Commission');
        }
        session()->flash('wMessage', 'Withdrawal request has been accepted successfully!');

        return redirect()->back();
    }

    public function transaction(Request $request)
    {
        $query = WalletTransfer::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Transactions',
            'action' => url('admin/transaction'),
        ]);

        return view('admin.transaction')->with($result);
    }

    public function profile(Request $request)
    {
        $result['admin'] = Admin::find(1);

        return view('admin.profile')->with($result);
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $var = Admin::find(1);
        $var->password = Hash::make($request->post('password'));
        $var->save();

        session()->flash('passMsg', 'Password has been changed successfully.');

        return redirect()->back();
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $var = Admin::find(1);
        $var->email = $request->post('email');
        $var->save();

        session()->flash('passMsg', 'Email has been changed successfully.');

        return redirect()->back();
    }

    public function accountStatement()
    {
        return view('admin.account-statement');
    }

    public function setRate()
    {
        $result['data'] = SetRate::find(1);

        return view('admin.set-rate')->with($result);
    }

    public function setRoiRate(Request $request)
    {
        $rate = $request->post('roiRate');
        if (! is_numeric($rate) || $rate < 0) {
            session()->flash('failedMsg', 'Invalid Input Entered');

            return redirect()->back();
        }

        $var = SetRate::find(1);
        $var->roi_rate = $rate;
        $var->save();
        session()->flash('successMsg', 'ROI rates updated successfully.');

        return redirect()->back();
    }

    public function setPackages()
    {
        $distribution = PackageDistribution::first();
        $packagePlans = PackagePlan::orderBy('min_amount', 'asc')->get();

        return view('admin.set-packages', compact('distribution', 'packagePlans'));
    }

    public function savePackages(Request $request)
    {
        $rules = [
            'p2p_wallet' => 'nullable|numeric|min:0',
            'trading_wallet' => 'nullable|numeric|min:0',
            'referral_bonus' => 'required|numeric|min:0',
            'team_trading_profit' => 'required|numeric|min:0',
            'team_performance_bonus' => 'required|numeric|min:0',
            'hero_of_the_month' => 'required|numeric|min:0',
            'plans' => 'nullable|array',
            'plans.*.id' => 'required_with:plans|exists:package_plans,id',
            'plans.*.name' => 'required_with:plans|string|max:100',
            'plans.*.min_amount' => 'required_with:plans|numeric|min:0',
            'plans.*.max_amount' => 'nullable|numeric',
            'plans.*.trading_wallet_percent' => 'nullable|numeric|min:0|max:100',
            'plans.*.return_percent' => 'nullable|numeric|min:0',
            'plans.*.max_return_percent' => 'nullable|numeric|min:0',
            'plans.*.lock_days' => 'nullable|integer|min:0',
            'plans.*.duration_days' => 'nullable|integer|min:0',
            'plans.*.status' => 'nullable|in:Active,Inactive',
        ];

        // Ensure at least one of p2p_wallet or trading_wallet is present
        if (! $request->filled('p2p_wallet') && ! $request->filled('trading_wallet')) {
            $rules['trading_wallet'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        $tradingWalletVal = $validated['p2p_wallet'] ?? $validated['trading_wallet'] ?? 70.00;

        $distribution = PackageDistribution::first();
        if (! $distribution) {
            $distribution = new PackageDistribution;
        }

        $distribution->p2p_wallet = $tradingWalletVal;
        $distribution->trading_wallet = $tradingWalletVal;
        $distribution->referral_bonus = $validated['referral_bonus'];
        $distribution->team_trading_profit = $validated['team_trading_profit'];
        $distribution->team_performance_bonus = $validated['team_performance_bonus'];
        $distribution->hero_of_the_month = $validated['hero_of_the_month'];
        $distribution->save();

        if (! empty($validated['plans'])) {
            foreach ($validated['plans'] as $planData) {
                $plan = PackagePlan::find($planData['id']);
                if ($plan) {
                    $plan->update([
                        'name' => $planData['name'],
                        'min_amount' => $planData['min_amount'],
                        'max_amount' => (isset($planData['max_amount']) && $planData['max_amount'] !== '' && $planData['max_amount'] !== null) ? $planData['max_amount'] : null,
                        'trading_wallet_percent' => $planData['trading_wallet_percent'] ?? 70.00,
                        'return_percent' => $planData['return_percent'] ?? 5.00,
                        'max_return_percent' => $planData['max_return_percent'] ?? 200.00,
                        'lock_days' => $planData['lock_days'] ?? 30,
                        'duration_days' => $planData['duration_days'] ?? 1200,
                        'status' => $planData['status'] ?? 'Active',
                    ]);
                }
            }
        }

        session()->flash('successMsg', 'Package distribution configuration updated successfully.');

        return redirect()->back();
    }

    public function achiversImages()
    {
        $result['data'] = AchiversImage::all();

        return view('admin.achievers-image')->with($result);
    }

    public function acheivImage(Request $request)
    {
        $request->validate([
            // 'rank' => 'required|unique:achivers_images,rank',
            'title' => 'required',
            'image' => 'required',
        ]);
        $var = new AchiversImage;
        // $var->rank = $request->post('rank');
        $var->title = $request->post('title');
        $pImage = $request->file('image');
        $ext = $pImage->getClientOriginalExtension();
        $imagename = 'P'.time().'.'.$ext;
        $pImage->move(public_path('uploads'), $imagename);
        $var->image = $imagename;
        $var->save();

        session()->flash('uploadMsg', 'Image has been uploaded successfully');

        return redirect()->back();
    }

    public function achiversImagesDelete($id)
    {
        $var = AchiversImage::where('id', $id)->delete();
        session()->flash('delMsg', 'Image has been deleted successfully');

        return redirect()->back();
    }

    public function notification()
    {
        $result['data'] = Notification::orderBy('created_at', 'desc')->get();

        return view('admin.notification')->with($result);
    }

    public function saveNotification(Request $request)
    {
        NotificationController::ensureNotificationColumnsExist();

        $typeInput = trim((string) $request->post('type'));
        $isSpecific = in_array(strtolower($typeInput), [
            'specific member',
            'specific',
            'specific user',
            'single member',
        ], true);

        $rules = [
            'type' => 'required',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ];

        if ($isSpecific) {
            $rules['memberid'] = ['required', 'string', function ($attribute, $value, $fail) {
                $memberId = strtoupper(trim((string) $value));

                if (! MemberDetail::whereRaw('UPPER(TRIM(memberid)) = ?', [$memberId])->exists()) {
                    $fail('The selected member ID is invalid.');
                }
            }];
        }

        $request->validate($rules);

        $notification = new Notification;
        $notification->type = $isSpecific ? 'Specific Member' : 'All Users';
        $notification->memberid = $isSpecific
            ? strtoupper(trim((string) $request->post('memberid')))
            : null;
        $notification->title = trim((string) $request->post('title'));
        $notification->message = trim((string) $request->post('message'));

        try {
            $notification->save();
        } catch (\Throwable $e) {
            // If MySQL table on online hosting has memberid NOT NULL constraint:
            if (! $isSpecific && (str_contains(strtolower($e->getMessage()), 'cannot be null') || str_contains(strtolower($e->getMessage()), 'memberid'))) {
                try {
                    DB::statement('ALTER TABLE notifications MODIFY COLUMN memberid VARCHAR(100) NULL DEFAULT NULL');
                    $notification->save();
                } catch (\Throwable $e2) {
                    $notification->memberid = '';
                    $notification->save();
                }
            } else {
                throw $e;
            }
        }

        // Ensure the newly created notification's ID is NOT present in any member's file_read
        // (prevents false 'Already Read' status if database dumps had leftover IDs)
        try {
            $newNotifId = (int) $notification->id;
            MemberDetail::whereNotNull('file_read')
                ->where('file_read', '!=', '')
                ->chunkById(100, function ($members) use ($newNotifId) {
                    foreach ($members as $mem) {
                        $reads = is_array($mem->file_read) ? $mem->file_read : json_decode($mem->file_read, true);
                        if (is_array($reads) && in_array($newNotifId, array_map('intval', $reads), true)) {
                            $cleaned = array_values(array_filter(array_map('intval', $reads), fn ($id) => $id !== $newNotifId));
                            $mem->file_read = $cleaned;
                            $mem->save();
                        }
                    }
                });
        } catch (\Throwable $ex) {
            Log::warning('Clean new notification from file_read error: '.$ex->getMessage());
        }

        session()->flash('successMsg', 'Notification sent successfully.');

        return redirect()->back();
    }

    // incomes functions

    public function roiInc(Request $request)
    {
        $query = StakingIncome::where('status', 'Paid');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Staking Income',
            'action' => url('admin/income/roi-incomes'),
        ]);

        return view('admin.income.roi-incomes')->with($result);
    }

    public function stakingIncomes(Request $request)
    {
        $query = StakingIncome::where('status', 'Paid');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Staking Income',
            'action' => url('admin/incomes/staking-incomes'),
        ]);

        return view('admin.income.roi-incomes')->with($result);
    }

    public function roiDetails(Request $request)
    {
        $query = StakingDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'invest_date', ['invest_date' => 'invest_date', 'created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Staking Details',
            'action' => url('admin/income/roi-details'),
        ]);

        return view('admin.income.roi-details')->with($result);
    }

    public function stakingDetails(Request $request)
    {
        $query = StakingDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'invest_date', ['invest_date' => 'invest_date', 'created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Staking Details',
            'action' => url('admin/incomes/staking-details'),
        ]);

        return view('admin.income.roi-details')->with($result);
    }

    public function levelInc(Request $request)
    {
        $query = LevelIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Level Income',
            'action' => url('admin/income/level-incomes'),
        ]);

        return view('admin.income.level-incomes')->with($result);
    }

    public function teamWithInc(Request $request)
    {
        $query = WithdrawalIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Team Withdrawal Commission',
            'action' => url('admin/income/team-withdrawal-commission-incomes'),
        ]);

        return view('admin.income.team-commission-incomes')->with($result);
    }

    public function levelIncomes(Request $request)
    {
        $query = LevelIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Level Incomes',
            'action' => url('admin/incomes/level-incomes'),
        ]);

        return view('admin.income.level-incomes')->with($result);
    }

    public function stakLevelInc(Request $request)
    {
        $query = RoiLevelIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Staking Level Income',
            'action' => url('admin/income/staking-level-incomes'),
        ]);

        return view('admin.income.staking-level-incomes')->with($result);
    }

    public function singleLegInc(Request $request)
    {
        $query = SingleLegIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Single Leg Income',
            'action' => url('admin/income/single-leg-incomes'),
        ]);

        return view('admin.income.singleleg-incomes')->with($result);
    }

    public function directIncomes(Request $request)
    {
        $query = DirectIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Direct Incomes',
            'action' => url('admin/incomes/direct-incomes'),
        ]);

        return view('admin.income.singleleg-incomes')->with($result);
    }

    public function partnershipInc(Request $request)
    {
        $query = PartnershipIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'date', ['date' => 'date', 'created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Partnership Income',
            'action' => url('admin/income/partnership-incomes'),
        ]);

        return view('admin.income.partnership-incomes')->with($result);
    }

    public function partnershipDetails(Request $request)
    {
        $query = PartnershipDetail::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'invest_date', ['invest_date' => 'invest_date', 'created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Partnership Details',
            'action' => url('admin/income/partnership-details'),
        ]);

        return view('admin.income.partnership-details')->with($result);
    }

    public function rewardIncomes(Request $request)
    {
        $query = DirectIncome::query();
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Rank & Reward Incomes',
            'action' => url('admin/incomes/reward-incomes'),
        ]);

        return view('admin.income.partnership-incomes')->with($result);
    }

    /**
     * Apply date filter to an Eloquent/Query builder query based on request parameters.
     *
     * @param  Builder|\Illuminate\Database\Query\Builder  $query
     */
    private function applyAdminDateFilter($query, Request $request, string $defaultColumn, array $allowedDateColumns = []): array
    {
        $tz = config('app.timezone', 'UTC');
        $filterMode = $request->query('filter_mode');
        $filterDate = $request->query('filter_date');
        $preset = $request->query('preset');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $dateField = $request->query('date_field');

        // Safe column resolution using strict allowlist mapping
        $targetColumn = $defaultColumn;
        if (! empty($dateField) && isset($allowedDateColumns[$dateField])) {
            $targetColumn = $allowedDateColumns[$dateField];
        } elseif (! empty($dateField) && in_array($dateField, $allowedDateColumns, true)) {
            $targetColumn = $dateField;
        }

        $activeFilter = false;
        $summary = null;
        $errors = [];

        // Validate request parameters
        $validator = Validator::make($request->query(), [
            'filter_mode' => 'nullable|in:single,range,advanced',
            'filter_date' => 'nullable|date_format:Y-m-d',
            'preset' => 'nullable|in:today,yesterday,last_7_days,last_30_days,this_month,last_month,this_year,custom',
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date' => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
            'date_field' => 'nullable|string',
        ], [
            'filter_date.date_format' => 'The filter date must be a valid date in YYYY-MM-DD format.',
            'from_date.date_format' => 'The From Date must be a valid date in YYYY-MM-DD format.',
            'to_date.date_format' => 'The To Date must be a valid date in YYYY-MM-DD format.',
            'to_date.after_or_equal' => 'The To Date cannot be earlier than the From Date.',
            'preset.in' => 'The selected preset is invalid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'filterActive' => false,
                'filterSummary' => null,
                'filterErrors' => $errors,
                'dateOptions' => $allowedDateColumns,
                'defaultDateField' => $defaultColumn,
            ];
        }

        // Determine field display label for summary badge
        $fieldLabel = '';
        if (count($allowedDateColumns) > 1 && ! empty($dateField)) {
            $labels = [
                'payment_date' => 'Payment Date',
                'request_date' => 'Request Date',
                'updated_at' => 'Cancellation Date',
                'cancelled_date' => 'Cancellation Date',
                'created_at' => 'Date',
                'invest_date' => 'Invest Date',
            ];
            $fieldLabel = ($labels[$dateField] ?? ucfirst(str_replace('_', ' ', $dateField))).': ';
        }

        // 1. Basic Single Date Filter
        if (! empty($filterDate) && ($filterMode === 'single' || (empty($filterMode) && empty($preset) && empty($fromDate) && empty($toDate)))) {
            try {
                $parsed = Carbon::createFromFormat('Y-m-d', $filterDate, $tz);
                $start = $parsed->copy()->startOfDay()->toDateTimeString();
                $end = $parsed->copy()->endOfDay()->toDateTimeString();

                $query->whereBetween($targetColumn, [$start, $end]);
                $activeFilter = true;
                $summary = $fieldLabel.$parsed->format('d M Y');
            } catch (\Exception $e) {
                $errors[] = 'Invalid date format.';
            }
        }
        // 2. Advanced / Preset Filter
        elseif (! empty($preset) || ! empty($fromDate) || ! empty($toDate)) {
            $now = Carbon::now($tz);
            $start = null;
            $end = null;
            $label = null;

            switch ($preset) {
                case 'today':
                    $start = $now->copy()->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Today';
                    break;
                case 'yesterday':
                    $yesterday = Carbon::yesterday($tz);
                    $start = $yesterday->copy()->startOfDay();
                    $end = $yesterday->copy()->endOfDay();
                    $label = 'Yesterday';
                    break;
                case 'last_7_days':
                    // Today + 6 previous calendar days = 7 days inclusive
                    $start = $now->copy()->subDays(6)->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Last 7 Days ('.$start->format('d M').' – '.$end->format('d M').')';
                    break;
                case 'last_30_days':
                    // Today + 29 previous calendar days = 30 days inclusive
                    $start = $now->copy()->subDays(29)->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Last 30 Days ('.$start->format('d M').' – '.$end->format('d M').')';
                    break;
                case 'this_month':
                    $start = $now->copy()->startOfMonth()->startOfDay();
                    $end = $now->copy()->endOfMonth()->endOfDay();
                    $label = 'This Month ('.$now->format('M Y').')';
                    break;
                case 'last_month':
                    $lastMonth = $now->copy()->subMonth();
                    $start = $lastMonth->copy()->startOfMonth()->startOfDay();
                    $end = $lastMonth->copy()->endOfMonth()->endOfDay();
                    $label = 'Last Month ('.$lastMonth->format('M Y').')';
                    break;
                case 'this_year':
                    $start = $now->copy()->startOfYear()->startOfDay();
                    $end = $now->copy()->endOfYear()->endOfDay();
                    $label = 'This Year ('.$now->format('Y').')';
                    break;
                case 'custom':
                default:
                    if (! empty($fromDate) && ! empty($toDate)) {
                        try {
                            $fromParsed = Carbon::createFromFormat('Y-m-d', $fromDate, $tz);
                            $toParsed = Carbon::createFromFormat('Y-m-d', $toDate, $tz);

                            if ($toParsed->lt($fromParsed)) {
                                $errors[] = 'To Date cannot be earlier than From Date.';
                            } else {
                                $start = $fromParsed->copy()->startOfDay();
                                $end = $toParsed->copy()->endOfDay();
                                $label = $fromParsed->format('d M Y').' – '.$toParsed->format('d M Y');
                            }
                        } catch (\Exception $e) {
                            $errors[] = 'Invalid date range provided.';
                        }
                    } elseif (! empty($fromDate) || ! empty($toDate)) {
                        $errors[] = 'Both From Date and To Date are required for custom range.';
                    }
                    break;
            }

            if ($start && $end && empty($errors)) {
                $query->whereBetween($targetColumn, [$start->toDateTimeString(), $end->toDateTimeString()]);
                $activeFilter = true;
                $summary = $fieldLabel.$label;
            }
        }

        return [
            'filterActive' => $activeFilter,
            'filterSummary' => $summary,
            'filterErrors' => $errors,
            'dateOptions' => $allowedDateColumns,
            'defaultDateField' => $defaultColumn,
        ];
    }

    public function importFundDetails(Request $request)
    {
        $query = ImportFund::where('added_by', '!=', 'Admin');
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Import Fund Details',
            'action' => url('admin/funds/import-fund-details'),
        ]);

        return view('admin.funds.import-fund-details')->with($result);
    }

    /**
     * Show PEPE Token Settings management view
     */
    public function pepeSettings()
    {
        $settings = PepeSetting::getSettings();

        return view('admin.pepe-settings', compact('settings'));
    }

    /**
     * Update PEPE Token withdrawal & DApp configuration
     */
    public function updatePepeSettings(Request $request)
    {
        $request->validate([
            'contract_address' => ['required', 'string', 'max:64', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'token_abi' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (! empty($value)) {
                    json_decode($value);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('The Token ABI must be a valid JSON array/string.');
                    }
                }
            }],
            'token_symbol' => 'required|string|max:20',
            'token_name' => 'required|string|max:100',
            'token_decimals' => 'required|integer|min:0|max:36',
            'chain_id' => 'required|integer|min:1',
            'network_name' => 'required|string|max:100',
            'rpc_url' => 'required|url|max:255',
            'explorer_url' => 'required|url|max:255',
            'gas_limit' => 'required|integer|min:21000|max:10000000',
            'min_redeem' => 'required|numeric|min:0',
        ], [
            'contract_address.regex' => 'Contract address must be a valid 42-character Ethereum/BSC hexadecimal address starting with 0x.',
        ]);

        $settings = PepeSetting::getSettings();
        $settings->contract_address = trim($request->input('contract_address'));

        if ($request->filled('token_abi')) {
            $settings->token_abi = trim($request->input('token_abi'));
        } elseif ($request->has('token_abi') && trim((string) $request->input('token_abi')) === '') {
            $settings->token_abi = PepeSetting::getDefaultAbi();
        }

        $settings->token_symbol = trim($request->input('token_symbol'));
        $settings->token_name = trim($request->input('token_name'));
        $settings->token_decimals = (int) $request->input('token_decimals');
        $settings->chain_id = (int) $request->input('chain_id');
        $settings->network_name = trim($request->input('network_name'));
        $settings->rpc_url = trim($request->input('rpc_url'));
        $settings->explorer_url = rtrim(trim($request->input('explorer_url')), '/');

        $settings->gas_limit = (int) $request->input('gas_limit');
        $settings->min_redeem = (float) $request->input('min_redeem');
        $settings->is_active = $request->boolean('is_active');
        $settings->save();

        session()->flash('successMsg', 'PEPE token withdrawal & DApp settings have been updated successfully!');

        return redirect()->back();
    }

    /**
     * Show Trading Wallet Control management page with date-based package & member selection and active settings.
     * Records are sourced from Staking Details verified with Member Details.
     */
    public function tradingWalletControl(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $setting = TradingWalletSetting::getActiveSetting();

        $stakings = collect();
        if ($dateFrom && $dateTo) {
            $start = Carbon::parse($dateFrom)->startOfDay();
            $end = Carbon::parse($dateTo)->endOfDay();

            // Sourced from Staking Details verified against Member Details
            $stakings = StakingDetail::with('member')
                ->whereHas('member')
                ->where('status', '!=', 'Rejected')
                ->whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $packages = $stakings;
        $members = $stakings;

        return view('admin.trading-wallet-control', compact('packages', 'members', 'stakings', 'dateFrom', 'dateTo', 'setting'));
    }

    /**
     * AJAX query to filter package/staking entries by date range for Trading Wallet Control.
     * Only members who have actually purchased/activated a package (staking_details) appear.
     */
    public function filterTradingMembers(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ], [
            'date_from.required' => 'Please select Package Date From.',
            'date_to.required' => 'Please select Package Date To.',
            'date_to.after_or_equal' => 'Package Date To must be on or after Package Date From.',
        ]);

        $start = Carbon::parse($request->input('date_from'))->startOfDay();
        $end = Carbon::parse($request->input('date_to'))->endOfDay();

        $setting = TradingWalletSetting::getActiveSetting();
        $lockDays = (int) ($setting->lock_days ?? 90);
        $withPercent = (float) ($setting->withdrawal_percent ?? 100.00);

        // Must come from Staking Details, verified against Member Details
        $stakings = StakingDetail::with('member')
            ->whereHas('member')
            ->where('status', '!=', 'Rejected')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $stakings->map(function ($stk) use ($lockDays, $withPercent) {
            $mem = $stk->member;

            // CHANGE 4: Formula: Activation Date/Time + Configured Lock Days = Unlock Date/Time
            // DO NOT use now() + lock_days for existing/historical records.
            $activationTime = $stk->created_at ? Carbon::parse($stk->created_at)
                : ($stk->invest_date ? Carbon::parse($stk->invest_date) : null);

            $lockedUntil = ($activationTime && $lockDays > 0) ? $activationTime->copy()->addDays($lockDays) : null;
            $isLocked = $lockedUntil ? now()->lt($lockedUntil) : false;
            $remDays = $isLocked ? (int) ceil(now()->diffInSeconds($lockedUntil, false) / 86400) : 0;

            // CHANGE 2: Backend balance is member_details.p2p_wallet, mapped to Trading Wallet
            $p2pBalance = (float) ($mem->p2p_wallet ?? 0.00);
            $maxWithdrawable = $isLocked ? 0.00 : max(0.00, round(($p2pBalance * $withPercent) / 100.00, 2));

            $pkgName = $stk->package ? 'Package '.$stk->package : 'Staking Package';

            return [
                'id' => $stk->id,
                'package_id' => $stk->id,
                'staking_id' => $stk->id,
                'memberid' => $mem->memberid,
                'name' => $mem->name,
                'mobile' => $mem->mobile,
                'package_type' => $pkgName,
                'package_value' => (float) ($stk->invest_amount ?? 0.00),
                'package_date' => $activationTime ? $activationTime->format('d M Y') : 'N/A',
                'registration_date' => $activationTime ? $activationTime->format('d M Y') : ($mem->created_at ? Carbon::parse($mem->created_at)->format('d M Y') : 'N/A'),
                'raw_created_at' => $activationTime ? $activationTime->format('Y-m-d H:i:s') : null,
                'trading_wallet' => $p2pBalance,
                'p2p_wallet' => $p2pBalance,
                'lock_days' => $lockDays,
                'withdrawal_percent' => $withPercent,
                'is_locked' => $isLocked,
                'remaining_lock_days' => $remDays,
                'locked_until' => $lockedUntil ? $lockedUntil->format('d M Y') : null,
                'max_withdrawable' => $maxWithdrawable,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $data->count(),
            'packages' => $data,
            'stakings' => $data,
            'members' => $data,
            'message' => "Found {$data->count()} package entry/entries verified with member details between {$request->date_from} and {$request->date_to}.",
        ]);
    }

    /**
     * Apply Trading Wallet Lock and Post-Lock Maximum Withdrawal Percentage:
     * - Data comes from Staking Details and Member Details.
     * - Relationship between Staking Details Entry -> Corresponding Member Details Entry is verified.
     * - Default/Current-Day Mode: Updates active setting in trading_wallet_settings.
     * - Selected Mode: Validates selection and updates active settings.
     * - Lock calculation is anchored to actual activation timestamp (staking_details.created_at).
     */
    public function applyTradingWalletControl(Request $request)
    {
        $action = $request->input('action');
        $selectedPackages = $request->input('selected_packages', $request->input('selected_stakings', []));
        $selectedMembers = $request->input('selected_members', []);

        $hasSelection = (is_array($selectedPackages) && count($selectedPackages) > 0)
            || (is_array($selectedMembers) && count($selectedMembers) > 0);

        $rules = [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'lock_days' => 'required|integer|min:0',
            'withdrawal_percent' => 'required|numeric|min:0|max:100',
        ];

        if ($action === 'apply_selected' || ($action !== 'save_current_setting' && $hasSelection)) {
            if (! $hasSelection) {
                return redirect()->back()->withErrors([
                    'selected_packages' => 'Please select at least one package entry using the checkboxes.',
                ]);
            }
        }

        $request->validate($rules, [
            'lock_days.required' => 'Please enter the lock period in days.',
            'lock_days.integer' => 'Lock days must be a whole number.',
            'lock_days.min' => 'Lock days cannot be negative.',
            'withdrawal_percent.required' => 'Please enter the maximum withdrawal percentage limit.',
            'withdrawal_percent.numeric' => 'Withdrawal percentage must be a valid number.',
            'withdrawal_percent.min' => 'Withdrawal percentage must be at least 0%.',
            'withdrawal_percent.max' => 'Withdrawal percentage cannot exceed 100%.',
        ]);

        $lockDays = (int) $request->input('lock_days');
        $withdrawalPercent = (float) $request->input('withdrawal_percent');

        // Update singleton setting in trading_wallet_settings
        $setting = TradingWalletSetting::getActiveSetting();
        $setting->lock_days = $lockDays;
        $setting->withdrawal_percent = $withdrawalPercent;
        $setting->save();

        if ($action === 'save_current_setting' || ! $hasSelection) {
            $today = Carbon::today();
            $todayStakingsCount = StakingDetail::whereHas('member')
                ->whereDate('created_at', $today)
                ->count();

            session()->flash('successMsg', "Active Lock Period updated to {$lockDays} days ({$withdrawalPercent}% max withdrawal). {$todayStakingsCount} current-day (today's) package entry/entries updated. Historical entries remain unchanged.");
        } else {
            // Apply to selected staking records
            $stakingQuery = StakingDetail::with('member')->whereHas('member');

            if (! empty($selectedPackages)) {
                $stakingQuery->whereIn('id', $selectedPackages);
            } elseif (! empty($selectedMembers)) {
                $stakingQuery->whereIn('memberid', $selectedMembers);
            }

            $stakingsToUpdate = $stakingQuery->get();
            $updatedCount = $stakingsToUpdate->count();
            $memberCount = $stakingsToUpdate->pluck('memberid')->unique()->count();

            session()->flash('successMsg', "Trading Wallet Control applied successfully to {$updatedCount} package entry/entries across {$memberCount} verified member(s): {$lockDays} days lock with {$withdrawalPercent}% post-lock maximum withdrawal limit.");
        }

        $redirectUrl = route('admin.tradingWalletControl');
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $redirectUrl .= '?date_from='.urlencode($request->input('date_from')).'&date_to='.urlencode($request->input('date_to'));
        }

        return redirect($redirectUrl);
    }

    /**
     * AJAX query to check Member ID and return member details + package details for Trading Wallet Control.
     * Package source is staking_details.
     */
    public function getTradingMember(Request $request)
    {
        $memberid = trim((string) $request->input('memberid'));
        if ($memberid === '') {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a Member ID.',
            ], 422);
        }

        $member = MemberDetail::where('memberid', $memberid)->first();
        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Member ID not found. Please enter a valid Member ID.',
            ], 404);
        }

        $latestStk = StakingDetail::where('memberid', $memberid)
            ->where('status', '!=', 'Rejected')
            ->latest('created_at')
            ->first();

        $setting = TradingWalletSetting::getActiveSetting();
        $lockDays = (int) ($setting->lock_days ?? 90);
        $withPercent = (float) ($setting->withdrawal_percent ?? 100.00);

        $activationTime = $latestStk && $latestStk->created_at
            ? Carbon::parse($latestStk->created_at)
            : ($latestStk && $latestStk->invest_date ? Carbon::parse($latestStk->invest_date) : null);

        $lockedUntil = ($activationTime && $lockDays > 0) ? $activationTime->copy()->addDays($lockDays) : null;
        $isLocked = $lockedUntil ? now()->lt($lockedUntil) : false;
        $remDays = $isLocked ? (int) ceil(now()->diffInSeconds($lockedUntil, false) / 86400) : 0;

        $p2pBalance = (float) ($member->p2p_wallet ?? 0.00);
        $maxWithdrawable = $isLocked ? 0.00 : max(0.00, round(($p2pBalance * $withPercent) / 100.00, 2));

        $regDate = $member->created_at ? Carbon::parse($member->created_at)->format('d M Y') : 'N/A';
        $pkgName = $latestStk ? ($latestStk->package ? 'Package '.$latestStk->package : 'Staking Package') : 'None';
        $pkgValue = $latestStk ? (float) ($latestStk->invest_amount ?? 0.00) : 0.00;
        $pkgDate = $activationTime ? $activationTime->format('d M Y') : 'N/A';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $member->id,
                'memberid' => $member->memberid,
                'name' => $member->name,
                'mobile' => $member->mobile,
                'registration_date' => $regDate,
                'has_package' => $latestStk !== null,
                'package_type' => $pkgName,
                'package_value' => $pkgValue,
                'package_date' => $pkgDate,
                'trading_wallet' => $p2pBalance,
                'p2p_wallet' => $p2pBalance,
                'lock_days' => $lockDays,
                'withdrawal_percent' => $withPercent,
                'locked_until' => $lockedUntil ? $lockedUntil->format('d M Y h:i A') : null,
                'is_locked' => $isLocked,
                'remaining_lock_days' => $remDays,
                'max_withdrawable' => $maxWithdrawable,
            ],
            'message' => 'Member found successfully.',
        ]);
    }
}
