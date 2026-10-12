<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardWithdrawalOverviewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function test_helpers_correctly_calculate_trading_and_income_withdrawals(): void
    {
        $memberId = 'ST9988776';

        DB::table('member_details')->insert([
            'memberid' => $memberId,
            'name' => 'Test Member',
            'mobile' => '9876543210',
            'email' => 'test@example.com',
            'status' => 'Active',
            'wallet' => 1000,
            'trading_wallet' => 500,
            'pepe_wallet' => 200,
            'country' => 'India',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. Regular Income Withdrawal (Approved, today)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'REQ-INC-1',
            'memberid' => $memberId,
            'gross_amount' => 120.00,
            'service_charge' => 6.00,
            'net_amount' => 114.00,
            'type' => 'USDT',
            'status' => 'Approved',
            'request_date' => now()->toDateString(),
            'payment_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Regular Income Withdrawal (Approved, yesterday)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'REQ-INC-2',
            'memberid' => $memberId,
            'gross_amount' => 80.00,
            'service_charge' => 4.00,
            'net_amount' => 76.00,
            'type' => 'Wallet',
            'status' => 'Approved',
            'request_date' => now()->subDay()->toDateString(),
            'payment_date' => now()->subDay()->toDateString(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // 3. Trading Withdrawal (Approved, today)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'REQ-TRD-1',
            'memberid' => $memberId,
            'gross_amount' => 300.00,
            'service_charge' => 15.00,
            'net_amount' => 285.00,
            'type' => 'Trading Withdrawal',
            'status' => 'Approved',
            'request_date' => now()->toDateString(),
            'payment_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Trading Withdrawal (Approved, yesterday)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'REQ-TRD-2',
            'memberid' => $memberId,
            'gross_amount' => 200.00,
            'service_charge' => 10.00,
            'net_amount' => 190.00,
            'type' => 'Trading Withdrawal',
            'status' => 'Approved',
            'request_date' => now()->subDay()->toDateString(),
            'payment_date' => now()->subDay()->toDateString(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // 5. PEPE Token Withdrawal (Airdrop, should NOT be counted in USD withdrawals)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'REQ-PEPE-1',
            'memberid' => $memberId,
            'gross_amount' => 50000.00,
            'service_charge' => 0.00,
            'net_amount' => 50000.00,
            'type' => 'Airdrop Withdrawal',
            'status' => 'Approved',
            'request_date' => now()->toDateString(),
            'payment_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Member Helpers Verification
        $this->assertEquals(120.00, (float) todayWithdrawals($memberId));
        $this->assertEquals(200.00, (float) totalWithdrawals($memberId));
        $this->assertEquals(300.00, (float) todayTradingWithdrawals($memberId));
        $this->assertEquals(500.00, (float) totalTradingWithdrawals($memberId));

        // Admin Helpers Verification
        $this->assertEquals(120.00, (float) todaysWithdrawal());
        $this->assertEquals(200.00, (float) totalWithdrawal());
        $this->assertEquals(300.00, (float) todayTradingWithdrawal());
        $this->assertEquals(500.00, (float) totalTradingWithdrawal());
    }

    public function test_admin_dashboard_renders_withdrawal_overview_and_trading_metrics(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Platform Token &amp; Withdrawal Live Metrics', false);
        $response->assertSee('Withdrawal Overview');
        $response->assertSee("Today's Withdrawal", false);
        $response->assertSee('Total Withdrawal');
        $response->assertSee('Today Trading Withdrawal');
        $response->assertSee('Total Trading Withdrawal');
        $response->assertSee('Today PEPE Withdrawal');
        $response->assertSee('Total PEPE Withdrawal');
        $response->assertSee('col-xl-6 col-lg-6 col-md-6 col-12 mb-3');
    }

    public function test_member_dashboard_renders_withdrawal_overview_and_trading_cards(): void
    {
        $memberId = 'ST1234567';

        DB::table('member_details')->insert([
            'memberid' => $memberId,
            'name' => 'Test Member',
            'mobile' => '9876543210',
            'email' => 'test@example.com',
            'status' => 'Active',
            'wallet' => 1000,
            'trading_wallet' => 500,
            'pepe_wallet' => 200,
            'country' => 'India',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberId, 'country' => 'India'])
            ->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Withdrawal Overview');
        $response->assertSee("Today's Withdrawal", false);
        $response->assertSee('Total withdrawal');
        $response->assertSee('Today Trading Withdrawal');
        $response->assertSee('Total Trading Withdrawal');
        $response->assertSee('Trading Wallet');
        $response->assertSee('500.00');
    }
}
