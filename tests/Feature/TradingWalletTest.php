<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class TradingWalletTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        view()->share('errors', new ViewErrorBag);
    }

    /**
     * CHANGE 2: Backend balance is member_details.p2p_wallet.
     * No trading_wallet column in database schema.
     */
    public function test_backend_wallet_storage_is_p2p_wallet_and_no_trading_wallet_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('member_details', 'p2p_wallet'),
            'Column p2p_wallet must exist in member_details table'
        );

        $this->assertFalse(
            Schema::hasColumn('member_details', 'trading_wallet'),
            'Column trading_wallet must not exist in member_details table'
        );

        $member = MemberDetail::first();
        if ($member) {
            $origP2P = $member->p2p_wallet;

            $member->p2p_wallet = 150.75;
            $member->save();

            $freshMember = MemberDetail::find($member->id);
            $this->assertEqualsWithDelta(150.75, (float) $freshMember->trading_wallet, 0.001);
            $this->assertEqualsWithDelta(150.75, (float) $freshMember->p2p_wallet, 0.001);

            $freshMember->trading_wallet = 220.50;
            $freshMember->save();

            $reloadedMember = MemberDetail::find($member->id);
            $this->assertEqualsWithDelta(220.50, (float) $reloadedMember->p2p_wallet, 0.001);
            $this->assertEqualsWithDelta(220.50, (float) $reloadedMember->trading_wallet, 0.001);

            $reloadedMember->p2p_wallet = $origP2P;
            $reloadedMember->save();
        }
    }

    /**
     * CHANGE 3: Default Trading Wallet rules are 90 Days Lock and 100% Maximum Withdrawal.
     */
    public function test_default_trading_wallet_rules_are_90_days_and_100_percent(): void
    {
        $this->assertTrue(
            Schema::hasTable('trading_wallet_settings'),
            'Table trading_wallet_settings must exist'
        );

        $setting = TradingWalletSetting::getActiveSetting();
        $this->assertNotNull($setting);
        $this->assertEquals(90, (int) $setting->lock_days);
        $this->assertEqualsWithDelta(100.00, (float) $setting->withdrawal_percent, 0.01);

        $this->assertEquals(90, TradingWalletSetting::getDefaultLockDays());
        $this->assertEqualsWithDelta(100.00, TradingWalletSetting::getDefaultWithdrawalPercent(), 0.01);
    }

    /**
     * Admin Trading Wallet Control UI loads successfully with 90 days / 100% and "Trading Wallet" terminology.
     */
    public function test_trading_wallet_control_admin_page_renders_with_90_days_and_100_percent(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/trading-wallet-control');

        $response->assertStatus(200);
        $response->assertViewIs('admin.trading-wallet-control');
        $response->assertSee('Trading Wallet Control');
        $response->assertSee('Trading Wallet');
        $response->assertSee('90 Days Lock');
        $response->assertSee('100.0% Max Withdr.');
        $response->assertSee('Lock Period (Days)');
        $response->assertSee('Maximum Withdrawal Allowed (%)');
        $response->assertSee('Matching Package Members (Staking Details + Member Details)');
    }

    /**
     * CHANGE 1: AJAX filter query sources packages/activations from staking_details.
     */
    public function test_ajax_filter_trading_members_sources_from_staking_details(): void
    {
        $staking = StakingDetail::with('member')->whereHas('member')->first();
        $this->assertNotNull($staking, 'A staking record must exist for testing');

        $member = $staking->member;
        $this->assertNotNull($member, 'Staking record must be linked to member');

        $fromDate = Carbon::parse($staking->created_at)->subDay()->format('Y-m-d');
        $toDate = Carbon::parse($staking->created_at)->addDay()->format('Y-m-d');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/admin/filter-trading-members', [
                'date_from' => $fromDate,
                'date_to' => $toDate,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $packages = collect($response->json('packages'));
        $matched = $packages->firstWhere('memberid', $member->memberid);
        $this->assertNotNull($matched, 'Matching staking entry must appear in results');

        // Sourced from staking_details
        $this->assertEquals((float) $staking->invest_amount, (float) $matched['package_value']);
        // Backend balance is p2p_wallet
        $this->assertEquals((float) $member->p2p_wallet, (float) $matched['trading_wallet']);
        $this->assertEquals((float) $member->p2p_wallet, (float) $matched['p2p_wallet']);
    }

    /**
     * CHANGE 4: Lock calculation must start from actual staking activation timestamp.
     * Formula: Activation Timestamp + Lock Days = Unlock Timestamp.
     * Must NOT use now() + lock_days for existing/historical records.
     */
    public function test_lock_calculation_starts_from_actual_staking_activation_date(): void
    {
        $testMemberId = 'TEST_STK_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $testMemberId,
            'name' => 'Historical Staking Member',
            'email' => $testMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Active',
            'p2p_wallet' => 500.00,
        ]);

        // Staking activated 30 days ago
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $staking = new StakingDetail;
        $staking->memberid = $testMemberId;
        $staking->invest_date = $thirtyDaysAgo->format('Y-m-d H:i:s');
        $staking->invest_amount = 50.00;
        $staking->package = '50';
        $staking->status = 'Active';
        $staking->created_at = $thirtyDaysAgo;
        $staking->updated_at = $thirtyDaysAgo;
        $staking->save();

        // Lock days is 90 days
        $lockDays = 90;
        $expectedUnlock = $thirtyDaysAgo->copy()->addDays($lockDays);

        // Staking model unlock timestamp must match activation + 90 days
        $this->assertNotNull($staking->locked_until);
        $this->assertEquals(
            $expectedUnlock->format('Y-m-d H:i:s'),
            $staking->locked_until->format('Y-m-d H:i:s')
        );

        // It is currently locked because 30 days elapsed < 90 days lock
        $this->assertTrue($staking->isLocked());
        $this->assertTrue($member->isTradingWalletLocked());

        // Remaining lock days should be ~60 days, definitely NOT 90 days!
        $this->assertLessThanOrEqual(61, $staking->remainingLockDays());
        $this->assertGreaterThanOrEqual(59, $staking->remainingLockDays());

        // Verify it did NOT use now() + 90 days
        $nowPlus90 = Carbon::now()->addDays(90);
        $this->assertNotEquals($nowPlus90->format('Y-m-d'), $staking->locked_until->format('Y-m-d'));

        // Clean up
        $staking->delete();
        $member->delete();
    }

    /**
     * CHANGE 4: Historical staking record that was activated > 90 days ago is already UNLOCKED.
     */
    public function test_historical_staking_record_past_lock_period_is_unlocked(): void
    {
        $testMemberId = 'TEST_OLD_STK_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $testMemberId,
            'name' => 'Old Staking Member',
            'email' => $testMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Active',
            'p2p_wallet' => 300.00,
        ]);

        // Staking activated 100 days ago
        $hundredDaysAgo = Carbon::now()->subDays(100);
        $staking = new StakingDetail;
        $staking->memberid = $testMemberId;
        $staking->invest_date = $hundredDaysAgo->format('Y-m-d H:i:s');
        $staking->invest_amount = 100.00;
        $staking->package = '100';
        $staking->status = 'Active';
        $staking->created_at = $hundredDaysAgo;
        $staking->updated_at = $hundredDaysAgo;
        $staking->save();

        // 100 days ago + 90 days lock = unlocked 10 days ago
        $this->assertFalse($staking->isLocked());
        $this->assertFalse($member->isTradingWalletLocked());
        $this->assertEquals(0, $staking->remainingLockDays());
        $this->assertEquals(0, $member->tradingWalletRemainingLockDays());

        // Post-lock withdrawal is allowed (100% of p2p_wallet)
        $this->assertEqualsWithDelta(300.00, $member->tradingWalletMaxWithdrawable(), 0.01);
        $error = null;
        $this->assertTrue($member->canWithdrawTradingWallet(150.00, $error));
        $this->assertTrue($member->canPurchasePackage($error));

        // Clean up
        $staking->delete();
        $member->delete();
    }

    /**
     * Admin can save Trading Wallet rules via applyTradingWalletControl.
     */
    public function test_admin_can_save_trading_wallet_rules(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/apply-trading-wallet-control', [
                'action' => 'save_current_setting',
                'lock_days' => 90,
                'withdrawal_percent' => 100.00,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('successMsg');

        $setting = TradingWalletSetting::getActiveSetting();
        $this->assertEquals(90, (int) $setting->lock_days);
        $this->assertEqualsWithDelta(100.00, (float) $setting->withdrawal_percent, 0.01);
    }

    /**
     * AJAX getTradingMember sources latest package data from staking_details.
     */
    public function test_get_trading_member_ajax_sources_from_staking_details(): void
    {
        $staking = StakingDetail::with('member')->whereHas('member')->first();
        $this->assertNotNull($staking);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/admin/get-trading-member', [
                'memberid' => $staking->memberid,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment([
            'memberid' => $staking->memberid,
            'trading_wallet' => (float) $staking->member->p2p_wallet,
        ]);
    }
}
