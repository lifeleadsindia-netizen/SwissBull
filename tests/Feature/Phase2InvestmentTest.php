<?php

namespace Tests\Feature;

use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackagePlan;
use App\Models\TradingWalletSetting;
use App\Models\WalletTransfer;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class Phase2InvestmentTest extends TestCase
{
    private string $memberId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        // Clean any leftovers from past test runs
        PackageDetail::where('txnid', 'like', '0x_phase2_%')->delete();
        ImportFund::where('txnid', 'like', '0x_phase2_%')->delete();
        \App\Models\StakingDetail::where('txnid', 'like', '0x_phase2_%')->delete();

        $this->memberId = 'P2MEM'.rand(10000, 99999);
        MemberDetail::create([
            'memberid' => $this->memberId,
            'name' => 'Phase 2 Investor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'p2investor'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('secret123'),
            'p2p_wallet' => 0.00,
            'wallet' => 0.00,
            'country' => 'India',
            'status' => 'Temp',
        ]);
    }

    protected function tearDown(): void
    {
        PackageDetail::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_phase2_%')->delete();
        ImportFund::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_phase2_%')->delete();
        \App\Models\StakingDetail::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_phase2_%')->delete();
        WalletTransfer::where('memberid', $this->memberId)->delete();
        MemberDetail::where('memberid', $this->memberId)->delete();
        parent::tearDown();
    }

    /**
     * Requirement F: Minimum deposit must be 50 USDT. Rejects anything below 50.
     */
    public function test_minimum_deposit_below_50_is_rejected(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 49.99,
                'txnid' => '0x_phase2_min_fail',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);

        // Wallet must not be modified
        $balance = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $this->assertEquals(0.00, $balance);
    }

    /**
     * Requirement B & I: Sample investment of 50 USDT allocates 70% ($35.00) to Trading Wallet.
     */
    public function test_sample_investment_of_50_usdt_allocates_70_percent_to_trading_wallet(): void
    {
        $depositAmount = 50.00;
        $expectedTradingWallet = 35.00; // 70% of 50

        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => $depositAmount,
                'txnid' => '0x_phase2_test_50',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        // 1. Verify Member Trading Wallet (p2p_wallet) received exactly 70%
        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertEquals($expectedTradingWallet, (float) $member->p2p_wallet);
        $this->assertEquals('Active', $member->status);

        // 2. Verify Deposit Record
        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'amount' => $depositAmount,
            'txnid' => '0x_phase2_test_50',
            'status' => 'Approved',
        ]);

        // 3. Verify Package Detail / Investment Record
        $this->assertDatabaseHas('package_details', [
            'memberid' => $this->memberId,
            'invest_amount' => $depositAmount,
            'trading_wallet_amount' => $expectedTradingWallet,
            'txnid' => '0x_phase2_test_50',
            'status' => 'Active',
        ]);

        // 4. Verify Ledger Transfer
        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Fund Added',
            'debit' => $expectedTradingWallet,
        ]);
    }

    /**
     * Requirement B & I: Sample investment of 100 USDT allocates 70% ($70.00) to Trading Wallet.
     */
    public function test_sample_investment_of_100_usdt_allocates_70_percent_to_trading_wallet(): void
    {
        $depositAmount = 100.00;
        $expectedTradingWallet = 70.00; // 70% of 100

        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => $depositAmount,
                'txnid' => '0x_phase2_test_100',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertEquals($expectedTradingWallet, (float) $member->p2p_wallet);

        $packageDetail = PackageDetail::where('txnid', '0x_phase2_test_100')->first();
        $this->assertNotNull($packageDetail);
        $this->assertEquals(100.00, (float) $packageDetail->invest_amount);
        $this->assertEquals(70.00, (float) $packageDetail->trading_wallet_amount);
        $this->assertNotNull($packageDetail->activated_at);
        $this->assertNotNull($packageDetail->expires_at);
        $this->assertEquals('Active', $packageDetail->status);
        $this->assertEquals(200.00, (float) $packageDetail->max_earning); // 200% max return cap for Tier 1
        $this->assertEquals(200.00, (float) $packageDetail->max_return_percent);
    }

    /**
     * Requirement A & B: Tier 2 (600 - 5000 USDT) deposit creates correct investment record.
     */
    public function test_tier2_investment_of_600_usdt_allocates_70_percent(): void
    {
        $depositAmount = 600.00;
        $expectedTradingWallet = 420.00; // 70% of 600

        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => $depositAmount,
                'txnid' => '0x_phase2_test_600',
            ]);

        $response->assertStatus(200);

        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertEquals($expectedTradingWallet, (float) $member->p2p_wallet);

        $packageDetail = PackageDetail::where('txnid', '0x_phase2_test_600')->first();
        $this->assertNotNull($packageDetail);
        $this->assertEquals(600.00, (float) $packageDetail->invest_amount);
        $this->assertEquals(420.00, (float) $packageDetail->trading_wallet_amount);
        $this->assertEquals('600-5000', $packageDetail->package_range);
    }

    /**
     * Requirement G: Duplicate transaction/request does NOT double-credit the wallet.
     */
    public function test_duplicate_transaction_is_rejected_and_prevents_double_credit(): void
    {
        $txnid = '0x_phase2_duplicate_check';

        // First deposit: 100 USDT -> 70 USDT credited
        $response1 = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 100.00,
                'txnid' => $txnid,
            ]);
        $response1->assertStatus(200);

        $balanceAfterFirst = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $this->assertEquals(70.00, $balanceAfterFirst);

        // Second deposit with same txnid must be REJECTED
        $response2 = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 100.00,
                'txnid' => $txnid,
            ]);

        $response2->assertStatus(422);
        $response2->assertJson(['status' => false]);

        // Balance must remain unchanged at 70.00 (NOT 140.00)
        $balanceAfterSecond = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $this->assertEquals(70.00, $balanceAfterSecond);

        // Only 1 record should exist for this txnid
        $this->assertEquals(1, ImportFund::where('txnid', $txnid)->count());
        $this->assertEquals(1, PackageDetail::where('txnid', $txnid)->count());
    }

    /**
     * Requirement D: Member dashboard displays investment, package, trading wallet, and lock details.
     */
    public function test_user_dashboard_displays_investment_and_package_information(): void
    {
        // Make an investment first
        $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 100.00,
                'txnid' => '0x_phase2_dashboard_test',
            ])
            ->assertStatus(200);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
            'country' => 'India',
        ])->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Active Package &amp; Investment Details', false);
        $response->assertSee('Trading Wallet');
        $response->assertSee('70% Allocated');
        $response->assertSee('Package Active');
        $response->assertSee('70.00'); // Trading wallet amount
        $response->assertSee('100.00'); // Investment amount
    }

    /**
     * Requirement E: Admin can view package details list with Phase 2 investment columns.
     */
    public function test_admin_can_view_investment_records(): void
    {
        $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 50.00,
                'txnid' => '0x_phase2_admin_view_test',
            ])
            ->assertStatus(200);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/package-details');

        $response->assertStatus(200);
        $response->assertSee('Trading Wallet (70%)');
        $response->assertSee('0x_phase2_admin_view_test');
        $response->assertSee('35.00');
    }

    /**
     * Requirement E: PackagePlan model provides dynamic configuration helpers.
     */
    public function test_package_plan_tiers_are_configured_and_retrievable(): void
    {
        $plan1 = PackagePlan::findByRange('50-500');
        $this->assertNotNull($plan1);
        $this->assertEquals(50.00, (float) $plan1->min_amount);
        $this->assertEquals(500.00, (float) $plan1->max_amount);
        $this->assertTrue($plan1->isValidAmount(50));
        $this->assertTrue($plan1->isValidAmount(500));
        $this->assertFalse($plan1->isValidAmount(49));
        $this->assertFalse($plan1->isValidAmount(501));

        $this->assertEquals(35.00, $plan1->calculateTradingWalletAmount(50));
        $this->assertEquals(70.00, $plan1->calculateTradingWalletAmount(100));

        $plan2 = PackagePlan::findByRange('600-5000');
        $this->assertNotNull($plan2);
        $this->assertTrue($plan2->isValidAmount(600));
        $this->assertTrue($plan2->isValidAmount(5000));
        $this->assertFalse($plan2->isValidAmount(599));

        $plan3 = PackagePlan::findByRange('6000+');
        $this->assertNotNull($plan3);
        $this->assertTrue($plan3->isValidAmount(6000));
        $this->assertTrue($plan3->isValidAmount(15000));
        $this->assertFalse($plan3->isValidAmount(5999));
    }
}
