<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\PepeRewardLog;
use App\Models\WhatsappReferral;
use App\Models\WhatsappReferralMessage;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsappReferralTest extends TestCase
{
    public function test_whatsapp_referral_full_flow_and_security_rules(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'WATEST'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Alice Tester',
            'mobile' => '9823487123',
            'email' => 'alice'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'pepe_wallet' => 0,
        ]);

        // Create Admin Message Template
        $msg = WhatsappReferralMessage::create([
            'title' => 'Daily Promo',
            'content' => 'Hello {member_name}, join using link {referral_link}',
            'status' => 'Active',
            'apply_to' => 'All Members',
        ]);

        // 0. Fake / Dummy number check
        $resFake = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', ['mobile_number' => '1234567890']);
        $resFake->assertStatus(422)->assertJson([
            'success' => false,
            'message' => 'Please enter a valid, real WhatsApp mobile number. Fake or dummy numbers are not allowed.',
        ]);

        $resFake2 = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', ['mobile_number' => '9999999999']);
        $resFake2->assertStatus(422)->assertJson([
            'success' => false,
            'message' => 'Please enter a valid, real WhatsApp mobile number. Fake or dummy numbers are not allowed.',
        ]);

        // 1. Rule 3: Self referral check
        $resSelf = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', ['mobile_number' => '9823487123']);
        $resSelf->assertStatus(422)->assertJson(['success' => false, 'message' => 'You cannot refer yourself.']);

        // 2. Successful verification of valid external number
        Http::fake([
            'https://wa.me/*' => Http::response('<meta property="og:title" content="Registered User"><meta property="og:description" content="WhatsApp Account">', 200),
        ]);

        $targetMobile = '98'.rand(20000000, 99999999);
        $resVerify = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', ['mobile_number' => $targetMobile]);
        $resVerify->assertStatus(200)->assertJson(['success' => true]);

        // 3. Successful Process Referral & 1000 PEPE credit
        $resProcess = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/process', ['mobile_number' => $targetMobile]);
        $resProcess->assertStatus(200)->assertJson(['success' => true]);

        // Verify promotional rewards are recorded
        $this->assertDatabaseHas('whatsapp_referrals', [
            'member_id' => $memberId,
            'mobile_number' => $targetMobile,
            'reward_amount' => 1000.00,
        ]);

        $this->assertDatabaseHas('pepe_reward_logs', [
            'member_id' => $memberId,
            'mobile_number' => $targetMobile,
            'reward_amount' => 1000.00,
        ]);

        // Redeem earned PEPE tokens directly
        $resRedeem = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/pepe/redeem', ['amount' => 1000]);
        $resRedeem->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('withdrawal_requests', [
            'memberid' => $memberId,
            'gross_amount' => 1000,
            'type' => 'Airdrop Withdrawal',
            'status' => 'Approved',
        ]);

        // 4. Rule 1: Daily Limit Check (Attempting second referral today)
        $resDaily = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/process', ['mobile_number' => '9891238475']);
        $resDaily->assertStatus(422)->assertJson(['success' => false, 'message' => 'You have already used your daily referral for today.']);

        // 5. Member 2 attempt to use the same mobile number (Cross-user duplicate check)
        $member2Id = 'WATEST'.rand(1000, 9999);
        $member2 = MemberDetail::create([
            'memberid' => $member2Id,
            'name' => 'Bob Tester',
            'mobile' => '9876512399',
            'email' => 'bob'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'pepe_wallet' => 0,
        ]);

        $resMember2Verify = $this->withSession(['MEMBER_ID' => $member2Id])
            ->postJson('/member/whatsapp/verify', ['mobile_number' => '+91'.$targetMobile]);
        $resMember2Verify->assertStatus(422)
            ->assertJson(['success' => false, 'message' => 'This mobile number has already been used for a WhatsApp referral.']);

        // 6. Member Referral Details Page
        $resDetails = $this->withSession(['MEMBER_ID' => $memberId])
            ->get('/member/whatsapp/referral-details');
        $resDetails->assertStatus(200)->assertSee('Referral Details')->assertSee($targetMobile);

        // Cleanup
        $member->delete();
        $member2->delete();
        $msg->delete();
        WhatsappReferral::whereIn('member_id', [$memberId, $member2Id])->delete();
        PepeRewardLog::whereIn('member_id', [$memberId, $member2Id])->delete();
    }

    public function test_whatsapp_referral_supports_various_country_mobile_lengths(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'WATEST'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Charlie Global',
            'mobile' => '9899999999',
            'email' => 'charlie'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'pepe_wallet' => 0,
        ]);

        // UAE: 9 digits local (+971 501234567) -> 12 digits total
        $resUae = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', [
                'mobile_number' => '+971501234567',
                'country' => 'United Arab Emirates',
                'phonecode' => '971',
            ]);
        $resUae->assertStatus(200)->assertJson(['success' => true]);

        // Singapore: 8 digits local (+65 81234567) -> 10 digits total
        $resSg = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', [
                'mobile_number' => '+6581234567',
                'country' => 'Singapore',
                'phonecode' => '65',
            ]);
        $resSg->assertStatus(200)->assertJson(['success' => true]);

        // USA: 10 digits local (+1 2025550199) starting with 2 -> 11 digits total
        $resUs = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', [
                'mobile_number' => '+12025550199',
                'country' => 'United States',
                'phonecode' => '1',
            ]);
        $resUs->assertStatus(200)->assertJson(['success' => true]);

        // India: 10 digits local starting with 91 (e.g. 9140123456) -> +919140123456
        $resIn = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/verify', [
                'mobile_number' => '+919140123456',
                'country' => 'India',
                'phonecode' => '91',
            ]);
        $resIn->assertStatus(200)->assertJson(['success' => true]);

        // Process referral and check whatsapp_url has clean digits in phone parameter
        $resProcessIn = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/whatsapp/process', [
                'mobile_number' => '+919140123456',
                'country' => 'India',
                'phonecode' => '91',
            ]);
        $resProcessIn->assertStatus(200)->assertJson(['success' => true]);
        $json = $resProcessIn->json();
        $this->assertStringContainsString('phone=919140123456', $json['whatsapp_url']);
        $this->assertStringNotContainsString('phone=%2B', $json['whatsapp_url']);
        $this->assertStringNotContainsString('phone=+', $json['whatsapp_url']);

        $member->delete();
        WhatsappReferral::where('member_id', $memberId)->delete();
        PepeRewardLog::where('member_id', $memberId)->delete();
    }
}
