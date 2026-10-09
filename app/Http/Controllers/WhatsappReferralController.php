<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PepeRewardLog;
use App\Models\WhatsappReferral;
use App\Services\PepeRewardService;
use Illuminate\Http\Request;

class WhatsappReferralController extends Controller
{
    /**
     * Sanitize and format a mobile number.
     */
    private function sanitizeMobile(?string $mobile): string
    {
        if (! $mobile) {
            return '';
        }
        $trimmed = trim($mobile);
        // Remove special characters except leading plus or digits
        $clean = preg_replace('/[^0-9+]/', '', $trimmed);

        return $clean;
    }

    /**
     * Validate if mobile matches member's own mobile.
     */
    private function isSelfReferral(MemberDetail $member, string $cleanMobile): bool
    {
        $memberMobile = preg_replace('/[^0-9]/', '', $member->mobile ?? '');
        $inputDigits = preg_replace('/[^0-9]/', '', $cleanMobile);

        if ($memberMobile === '' || $inputDigits === '') {
            return false;
        }

        // Compare exact digits or ending 10 digits
        if ($memberMobile === $inputDigits) {
            return true;
        }

        if (strlen($memberMobile) >= 10 && strlen($inputDigits) >= 10) {
            return substr($memberMobile, -10) === substr($inputDigits, -10);
        }

        return false;
    }

    /**
     * Check if a mobile number has already been used by any member.
     */
    private function isMobileAlreadyUsed(string $cleanMobile, bool $forUpdate = false): bool
    {
        $inputDigits = preg_replace('/[^0-9]/', '', $cleanMobile);
        if ($inputDigits === '') {
            return false;
        }

        $query = WhatsappReferral::query();
        if ($forUpdate) {
            $query->lockForUpdate();
        }

        if ((clone $query)->where('mobile_number', $cleanMobile)->exists()) {
            return true;
        }

        if (strlen($inputDigits) >= 10) {
            $last10 = substr($inputDigits, -10);

            return (clone $query)->where('mobile_number', 'LIKE', '%'.$last10)->exists();
        }

        return false;
    }

    /**
     * Validate if a mobile number is a plausible, real WhatsApp mobile number.
     * Respects country-specific digit counts and formats.
     */
    private function isValidWhatsappMobile(string $cleanMobile, ?string $countryName = null, ?string $phonecode = null): bool
    {
        $digits = preg_replace('/[^0-9]/', '', $cleanMobile);
        $len = strlen($digits);

        // International E.164 phone numbers (with or without country code) are between 6 and 16 digits.
        if ($len < 6 || $len > 16) {
            return false;
        }

        // Must have at least 3 unique digits (prevents 1111111, 0000000 etc.)
        $uniqueCount = count(array_unique(str_split($digits)));
        if ($uniqueCount < 3) {
            return false;
        }

        // Sequential / Ascending / Descending fake pattern checks (full 10-digit or repeated sequences)
        $sequentialPatterns = [
            '0123456789', '1234567890', '2345678901', '3456789012',
            '9876543210', '8765432109', '7654321098', '6543210987',
            '1122334455', '5544332211', '1231231234', '1234123412',
            '00000000',   '11111111',   '22222222',   '33333333',
            '44444444',   '55555555',   '66666666',   '77777777',
            '88888888',   '99999999',
        ];
        foreach ($sequentialPatterns as $pat) {
            if (str_contains($digits, $pat)) {
                return false;
            }
        }

        // Repeated 2-digit or 3-digit pattern check (e.g., 9898989898, 1212121212)
        if (preg_match('/^(\d{2,3})\1{3,}$/', $digits)) {
            return false;
        }

        // Only apply Indian-specific rule (must start with 6, 7, 8, 9) if explicitly Indian number
        $isIndia = ($countryName && strtolower($countryName) === 'india')
            || ($phonecode === '91')
            || ($len === 12 && str_starts_with($digits, '91'));

        if ($isIndia) {
            if ($len === 12 && str_starts_with($digits, '91')) {
                $firstDigit = $digits[2];
                if (! in_array($firstDigit, ['6', '7', '8', '9'], true)) {
                    return false;
                }
            } elseif ($len === 10) {
                $firstDigit = $digits[0];
                if (! in_array($firstDigit, ['6', '7', '8', '9'], true)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Step 1: Verify Mobile Number via AJAX.
     */
    public function verifyMobile(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:6|max:25',
            'country' => 'nullable|string|max:100',
            'phonecode' => 'nullable|string|max:10',
        ]);

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired or member not found.',
            ], 401);
        }

