<?php

namespace Tests\Feature;

use App\Models\DirectIncome;
use App\Models\HeroOfTheMonthReward;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PartnershipIncome;
use App\Models\RoiLevelIncome;
use App\Models\StakingIncome;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class IncomeDashboardAndSidebarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_income_helpers_and_totals(): void
    {
        $memberId = 'TEST_INC_'.rand(1000, 9999);

        // Seed one paid entry for each stream
        StakingIncome::create([
            'memberid' => $memberId,
            'amount' => 100.00,
            'date' => Carbon::now()->toDateString(),
            'status' => 'Paid',
            'created_at' => Carbon::now(),
        ]);

        DirectIncome::create([
            'memberid' => $memberId,
            'name' => 'Downline User',
            'amount' => 50.00,
            'status' => 'Paid',
            'package' => 1000,
            'type' => 'Referral Bonus',
            'activatingid' => 'DOWNLINE1',
            'created_at' => Carbon::now(),
        ]);

        RoiLevelIncome::create([
            'memberid' => $memberId,
            'name' => 'Downline User',
            'level' => 1,
            'level_id' => 'DOWNLINE1',
            'amount' => 20.00,
            'status' => 'Paid',
            'created_at' => Carbon::now(),
        ]);

        LevelIncome::create([
            'memberid' => $memberId,
            'name' => 'Downline User',
            'amount' => 10.00,
            'status' => 'Paid',
            'level' => 1,
            'level_id' => 'DOWNLINE1',
            'created_at' => Carbon::now(),
        ]);

        HeroOfTheMonthReward::create([
            'month' => '2026-10',
            'memberid' => $memberId,
            'direct_business' => 5000,
            'total_pool' => 50000,
            'pool_percentage' => 2.0,
            'total_winners' => 1,
            'prize_amount' => 1000.00,
            'status' => 'Paid',
        ]);

        PartnershipIncome::create([
            'invest_id' => 1,
            'memberid' => $memberId,
            'rank' => 'Silver',
            'amount' => 30.00,
            'status' => 'Paid',
            'date' => Carbon::now()->toDateString(),
            'created_at' => Carbon::now(),
        ]);

        $this->assertEquals(100.00, (float) totalMemberRoiIncome($memberId));
        $this->assertEquals(50.00, (float) totalMemberDirectIncome($memberId));
        $this->assertEquals(20.00, (float) totalMemberStakingLevelIncome($memberId));
        $this->assertEquals(10.00, (float) totalMemberLevelIncome($memberId));
        $this->assertEquals(1000.00, (float) totalMemberHeroOfTheMonthIncome($memberId));
        $this->assertEquals(30.00, (float) totalMemberPartnershipIncome($memberId));

        $expectedTotal = 100.00 + 50.00 + 20.00 + 10.00 + 1000.00 + 30.00;
        $this->assertEquals($expectedTotal, (float) totalIncome($memberId));

        // Admin helpers
        $this->assertGreaterThanOrEqual(1000.00, (float) totalAdminHeroOfTheMonthIncome());
        $this->assertGreaterThanOrEqual(50.00, (float) totalAdminDirectIncome());
    }

    public function test_member_income_views_render_successfully(): void
    {
        $memberId = 'TEST_VIEW_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'View Test User',
            'email' => 'viewtest@example.com',
            'mobile' => '9876543210',
            'phonecode' => '91',
            'sponsorid' => 'Root',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 100,
            'p2p_wallet' => 50,
            'created_at' => Carbon::now(),
        ]);

        $session = ['MEMBER_ID' => $memberId];

        // 1. Monthly Trading Profit
        $res = $this->withSession($session)->get('member/income/monthly-trading-profit');
        $res->assertStatus(200);
        $res->assertSee('Monthly Trading Profit');

        // 2. Referral Bonus
        $res = $this->withSession($session)->get('member/income/referral-bonus');
        $res->assertStatus(200);
        $res->assertSee('Referral Bonus');

        // 3. Team Trading Profit
        $res = $this->withSession($session)->get('member/income/team-trading-profit');
        $res->assertStatus(200);
        $res->assertSee('Team Trading Profit');

        // 4. Daily Team Investment Share
        $res = $this->withSession($session)->get('member/income/daily-team-investment-share');
        $res->assertStatus(200);
        $res->assertSee('Daily Team Investment Share');

        // 5. Hero of the Month
        $res = $this->withSession($session)->get('member/income/hero-of-the-month');
        $res->assertStatus(200);
        $res->assertSee('Hero of the Month');

        // 6. Member Dashboard has new income titles & values and does not show Team Withdrawal Commission
        $dashRes = $this->withSession($session)->get('member/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Monthly Trading Profit');
        $dashRes->assertSee('Referral Bonus');
        $dashRes->assertSee('Team Trading Profit');
        $dashRes->assertSee('Daily Team Investment Share');
        $dashRes->assertSee('Hero of the Month');
        $dashRes->assertSee('Partnership Income');
        $dashRes->assertDontSee('Team Withdrawal Commission');
    }

    public function test_admin_income_views_render_successfully(): void
    {
        $adminSession = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        // 1. Monthly Trading Profit
        $res = $this->withSession($adminSession)->get('admin/income/monthly-trading-profit');
        $res->assertStatus(200);
        $res->assertSee('Monthly Trading Profit');

        // 2. Referral Bonus
        $res = $this->withSession($adminSession)->get('admin/income/referral-bonus');
        $res->assertStatus(200);
        $res->assertSee('Referral Bonus');

        // 3. Team Trading Profit
        $res = $this->withSession($adminSession)->get('admin/income/team-trading-profit');
        $res->assertStatus(200);
        $res->assertSee('Team Trading Profit');

        // 4. Daily Team Investment Share
        $res = $this->withSession($adminSession)->get('admin/income/daily-team-investment-share');
        $res->assertStatus(200);
        $res->assertSee('Daily Team Investment Share');

        // 5. Hero of the Month
        $res = $this->withSession($adminSession)->get('admin/income/hero-of-the-month');
        $res->assertStatus(200);
        $res->assertSee('Hero of the Month');

        // 6. Admin Dashboard and Sidebar
        $dashRes = $this->withSession($adminSession)->get('admin/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Monthly Trading Profit');
        $dashRes->assertSee('Referral Bonus');
        $dashRes->assertSee('Team Trading Profit');
        $dashRes->assertSee('Daily Team Investment Share');
        $dashRes->assertSee('Hero of the Month');
        $dashRes->assertDontSee('Team Withdrawal Commission');
    }
}
