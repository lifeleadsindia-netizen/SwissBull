<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class AdminAddTradingProfitStakingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_get_member_capping_returns_stakings_breakdown(): void
    {
        $member = MemberDetail::where('memberid', 'ST1234567')->first();
        if (! $member) {
            $this->markTestSkipped('Member ST1234567 does not exist in testing database.');
        }

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/hdgteyusjasget/trade/get-member-capping', [
                'memberid' => 'ST1234567',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'member' => ['memberid', 'name', 'trading_wallet', 'wallet'],
            'capping' => ['total_capping', 'remaining_capping', 'achieved_profit'],
            'stakings' => [
                '*' => [
                    'id',
                    'package',
                    'order_id',
                    'invest_amount',
                    'trading_wallet_amount',
                    'total_capping',
                    'paid_profit',
                    'remaining_capping',
                    'has_remaining',
                ],
            ],
            'stakings_count',
            'remaining_stakings_count',
        ]);

        $this->assertTrue($response->json('status'));
        $this->assertGreaterThan(0, $response->json('stakings_count'));
    }

    public function test_admin_view_contains_stakings_table_and_action_elements(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/trade/add-trading-profit');

        $response->assertStatus(200);
        $response->assertSee('id="memberStakingsSection"', false);
        $response->assertSee('id="tableMemberStakings"', false);
        $response->assertSee('id="selectedStakingBox"', false);
        $response->assertSee('id="staking_id"', false);
        $response->assertSee('Member Stakings & Remaining Capping Breakdown');
        $response->assertSee('Autofill Capping');
    }

    public function test_admin_can_disburse_profit_for_specific_staking(): void
    {
        $testMember = MemberDetail::create([
            'memberid' => 'ST_TEST_999',
            'name' => 'Test User',
            'email' => 'test_st999@example.com',
            'mobile' => '9999999999',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'trading_wallet' => 70.00,
            'wallet' => 0.00,
            'package' => 100.00,
        ]);

        $testStaking = StakingDetail::create([
            'memberid' => 'ST_TEST_999',
            'package' => 'Package1',
            'invest_amount' => 100.00,
            'invest_date' => now()->toDateString(),
            'trading_wallet_amount' => 70.00,
            'trade_percentage' => 70.00,
            'status' => 'Active',
            'order_id' => 'OD_TEST_999',
            'installments' => 1,
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/hdgteyusjasget/addTradingProfit', [
                'memberid' => 'ST_TEST_999',
                'amount' => 10.00,
                'staking_id' => $testStaking->id,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('successMsg');

        // Check StakingIncome record created with staking_id
        $income = StakingIncome::where([
            ['memberid', 'ST_TEST_999'],
            ['staking_id', $testStaking->id],
            ['added_by', 'Admin'],
            ['amount', 10.00],
        ])->first();

        $this->assertNotNull($income);
        $this->assertEquals($testStaking->id, $income->staking_id);

        // Clean up
        $income->delete();
        $testStaking->delete();
        $testMember->delete();
    }

    public function test_staking_is_deactivated_when_remaining_capping_is_fully_exhausted(): void
    {
        $member = MemberDetail::where('memberid', 'ST1234567')->first();
        if (! $member) {
            $this->markTestSkipped('Member ST1234567 does not exist.');
        }

        // Fetching member capping should automatically detect remaining <= 0 and deactivate all exhausted stakings
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/hdgteyusjasget/trade/get-member-capping', [
                'memberid' => 'ST1234567',
            ]);

        $response->assertStatus(200);

        $stakings = $response->json('stakings');
        $target = collect($stakings)->firstWhere('id', 1);

        $this->assertNotNull($target);
        $this->assertEquals(0.00, $target['remaining_capping']);
        $this->assertEquals('Deactive', $target['status']);
        $this->assertFalse($target['has_remaining']);

        // Verify trying to disburse to this deactivated staking is rejected
        $disburseResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/hdgteyusjasget/addTradingProfit', [
                'memberid' => 'ST1234567',
                'amount' => 10.00,
                'staking_id' => 1,
            ]);

        $disburseResponse->assertStatus(302);
        $disburseResponse->assertSessionHas('failedMsg');
    }

    public function test_all_stakings_deactivate_when_member_overall_capping_is_fully_exhausted(): void
    {
        $testMember = MemberDetail::create([
            'memberid' => 'ST_TEST_TOTAL',
            'name' => 'Total Cap Test User',
            'email' => 'test_total@example.com',
            'mobile' => '9999999991',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'trading_wallet' => 100.00,
            'wallet' => 0.00,
            'package' => 100.00,
        ]);

        $stk1 = StakingDetail::create([
            'memberid' => 'ST_TEST_TOTAL',
            'package' => 'Package1',
            'invest_amount' => 100.00,
            'invest_date' => now()->toDateString(),
            'trading_wallet_amount' => 70.00,
            'trade_percentage' => 70.00,
            'status' => 'Active',
            'order_id' => 'OD_TEST_T1',
            'installments' => 1,
        ]);

        $stk2 = StakingDetail::create([
            'memberid' => 'ST_TEST_TOTAL',
            'package' => 'Package1',
            'invest_amount' => 50.00,
            'invest_date' => now()->toDateString(),
            'trading_wallet_amount' => 30.00,
            'trade_percentage' => 70.00,
            'status' => 'Active',
            'order_id' => 'OD_TEST_T2',
            'installments' => 1,
        ]);

        // Disburse full 500.00 (100 * 500%) to member overall (staking_id = 0)
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/hdgteyusjasget/addTradingProfit', [
                'memberid' => 'ST_TEST_TOTAL',
                'amount' => 500.00,
                'staking_id' => 0,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('successMsg');

        // Both stakings must now be Deactive
        $stk1->refresh();
        $stk2->refresh();
        $this->assertEquals('Deactive', $stk1->status);
        $this->assertEquals('Deactive', $stk2->status);

        // Fetch member capping should show remaining 0 and both stakings Deactive
        $capResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->postJson('/hdgteyusjasget/trade/get-member-capping', [
                'memberid' => 'ST_TEST_TOTAL',
            ]);

        $capResponse->assertStatus(200);
        $this->assertEquals(0.00, $capResponse->json('capping.remaining_capping'));
        $this->assertEquals(0, $capResponse->json('remaining_stakings_count'));

        foreach ($capResponse->json('stakings') as $stkJson) {
            $this->assertEquals('Deactive', $stkJson['status']);
            $this->assertFalse($stkJson['has_remaining']);
        }

        // Clean up
        StakingIncome::where('memberid', 'ST_TEST_TOTAL')->delete();
        $stk1->delete();
        $stk2->delete();
        $testMember->delete();
    }
}

