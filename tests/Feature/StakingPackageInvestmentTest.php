<?php

namespace Tests\Feature;

use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\MonthlyTradingProfitConfiction;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
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

    public function test_staking_deducts_p2p_wallet_and_credits_dynamic_percent_to_trading_wallet(): void
    {
        $distributionConfig = PackageDistribution::getDistributionConfig();
        $tradingPercent = (float) ($distributionConfig['trading_wallet'] ?? 70.0);
        $stakeAmount = 100.00;
        $expectedTradingWallet = round($stakeAmount * ($tradingPercent / 100), 2);
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
        $this->assertEquals($expectedTradingWallet, (float) $pkg->trading_wallet_amount);
        $this->assertEquals('Active', $pkg->status);

        // Verify Staking Detail record
        $staking = StakingDetail::where('memberid', $this->memberId)->latest()->first();
        $this->assertNotNull($staking);
        $this->assertEquals(100.00, (float) $staking->invest_amount);
        $this->assertEquals('50-500', $staking->package);
        $this->assertEquals($expectedTradingWallet, (float) $staking->trading_wallet_amount);
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
            'debit' => $expectedTradingWallet, // Inflow to Trading Wallet
        ]);
    }

    public function test_admin_changes_percentages_and_member_panel_reflects_dynamically(): void
    {
        $origDist = PackageDistribution::first()?->only(['trading_wallet', 'p2p_wallet']);
        $origProfit = MonthlyTradingProfitConfiction::first()?->only([
            'capping_percent', 'rate', 'package_1_rate', 'package_2_rate', 'package_3_rate',
        ]);

        try {
            // 1. Admin configures custom package distribution (60% trading wallet)
            PackageDistribution::query()->update([
                'trading_wallet' => 60.00,
                'p2p_wallet' => 60.00,
            ]);

            // 2. Admin configures custom Monthly Trading Profit (6.5% rate and 300% capping)
            MonthlyTradingProfitConfiction::query()->update([
                'capping_percent' => 300.00,
                'rate' => 6.50,
                'package_1_rate' => 6.50,
                'package_2_rate' => 8.50,
                'package_3_rate' => 12.00,
            ]);

            // 3. Member panel Staking page renders with updated dynamic percentages
            $response = $this->withSession(['MEMBER_ID' => $this->memberId])
                ->get('/member/Staking/create');

            $response->assertStatus(200);
            $response->assertSee('6.5% Daily ROI, 300% Cap');
            $response->assertSeeText('60% Credited to Trading Wallet');

            // 4. Staking created with dynamic percentages
            $investResponse = $this->withSession(['MEMBER_ID' => $this->memberId])
                ->postJson(route('createInvestment'), [
                    'memberid' => $this->memberId,
                    'package' => '50-500',
                    'amount' => 100.00,
                ]);

            $investResponse->assertStatus(200);
            $investResponse->assertJson(['status' => true]);

            $member = MemberDetail::where('memberid', $this->memberId)->first();
            $this->assertEquals(60.00, (float) $member->trading_wallet);

            $staking = StakingDetail::where('memberid', $this->memberId)->latest()->first();
            $this->assertEquals(6.50, (float) $staking->rate);
            $this->assertEquals(300.00, (float) $staking->capping_percent);
            $this->assertEquals(300.00, (float) $staking->max_amount);
        } finally {
            if ($origDist) {
                PackageDistribution::query()->update($origDist);
            }
            if ($origProfit) {
                MonthlyTradingProfitConfiction::query()->update($origProfit);
            }
        }
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

        // Second package purchase is allowed: Every package has individual returns and expiry
        $secondResponse = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('createInvestment'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 150.00,
            ]);

        $secondResponse->assertStatus(200);
        $secondResponse->assertJson(['status' => true]);

        // Verify multiple packages tracked individually
        $stakings = StakingDetail::where('memberid', $this->memberId)->orderBy('id', 'asc')->get();
        $this->assertCount(2, $stakings);
        $this->assertEquals(100.00, (float) $stakings[0]->invest_amount);
        $this->assertEquals(150.00, (float) $stakings[1]->invest_amount);
        $this->assertEquals('Active', $stakings[0]->status);
        $this->assertEquals('Active', $stakings[1]->status);
        $this->assertEquals(200.00, (float) $stakings[0]->max_amount); // 100 * 200%
        $this->assertEquals(300.00, (float) $stakings[1]->max_amount); // 150 * 200%
    }
}
