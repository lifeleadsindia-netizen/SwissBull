<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class MemberDashboardLockTest extends TestCase
{
    private string $memberId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        TradingWalletSetting::updateOrCreate(['id' => 1], [
            'lock_days' => 30,
            'withdrawal_percent' => 100.00,
        ]);

        $this->memberId = 'MBTEST'.rand(10000, 99999);
        MemberDetail::create([
            'memberid' => $this->memberId,
            'name' => 'Dashboard Test Member',
            'mobile' => '99'.rand(10000000, 99999999),
            'email' => 'mbtest'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('password123'),
            'p2p_wallet' => 350.00,
            'wallet' => 100.00,
            'country' => 'India',
            'status' => 'Active',
        ]);
    }

    protected function tearDown(): void
    {
        PackageDetail::where('memberid', $this->memberId)->delete();
        StakingDetail::where('memberid', $this->memberId)->delete();
        MemberDetail::where('memberid', $this->memberId)->delete();
        parent::tearDown();
    }

    /**
     * Test 1-9: When Trading Wallet is locked, Member Dashboard loads and displays
     * Investment, Trading Wallet, Total/Max Earning, Package Status,
     * "Fund Locked", "Fund Unlocks In XX Days", and "Fund Locked, You are eligible for total return of XXX% Returns".
     */
    public function test_member_dashboard_displays_locked_state_and_exact_required_messages(): void
    {
        $package = PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '50-500',
            'package_value' => 500.00,
            'invest_amount' => 500.00,
            'trading_wallet_amount' => 350.00,
            'order_id' => 'ORD'.time(),
            'txnid' => '0x_dash_locked_'.time(),
            'status' => 'Active',
            'activated_at' => now(),
            'expires_at' => now()->addDays(1200),
            'return_percent' => 5.00,
            'total_earning' => 25.00,
            'max_earning' => 1000.00,
            'max_return_percent' => 200.00,
            'lock_days' => 30,
            'lock_applied_at' => now(),
            'locked_until' => now()->addDays(30),
        ]);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
            'country' => 'India',
        ])->get('/member/dashboard');

        $response->assertStatus(200);

        // Investment, Trading Wallet, Total Earning, Max Earning
        $response->assertSee('500.00');
        $response->assertSee('350.00');
        $response->assertSee('25.00');
        $response->assertSee('1,000.00');

        // Required Exact Text Strings
        $response->assertSee('Fund Locked');
        $response->assertSee('Fund Unlocks In 30 Days');
        $response->assertSee('Fund Locked, You are eligible for total return of 200% Returns');
    }

    /**
     * Test 10-11: When lock period has expired/unlocked, Member Dashboard reflects
     * "Fund Unlocked" and unlocked return eligibility message.
     */
    public function test_member_dashboard_reflects_unlocked_state_after_lock_expiry(): void
    {
        PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '50-500',
            'package_value' => 200.00,
            'invest_amount' => 200.00,
            'trading_wallet_amount' => 140.00,
            'order_id' => 'ORD_UNL_'.time(),
            'txnid' => '0x_dash_unlocked_'.time(),
            'status' => 'Active',
            'activated_at' => now()->subDays(35),
            'expires_at' => now()->addDays(1165),
            'return_percent' => 5.00,
            'total_earning' => 10.00,
            'max_earning' => 400.00,
            'max_return_percent' => 200.00,
            'lock_days' => 30,
            'lock_applied_at' => now()->subDays(35),
            'locked_until' => now()->subDays(5), // Passed 5 days ago -> Unlocked!
        ]);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
            'country' => 'India',
        ])->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Fund Unlocked');
        $response->assertSee('You are eligible for total return of 200% Returns');
    }

    /**
     * Test Multiple Packages: Each package shows its individual lock status and metadata.
     */
    public function test_member_dashboard_displays_multiple_packages_with_package_wise_lock_status(): void
    {
        // Package 1: Locked
        PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '50-500',
            'package_value' => 100.00,
            'invest_amount' => 100.00,
            'trading_wallet_amount' => 70.00,
            'order_id' => 'ORD1_'.time(),
            'txnid' => '0x_multi_1_'.time(),
            'status' => 'Active',
            'activated_at' => now(),
            'expires_at' => now()->addDays(1200),
            'max_return_percent' => 200.00,
            'lock_days' => 30,
            'locked_until' => now()->addDays(30),
        ]);

        // Package 2: Unlocked
        PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '600-5000',
            'package_value' => 1000.00,
            'invest_amount' => 1000.00,
            'trading_wallet_amount' => 700.00,
            'order_id' => 'ORD2_'.time(),
            'txnid' => '0x_multi_2_'.time(),
            'status' => 'Active',
            'activated_at' => now()->subDays(40),
            'expires_at' => now()->addDays(1160),
            'max_return_percent' => 200.00,
            'lock_days' => 30,
            'locked_until' => now()->subDays(10),
        ]);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
            'country' => 'India',
        ])->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('50-500');
        $response->assertSee('600-5000');
        $response->assertSee('Package Investments & Individual Lock Status');
    }

    /**
     * Test 13: Member cannot bypass the lock through API (tradingWalletValidate blocks withdrawal).
     */
    public function test_member_cannot_bypass_trading_wallet_lock_via_api(): void
    {
        PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '50-500',
            'package_value' => 500.00,
            'invest_amount' => 500.00,
            'trading_wallet_amount' => 350.00,
            'order_id' => 'ORD_BYP_'.time(),
            'txnid' => '0x_byp_'.time(),
            'status' => 'Active',
            'activated_at' => now(),
            'expires_at' => now()->addDays(1200),
            'lock_days' => 30,
            'locked_until' => now()->addDays(30),
        ]);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
        ])->postJson('/member/tradingWalletValidate', [
            'memberid' => $this->memberId,
            'withAmount' => 50.00,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'code' => 0,
            'data' => 0,
        ]);
        $this->assertStringContainsString('locked', strtolower($response->json('message')));
    }

    /**
     * Test 15: API endpoint returns accurate real-time backend data for Member Dashboard.
     */
    public function test_trading_wallet_status_api_endpoint_returns_accurate_backend_data(): void
    {
        PackageDetail::create([
            'memberid' => $this->memberId,
            'package_type' => 'Investment Package',
            'package_range' => '50-500',
            'package_value' => 300.00,
            'invest_amount' => 300.00,
            'trading_wallet_amount' => 210.00,
            'order_id' => 'ORD_API_'.time(),
            'txnid' => '0x_api_'.time(),
            'status' => 'Active',
            'activated_at' => now(),
            'expires_at' => now()->addDays(1200),
            'total_earning' => 15.00,
            'max_earning' => 600.00,
            'max_return_percent' => 200.00,
            'lock_days' => 30,
            'locked_until' => now()->addDays(30),
        ]);

        $response = $this->withSession([
            'MEMBER_ID' => $this->memberId,
        ])->getJson('/member/trading-wallet/status');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'data' => [
                'investment' => 300.00,
                'trading_wallet' => 350.00,
                'total_earning' => 15.00,
                'max_earning' => 600.00,
                'package_status' => 'Active',
                'is_locked' => true,
                'lock_status' => 'Fund Locked',
                'maximum_return_percent' => 200.00,
                'fund_lock_title' => 'Fund Locked',
                'fund_unlocks_in_text' => 'Fund Unlocks In 30 Days',
                'fund_lock_return_message' => 'Fund Locked, You are eligible for total return of 200% Returns',
            ],
        ]);
    }
}
