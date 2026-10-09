<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminMemberIdFilterPhase2Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        DB::table('member_details')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('package_details')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('import_funds')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('support_tickets')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('notifications')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('direct_incomes')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        DB::table('partnership_incomes')->whereIn('memberid', ['TEST_P2_USER1', 'TEST_P2_USER2'])->delete();
        parent::tearDown();
    }

    /**
     * All 20 active Phase 2 admin pages
     *
     * @return array<int, string>
     */
    private function getPhase2PageUrls(): array
    {
        return [
            // Member Management
            '/admin/member-details',
            '/admin/account-control',
            '/admin/wallet-address',
            '/admin/member-security',
            // Package Details
            '/admin/package-details',
            // Fund Management
            '/admin/funds/add-funds-details',
            '/admin/funds/import-fund-details',
            // Income & ROI
            '/admin/income/monthly-trading-profit',
            '/admin/income/roi-details',
            '/admin/income/referral-bonus',
            '/admin/income/team-trading-profit',
            '/admin/income/daily-team-investment-share',
            '/admin/income/hero-of-the-month',
            '/admin/income/partnership-incomes',
            '/admin/income/partnership-details',
            // Transaction
            '/admin/transaction',
            // Notification
            '/admin/notification',
            // Support
            '/admin/support/new-support-ticket',
            '/admin/support/open-support-ticket',
            '/admin/support/close-support-ticket',
        ];
    }

    public function test_all_phase_2_pages_display_member_id_filter_input(): void
    {
        $urls = $this->getPhase2PageUrls();

        foreach ($urls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $this->assertSame(200, $response->getStatusCode(), "Failed asserting status 200 on {$url}");
            $response->assertSee('name="member_id"', false);
        }
    }

    public function test_all_phase_2_pages_accept_member_id_query_parameter_without_error(): void
    {
        $urls = $this->getPhase2PageUrls();

        foreach ($urls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url.'?member_id=NONEXISTENT_99999');

            $this->assertSame(200, $response->getStatusCode(), "Failed query with member_id on {$url}");
        }
    }

    public function test_member_details_filters_by_member_id(): void
    {
        DB::table('member_details')->insert([
            'memberid' => 'TEST_P2_USER1',
            'name' => 'Alice Test',
            'email' => 'alice_test@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('password'),
            'sponsorid' => 'ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('member_details')->insert([
            'memberid' => 'TEST_P2_USER2',
            'name' => 'Bob Test',
            'email' => 'bob_test@example.com',
            'mobile' => '1234567891',
            'password' => bcrypt('password'),
            'sponsorid' => 'ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/member-details?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
        $response->assertDontSee('TEST_P2_USER2');
    }

    public function test_package_details_filters_by_member_id(): void
    {
        DB::table('package_details')->insert([
            'memberid' => 'TEST_P2_USER1',
            'package_type' => 'Tier 1 Test',
            'package_value' => 100,
            'invest_amount' => 100,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('package_details')->insert([
            'memberid' => 'TEST_P2_USER2',
            'package_type' => 'Tier 2 Test',
            'package_value' => 500,
            'invest_amount' => 500,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/package-details?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
        $response->assertDontSee('TEST_P2_USER2');
    }

    public function test_add_funds_details_filters_by_member_id(): void
    {
        DB::table('import_funds')->insert([
            'memberid' => 'TEST_P2_USER1',
            'amount' => 150,
            'type' => 'Add',
            'status' => 'Approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('import_funds')->insert([
            'memberid' => 'TEST_P2_USER2',
            'amount' => 250,
            'type' => 'Add',
            'status' => 'Approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/funds/add-funds-details?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
        $response->assertDontSee('TEST_P2_USER2');
    }

    public function test_support_tickets_filters_by_member_id(): void
    {
        DB::table('support_tickets')->insert([
            'ticket_id' => 'TICK_P2_01',
            'memberid' => 'TEST_P2_USER1',
            'subject' => 'Ticket 1 Test',
            'support_status' => 'New',
            'status' => 'Open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('support_tickets')->insert([
            'ticket_id' => 'TICK_P2_02',
            'memberid' => 'TEST_P2_USER2',
            'subject' => 'Ticket 2 Test',
            'support_status' => 'New',
            'status' => 'Open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/support/new-support-ticket?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TICK_P2_01');
        $response->assertDontSee('TICK_P2_02');
    }

    public function test_notifications_filters_by_member_id(): void
    {
        DB::table('notifications')->insert([
            'type' => 'System',
            'memberid' => 'TEST_P2_USER1',
            'title' => 'Notif Title 1',
            'message' => 'Notif Message 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('notifications')->insert([
            'type' => 'System',
            'memberid' => 'TEST_P2_USER2',
            'title' => 'Notif Title 2',
            'message' => 'Notif Message 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/notification?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
        $response->assertDontSee('TEST_P2_USER2');
    }

    public function test_direct_incomes_filters_by_member_id(): void
    {
        DB::table('direct_incomes')->insert([
            'memberid' => 'TEST_P2_USER1',
            'package' => '100',
            'amount' => 50,
            'activatingid' => 'TEST_P2_USER2',
            'name' => 'Alice Test',
            'status' => 'Paid',
            'type' => 'Direct',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('direct_incomes')->insert([
            'memberid' => 'TEST_P2_USER2',
            'package' => '100',
            'amount' => 60,
            'activatingid' => 'TEST_P2_USER1',
            'name' => 'Bob Test',
            'status' => 'Paid',
            'type' => 'Direct',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/referral-bonus?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
    }

    public function test_partnership_incomes_filters_by_member_id(): void
    {
        DB::table('partnership_incomes')->insert([
            'invest_id' => 99991,
            'date' => now()->format('Y-m-d'),
            'memberid' => 'TEST_P2_USER1',
            'total_investment' => 1000,
            'daily_team_biz' => 500,
            'rate' => 2,
            'amount' => 120,
            'installment' => 1,
            'rank' => 'Gold',
            'achieved' => 1000,
            'status' => 'Paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('partnership_incomes')->insert([
            'invest_id' => 99992,
            'date' => now()->format('Y-m-d'),
            'memberid' => 'TEST_P2_USER2',
            'total_investment' => 2000,
            'daily_team_biz' => 1000,
            'rate' => 2,
            'amount' => 220,
            'installment' => 1,
            'rank' => 'Gold',
            'achieved' => 2000,
            'status' => 'Paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/partnership-incomes?member_id=TEST_P2_USER1');

        $response->assertStatus(200);
        $response->assertSee('TEST_P2_USER1');
        $response->assertDontSee('TEST_P2_USER2');
    }
}
