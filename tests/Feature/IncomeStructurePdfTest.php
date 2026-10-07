<?php

namespace Tests\Feature;

use App\Models\HeroOfTheMonthReward;
use App\Models\MemberDetail;
use App\Models\MonthlyTradingProfitConfiction;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\RoiLevelIncome;
use App\Models\StakingDetail;
use App\Services\HeroOfTheMonthService;
use App\Services\StakingRoiService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class IncomeStructurePdfTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        MonthlyTradingProfitConfiction::truncate();
    }

    /**
     * 1. Monthly Trading Profit:
     * $50–500 => 5%, $600–5,000 => 7%, $6,000+ => 10%.
     * Dynamic rates from MonthlyTradingProfitConfiction and dynamic capping.
     */
    public function test_monthly_trading_profit_rates_and_dynamic_configuration(): void
    {
        // A. Verify default package plans
        $plan1 = PackagePlan::where('package_range', '50-500')->first();
        $plan2 = PackagePlan::where('package_range', '600-5000')->first();
        $plan3 = PackagePlan::where('package_range', '6000+')->first();

        $this->assertEquals(5.00, (float) $plan1->return_percent);
        $this->assertEquals(7.00, (float) $plan2->return_percent);
        $this->assertEquals(10.00, (float) $plan3->return_percent);

        // B. StakingDetail dynamic rate without admin override
        $stk1 = new StakingDetail(['invest_amount' => 100, 'package' => '50-500']);
        $this->assertEquals(5.00, $stk1->getDailyRate());

        $stk2 = new StakingDetail(['invest_amount' => 1000, 'package' => '600-5000']);
        $this->assertEquals(7.00, $stk2->getDailyRate());

        $stk3 = new StakingDetail(['invest_amount' => 10000, 'package' => '6000+']);
        $this->assertEquals(10.00, $stk3->getDailyRate());

        // C. Override with MonthlyTradingProfitConfiction
        MonthlyTradingProfitConfiction::updateOrCreate(
            ['package_id' => $plan2->id],
            ['rate' => 7.50, 'capping_percent' => 250.00]
        );

        $this->assertEquals(7.50, $stk2->getDailyRate());
        $this->assertEquals(250.00, $stk2->getCappingPercent());

        // Cleanup
        MonthlyTradingProfitConfiction::where('package_id', $plan2->id)->delete();
    }

    /**
     * 2. Referral Bonus:
     * L1 = 5%, L2 = 3%, L3 = 2% (Total 10%).
     * Triggered on account activation and investment.
     */
    public function test_referral_bonus_three_levels_distribution(): void
    {
        // Hierarchy: Sponsor3 -> Sponsor2 -> Sponsor1 -> NewMember
        $s3Id = 'REF_S3_'.rand(1000, 9999);
        $s2Id = 'REF_S2_'.rand(1000, 9999);
        $s1Id = 'REF_S1_'.rand(1000, 9999);
        $memId = 'REF_MEM_'.rand(1000, 9999);

        MemberDetail::create([
            'memberid' => $s3Id,
            'sponsorid' => 'Root',
            'name' => 'Sponsor 3',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 's3'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $s2Id,
            'sponsorid' => $s3Id,
            'name' => 'Sponsor 2',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 's2'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $s1Id,
            'sponsorid' => $s2Id,
            'name' => 'Sponsor 1',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 's1'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $memId,
            'sponsorid' => $s1Id,
            'name' => 'New User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'new'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Temp',
            'p2p_wallet' => 500.00,
            'wallet' => 0.00,
        ]);

        // Direct activation for 100 USDT
        directIncome($s1Id, $memId, 'New User', 100.00, 'Referral Bonus');

        // Check L1: 5% of 100 = 5 USDT
        $s1 = MemberDetail::where('memberid', $s1Id)->first();
        $this->assertEquals(5.00, (float) $s1->wallet);
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $s1Id,
            'activatingid' => $memId,
            'amount' => 5.00,
            'type' => 'Referral Bonus',
        ]);

        // Check L2: 3% of 100 = 3 USDT
        $s2 = MemberDetail::where('memberid', $s2Id)->first();
        $this->assertEquals(3.00, (float) $s2->wallet);
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $s2Id,
            'activatingid' => $memId,
            'amount' => 3.00,
            'type' => 'Referral Bonus',
        ]);

        // Check L3: 2% of 100 = 2 USDT
        $s3 = MemberDetail::where('memberid', $s3Id)->first();
        $this->assertEquals(2.00, (float) $s3->wallet);
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $s3Id,
            'activatingid' => $memId,
            'amount' => 2.00,
            'type' => 'Referral Bonus',
        ]);
    }

    /**
     * 3. Team Trading Profit:
     * L1–L10: 5%, 5%, 4%, 4%, 3%, 3%, 2%, 2%, 1%, 1%
     * Daily trading profit par calculate hoga, minimum 4-decimal precision ke saath.
     */
    public function test_team_trading_profit_ten_levels_with_four_decimals(): void
    {
        // Build a 10-level upline chain
        $sponsors = [];
        $previousId = 'Root';

        for ($lvl = 10; $lvl >= 1; $lvl--) {
            $id = 'TTP_SP_'.$lvl.'_'.rand(100, 999);
            MemberDetail::create([
                'memberid' => $id,
                'sponsorid' => $previousId,
                'name' => "Sponsor Level {$lvl}",
                'mobile' => '98'.rand(10000000, 99999999),
                'email' => "ttp{$lvl}".rand(100, 999).'@test.com',
                'password' => bcrypt('password'),
                'status' => 'Active',
                'wallet' => 0.00,
            ]);
            $sponsors[$lvl] = $id;
            $previousId = $id;
        }

        // Investor at bottom (Level 1 sponsor is $sponsors[1])
        $investorId = 'TTP_INV_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $investorId,
            'sponsorid' => $sponsors[1],
            'name' => 'Daily ROI Investor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'ttpinv'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        $service = new StakingRoiService;
        $dailyRoi = 10.5555; // Daily trading profit with decimals
        $dateStr = now()->toDateString();

        $service->distributeTeamTradingProfit($investorId, 'Daily ROI Investor', $dailyRoi, $dateStr);

        // Expected rates: L1: 5%, L2: 5%, L3: 4%, L4: 4%, L5: 3%, L6: 3%, L7: 2%, L8: 2%, L9: 1%, L10: 1%
        $expectedPercents = [
            1 => 5.00,
            2 => 5.00,
            3 => 4.00,
            4 => 4.00,
            5 => 3.00,
            6 => 3.00,
            7 => 2.00,
            8 => 2.00,
            9 => 1.00,
            10 => 1.00,
        ];

        foreach ($expectedPercents as $lvl => $pct) {
            $expectedAmount = round($dailyRoi * ($pct / 100), 4);
            $sponsorMember = MemberDetail::where('memberid', $sponsors[$lvl])->first();

            // Member wallet stores up to 2 decimal places in MySQL
            $this->assertEqualsWithDelta(round($expectedAmount, 2), (float) $sponsorMember->wallet, 0.01);

            // RoiLevelIncome ledger table stores full 4-decimal precision
            $this->assertDatabaseHas('roi_level_incomes', [
                'memberid' => $sponsors[$lvl],
                'level' => $lvl,
                'level_id' => $investorId,
                'type' => 'Team Trading Profit',
                'amount' => $expectedAmount,
                'status' => 'Paid',
            ]);
        }
    }

    /**
     * 4. Daily Team Investment Share:
     * L1–L10 = 1% each.
     * Qualification: L1 requires 4 direct referrals; thereafter +2 direct referrals per level.
     */
    public function test_daily_team_investment_share_qualifications_and_flush(): void
    {
        // Qualified Sponsor (has 4 direct referrals for Level 1)
        $sp1Id = 'DIS_SP1_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $sp1Id,
            'sponsorid' => 'Root',
            'name' => 'Qualified Sponsor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'dis_sp1_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'downline' => 4, // Exactly 4 directs (qualifies for L1)
            'wallet' => 0.00,
        ]);

        // Unqualified Sponsor (has only 3 direct referrals for Level 2 where 6 is required)
        $sp2Id = 'DIS_SP2_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $sp2Id,
            'sponsorid' => 'Root',
            'name' => 'Unqualified Sponsor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'dis_sp2_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'downline' => 3, // Needs 6 directs for L2 -> will be Flushed
            'wallet' => 0.00,
        ]);

        // Link sp1's sponsor to sp2
        MemberDetail::where('memberid', $sp1Id)->update(['sponsorid' => $sp2Id]);

        $investorId = 'DIS_INV_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $investorId,
            'sponsorid' => $sp1Id,
            'name' => 'New Package User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'dis_inv_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        // Distribute on 1000 USDT investment
        levelIncome($sp1Id, $investorId, 'New Package User', 1000.00, 'Daily Team Investment Share');

        // sp1 is Level 1: requires 4 directs, has 4 directs => PAID (1% of 1000 = 10 USDT)
        $sp1 = MemberDetail::where('memberid', $sp1Id)->first();
        $this->assertEquals(10.00, (float) $sp1->wallet);
        $this->assertDatabaseHas('level_incomes', [
            'memberid' => $sp1Id,
            'level' => 1,
            'level_id' => $investorId,
            'rate' => 1.00,
            'amount' => 10.00,
            'status' => 'Paid',
        ]);

        // sp2 is Level 2: requires 6 directs, has 3 directs => FLUSHED (wallet remains 0)
        $sp2 = MemberDetail::where('memberid', $sp2Id)->first();
        $this->assertEquals(0.00, (float) $sp2->wallet);
        $this->assertDatabaseHas('level_incomes', [
            'memberid' => $sp2Id,
            'level' => 2,
            'level_id' => $investorId,
            'rate' => 1.00,
            'amount' => 10.00,
            'status' => 'Flushed',
        ]);
    }

    /**
     * 5. Hero of the Month:
     * 2% pool of all investments in month.
     * Highest Level-1 direct referral volume wins.
     * Equal distribution in case of tie.
     */
    public function test_hero_of_the_month_distribution_with_tie_split(): void
    {
        $testMonth = '2026-08';
        HeroOfTheMonthReward::where('month', $testMonth)->delete();
        StakingDetail::whereBetween('created_at', ['2026-08-01 00:00:00', '2026-08-31 23:59:59'])->delete();

        // Ensure 2% pool
        PackageDistribution::updateOrCreate(['id' => 1], ['hero_of_the_month' => 2.00]);

        // Two top sponsors with equal direct referral business (Tie scenario)
        $winner1Id = 'HERO_W1_'.rand(1000, 9999);
        $winner2Id = 'HERO_W2_'.rand(1000, 9999);
        $otherSpId = 'HERO_OTH_'.rand(1000, 9999);

        MemberDetail::create([
            'memberid' => $winner1Id,
            'sponsorid' => 'Root',
            'name' => 'Winner One',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'hero1_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $winner2Id,
            'sponsorid' => 'Root',
            'name' => 'Winner Two',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'hero2_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $otherSpId,
            'sponsorid' => 'Root',
            'name' => 'Other Sponsor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'hero_oth_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        // Direct referrals for Winner 1 ($5,000 direct business)
        $refW1 = 'HERO_REF1_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $refW1,
            'sponsorid' => $winner1Id,
            'name' => 'Direct Ref 1',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'ref1_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
        ]);
        StakingDetail::create([
            'memberid' => $refW1,
            'invest_amount' => 5000.00,
            'package' => '600-5000',
            'status' => 'Active',
            'created_at' => Carbon::parse("{$testMonth}-10 12:00:00"),
        ]);

        // Direct referrals for Winner 2 ($5,000 direct business -> TIE!)
        $refW2 = 'HERO_REF2_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $refW2,
            'sponsorid' => $winner2Id,
            'name' => 'Direct Ref 2',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'ref2_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
        ]);
        StakingDetail::create([
            'memberid' => $refW2,
            'invest_amount' => 5000.00,
            'package' => '600-5000',
            'status' => 'Active',
            'created_at' => Carbon::parse("{$testMonth}-12 12:00:00"),
        ]);

        // Direct referrals for Other Sponsor ($2,000 direct business -> Not top)
        $refOth = 'HERO_REFO_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $refOth,
            'sponsorid' => $otherSpId,
            'name' => 'Direct Ref Other',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'refo_'.rand(100, 999).'@test.com',
            'password' => bcrypt('password'),
            'status' => 'Active',
        ]);
        StakingDetail::create([
            'memberid' => $refOth,
            'invest_amount' => 2000.00,
            'package' => '600-5000',
            'status' => 'Active',
            'created_at' => Carbon::parse("{$testMonth}-15 12:00:00"),
        ]);

        // Total investment volume = 5000 + 5000 + 2000 = 12,000 USDT
        // Total 2% pool = 240 USDT
        // 2 winners in tie => 120 USDT each!

        $heroService = new HeroOfTheMonthService;
        $result = $heroService->processMonthlyDistribution($testMonth);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(12000.00, (float) $result['total_investment']);
        $this->assertEquals(240.00, (float) $result['total_pool']);
        $this->assertEquals(2, $result['winner_count']);
        $this->assertEquals(120.00, (float) $result['prize_per_winner']);

        // Check wallet balances
        $w1 = MemberDetail::where('memberid', $winner1Id)->first();
        $this->assertEquals(120.00, (float) $w1->wallet);

        $w2 = MemberDetail::where('memberid', $winner2Id)->first();
        $this->assertEquals(120.00, (float) $w2->wallet);

        $oth = MemberDetail::where('memberid', $otherSpId)->first();
        $this->assertEquals(0.00, (float) $oth->wallet);

        // Check command executes and reports idempotent behavior
        $exitCode = Artisan::call('app:hero-of-the-month-dis', ['month' => $testMonth]);
        $this->assertEquals(0, $exitCode);
    }
}