        $cleanMobile = $this->sanitizeMobile($request->input('mobile_number'));
        $countryName = $request->input('country');
        $phonecode = $request->input('phonecode');

        if (! $this->isValidWhatsappMobile($cleanMobile, $countryName, $phonecode)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid, real WhatsApp mobile number. Fake or dummy numbers are not allowed.',
            ], 422);
        }

        // Rule 3: Cannot refer self
        if ($this->isSelfReferral($member, $cleanMobile)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot refer yourself.',
            ], 422);
        }

        // Rule 1: Daily referral limit (max 10 messages/day)
        $today = now()->toDateString();
        $todayCount = WhatsappReferral::where('member_id', $memberid)
            ->whereDate('created_at', $today)
            ->count();

        if ($todayCount >= PepeRewardService::DAILY_MESSAGE_LIMIT) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the maximum daily limit of '.PepeRewardService::DAILY_MESSAGE_LIMIT.' messages ('.number_format(PepeRewardService::DAILY_MESSAGE_TOKEN_LIMIT, 0).' PEPE tokens) for today.',
            ], 422);
        }

        // Rule 2: Cannot use same mobile number again across ANY user
        if ($this->isMobileAlreadyUsed($cleanMobile)) {
            return response()->json([
                'success' => false,
                'message' => 'This mobile number has already been used for a WhatsApp referral.',
            ], 422);
        }

        $remainingToday = PepeRewardService::DAILY_MESSAGE_LIMIT - $todayCount;

        return response()->json([
            'success' => true,
            'message' => 'Number verified successfully. Click Send WhatsApp Referral to earn 500 PEPE Tokens. ('.$remainingToday.' messages remaining today)',
            'clean_mobile' => $cleanMobile,
            'today_count' => $todayCount,
            'daily_limit' => PepeRewardService::DAILY_MESSAGE_LIMIT,
        ]);
    }

    /**
     * Resolve default referral link message for WhatsApp sharing.
     */
    private function resolveMessage(MemberDetail $member, &$messageModel = null): string
    {
        $messageModel = null;
        $referralLink = url('/member/register/'.$member->memberid);

        // return "Hello! Join SYNC TRADE using my referral link:\n".$referralLink;
        return 'Hi! 👋

            I wanted to share something interesting with you. I’ve recently come across a *business opportunity with excellent earning potential* and a model that I feel is worth exploring.

            What caught my attention is the *long-term income potential and the opportunity to build an additional source of income* with the right effort and consistency. I personally feel it’s worth taking a look before making any decision.

            👉 *Here’s my referral link:*
            '.$referralLink.'

            Just *register your ID through my referral link* using DApp Browser and   BEP20 Network in any wallet that supports BEP20 Network. Take some time to check the opportunity, business model and earning plan yourself.

            There’s no harm in exploring a good opportunity—and if you find it interesting, we can discuss it further. 😊

            *Do check it out. You might discover something really valuable! *';
    }

    /**
     * Step 2: Process Referral, Grant Reward & Generate WhatsApp URL.
     */
    public function processReferral(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:6|max:25',
            'country' => 'nullable|string|max:100',
            'phonecode' => 'nullable|string|max:10',
        ]);

        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired or member not found.',
            ], 401);
        }

        $cleanMobile = $this->sanitizeMobile($request->input('mobile_number'));
        $countryName = $request->input('country');
        $phonecode = $request->input('phonecode');

        if (! $this->isValidWhatsappMobile($cleanMobile, $countryName, $phonecode)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid, real WhatsApp mobile number. Fake or dummy numbers are not allowed.',
            ], 422);
        }

        // Rule 3: Self referral check
        if ($this->isSelfReferral($member, $cleanMobile)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot refer yourself.',
            ], 422);
        }

        $messageModel = null;
        $finalMessageText = $this->resolveMessage($member, $messageModel);

        try {
            $awardResult = PepeRewardService::awardMessageReward($memberid, $cleanMobile, $messageModel);

            // Reload member data
            $member->refresh();
            $waTotalPepe = PepeRewardService::getTotalEarned($memberid);
            $waTotalReferrals = WhatsappReferral::where('member_id', $memberid)->count();

            $waPhoneDigits = preg_replace('/[^0-9]/', '', $cleanMobile);
            $whatsappUrl = 'https://api.whatsapp.com/send?phone='.$waPhoneDigits.'&text='.urlencode($finalMessageText);

            $todayCount = $awardResult['today_count'];

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp promotional link generated! You have earned 500 PEPE tokens ('.$todayCount.'/'.PepeRewardService::DAILY_MESSAGE_LIMIT.' today). Redeem them anytime to your PEPE Wallet.',
                'whatsapp_url' => $whatsappUrl,
                'pepe_wallet' => $member->pepe_wallet ?? 0,
                'wa_total_pepe' => $waTotalPepe,
                'wa_total_referrals' => $waTotalReferrals,
                'reward_amount' => PepeRewardService::AMOUNT_MESSAGE,
                'today_count' => $todayCount,
                'daily_limit' => PepeRewardService::DAILY_MESSAGE_LIMIT,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display Member Referral Details & History Page.
     */
    public function referralDetails(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $member = MemberDetail::where('memberid', $memberid)->first();

        $today = now()->toDateString();
        $waTodayCount = WhatsappReferral::where('member_id', $memberid)
            ->whereDate('created_at', $today)
            ->count();

        $todayStatus = $waTodayCount >= PepeRewardService::DAILY_MESSAGE_LIMIT
            ? 'Completed (10/10)'
            : ($waTodayCount > 0 ? 'Available ('.$waTodayCount.'/10)' : 'Available (0/10)');

        // Rule 1 Statistics: WhatsApp Messages
        $msgCount = PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_MESSAGE)->count();
        $msgTokens = (float) PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_MESSAGE)->sum('reward_amount');
        if ($msgCount === 0) {
            $msgCount = WhatsappReferral::where('member_id', $memberid)->count();
            $msgTokens = (float) WhatsappReferral::where('member_id', $memberid)->sum('reward_amount');
        }

        // Rule 2 Statistics: Direct Registrations
        $regCount = PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_REGISTRATION)->count();
        $regTokens = (float) PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_REGISTRATION)->sum('reward_amount');

        // Rule 3 Statistics: Direct Activations
        $actCount = PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_ACTIVATION)->count();
        $actTokens = (float) PepeRewardLog::where('member_id', $memberid)->where('reward_type', PepeRewardService::TYPE_DIRECT_ACTIVATION)->sum('reward_amount');

        $totalReferrals = $msgCount;
        $totalPepeEarned = PepeRewardService::getTotalEarned($memberid);
        $lastReferral = PepeRewardLog::where('member_id', $memberid)->latest('created_at')->first()
            ?: WhatsappReferral::where('member_id', $memberid)->latest('created_at')->first();
        $lastReferralDate = $lastReferral ? $lastReferral->created_at : null;

        // Filter reward logs by selected rule type
        $selectedType = $request->query('type', 'all');
        $rewardLogsQuery = PepeRewardLog::where('member_id', $memberid);

        if ($selectedType !== 'all' && in_array($selectedType, [PepeRewardService::TYPE_MESSAGE, PepeRewardService::TYPE_DIRECT_REGISTRATION, PepeRewardService::TYPE_DIRECT_ACTIVATION], true)) {
            $rewardLogsQuery->where('reward_type', $selectedType);
        }

        $rewardLogs = $rewardLogsQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $referrals = WhatsappReferral::where('member_id', $memberid)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'ref_page')
            ->withQueryString();

        $data = $member;

        return view('member.whatsapp.referral-details', compact(
            'data',
            'member',
            'referrals',
            'rewardLogs',
            'totalReferrals',
            'totalPepeEarned',
            'lastReferralDate',
            'todayStatus',
            'waTodayCount',
            'msgCount',
            'msgTokens',
            'regCount',
            'regTokens',
            'actCount',
            'actTokens',
            'selectedType'
        ));
    }
}
