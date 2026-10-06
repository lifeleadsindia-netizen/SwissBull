<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\MemberDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserAccessController extends Controller
{
    public function index()
    {
        if (session()->has('MEMBER_ID')) {
            return redirect('member/dashboard');
        } else {
            return redirect('/');
        }
    }

    public function addressValidate(Request $request)
    {
        $address = $request->post('address');
        $var = MemberDetail::where('member_wallet', $address)->first();
        if ($var) {
            session()->put('address', $address);
            session()->put('country', $var->country);
            session()->put('MEMBER_ID', $var->memberid);
            session()->flash('show_dashboard_popup', true);

            return response()->json([
                'code' => 1,
            ]);
        } else {
            session()->put('address', $address);

            return response()->json([
                'code' => 0,
            ]);
        }
    }

    public function register(Request $request, $sid)
    {
        if ($sid != 1) {
            session()->put('sponsorid', $sid);
        }
        if (! session()->has('address')) {
            return redirect('/');
        }
        $result['cdata'] = Country::all();
        $result['rootSponsorId'] = MemberDetail::where('sponsorid', 'Root')->value('memberid');

        return view('member.access.register')->with($result);
    }

    public function userRegister(Request $request)
    {
        // Check if email is verified
        if (! session()->get('email_verified') || session()->get('verified_email') !== $request->post('email')) {
            session()->flash('failedMsg', 'Please verify your email address before completing registration.');

            return redirect()->back()->withInput();
        }
        $request->validate([
            'sponsorid' => 'required|exists:member_details,memberid',
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric|unique:member_details,mobile',
            'country' => 'required',
        ]);

        $userAddress = session('address');
        $email = $request->post('email');
        $mobile = $request->post('mobile');
        $phoneCode = $request->post('phone_code');
        $sponsorid = strtoupper($request->post('sponsorid'));
        $country = $request->post('country');

        $spon = MemberDetail::where('memberid', $sponsorid)->first();

        // Double check email doesn't already exist
        $existingUser = MemberDetail::where('email', $request->post('email'))->first();
        if ($existingUser) {
            session()->flash('failedMsg', 'This email is already registered. Please use a different email address.');

            return redirect()->back()->withInput();
        }

        if ($spon->status == 'Temp') {
            session()->flash('failedMsg', 'This sponsorid is not activated');

            return redirect()->back();
        }

        $check1 = MemberDetail::where('mobile', $mobile)->first();
        if ($check1) {
            session()->flash('failedMsg', 'This mobile no is already registered. Please change mobile no');

            return redirect()->back();
        }

        $country_c = Country::where('name', $country)->first();
        $cou_code = $country_c->iso3;
        if ($cou_code == null) {
            session()->flash('failedMsg', 'Please select Other country. Country code not found');

            return redirect()->back();
        }

        if ($spon) {

            $check2 = MemberDetail::where('member_wallet', $userAddress)->first();
            if (! $check2) {
                $var = new MemberDetail;
                // $var->memberid = $cou_code.$mobile;
                $var->memberid = userId();
                $var->member_wallet = $userAddress;
                $var->sponsorid = $sponsorid;
                $var->name = $request->post('name');
                $var->email = $email;
                $var->mobile = $request->post('mobile');
                $var->phonecode = $phoneCode;
                $var->country = $country;
                $var->save();

                $data = MemberDetail::find($var->id);
                $userid = $data->memberid;
                $email = $data->email;
                $name = $data->name;
                $mobile = $data->mobile;
                if ($email != '') {
                    $mailData = [
                        'name' => $name,
                        'email' => $email,
                        'mobile' => $mobile,
                        'memberid' => $userid,
                    ];
                    $user['to'] = $email;
                    Mail::send('member.mails.mail', $mailData, function ($message) use ($user) {

                        $message->to($user['to']);
                        $message->subject('Math Wallet Member Login Credentials');
                    });
                }
                $request->session()->put('name', $request->post('name'));
                $request->session()->put('userid', $userid);
                $request->session()->put('email', $email);
                $request->session()->put('mobile', $mobile);
                $request->session()->put('country', $country);
                session()->flash('show_dashboard_popup', true);
                session()->put('MEMBER_ID', $var->memberid);
                session()->put('country', $var->country);
                session()->flash('successMsg', 'Your account has been created successfully. Please check your credentials for login details.');

                return redirect('member/dashboard');
            } else {
                session()->flash('failedMsg', 'This user is already registered');

                return redirect()->back();
            }
        }
    }
}
