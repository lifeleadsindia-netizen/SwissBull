<?php

namespace App\Services;

use App\Models\MemberDetail;
use App\Models\PepeRewardLog;
use App\Models\WhatsappReferral;
use App\Models\WhatsappReferralMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PepeRewardService
{
    public const TYPE_MESSAGE = 'message';

    public const TYPE_DIRECT_REGISTRATION = 'direct_registration';

    public const TYPE_DIRECT_ACTIVATION = 'direct_activation';

    public const AMOUNT_MESSAGE = 500.00;

    public const AMOUNT_DIRECT_REGISTRATION = 500.00;

    public const AMOUNT_DIRECT_ACTIVATION = 500.00;

    public const DAILY_MESSAGE_LIMIT = 10;

    public const DAILY_MESSAGE_TOKEN_LIMIT = 5000.00;

    /**
     * Get the count of promotional messages sent by a member today.
     */
    public static function getTodayMessageCount(string $memberId): int
    {
        $today = now()->toDateString();

        return WhatsappReferral::where('member_id', $memberId)
            ->whereDate('created_at', $today)
            ->count();
    }

    /**
     * Check if a member can send another promotional message today (max 10/day).
     */
    public static function canSendMessageToday(string $memberId): bool
    {
        return self::getTodayMessageCount($memberId) < self::DAILY_MESSAGE_LIMIT;
    }

    /**
     * Process Message Reward (Rule 1: 500 tokens/msg, max 10 msgs/day = 5,000/day).
     *
     * @throws \Exception
     */
    public static function awardMessageReward(
        string $memberId,
        string $cleanMobile,
        ?WhatsappReferralMessage $messageModel = null
    ): array {
        $today = now()->toDateString();
        $rewardAmount = self::AMOUNT_MESSAGE;

        return DB::transaction(function () use ($memberId, $cleanMobile, $messageModel, $today, $rewardAmount) {
            // Lock and check daily limit (max 10)
            $todayCount = WhatsappReferral::where('member_id', $memberId)
                ->whereDate('created_at', $today)
                ->lockForUpdate()
                ->count();

            if ($todayCount >= self::DAILY_MESSAGE_LIMIT) {
                throw new \Exception('You have reached the maximum daily limit of '.self::DAILY_MESSAGE_LIMIT.' messages ('.number_format(self::DAILY_MESSAGE_TOKEN_LIMIT, 0).' PEPE tokens) for today.');
            }

            // Lock and check duplicate mobile number across all members
            $duplicateExists = WhatsappReferral::where('mobile_number', $cleanMobile)
                ->lockForUpdate()
                ->exists();

            if (! $duplicateExists) {
                $inputDigits = preg_replace('/[^0-9]/', '', $cleanMobile);
                if (strlen($inputDigits) >= 10) {
                    $last10 = substr($inputDigits, -10);
                    $duplicateExists = WhatsappReferral::where('mobile_number', 'LIKE', '%'.$last10)
                        ->lockForUpdate()
                        ->exists();
                }
            }

            if ($duplicateExists) {
                throw new \Exception('This mobile number has already been used for a WhatsApp referral.');
            }

            // 1. Create WhatsappReferral record
            $referral = WhatsappReferral::create([
                'member_id' => $memberId,
                'mobile_number' => $cleanMobile,
                'message_id' => $messageModel ? $messageModel->id : null,
                'reward_amount' => $rewardAmount,
                'status' => 'Completed',
                'reward_given' => true,
            ]);

            // 2. Create PepeRewardLog record
            $log = PepeRewardLog::create([
                'member_id' => $memberId,
                'mobile_number' => $cleanMobile,
                'reward_amount' => $rewardAmount,
                'reward_type' => self::TYPE_MESSAGE,
                'referred_member_id' => null,
                'description' => 'WhatsApp Promotional Message',
                'message_id' => $messageModel ? $messageModel->id : null,
                'reward_date' => $today,
            ]);

            // 3. Directly credit member's pepe_wallet
            $memLock = MemberDetail::where('memberid', $memberId)->lockForUpdate()->first();
            if ($memLock) {
                $memLock->pepe_wallet = ($memLock->pepe_wallet ?? 0) + $rewardAmount;
                $memLock->save();
            }

            $newTodayCount = $todayCount + 1;

            return [
                'success' => true,
                'reward_amount' => $rewardAmount,
                'today_count' => $newTodayCount,
                'daily_limit' => self::DAILY_MESSAGE_LIMIT,
                'referral' => $referral,
                'log' => $log,
            ];
        });
    }

    /**
     * Process Direct Referral Registration Reward (Rule 2: 500 tokens for direct registration).
     */
    public static function awardDirectRegistrationReward(string $sponsorId, MemberDetail $newMember): ?PepeRewardLog
    {
        if (empty($sponsorId) || strtoupper($sponsorId) === 'ROOT') {
            return null;
        }

        $sponsor = MemberDetail::where('memberid', $sponsorId)->first();
        if (! $sponsor) {
            return null;
        }

        // Check if already awarded for this newly registered member
        $alreadyAwarded = PepeRewardLog::where('reward_type', self::TYPE_DIRECT_REGISTRATION)
            ->where('referred_member_id', $newMember->memberid)
            ->exists();

        if ($alreadyAwarded) {
            return null;
        }

        $rewardAmount = self::AMOUNT_DIRECT_REGISTRATION;
        $today = now()->toDateString();

        try {
            return DB::transaction(function () use ($sponsorId, $newMember, $rewardAmount, $today) {
                // Lock sponsor record
                $sponsorLock = MemberDetail::where('memberid', $sponsorId)->lockForUpdate()->first();
                if (! $sponsorLock) {
                    return null;
                }

                // Check again inside lock to prevent race conditions
                $exists = PepeRewardLog::where('reward_type', self::TYPE_DIRECT_REGISTRATION)
                    ->where('referred_member_id', $newMember->memberid)
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    return null;
                }

                $log = PepeRewardLog::create([
                    'member_id' => $sponsorId,
                    'mobile_number' => $newMember->mobile ?? '',
                    'reward_amount' => $rewardAmount,
                    'reward_type' => self::TYPE_DIRECT_REGISTRATION,
                    'referred_member_id' => $newMember->memberid,
                    'description' => 'Direct Referral Registration: '.$newMember->memberid.' ('.$newMember->name.')',
                    'message_id' => null,
                    'reward_date' => $today,
                ]);

                $sponsorLock->pepe_wallet = ($sponsorLock->pepe_wallet ?? 0) + $rewardAmount;
                $sponsorLock->save();

                return $log;
            });
        } catch (\Throwable $e) {
            Log::error('Error awarding direct registration PEPE reward: '.$e->getMessage(), [
                'sponsor' => $sponsorId,
                'newMember' => $newMember->memberid,
            ]);

            return null;
        }
    }

    /**
     * Process Direct Referral Activation Reward (Rule 3: 500 tokens when direct referral activates).
     */
    public static function awardDirectActivationReward(string $sponsorId, MemberDetail $activatedMember): ?PepeRewardLog
    {
        if (empty($sponsorId) || strtoupper($sponsorId) === 'ROOT') {
            return null;
        }

        $sponsor = MemberDetail::where('memberid', $sponsorId)->first();
        if (! $sponsor) {
            return null;
        }

        // Check if already awarded for this activated member
        $alreadyAwarded = PepeRewardLog::where('reward_type', self::TYPE_DIRECT_ACTIVATION)
            ->where('referred_member_id', $activatedMember->memberid)
            ->exists();

        if ($alreadyAwarded) {
            return null;
        }

        $rewardAmount = self::AMOUNT_DIRECT_ACTIVATION;
        $today = now()->toDateString();

        try {
            return DB::transaction(function () use ($sponsorId, $activatedMember, $rewardAmount, $today) {
                // Lock sponsor record
                $sponsorLock = MemberDetail::where('memberid', $sponsorId)->lockForUpdate()->first();
                if (! $sponsorLock) {
                    return null;
                }

                // Check again inside lock to prevent race conditions
                $exists = PepeRewardLog::where('reward_type', self::TYPE_DIRECT_ACTIVATION)
                    ->where('referred_member_id', $activatedMember->memberid)
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    return null;
                }

                $log = PepeRewardLog::create([
                    'member_id' => $sponsorId,
                    'mobile_number' => $activatedMember->mobile ?? '',
                    'reward_amount' => $rewardAmount,
                    'reward_type' => self::TYPE_DIRECT_ACTIVATION,
                    'referred_member_id' => $activatedMember->memberid,
                    'description' => 'Direct Referral Activation: '.$activatedMember->memberid.' ('.$activatedMember->name.')',
                    'message_id' => null,
                    'reward_date' => $today,
                ]);

                $sponsorLock->pepe_wallet = ($sponsorLock->pepe_wallet ?? 0) + $rewardAmount;
                $sponsorLock->save();

                return $log;
            });
        } catch (\Throwable $e) {
            Log::error('Error awarding direct activation PEPE reward: '.$e->getMessage(), [
                'sponsor' => $sponsorId,
                'activatedMember' => $activatedMember->memberid,
            ]);

            return null;
        }
    }

    /**
     * Calculate Total PEPE Tokens Earned by a member across all promotion rules.
     */
    public static function getTotalEarned(string $memberId): float
    {
        $logSum = (float) PepeRewardLog::where('member_id', $memberId)->sum('reward_amount');
        if ($logSum > 0) {
            return $logSum;
        }

        return (float) WhatsappReferral::where('member_id', $memberId)->sum('reward_amount');
    }
}
