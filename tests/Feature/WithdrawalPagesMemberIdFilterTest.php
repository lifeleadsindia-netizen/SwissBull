<?php

namespace Tests\Feature;

use App\Models\WithdrawalRequest;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WithdrawalPagesMemberIdFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        WithdrawalRequest::whereIn('request_id', [
            'TEST-NEW-1', 'TEST-NEW-2',
            'TEST-CAN-1', 'TEST-CAN-2',
            'TEST-PAID-1', 'TEST-PAID-2',
            'TEST-PEPE-1', 'TEST-PEPE-2',
            'TEST-TRD-1', 'TEST-TRD-2',
        ])->delete();
        parent::tearDown();
    }

    private function createTestWithdrawals(): void
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // New (Pending) Withdrawals
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-NEW-1',
            'memberid' => 'MW1111111',
            'status' => 'Pending',
            'type' => 'USDT',
            'request_date' => $today,
            'wallet_address' => '0x1111111111111111111111111111111111111111',
            'gross_amount' => 100.00,
            'service_charge' => 5.00,
            'net_amount' => 95.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-NEW-2',
            'memberid' => 'MW2222222',
            'status' => 'Pending',
            'type' => 'USDT',
            'request_date' => $yesterday,
            'wallet_address' => '0x2222222222222222222222222222222222222222',
            'gross_amount' => 200.00,
            'service_charge' => 10.00,
            'net_amount' => 190.00,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // Cancelled Withdrawals
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-CAN-1',
            'memberid' => 'MW1111111',
            'status' => 'Cancelled',
            'type' => 'USDT',
            'request_date' => $today,
            'wallet_address' => '0x1111111111111111111111111111111111111111',
            'gross_amount' => 50.00,
            'service_charge' => 2.50,
            'net_amount' => 47.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-CAN-2',
            'memberid' => 'MW2222222',
            'status' => 'Cancelled',
            'type' => 'USDT',
            'request_date' => $yesterday,
            'wallet_address' => '0x2222222222222222222222222222222222222222',
            'gross_amount' => 75.00,
            'service_charge' => 3.75,
            'net_amount' => 71.25,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // Paid (Approved) Withdrawals
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-PAID-1',
            'memberid' => 'MW1111111',
            'status' => 'Approved',
            'type' => 'USDT',
            'request_date' => $today,
            'payment_date' => $today,
            'wallet_address' => '0x1111111111111111111111111111111111111111',
            'gross_amount' => 300.00,
            'service_charge' => 15.00,
            'net_amount' => 285.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-PAID-2',
            'memberid' => 'MW2222222',
            'status' => 'Approved',
            'type' => 'USDT',
            'request_date' => $yesterday,
            'payment_date' => $yesterday,
            'wallet_address' => '0x2222222222222222222222222222222222222222',
            'gross_amount' => 400.00,
            'service_charge' => 20.00,
            'net_amount' => 380.00,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // PEPE Tokens Withdrawals (Pending)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-PEPE-1',
            'memberid' => 'MW1111111',
            'status' => 'Pending',
            'type' => 'Airdrop Withdrawal',
            'request_date' => $today,
            'wallet_address' => '0xpepe111111111111111111111111111111111111',
            'gross_amount' => 100000.00,
            'service_charge' => 0.00,
            'net_amount' => 100000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-PEPE-2',
            'memberid' => 'MW2222222',
            'status' => 'Pending',
            'type' => 'Airdrop Withdrawal',
            'request_date' => $yesterday,
            'wallet_address' => '0xpepe222222222222222222222222222222222222',
            'gross_amount' => 200000.00,
            'service_charge' => 0.00,
            'net_amount' => 200000.00,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // Trading Withdrawals (Pending)
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-TRD-1',
            'memberid' => 'MW1111111',
            'status' => 'Pending',
            'type' => 'Trading Withdrawal',
            'request_date' => $today,
            'wallet_address' => '0xtrd111111111111111111111111111111111111',
            'gross_amount' => 500.00,
            'service_charge' => 25.00,
            'net_amount' => 475.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('withdrawal_requests')->insert([
            'request_id' => 'TEST-TRD-2',
            'memberid' => 'MW2222222',
            'status' => 'Pending',
            'type' => 'Trading Withdrawal',
            'request_date' => $yesterday,
            'wallet_address' => '0xtrd222222222222222222222222222222222222',
            'gross_amount' => 600.00,
            'service_charge' => 30.00,
            'net_amount' => 570.00,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
    }

    public function test_all_three_pages_display_member_id_field(): void
    {
        $urls = [
            '/admin/new-withdrawal-request',
            '/admin/cancelled-request',
            '/admin/payment-history',
        ];

        foreach ($urls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $response->assertStatus(200);
            $response->assertSee('Member ID');
            $response->assertSee('name="member_id"', false);
            $response->assertSee('placeholder="Enter Member ID"', false);
        }
    }

    public function test_new_withdrawal_requests_filters_by_member_id(): void
    {
        $this->createTestWithdrawals();

        // 1. Filter by MW1111111
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?member_id=MW1111111');

        $response->assertStatus(200);
        $response->assertSee('TEST-NEW-1');
        $response->assertDontSee('TEST-NEW-2');
        $response->assertSee('Member: MW1111111');

        // 2. Filter by MW2222222
        $response2 = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?member_id=MW2222222');

        $response2->assertStatus(200);
        $response2->assertSee('TEST-NEW-2');
        $response2->assertDontSee('TEST-NEW-1');
    }

    public function test_cancelled_requests_filters_by_member_id(): void
    {
        $this->createTestWithdrawals();

        // 1. Filter by MW1111111
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/cancelled-request?member_id=MW1111111');

        $response->assertStatus(200);
        $response->assertSee('TEST-CAN-1');
        $response->assertDontSee('TEST-CAN-2');
        $response->assertSee('Member: MW1111111');

        // 2. Filter by MW2222222
        $response2 = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/cancelled-request?member_id=MW2222222');

        $response2->assertStatus(200);
        $response2->assertSee('TEST-CAN-2');
        $response2->assertDontSee('TEST-CAN-1');
    }

    public function test_payment_history_filters_by_member_id(): void
    {
        $this->createTestWithdrawals();

        // 1. Filter by MW1111111
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/payment-history?member_id=MW1111111');

        $response->assertStatus(200);
        $response->assertSee('TEST-PAID-1');
        $response->assertDontSee('TEST-PAID-2');
        $response->assertSee('Member: MW1111111');

        // 2. Filter by MW2222222
        $response2 = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/payment-history?member_id=MW2222222');

        $response2->assertStatus(200);
        $response2->assertSee('TEST-PAID-2');
        $response2->assertDontSee('TEST-PAID-1');
    }

    public function test_filters_combined_member_id_and_specific_date(): void
    {
        $this->createTestWithdrawals();
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // Member MW1111111 on today's date -> should match TEST-NEW-1
        $response1 = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'member_id' => 'MW1111111',
                'filter_date' => $today,
                'filter_mode' => 'single',
            ]));

        $response1->assertStatus(200);
        $response1->assertSee('TEST-NEW-1');
        $response1->assertDontSee('TEST-NEW-2');

        // Member MW1111111 on yesterday's date -> no matching records
        $response2 = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'member_id' => 'MW1111111',
                'filter_date' => $yesterday,
                'filter_mode' => 'single',
            ]));

        $response2->assertStatus(200);
        $response2->assertDontSee('TEST-NEW-1');
        $response2->assertDontSee('TEST-NEW-2');
    }

    public function test_filters_combined_member_id_and_advanced_date_presets(): void
    {
        $this->createTestWithdrawals();

        // Member MW1111111 + Preset "today"
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'member_id' => 'MW1111111',
                'filter_mode' => 'advanced',
                'preset' => 'today',
                'from_date' => now()->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertSee('TEST-NEW-1');
        $response->assertDontSee('TEST-NEW-2');
        $response->assertSee('Member: MW1111111');
        $response->assertSee('Today');
    }

    public function test_invalid_or_nonexistent_member_id_handled_normally(): void
    {
        $this->createTestWithdrawals();

        $urls = [
            '/admin/new-withdrawal-request?member_id=NONEXISTENT_99999',
            '/admin/cancelled-request?member_id=NONEXISTENT_99999',
            '/admin/payment-history?member_id=NONEXISTENT_99999',
        ];

        foreach ($urls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $response->assertStatus(200);
            $response->assertDontSee('TEST-NEW-1');
            $response->assertDontSee('TEST-CAN-1');
            $response->assertDontSee('TEST-PAID-1');
            $response->assertSee('Member: NONEXISTENT_99999');
        }
    }

    public function test_clear_filters_resets_member_id(): void
    {
        $this->createTestWithdrawals();

        // When visiting the clean URL (which the Reset / Clear Filters buttons link to)
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request');

        $response->assertStatus(200);
        $response->assertSee('TEST-NEW-1');
        $response->assertSee('TEST-NEW-2');
    }

    public function test_other_admin_pages_do_not_display_member_id_field(): void
    {
        $otherPages = [
            '/admin/set-packages',
            '/admin/trading-wallet-control',
            '/admin/promotion-banners',
        ];

        foreach ($otherPages as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $response->assertStatus(200);
            $response->assertDontSee('name="member_id"', false);
            $response->assertDontSee('placeholder="Enter Member ID"', false);
        }
    }

    public function test_filters_combined_member_id_and_date_type(): void
    {
        $this->createTestWithdrawals();

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'member_id' => 'MW1111111',
                'filter_mode' => 'advanced',
                'preset' => 'today',
                'from_date' => now()->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
                'date_field' => 'created_at',
            ]));

        $response->assertStatus(200);
        $response->assertSee('TEST-NEW-1');
        $response->assertDontSee('TEST-NEW-2');
        $response->assertSee('Member: MW1111111');
    }

    public function test_member_id_filters_pepe_and_trading_tabs(): void
    {
        $this->createTestWithdrawals();

        // New Withdrawal Requests: PEPE & Trading tabs
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?member_id=MW1111111');

        $response->assertStatus(200);
        $response->assertSee('TEST-PEPE-1');
        $response->assertDontSee('TEST-PEPE-2');
        $response->assertSee('TEST-TRD-1');
        $response->assertDontSee('TEST-TRD-2');
    }
}
