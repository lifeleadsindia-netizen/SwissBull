<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\TradingWalletSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TradingWalletTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_member_details_table_has_p2p_wallet_and_trading_wallet_column_is_removed(): void
    {
        // Requirement 1: Trading Wallet column removed from database completely
        $this->assertFalse(
            Schema::hasColumn('member_details', 'trading_wallet'),
            'Column trading_wallet must be completely removed from member_details table'
        );

        // Requirement 2: Existing P2P Wallet column remains in database with same name
        $this->assertTrue(
            Schema::hasColumn('member_details', 'p2p_wallet'),
            'Column p2p_wallet must exist in member_details table'
        );

        // Control columns remain in database
        $this->assertTrue(
            Schema::hasColumn('member_details', 'trading_wallet_lock_days'),
            'Column trading_wallet_lock_days does not exist on member_details table'
        );
        $this->assertTrue(
            Schema::hasColumn('member_details', 'trading_wallet_locked_until'),
            'Column trading_wallet_locked_until does not exist on member_details table'
        );
        $this->assertTrue(
            Schema::hasColumn('member_details', 'trading_wallet_withdrawal_percent'),
            'Column trading_wallet_withdrawal_percent does not exist on member_details table'
        );
    }

    public function test_trading_wallet_settings_table_exists_and_manages_default_lock_period(): void
    {
        $this->assertTrue(
            Schema::hasTable('trading_wallet_settings'),
            'Table trading_wallet_settings must exist'
        );

        $setting = TradingWalletSetting::getActiveSetting();
        $this->assertNotNull($setting);
        $this->assertIsInt($setting->lock_days);
        $this->assertGreaterThanOrEqual(0, $setting->lock_days);
    }

    public function test_trading_wallet_balance_is_sourced_from_p2p_wallet_column(): void
    {
        $member = MemberDetail::first();
        if ($member) {
            $originalP2P = $member->p2p_wallet;

            // Setting p2p_wallet reflects in trading_wallet accessor
            $member->p2p_wallet = 150.75;
            $member->save();

            $freshMember = MemberDetail::find($member->id);
            $this->assertEqualsWithDelta(150.75, (float) $freshMember->trading_wallet, 0.001);
            $this->assertEqualsWithDelta(150.75, (float) $freshMember->p2p_wallet, 0.001);

            // Mutator check: setting trading_wallet safely updates p2p_wallet without DB column error
            $freshMember->trading_wallet = 220.50;
            $freshMember->save();

            $reloadedMember = MemberDetail::find($member->id);
            $this->assertEqualsWithDelta(220.50, (float) $reloadedMember->p2p_wallet, 0.001);
            $this->assertEqualsWithDelta(220.50, (float) $reloadedMember->trading_wallet, 0.001);

            // Revert back
            $reloadedMember->p2p_wallet = $originalP2P;
            $reloadedMember->save();
        }
    }

    public function test_guest_cannot_access_trading_wallet_control_page(): void
    {
        $response = $this->get('/admin/trading-wallet-control');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_trading_wallet_control_page(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/trading-wallet-control');

        $response->assertStatus(200);
        $response->assertViewIs('admin.trading-wallet-control');
        // Frontend/Admin display continues showing "Trading Wallet"
        $response->assertSee('Trading Wallet Control');
        $response->assertSee('Trading Wallet');
        $response->assertSee('Package Date From');
        $response->assertSee('Package Date To');
        $response->assertSee('Lock Period (Days)');
        $response->assertSee('Maximum Withdrawal Allowed (%)');
        $response->assertSee('Check All');
        $response->assertSee('Apply to Selected Members');
        $response->assertSee('Save Current Setting (Today & New Entries)');
    }

    public function test_sidebar_contains_trading_wallet_control_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/trading-wallet-control');

        $response->assertStatus(200);
        $response->assertSee(route('admin.tradingWalletControl'));
    }

    public function test_ajax_filter_trading_members_fetches_p2p_wallet_as_trading_wallet(): void
    {
        $package = PackageDetail::with('member')->whereHas('member')->first();
        if ($package && $package->member) {
            $member = $package->member;
            $origP2P = $member->p2p_wallet;
            $member->p2p_wallet = 88.50;
            $member->save();

            $fromDate = Carbon::parse($package->created_at)->subDay()->format('Y-m-d');
            $toDate = Carbon::parse($package->created_at)->addDay()->format('Y-m-d');

            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->postJson('/admin/filter-trading-members', [
                    'date_from' => $fromDate,
                    'date_to' => $toDate,
                ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true,
            ]);
            $response->assertJsonFragment([
                'memberid' => $member->memberid,
                'trading_wallet' => 88.50,
            ]);

            // Revert
            $member->p2p_wallet = $origP2P;
            $member->save();
        }
    }

    public function test_task1_and_2_lock_period_restricts_trading_wallet_withdrawal(): void
    {
        $member = MemberDetail::first();
        if ($member) {
            $origLock = $member->trading_wallet_lock_days;
            $origUntil = $member->trading_wallet_locked_until;
            $origPercent = $member->trading_wallet_withdrawal_percent;
            $origWallet = $member->p2p_wallet;

            // Set $100 balance in p2p_wallet, locked for 30 days
            $member->p2p_wallet = 100.00;
            $member->trading_wallet_lock_days = 30;
            $member->trading_wallet_locked_until = now()->addDays(30);
            $member->trading_wallet_withdrawal_percent = 50.00;
            $member->save();

            // Task 2: Trading Wallet Withdrawal completely blocked during active lock
            $this->assertTrue($member->isTradingWalletLocked());
            $this->assertEquals(0.00, $member->tradingWalletMaxWithdrawable());

            $errorMessage = null;
            $canWithdraw = $member->canWithdrawTradingWallet(10.00, $errorMessage);
            $this->assertFalse($canWithdraw);
            $this->assertStringContainsString('locked', strtolower($errorMessage));

            // Test withdrawal validation endpoint
            $resLocked = $this->withSession(['MEMBER_ID' => $member->memberid])
                ->postJson('/member/tradingWalletValidate', [
                    'memberid' => $member->memberid,
                    'withAmount' => 20.00,
                ]);
            $resLocked->assertStatus(200);
            $resLocked->assertJson(['code' => 0]);

            // Restore
            $member->p2p_wallet = $origWallet;
            $member->trading_wallet_lock_days = $origLock;
            $member->trading_wallet_locked_until = $origUntil;
            $member->trading_wallet_withdrawal_percent = $origPercent;
            $member->save();
        }
    }

    public function test_task2_and_8_lock_period_restricts_next_package_purchase(): void
    {
        $member = MemberDetail::first();
        if ($member) {
            $origLock = $member->trading_wallet_lock_days;
            $origUntil = $member->trading_wallet_locked_until;
            $origStatus = $member->status;
            $origPkg = $member->package;
            $origP2P = $member->p2p_wallet;

            // Set member active, $1000 balance, but locked for 30 days
            $member->status = 'Active';
            $member->package = 0;
            $member->p2p_wallet = 1000.00;
            $member->trading_wallet_lock_days = 30;
            $member->trading_wallet_locked_until = now()->addDays(30);
            $member->save();

            // 1. Model check
            $lockError = null;
            $this->assertFalse($member->canPurchasePackage($lockError));
            $this->assertStringContainsString('Package purchase is locked', $lockError);

            // 2. Controller check: Attempt Staking investment during active lock period
            $resStaking = $this->withSession(['MEMBER_ID' => $member->memberid])
                ->post('/member/createInvestment', [
                    'memberid' => $member->memberid,
                    'amount' => 20,
                ]);
            $resStaking->assertStatus(302);
            $resStaking->assertSessionHas('failedMsg');

            // 3. Controller check: Attempt Partnership investment during active lock period
            $resPart = $this->withSession(['MEMBER_ID' => $member->memberid])
                ->post('/member/partCreateInvest', [
                    'memberid' => $member->memberid,
                    'amount' => 100,
                ]);
            $resPart->assertStatus(302);
            $resPart->assertSessionHas('failedMsg');

            // Revert
            $member->status = $origStatus;
            $member->package = $origPkg;
            $member->p2p_wallet = $origP2P;
            $member->trading_wallet_lock_days = $origLock;
            $member->trading_wallet_locked_until = $origUntil;
            $member->save();
        }
    }

    public function test_task8_expired_lock_period_allows_next_package_purchase_and_withdrawal(): void
    {
        $member = MemberDetail::first();
        if ($member) {
            $origLock = $member->trading_wallet_lock_days;
            $origUntil = $member->trading_wallet_locked_until;
            $origPercent = $member->trading_wallet_withdrawal_percent;
            $origWallet = $member->p2p_wallet;

            // Lock has expired yesterday
            $member->p2p_wallet = 100.00;
            $member->trading_wallet_lock_days = 30;
            $member->trading_wallet_locked_until = now()->subDay();
            $member->trading_wallet_withdrawal_percent = 50.00;
            $member->save();

            $this->assertFalse($member->isTradingWalletLocked());

            // 1. Next package purchase is permitted
            $pkgError = null;
            $this->assertTrue($member->canPurchasePackage($pkgError));

            // 2. Withdrawal is allowed up to max percentage (50%)
            $withError = null;
            $this->assertTrue($member->canWithdrawTradingWallet(50.00, $withError));
            $this->assertFalse($member->canWithdrawTradingWallet(51.00, $withError));

            // Revert
            $member->p2p_wallet = $origWallet;
            $member->trading_wallet_lock_days = $origLock;
            $member->trading_wallet_locked_until = $origUntil;
            $member->trading_wallet_withdrawal_percent = $origPercent;
            $member->save();
        }
    }

    public function test_task3_current_day_lock_period_changes_apply_to_today_activations(): void
    {
        $member = MemberDetail::first();
        if ($member) {
            $origLock = $member->trading_wallet_lock_days;
            $origUntil = $member->trading_wallet_locked_until;
            $origApplied = $member->trading_wallet_lock_applied_at;
            $origAct = $member->activated_at;

            // Simulate member activated today with 30 days lock
            $today = now();
            $member->activated_at = $today;
            $member->trading_wallet_lock_applied_at = $today;
            $member->trading_wallet_lock_days = 30;
            $member->trading_wallet_locked_until = $today->copy()->addDays(30);
            $member->save();

            // Admin changes default lock period to 50 days on the same day (Task 3)
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->post('/admin/apply-trading-wallet-control', [
                    'action' => 'save_current_setting',
                    'lock_days' => 50,
                    'withdrawal_percent' => 50.00,
                ]);

            $response->assertStatus(302);
            $response->assertSessionHas('successMsg');

            // Verify member's lock was updated to 50 days
            $freshMember = MemberDetail::find($member->id);
            $this->assertEquals(50, $freshMember->trading_wallet_lock_days);
            $this->assertEquals(
                $today->copy()->addDays(50)->format('Y-m-d'),
                $freshMember->trading_wallet_locked_until->format('Y-m-d')
            );

            // Revert
            $member->activated_at = $origAct;
            $member->trading_wallet_lock_applied_at = $origApplied;
            $member->trading_wallet_lock_days = $origLock;
            $member->trading_wallet_locked_until = $origUntil;
            $member->save();
        }
    }

    public function test_task4_and_5_future_or_older_entries_are_not_modified_when_setting_changes(): void
    {
        $members = MemberDetail::take(2)->get();
        if ($members->count() >= 2) {
            $olderMember = $members[0];
            $todayMember = $members[1];

            // Older member registered/activated 3 days ago with 20 days lock
            $threeDaysAgo = now()->subDays(3);
            $olderMember->activated_at = $threeDaysAgo;
            $olderMember->trading_wallet_lock_applied_at = $threeDaysAgo;
            $olderMember->trading_wallet_lock_days = 20;
            $olderMember->trading_wallet_locked_until = $threeDaysAgo->copy()->addDays(20);
            $olderMember->save();

            // Today member registered/activated today with 20 days lock
            $today = now();
            $todayMember->activated_at = $today;
            $todayMember->trading_wallet_lock_applied_at = $today;
            $todayMember->trading_wallet_lock_days = 20;
            $todayMember->trading_wallet_locked_until = $today->copy()->addDays(20);
            $todayMember->save();

            // Admin changes Lock Period to 40 days today
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->post('/admin/apply-trading-wallet-control', [
                    'action' => 'save_current_setting',
                    'lock_days' => 40,
                    'withdrawal_percent' => 100.00,
                ]);

            $response->assertStatus(302);

            // Older member must NOT be modified (continues with 20 days)
            $freshOlder = MemberDetail::find($olderMember->id);
            $this->assertEquals(20, $freshOlder->trading_wallet_lock_days);
            $this->assertEquals(
                $threeDaysAgo->copy()->addDays(20)->format('Y-m-d'),
                $freshOlder->trading_wallet_locked_until->format('Y-m-d')
            );

            // Today's member IS updated to 40 days
            $freshToday = MemberDetail::find($todayMember->id);
            $this->assertEquals(40, $freshToday->trading_wallet_lock_days);
        }
    }

    public function test_task6_admin_date_range_update_modifies_only_selected_historical_records(): void
    {
        $members = MemberDetail::take(2)->get();
        if ($members->count() >= 2) {
            $memberA = $members[0];
            $memberB = $members[1];

            $pastDate = now()->subDays(10);
            foreach ([$memberA, $memberB] as $m) {
                $m->created_at = $pastDate;
                $m->activated_at = $pastDate;
                $m->trading_wallet_lock_days = 15;
                $m->trading_wallet_locked_until = $pastDate->copy()->addDays(15);
                $m->save();
            }

            // Admin selects ONLY Member A from the date range to update to 45 days lock
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->post('/admin/apply-trading-wallet-control', [
                    'action' => 'apply_selected',
                    'date_from' => $pastDate->copy()->subDay()->format('Y-m-d'),
                    'date_to' => $pastDate->copy()->addDay()->format('Y-m-d'),
                    'selected_members' => [$memberA->memberid],
                    'lock_days' => 45,
                    'withdrawal_percent' => 50.00,
                ]);

            $response->assertStatus(302);
            $response->assertSessionHas('successMsg');

            // Member A must have 45 days
            $freshA = MemberDetail::find($memberA->id);
            $this->assertEquals(45, $freshA->trading_wallet_lock_days);
            $this->assertEqualsWithDelta(50.00, (float) $freshA->trading_wallet_withdrawal_percent, 0.01);

            // Member B was NOT selected, so Member B must still have 15 days
            $freshB = MemberDetail::find($memberB->id);
            $this->assertEquals(15, $freshB->trading_wallet_lock_days);
        }
    }

    public function test_registered_member_without_package_is_not_included_in_lock_period_list(): void
    {
        // Requirement 2: Only members who have actually purchased/activated a package should appear
        // Member registers -> No package -> Do not include in Lock Period list
        $testRegMemberId = 'TEST_REG_ONLY_'.rand(1000, 9999);
        $regOnlyMember = MemberDetail::create([
            'memberid' => $testRegMemberId,
            'name' => 'Registered Only Member',
            'email' => $testRegMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Temp',
            'p2p_wallet' => 500.00,
            'created_at' => now(),
        ]);

        // Query the filter endpoint covering today
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/admin/filter-trading-members', [
                'date_from' => now()->subDay()->format('Y-m-d'),
                'date_to' => now()->addDay()->format('Y-m-d'),
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert that the registered-only member without a package is NOT in the list
        $membersInResponse = collect($response->json('members'));
        $this->assertFalse(
            $membersInResponse->contains('memberid', $testRegMemberId),
            'Members without a package in package_details must NOT appear in the Lock Period list'
        );

        // Clean up
        $regOnlyMember->delete();
    }

    public function test_package_purchase_creates_package_details_entry_and_applies_lock_period(): void
    {
        // Requirements 1, 3, 6:
        // Member purchases package -> Entry created in Package Details -> Verified corresponding Member -> Configured lock applied
        $testMemberId = 'TEST_BUYER_'.rand(1000, 9999);
        $buyerMember = MemberDetail::create([
            'memberid' => $testMemberId,
            'name' => 'Package Buyer Member',
            'email' => $testMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Active',
            'p2p_wallet' => 500.00,
            'created_at' => now(),
        ]);

        // Configure lock setting
        $setting = TradingWalletSetting::getActiveSetting();
        $setting->lock_days = 30;
        $setting->withdrawal_percent = 40.00;
        $setting->save();

        // Create package entry
        $pkg = new PackageDetail;
        $pkg->memberid = $testMemberId;
        $pkg->package_type = 'Account Activation';
        $pkg->package_value = 30.00;
        $pkg->payment_mode = 'Fund Wallet';
        $pkg->txnid = 'A/'.date('YmdHis');
        $pkg->status = 'Accepted';
        $pkg->applyLock($setting->lock_days);
        $pkg->save();

        // Verify relationship
        $this->assertNotNull($pkg->member);
        $this->assertEquals($testMemberId, $pkg->member->memberid);

        // Apply lock to member
        $buyerMember->applyTradingWalletLock($setting->lock_days, $setting->withdrawal_percent);
        $buyerMember->save();

        // Assert package is locked
        $this->assertTrue($pkg->isLocked());
        $this->assertEquals(30, $pkg->lock_days);
        $this->assertNotNull($pkg->locked_until);

        // Assert member's applicable trading wallet is frozen
        $this->assertTrue($buyerMember->isTradingWalletLocked());
        $this->assertEquals(0.00, $buyerMember->tradingWalletMaxWithdrawable());

        // Verify that this member now APPEARS in the Lock Period list
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/admin/filter-trading-members', [
                'date_from' => now()->subDay()->format('Y-m-d'),
                'date_to' => now()->addDay()->format('Y-m-d'),
            ]);

        $response->assertStatus(200);
        $membersInResponse = collect($response->json('members'));
        $this->assertTrue(
            $membersInResponse->contains('memberid', $testMemberId),
            'Members who have a package in package_details MUST appear in the Lock Period list'
        );

        // Clean up
        $pkg->delete();
        $buyerMember->delete();
    }

    public function test_package_based_lock_freezes_trading_wallet_and_blocks_next_package(): void
    {
        // Requirements 4 & 5:
        // During active lock:
        // 1. Trading Wallet withdrawal is frozen (0% allowed)
        // 2. Next package purchase is blocked
        $testMemberId = 'TEST_FROZEN_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $testMemberId,
            'name' => 'Frozen Member',
            'email' => $testMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Active',
            'p2p_wallet' => 1000.00,
            'trading_wallet_lock_days' => 30,
            'trading_wallet_locked_until' => now()->addDays(30),
            'trading_wallet_withdrawal_percent' => 50.00,
            'created_at' => now(),
        ]);

        $pkg = PackageDetail::create([
            'memberid' => $testMemberId,
            'package_type' => 'Staking Package',
            'package_value' => 20.00,
            'status' => 'Accepted',
            'lock_days' => 30,
            'locked_until' => now()->addDays(30),
            'lock_applied_at' => now(),
        ]);

        // 1. Trading Wallet withdrawal must remain frozen (0% allowed)
        $this->assertEquals(0.00, $member->tradingWalletMaxWithdrawable());
        $withError = null;
        $this->assertFalse($member->canWithdrawTradingWallet(50.00, $withError));
        $this->assertStringContainsString('locked', strtolower($withError));

        // 2. Package purchase blocked during lock period
        $pkgError = null;
        $this->assertFalse($member->canPurchasePackage($pkgError));
        $this->assertStringContainsString('Package purchase is locked', $pkgError);

        // 3. Expiration: When lock period expires, withdrawal and package purchase are allowed
        $pkg->locked_until = now()->subDay();
        $pkg->save();
        $member->trading_wallet_locked_until = now()->subDay();
        $member->save();

        $freshMember = MemberDetail::find($member->id);
        $this->assertFalse($freshMember->isTradingWalletLocked());
        $this->assertTrue($freshMember->canPurchasePackage());
        $this->assertTrue($freshMember->canWithdrawTradingWallet(100.00));

        // Clean up
        $pkg->delete();
        $member->delete();
    }

    public function test_admin_can_select_package_entries_and_update_lock_period(): void
    {
        // Admin selects records from Package Details -> updates Lock Period for both package and member
        $testMemberId = 'TEST_ADMIN_SELECT_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $testMemberId,
            'name' => 'Select Test Member',
            'email' => $testMemberId.'@example.com',
            'mobile' => (string) rand(1000000000, 9999999999),
            'status' => 'Active',
            'p2p_wallet' => 800.00,
            'trading_wallet_lock_days' => 10,
            'trading_wallet_locked_until' => now()->addDays(10),
            'created_at' => now()->subDays(5),
        ]);

        $pkg = PackageDetail::create([
            'memberid' => $testMemberId,
            'package_type' => 'Account Activation',
            'package_value' => 30.00,
            'status' => 'Accepted',
            'lock_days' => 10,
            'locked_until' => now()->addDays(10),
            'lock_applied_at' => now()->subDays(5),
            'created_at' => now()->subDays(5),
        ]);

        // Admin selects this package entry and sets 60 days lock with 30% max withdrawal
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/apply-trading-wallet-control', [
                'action' => 'apply_selected',
                'selected_packages' => [$pkg->id],
                'lock_days' => 60,
                'withdrawal_percent' => 30.00,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('successMsg');

        // Check package record is updated
        $freshPkg = PackageDetail::find($pkg->id);
        $this->assertEquals(60, $freshPkg->lock_days);
        $this->assertNotNull($freshPkg->locked_until);

        // Check member trading wallet is updated
        $freshMember = MemberDetail::find($member->id);
        $this->assertEquals(60, $freshMember->trading_wallet_lock_days);
        $this->assertEqualsWithDelta(30.00, (float) $freshMember->trading_wallet_withdrawal_percent, 0.01);

        // Clean up
        $pkg->delete();
        $member->delete();
    }
}
