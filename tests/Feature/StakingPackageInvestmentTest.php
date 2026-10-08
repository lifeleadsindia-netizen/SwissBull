<?php

namespace Tests\Feature;

use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use App\Models\WalletTransfer;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class StakingPackageInvestmentTest extends TestCase
{
    private string $sponsorId;

    private string $memberId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        TradingWalletSetting::updateOrCreate(['id' => 1], [
            'lock_days' => 90,
            'withdrawal_percent' => 100.00,
        ]);

        $this->sponsorId = 'SPONSOR'.rand(10000, 99999);
        MemberDetail::create([
            'memberid' => $this->sponsorId,
            'sponsorid' => 'Root',
            'name' => 'Sponsor User',
            'mobile' => '99'.rand(10000000, 99999999),
            'email' => 'sponsor'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('password123'),
            'p2p_wallet' => 0.00,
            'trading_wallet' => 0.00,
            'status' => 'Active',
        ]);

        $this->memberId = 'STKMEM'.rand(10000, 99999);
        MemberDetail::create([
            'memberid' => $this->memberId,
            'sponsorid' => $this->sponsorId,
            'name' => 'Staking Investor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'stkmember'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('password123'),
            'p2p_wallet' => 500.00, // Available in Fund Wallet
            'trading_wallet' => 0.00,
            'status' => 'Temp',
        ]);
    }

    protected function tearDown(): void
    {
        PackageDetail::where('memberid', $this->memberId)->delete();
        StakingDetail::where('memberid', $this->memberId)->delete();
        WalletTransfer::whereIn('memberid', [$this->memberId, $this->sponsorId])->delete();
        DirectIncome::where('memberid', $this->memberId)->delete();
        LevelIncome::where('memberid', $this->memberId)->delete();
        MemberDetail::whereIn('memberid', [$this->memberId, $this->sponsorId])->delete();
        parent::tearDown();
    }

    public function test_staking_page_renders_with_package_plans_and_wallet_balances(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->get('/member/Staking/create');

        $response->assertStatus(200);
        $response->assertSee('Activate Staking Package');
        $response->assertSee('Fund Wallet Balance');
        $response->assertSee('500.00');
        $response->assertSee('50-500');
        $response->assertSee('600-5000');
    }

    public function test_staking_requires_valid_package_and_minimum_50(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 49.00,
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_staking_rejects_insufficient_p2p_wallet_balance(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => 1000.00, // balance is only 500
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
        $response->assertJsonFragment(['message' => 'Insufficient Fund Wallet (P2P Wallet) balance. Available: $500.00']);
    }

    public function test_staking_rejects_amounts_outside_package_plan_boundaries(): void
    {
        // 550 is outside Package 1 (50-500)
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 550.00,
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_staking_deducts_p2p_wallet_and_credits_70_percent_to_trading_wallet(): void
    {
        $stakeAmount = 100.00;
        $expectedTradingWallet = 70.00; // 70% of 100
        $expectedP2PBalance = 400.00; // 500 - 100

        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => $stakeAmount,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertEquals($expectedP2PBalance, (float) $member->p2p_wallet);
        $this->assertEquals($expectedTradingWallet, (float) $member->trading_wallet);
        $this->assertEquals('Active', $member->status);

        // Verify Package Detail record
        $pkg = PackageDetail::where('memberid', $this->memberId)->latest()->first();
        $this->assertNotNull($pkg);
        $this->assertEquals(100.00, (float) $pkg->invest_amount);
        $this->assertEquals(70.00, (float) $pkg->trading_wallet_amount);
        $this->assertEquals('Active', $pkg->status);

        // Verify Staking Detail record
        $staking = StakingDetail::where('memberid', $this->memberId)->latest()->first();
        $this->assertNotNull($staking);
        $this->assertEquals(100.00, (float) $staking->invest_amount);
        $this->assertEquals('50-500', $staking->package);
        $this->assertEquals('Active', $staking->status);

        // Verify Wallet Ledger records
        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Staking Package',
            'credit' => 100.00, // Deduction from P2P Wallet
        ]);

        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Trading Wallet',
            'debit' => 70.00, // Inflow to Trading Wallet
        ]);
    }

    public function test_staking_applies_trading_wallet_lock_and_incomes(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 100.00,
            ]);

        $response->assertStatus(200);

        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertTrue($member->isTradingWalletLocked());
        $this->assertGreaterThan(0, $member->tradingWalletRemainingLockDays());

        // Second package purchase during active lock should be blocked
        $secondResponse = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 100.00,
            ]);

        $secondResponse->assertStatus(422);
        $secondResponse->assertJson(['status' => false]);
    }
}
