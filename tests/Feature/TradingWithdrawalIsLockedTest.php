<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\StakingDetail;
use Carbon\Carbon;
use Tests\TestCase;

class TradingWithdrawalIsLockedTest extends TestCase
{
    protected string $originalAdminStatus = 'on';

    protected function setUp(): void
    {
        parent::setUp();
        $setting = \DB::table('package_distributions')->where('id', 1)->first();
        if ($setting && isset($setting->status)) {
            $this->originalAdminStatus = $setting->status;
        }
    }

    protected function tearDown(): void
    {
        \DB::table('package_distributions')->where('id', 1)->update(['status' => $this->originalAdminStatus]);
        parent::tearDown();
    }

    public function test_staking_detail_has_is_locked_and_lock_methods(): void
    {
        $staking = new StakingDetail;
        $this->assertTrue(method_exists($staking, 'isLocked'), 'StakingDetail must implement isLocked()');
        $this->assertTrue(method_exists($staking, 'remainingLockDays'), 'StakingDetail must implement remainingLockDays()');
    }

    public function test_staking_detail_correctly_identifies_locked_status(): void
    {
        $staking = new StakingDetail;
        $staking->created_at = Carbon::now()->subDays(5);
        $staking->invest_date = Carbon::now()->subDays(5)->toDateTimeString();

        $this->assertTrue($staking->isLocked(), 'Staking created 5 days ago with default lock period should be locked');
        $this->assertGreaterThan(0, $staking->remainingLockDays(), 'Remaining lock days must be positive when locked');
        $this->assertNotNull($staking->locked_until, 'locked_until accessor must return a Carbon instance');
    }

    public function test_staking_detail_correctly_identifies_unlocked_status(): void
    {
        $staking = new StakingDetail;
        $staking->created_at = Carbon::now()->subDays(200);
        $staking->invest_date = Carbon::now()->subDays(200)->toDateTimeString();

        $this->assertFalse($staking->isLocked(), 'Staking created 200 days ago should be unlocked');
        $this->assertEquals(0, $staking->remainingLockDays(), 'Remaining lock days should be 0 when unlocked');
        $this->assertNotNull($staking->locked_until);
    }

    public function test_trading_withdrawal_page_renders_without_errors(): void
    {
        $member = MemberDetail::first();
        if (! $member) {
            $this->markTestSkipped('No MemberDetail found in database.');
        }

        $response = $this->withSession([
            'MEMBER_ID' => $member->memberid,
            'country' => $member->country ?? 'India',
        ])->get('/member/wallet/trading-withdrawal');

        $response->assertStatus(200);
        $response->assertViewIs('member.wallet.trading-withdrawal');
    }

    public function test_initiate_trading_withdrawal_blocks_locked_package_without_undefined_method_error(): void
    {
        \DB::table('package_distributions')->where('id', 1)->update(['status' => 'on']);

        $member = MemberDetail::first();
        if (! $member) {
            $this->markTestSkipped('No MemberDetail found in database.');
        }

        // Staking ID 1 is created recently and locked
        $staking = StakingDetail::find(1);
        if (! $staking) {
            $this->markTestSkipped('No StakingDetail with ID 1 found.');
        }

        $response = $this->withSession([
            'MEMBER_ID' => $member->memberid,
        ])->post(route('initiateTradingWithdrawal'), [
            'staking_id' => $staking->id,
            'amount' => 10,
            'memberid' => $member->memberid,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'code' => 0,
            'status' => 'error',
        ]);
        $data = $response->json();
        $this->assertStringContainsString('locked for', $data['message']);
    }

    public function test_eligible_unlocked_entry_can_proceed_through_withdrawal_flow(): void
    {
        \DB::beginTransaction();
        try {
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'on']);

