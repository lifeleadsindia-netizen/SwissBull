<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\MemberDetail;
use App\Models\PepeSetting;
use App\Models\WhatsappReferral;
use App\Models\WithdrawalRequest;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class PepeRedeemTest extends TestCase
{
    public function test_pepe_redeem_direct_approval_and_payment_history_flow(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PEPETEST'.rand(1000, 9999);
        $walletAddr = '0x1234567890abcdef1234567890abcdef12345678';
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Pepe Holder',
            'mobile' => '9812345678',
            'email' => 'pepe'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'member_wallet' => $walletAddr,
            'pepe_wallet' => 1000,
        ]);

        // Member earns 1000 PEPE tokens via promotional referral
        WhatsappReferral::create([
            'member_id' => $memberId,
            'mobile_number' => '+919812345678',
            'reward_amount' => 1000,
            'status' => 'Completed',
            'reward_given' => true,
        ]);

        // 1. Submit direct redeem for 1000 tokens
        $res = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/pepe/redeem', [
                'amount' => 1000,
                'wallet_address' => $walletAddr,
            ]);

        $res->assertStatus(200)->assertJson(['success' => true]);

        // Check member's pepe_wallet was deducted upon successful DApp/on-chain redemption
        $member->refresh();
        $this->assertEquals(0, $member->pepe_wallet);

        // Check request was saved directly as Approved (no Pending state)
        $this->assertDatabaseHas('withdrawal_requests', [
            'memberid' => $memberId,
            'gross_amount' => 1000,
            'type' => 'Airdrop Withdrawal',
            'status' => 'Approved',
        ]);

        $req = WithdrawalRequest::where('memberid', $memberId)->where('type', 'Airdrop Withdrawal')->first();
        $this->assertNotNull($req->payment_date);

        // 2. Prevent over-redeem when balance exhausted
        $resExhausted = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/pepe/redeem', [
                'amount' => 500,
                'wallet_address' => $walletAddr,
            ]);
        $resExhausted->assertStatus(422);

        // 3. Verify Admin Payment History page has this PEPE request in pepeData
        $admin = Admin::first();
        $resHistoryPage = $this->withSession(['ADMIN_LOGIN' => true, 'admin_id' => $admin ? $admin->id : 1])
            ->get('/admin/payment-history');
        $resHistoryPage->assertStatus(200);
        $resHistoryPage->assertSee($memberId);
        $resHistoryPage->assertSee('1,000 PEPE');

        // 4. Verify Admin New Withdrawal Requests page does NOT have any pending PEPE request
        $resNewRequests = $this->withSession(['ADMIN_LOGIN' => true, 'admin_id' => $admin ? $admin->id : 1])
            ->get('/admin/new-withdrawal-request');
        $resNewRequests->assertStatus(200);
        $resNewRequests->assertDontSee($req->request_id);

        // Cleanup
        $member->delete();
        WhatsappReferral::where('member_id', $memberId)->delete();
        WithdrawalRequest::where('memberid', $memberId)->delete();
    }

    public function test_admin_can_view_and_update_pepe_settings(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $admin = Admin::first();
        $session = ['ADMIN_LOGIN' => true, 'admin_id' => $admin ? $admin->id : 1];

        // 1. View settings page
        $resView = $this->withSession($session)->get('/admin/pepe-settings');
        $resView->assertStatus(200);
        $resView->assertSee('PEPE Token Withdrawal Settings');
        $resView->assertSee('0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00');

        // 2. Update settings with valid new configuration including custom token_abi
        $newContract = '0x1111111111111111111111111111111111111111';
        $customAbi = json_encode([
            ['inputs' => [], 'name' => 'customFunc', 'outputs' => [], 'stateMutability' => 'view', 'type' => 'function'],
        ]);
        $resUpdate = $this->withSession($session)->post('/admin/updatePepeSettings', [
            'contract_address' => $newContract,
            'token_abi' => $customAbi,
            'token_symbol' => 'PEPE2',
            'token_name' => 'PEPE Version 2',
            'token_decimals' => 18,
            'chain_id' => 56,
            'network_name' => 'BNB Smart Chain (BEP20)',
            'rpc_url' => 'https://bsc-dataseed1.binance.org/',
            'explorer_url' => 'https://bscscan.com',
            'disbursement_wallet' => '0x2222222222222222222222222222222222222222',
            'disbursement_key' => 'abcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcdefabcd',
            'gas_limit' => 160000,
            'min_redeem' => 10,
            'is_active' => '1',
        ]);

        $resUpdate->assertRedirect();
        $this->assertDatabaseHas('pepe_settings', [
            'contract_address' => $newContract,
            'token_abi' => $customAbi,
            'token_symbol' => 'PEPE2',
            'gas_limit' => 160000,
        ]);

        // 3. Test invalid JSON validation for token_abi
        $resInvalidAbi = $this->withSession($session)->post('/admin/updatePepeSettings', [
            'contract_address' => $newContract,
            'token_abi' => '{ invalid json }',
            'token_symbol' => 'PEPE2',
            'token_name' => 'PEPE Version 2',
            'token_decimals' => 18,
            'chain_id' => 56,
            'network_name' => 'BNB Smart Chain (BEP20)',
            'rpc_url' => 'https://bsc-dataseed1.binance.org/',
            'explorer_url' => 'https://bscscan.com',
            'gas_limit' => 160000,
            'min_redeem' => 10,
        ]);
        $resInvalidAbi->assertSessionHasErrors(['token_abi']);

        // 4. Reset settings back to standard address
        $settings = PepeSetting::first();
        $settings->contract_address = '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00';
        $settings->token_abi = PepeSetting::getDefaultAbi();
        $settings->token_symbol = 'PEPE';
        $settings->token_name = 'PEPE BEP-20';
        $settings->token_decimals = 18;
        $settings->chain_id = 56;
        $settings->network_name = 'BNB Smart Chain (BEP20)';
        $settings->rpc_url = 'https://bsc-dataseed.binance.org/';
        $settings->explorer_url = 'https://bscscan.com';
        $settings->gas_limit = 150000;
        $settings->min_redeem = 1;
        $settings->is_active = true;
        $settings->save();
    }

    public function test_dapp_config_endpoint_and_txnid_recording(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PEPEDAPP'.rand(1000, 9999);
        $walletAddr = '0x3333333333333333333333333333333333333333';
        $fakeTxHash = '0x'.bin2hex(random_bytes(32));

        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'DApp Holder',
            'mobile' => '9877665544',
            'email' => 'pepedapp'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'member_wallet' => $walletAddr,
            'pepe_wallet' => 0,
        ]);

        WhatsappReferral::create([
            'member_id' => $memberId,
            'mobile_number' => '+919877665544',
            'reward_amount' => 2000,
            'status' => 'Completed',
            'reward_given' => true,
        ]);

        // 1. Fetch DApp config endpoint as authenticated member
        $resConfig = $this->withSession(['MEMBER_ID' => $memberId])
            ->getJson('/member/pepe/dapp-config');
        $resConfig->assertStatus(200);
        $resConfig->assertJson([
            'success' => true,
            'contract_address' => '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00',
            'token_symbol' => 'PEPE',
            'chain_id' => 56,
        ]);

        // 2. Submit redemption with on-chain txnid

        $resRedeem = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/pepe/redeem', [
                'amount' => 1000,
                'wallet_address' => $walletAddr,
                'txnid' => $fakeTxHash,
            ]);

        $resRedeem->assertStatus(200);
        $resRedeem->assertJson([
            'success' => true,
            'txnid' => $fakeTxHash,
        ]);

        // Verify txnid was saved in database
        $this->assertDatabaseHas('withdrawal_requests', [
            'memberid' => $memberId,
            'txnid' => $fakeTxHash,
            'status' => 'Approved',
        ]);

        // Verify Admin Payment History displays this txnid
        $admin = Admin::first();
        $resAdminHistory = $this->withSession(['ADMIN_LOGIN' => true, 'admin_id' => $admin ? $admin->id : 1])
            ->get('/admin/payment-history');
        $resAdminHistory->assertStatus(200);
        $resAdminHistory->assertSee($fakeTxHash);

        // Cleanup
        $member->delete();
        WhatsappReferral::where('member_id', $memberId)->delete();
        WithdrawalRequest::where('memberid', $memberId)->delete();
    }

    public function test_member_dashboard_allows_redeeming_existing_pepe_wallet_balance(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PEPEBAL'.rand(1000, 9999);
        $walletAddr = '0x4444444444444444444444444444444444444444';

        // Member has 1,000 PEPE tokens directly in pepe_wallet, with 0 new referrals
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Existing Pepe Holder',
            'mobile' => '9988776655',
            'email' => 'holder'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'country' => 'India',
            'member_wallet' => $walletAddr,
            'pepe_wallet' => 1000,
        ]);

        // 1. Visit Member Dashboard
        $resDashboard = $this->withSession([
            'MEMBER_ID' => $memberId,
            'country' => 'India',
        ])->get('/member/dashboard');

        $resDashboard->assertStatus(200);
        $resDashboard->assertSee('1,000 PEPE');
        $resDashboard->assertSee('window.pepeWalletBalance = 1000', false);
        $resDashboard->assertSee('window.waAvailablePepe = 1000', false);

        // 2. Submit redeem request for 1000 tokens
        $resRedeem = $this->withSession(['MEMBER_ID' => $memberId])
            ->postJson('/member/pepe/redeem', [
                'amount' => 1000,
                'wallet_address' => $walletAddr,
            ]);

        $resRedeem->assertStatus(200);
        $resRedeem->assertJson([
            'success' => true,
            'new_balance' => 0,
            'redeemed_amount' => 1000,
        ]);

        // 3. Member's pepe_wallet should be updated to 0
        $member->refresh();
        $this->assertEquals(0, $member->pepe_wallet);

        // 4. Withdrawal record is created with Approved status
        $this->assertDatabaseHas('withdrawal_requests', [
            'memberid' => $memberId,
            'gross_amount' => 1000,
            'type' => 'Airdrop Withdrawal',
            'status' => 'Approved',
        ]);

        // Cleanup
        $member->delete();
        WithdrawalRequest::where('memberid', $memberId)->delete();
    }

    public function test_dashboard_reflects_zero_balance_after_redemption_and_blocks_further_redeem(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'ZEROTEST'.rand(1000, 9999);
        $walletAddr = '0x812f6784B3E9eAae424287ca99986374E98747C6';
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Zero Tester',
            'mobile' => '9811223344',
            'email' => 'zero'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'country' => 'India',
            'member_wallet' => $walletAddr,
            'pepe_wallet' => 1000,
        ]);

        WhatsappReferral::create([
            'member_id' => $memberId,
            'mobile_number' => '+919811223344',
            'reward_amount' => 1000,
            'status' => 'Completed',
            'reward_given' => true,
        ]);

        // 1. First visit to dashboard: should show 1,000 PEPE
        $resDash1 = $this->withSession(['MEMBER_ID' => $memberId, 'country' => 'India'])
            ->get('/member/dashboard');
        $resDash1->assertStatus(200);
        $resDash1->assertSee('1,000 PEPE');
        $resDash1->assertSee('window.pepeWalletBalance = 1000', false);

        // 2. Validate balance for 1000 tokens: should succeed
        $resValidate1 = $this->postJson('/member/pepeValidate', [
            'memberid' => $memberId,
            'withAmount' => 1000,
        ]);
        $resValidate1->assertStatus(200)->assertJson(['code' => 1, 'data' => 1000]);

        // 3. Complete redemption
        $resWithdraw = $this->postJson('/member/initiatePepeWithdrawal', [
            'memberid' => $memberId,
            'memberWallet' => $walletAddr,
            'amount' => 1000,
            'txnid' => '0x'.str_pad(bin2hex(random_bytes(32)), 64, '0', STR_PAD_LEFT),
        ]);
        $resWithdraw->assertStatus(200)->assertJson(['code' => 1, 'status' => 'success']);

        // Member's pepe_wallet in DB must now be 0
        $member->refresh();
        $this->assertEquals(0, $member->pepe_wallet);

        // 4. Second visit to dashboard: MUST show 0 PEPE, not 1,000 PEPE!
        $resDash2 = $this->withSession(['MEMBER_ID' => $memberId, 'country' => 'India'])
            ->get('/member/dashboard');
        $resDash2->assertStatus(200);
        $resDash2->assertSee('0 PEPE');
        $resDash2->assertSee('window.pepeWalletBalance = 0', false);
        $resDash2->assertSee('window.waAvailablePepe = 0', false);

        // 5. Subsequent balance validation must fail
        $resValidate2 = $this->postJson('/member/pepeValidate', [
            'memberid' => $memberId,
            'withAmount' => 1000,
        ]);
        $resValidate2->assertStatus(200)->assertJson(['code' => 0, 'data' => 0]);
        $this->assertStringContainsString('Insufficient balance', $resValidate2->json('message'));

        // 6. Subsequent initiate withdrawal must be rejected
        $resWithdraw2 = $this->postJson('/member/initiatePepeWithdrawal', [
            'memberid' => $memberId,
            'memberWallet' => $walletAddr,
            'amount' => 1000,
            'txnid' => '0x'.str_pad(bin2hex(random_bytes(32)), 64, '0', STR_PAD_LEFT),
        ]);
        $resWithdraw2->assertStatus(422)->assertJson(['code' => 0, 'status' => 'error']);

        // Cleanup
        $member->delete();
        WhatsappReferral::where('member_id', $memberId)->delete();
        WithdrawalRequest::where('memberid', $memberId)->delete();
    }
}