            $testMemberId = 'TEST_WLK_'.rand(1000, 9999);
            $member = MemberDetail::create([
                'memberid' => $testMemberId,
                'name' => 'Unlocked Staking Member',
                'email' => $testMemberId.'@example.com',
                'mobile' => (string) rand(1000000000, 9999999999),
                'status' => 'Active',
                'p2p_wallet' => 200.00,
                'member_wallet' => '0x'.bin2hex(random_bytes(20)),
            ]);

            // Staking created 120 days ago (lock is 90/30 days, so it is unlocked)
            $pastDate = Carbon::now()->subDays(120);
            $staking = new StakingDetail;
            $staking->memberid = $testMemberId;
            $staking->invest_date = $pastDate->format('Y-m-d H:i:s');
            $staking->invest_amount = 200.00;
            $staking->trading_wallet_amount = 130.00;
            $staking->package = '200';
            $staking->status = 'Active';
            $staking->created_at = $pastDate;
            $staking->updated_at = $pastDate;
            $staking->save();

            $this->assertFalse($staking->isLocked(), 'Staking created 120 days ago must be unlocked');

            $withdrawAmount = 50.00;
            $response = $this->withSession([
                'MEMBER_ID' => $member->memberid,
            ])->post(route('initiateTradingWithdrawal'), [
                'staking_id' => $staking->id,
                'amount' => $withdrawAmount,
                'memberid' => $member->memberid,
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'code' => 1,
                'status' => 'success',
            ]);

            // Confirm staking status was deactivated
            $staking->refresh();
            $this->assertEquals('Deactive', $staking->status);

            // Confirm withdrawal request was created
            $this->assertDatabaseHas('withdrawal_requests', [
                'memberid' => $testMemberId,
                'type' => 'Trading Withdrawal',
                'gross_amount' => $withdrawAmount,
            ]);
        } finally {
            \DB::rollBack();
        }
    }

    public function test_case_1_lock_active_and_admin_on_button_disabled_and_backend_blocked(): void
    {
        \DB::beginTransaction();
        try {
            // Set Admin status ON
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'on']);

            $testMemberId = 'TEST_C1_'.rand(1000, 9999);
            $member = MemberDetail::create([
                'memberid' => $testMemberId,
                'name' => 'Case 1 Member',
                'email' => $testMemberId.'@example.com',
                'mobile' => (string) rand(1000000000, 9999999999),
                'status' => 'Active',
                'p2p_wallet' => 200.00,
                'member_wallet' => '0x'.bin2hex(random_bytes(20)),
            ]);

            // Staking created 5 days ago (Lock is active)
            $recentDate = Carbon::now()->subDays(5);
            $staking = new StakingDetail;
            $staking->memberid = $testMemberId;
            $staking->invest_date = $recentDate->format('Y-m-d H:i:s');
            $staking->invest_amount = 200.00;
            $staking->trading_wallet_amount = 130.00;
            $staking->package = '200';
            $staking->status = 'Active';
            $staking->created_at = $recentDate;
            $staking->updated_at = $recentDate;
            $staking->save();

            $this->assertTrue($staking->isLocked());

            // 1. Page view test: button must be disabled with lock active title
            $response = $this->withSession([
                'MEMBER_ID' => $member->memberid,
                'country' => 'India',
            ])->get('/member/wallet/trading-withdrawal');

            $response->assertStatus(200);
            $response->assertSee('Lock period active');
            $response->assertSee('btn btn-secondary disabled btn-withdraw', false);

            // 2. Direct backend POST must be blocked
            $post = $this->withSession([
                'MEMBER_ID' => $member->memberid,
            ])->post(route('initiateTradingWithdrawal'), [
                'staking_id' => $staking->id,
                'amount' => 50,
                'memberid' => $member->memberid,
            ]);

            $post->assertStatus(200);
            $post->assertJson(['code' => 0, 'status' => 'error']);
            $this->assertStringContainsString('locked for', $post->json('message'));

            // Verify no withdrawal request was created
            $this->assertDatabaseMissing('withdrawal_requests', [
                'memberid' => $testMemberId,
            ]);
        } finally {
            \DB::rollBack();
        }
    }

    public function test_case_2_lock_active_and_admin_off_button_disabled_and_backend_blocked(): void
    {
        \DB::beginTransaction();
        try {
            // Set Admin status OFF
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'off']);

            $testMemberId = 'TEST_C2_'.rand(1000, 9999);
            $member = MemberDetail::create([
                'memberid' => $testMemberId,
                'name' => 'Case 2 Member',
                'email' => $testMemberId.'@example.com',
                'mobile' => (string) rand(1000000000, 9999999999),
                'status' => 'Active',
                'p2p_wallet' => 200.00,
                'member_wallet' => '0x'.bin2hex(random_bytes(20)),
            ]);

            // Staking created 5 days ago (Lock is active)
            $recentDate = Carbon::now()->subDays(5);
            $staking = new StakingDetail;
            $staking->memberid = $testMemberId;
            $staking->invest_date = $recentDate->format('Y-m-d H:i:s');
            $staking->invest_amount = 200.00;
            $staking->trading_wallet_amount = 130.00;
            $staking->package = '200';
            $staking->status = 'Active';
            $staking->created_at = $recentDate;
            $staking->updated_at = $recentDate;
            $staking->save();

            // 1. Page view test: button disabled with Trading Wallet withdrawals are currently OFF
            $response = $this->withSession([
                'MEMBER_ID' => $member->memberid,
                'country' => 'India',
            ])->get('/member/wallet/trading-withdrawal');

            $response->assertStatus(200);
            $response->assertSee('Trading Wallet withdrawals are currently OFF');
            $response->assertSee('btn btn-secondary disabled btn-withdraw', false);

            // 2. Direct backend POST must be blocked by admin status
            $post = $this->withSession([
                'MEMBER_ID' => $member->memberid,
            ])->post(route('initiateTradingWithdrawal'), [
                'staking_id' => $staking->id,
                'amount' => 50,
                'memberid' => $member->memberid,
            ]);

            $post->assertStatus(200);
            $post->assertJson(['code' => 0, 'status' => 'error']);
            $this->assertStringContainsString('disabled by administration', $post->json('message'));

            $this->assertDatabaseMissing('withdrawal_requests', [
                'memberid' => $testMemberId,
            ]);
        } finally {
            \DB::rollBack();
        }
    }

    public function test_case_3_lock_expired_and_admin_off_button_disabled_and_backend_blocked(): void
    {
        \DB::beginTransaction();
        try {
            // Set Admin status OFF
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'off']);

            $testMemberId = 'TEST_C3_'.rand(1000, 9999);
            $member = MemberDetail::create([
                'memberid' => $testMemberId,
                'name' => 'Case 3 Member',
                'email' => $testMemberId.'@example.com',
                'mobile' => (string) rand(1000000000, 9999999999),
                'status' => 'Active',
                'p2p_wallet' => 200.00,
                'member_wallet' => '0x'.bin2hex(random_bytes(20)),
            ]);

            // Staking created 150 days ago (Lock is EXPIRED)
            $pastDate = Carbon::now()->subDays(150);
            $staking = new StakingDetail;
            $staking->memberid = $testMemberId;
            $staking->invest_date = $pastDate->format('Y-m-d H:i:s');
            $staking->invest_amount = 200.00;
            $staking->trading_wallet_amount = 130.00;
            $staking->package = '200';
            $staking->status = 'Active';
            $staking->created_at = $pastDate;
            $staking->updated_at = $pastDate;
            $staking->save();

            $this->assertFalse($staking->isLocked(), 'Staking should be expired');

            // 1. Page view test: button disabled because Admin status is OFF
            $response = $this->withSession([
                'MEMBER_ID' => $member->memberid,
                'country' => 'India',
            ])->get('/member/wallet/trading-withdrawal');

            $response->assertStatus(200);
            $response->assertSee('Trading Wallet withdrawals are currently OFF');
            $response->assertSee('btn btn-secondary disabled btn-withdraw', false);

            // 2. Direct backend POST must be blocked by admin status
            $post = $this->withSession([
                'MEMBER_ID' => $member->memberid,
            ])->post(route('initiateTradingWithdrawal'), [
                'staking_id' => $staking->id,
                'amount' => 50,
                'memberid' => $member->memberid,
            ]);

            $post->assertStatus(200);
            $post->assertJson(['code' => 0, 'status' => 'error']);
            $this->assertStringContainsString('disabled by administration', $post->json('message'));

            $this->assertDatabaseMissing('withdrawal_requests', [
                'memberid' => $testMemberId,
            ]);
        } finally {
            \DB::rollBack();
        }
    }

    public function test_case_4_lock_expired_and_admin_on_button_enabled_and_withdrawal_succeeds(): void
    {
        \DB::beginTransaction();
        try {
            // Set Admin status ON
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'on']);

            $testMemberId = 'TEST_C4_'.rand(1000, 9999);
            $member = MemberDetail::create([
                'memberid' => $testMemberId,
                'name' => 'Case 4 Member',
                'email' => $testMemberId.'@example.com',
                'mobile' => (string) rand(1000000000, 9999999999),
                'status' => 'Active',
                'p2p_wallet' => 200.00,
                'member_wallet' => '0x'.bin2hex(random_bytes(20)),
            ]);

            // Staking created 150 days ago (Lock is EXPIRED)
            $pastDate = Carbon::now()->subDays(150);
            $staking = new StakingDetail;
            $staking->memberid = $testMemberId;
            $staking->invest_date = $pastDate->format('Y-m-d H:i:s');
            $staking->invest_amount = 200.00;
            $staking->trading_wallet_amount = 130.00;
            $staking->package = '200';
            $staking->status = 'Active';
            $staking->created_at = $pastDate;
            $staking->updated_at = $pastDate;
            $staking->save();

            $this->assertFalse($staking->isLocked(), 'Staking should be expired');

            // 1. Page view test: button enabled as btn-primary
            $response = $this->withSession([
                'MEMBER_ID' => $member->memberid,
                'country' => 'India',
            ])->get('/member/wallet/trading-withdrawal');

            $response->assertStatus(200);
            $response->assertSee('btn btn-primary btn-withdraw', false);

            // 2. Direct backend POST succeeds
            $post = $this->withSession([
                'MEMBER_ID' => $member->memberid,
            ])->post(route('initiateTradingWithdrawal'), [
                'staking_id' => $staking->id,
                'amount' => 50,
                'memberid' => $member->memberid,
            ]);

            $post->assertStatus(200);
            $post->assertJson(['code' => 1, 'status' => 'success']);

            $this->assertDatabaseHas('withdrawal_requests', [
                'memberid' => $testMemberId,
                'type' => 'Trading Withdrawal',
                'gross_amount' => 50.00,
            ]);
        } finally {
            \DB::rollBack();
        }
    }

    public function test_trading_wallet_validate_endpoint_returns_admin_status(): void
    {
        \DB::beginTransaction();
        try {
            $member = MemberDetail::first();

            // When ON
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'on']);
            $resOn = $this->withSession(['MEMBER_ID' => $member->memberid])
                ->post(route('tradingWalletValidate'), [
                    'withAmount' => 0,
                ]);
            $resOn->assertStatus(200);
            $resOn->assertJson(['code' => 1, 'admin_status' => 'on']);

            // When OFF
            \DB::table('package_distributions')->where('id', 1)->update(['status' => 'off']);
            $resOff = $this->withSession(['MEMBER_ID' => $member->memberid])
                ->post(route('tradingWalletValidate'), [
                    'withAmount' => 0,
                ]);
            $resOff->assertStatus(200);
            $resOff->assertJson(['code' => 0, 'admin_status' => 'off']);
        } finally {
            \DB::rollBack();
        }
    }
}
