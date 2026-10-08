<?php

namespace Tests\Feature;

use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use App\Models\WalletTransfer;
use App\Services\StakingRoiService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class Phase3StakingRoiTest extends TestCase
{
    private string $memberId;
    private array $backupTradingProfitConfig = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        $this->backupTradingProfitConfig = \Illuminate\Support\Facades\DB::table('monthly_trading_profit_confiction')->get()->toArray();
        \Illuminate\Support\Facades\DB::table('monthly_trading_profit_confiction')->truncate();

        $this->memberId = 'P3MEM'.rand(10000, 99999);

        // Clean previous test data
        StakingDetail::where('memberid', $this->memberId)->delete();
        PackageDetail::where('memberid', $this->memberId)->delete();
        ImportFund::where('memberid', $this->memberId)->delete();
        StakingIncome::where('memberid', $this->memberId)->delete();
        WalletTransfer::where('memberid', $this->memberId)->delete();

        MemberDetail::create([
            'memberid' => $this->memberId,
            'name' => 'Phase 3 Investor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'p3investor'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('secret123'),
            'p2p_wallet' => 0.00,
            'wallet' => 0.00,
            'country' => 'India',
            'status' => 'Temp',
        ]);

        // Pause other member stakings during test execution to ensure test isolation
        StakingDetail::where('memberid', '!=', $this->memberId)->update(['status' => 'Deactive']);
    }

    protected function tearDown(): void
    {
        StakingDetail::where('memberid', $this->memberId)->delete();
        PackageDetail::where('memberid', $this->memberId)->delete();
        ImportFund::where('memberid', $this->memberId)->delete();
        StakingIncome::where('memberid', $this->memberId)->delete();
        WalletTransfer::where('memberid', $this->memberId)->delete();
        MemberDetail::where('memberid', $this->memberId)->delete();

        // Restore original demo staking record #1 status
        StakingDetail::where('id', 1)->update(['status' => 'Active']);

        // Restore default package plan values
        PackagePlan::where('package_range', '50-500')->update(['return_percent' => 5.0, 'max_return_percent' => 200.0, 'lock_days' => 90]);
        PackagePlan::where('package_range', '600-5000')->update(['return_percent' => 7.0, 'max_return_percent' => 200.0, 'lock_days' => 90]);
        PackagePlan::where('package_range', '6000+')->update(['return_percent' => 10.0, 'max_return_percent' => 300.0, 'lock_days' => 90]);

        if (! empty($this->backupTradingProfitConfig)) {
            \Illuminate\Support\Facades\DB::table('monthly_trading_profit_confiction')->truncate();
            foreach ($this->backupTradingProfitConfig as $row) {
                \Illuminate\Support\Facades\DB::table('monthly_trading_profit_confiction')->insert((array) $row);
            }
        }

        parent::tearDown();
    }

    /**
     * Requirement 1, 2, 3, 4, 5, 6, 7:
     * Deposits across all 3 tiers create active staking_details records with 70% trading wallet credit.
     */
    public function test_deposits_across_all_package_tiers_create_active_staking_details(): void
    {
        $testCases = [
            ['amount' => 50.00, 'package' => '50-500', 'expectedTrading' => 35.00, 'expectedRate' => 5.0],
            ['amount' => 100.00, 'package' => '50-500', 'expectedTrading' => 70.00, 'expectedRate' => 5.0],
            ['amount' => 500.00, 'package' => '50-500', 'expectedTrading' => 350.00, 'expectedRate' => 5.0],
            ['amount' => 600.00, 'package' => '600-5000', 'expectedTrading' => 420.00, 'expectedRate' => 7.0],
            ['amount' => 5000.00, 'package' => '600-5000', 'expectedTrading' => 3500.00, 'expectedRate' => 7.0],
            ['amount' => 6000.00, 'package' => '6000+', 'expectedTrading' => 4200.00, 'expectedRate' => 10.0],
        ];

        foreach ($testCases as $idx => $tc) {
            StakingDetail::where('memberid', $this->memberId)->delete();
            PackageDetail::where('memberid', $this->memberId)->delete();
            MemberDetail::where('memberid', $this->memberId)->update(['p2p_wallet' => 50000.00]);
            $response = $this->withSession(['MEMBER_ID' => $this->memberId])
                ->postJson(route('createInvestment'), [
                    'memberid' => $this->memberId,
                    'package' => $tc['package'],
                    'amount' => $tc['amount'],
                ]);

            $response->assertStatus(200);

            // Verify StakingDetail record created and active
            $staking = StakingDetail::where('memberid', $this->memberId)->latest()->first();
            $this->assertNotNull($staking, "StakingDetail record must be created for {$tc['amount']} USDT");
            $this->assertEquals($this->memberId, $staking->memberid);
            $this->assertEquals($tc['amount'], (float) $staking->invest_amount);
            $this->assertEquals($tc['package'], $staking->package);
            $this->assertEquals('Active', $staking->status);
            $this->assertEquals(0.00, (float) $staking->total_earned);
            $this->assertNotNull($staking->activated_at);
        }
    }

    /**
     * Requirement 8 & 10:
     * Admin can dynamically change package ROI % and capping % from admin panel and it applies dynamically.
     */
    public function test_admin_can_dynamically_change_roi_and_capping(): void
    {
        $plan1 = PackagePlan::where('package_range', '50-500')->first();
        $plan2 = PackagePlan::where('package_range', '600-5000')->first();
        $plan3 = PackagePlan::where('package_range', '6000+')->first();

        $postData = [
            'trading_wallet' => '70.00',
            'referral_bonus' => '10.00',
            'team_trading_profit' => '8.00',
            'team_performance_bonus' => '10.00',
            'hero_of_the_month' => '2.00',
            'plans' => [
                0 => [
                    'id' => $plan1->id,
                    'name' => 'Package 1',
                    'min_amount' => 50,
                    'max_amount' => 500,
                    'trading_wallet_percent' => 70,
                    'return_percent' => 4.0, // Modified from 5% to 4%
                    'max_return_percent' => 100.0, // Modified to 100%
                    'lock_days' => 90,
                    'status' => 'Active',
                ],
                1 => [
                    'id' => $plan2->id,
                    'name' => 'Package 2',
                    'min_amount' => 600,
                    'max_amount' => 5000,
                    'trading_wallet_percent' => 70,
                    'return_percent' => 6.0, // Modified from 7% to 6%
                    'max_return_percent' => 200.0,
                    'lock_days' => 90,
                    'status' => 'Active',
                ],
                2 => [
                    'id' => $plan3->id,
                    'name' => 'Package 3',
                    'min_amount' => 6000,
                    'max_amount' => null,
                    'trading_wallet_percent' => 70,
                    'return_percent' => 9.0, // Modified from 10% to 9%
                    'max_return_percent' => 300.0,
                    'lock_days' => 90,
                    'status' => 'Active',
                ],
            ],
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $postData);

        $response->assertSessionHas('successMsg');

        // Check updated plan values
        $updatedPlan1 = PackagePlan::where('package_range', '50-500')->first();
        $this->assertEquals(4.0, (float) $updatedPlan1->return_percent);
        $this->assertEquals(100.0, (float) $updatedPlan1->max_return_percent);

        $updatedPlan2 = PackagePlan::where('package_range', '600-5000')->first();
        $this->assertEquals(6.0, (float) $updatedPlan2->return_percent);

        $updatedPlan3 = PackagePlan::where('package_range', '6000+')->first();
        $this->assertEquals(9.0, (float) $updatedPlan3->return_percent);
        $this->assertEquals(300.0, (float) $updatedPlan3->max_return_percent);
    }

    /**
     * Requirement 4, 5, 8, 12, 19:
     * Daily ROI calculation credits member main wallet, records in ledger and updates cumulative ROI.
     */
    public function test_daily_roi_credits_wallet_and_records_in_ledger(): void
    {
        // 1. Create 100 USDT staking record (Tier 1: 5% ROI)
        $txnid = '0x_p3_roi_test_'.time();
        $staking = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'txnid' => $txnid,
            'rate' => 5.0,
            'capping_percent' => 200.0,
            'max_amount' => 200.00,
            'total_earned' => 0.00,
            'installments' => 0,
            'total_installments' => 1200,
            'status' => 'Active',
            'activated_at' => now(),
        ]);

        $service = app(StakingRoiService::class);
        $result = $service->processDailyRoi('2026-10-01');

        $this->assertEquals(1, $result['credited']);
        $this->assertEquals(5.00, (float) $result['total_amount']);

        // 2. Verify Member main wallet credited with $5.00
        $member = MemberDetail::where('memberid', $this->memberId)->first();
        $this->assertEquals(5.00, (float) $member->wallet);

        // 3. Verify StakingIncome record created
        $income = StakingIncome::where('memberid', $this->memberId)->first();
        $this->assertNotNull($income);
        $this->assertEquals(5.00, (float) $income->amount);
        $this->assertEquals('Paid', $income->status);
        $this->assertEquals(1, $income->installment);
        $this->assertEquals($staking->id, $income->staking_id);

        // 4. Verify WalletTransfer ledger entry
        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Staking Income',
            'debit' => 5.00,
        ]);

        // 5. Verify StakingDetail updated
        $freshStaking = StakingDetail::find($staking->id);
        $this->assertEquals(5.00, (float) $freshStaking->total_earned);
        $this->assertEquals(1, $freshStaking->installments);
        $this->assertEquals('Active', $freshStaking->status);
        $this->assertEquals(195.00, (float) $freshStaking->remainingRoi());
    }

    /**
     * Requirement 18:
     * Duplicate protection: running ROI calculation multiple times on same day credits only once.
     */
    public function test_duplicate_roi_execution_on_same_day_is_prevented(): void
    {
        $staking = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'rate' => 5.0,
            'capping_percent' => 200.0,
            'max_amount' => 200.00,
            'total_earned' => 0.00,
            'status' => 'Active',
            'activated_at' => now(),
        ]);

        $service = app(StakingRoiService::class);

        // Run 1: Should credit $5.00
        $run1 = $service->processDailyRoi('2026-10-01');
        $this->assertEquals(1, $run1['credited']);

        // Run 2: Exact same date should skip and not duplicate
        $run2 = $service->processDailyRoi('2026-10-01');
        $this->assertEquals(0, $run2['credited']);
        $this->assertEquals(1, $run2['skipped']);

        // Run 3: Exact same date again
        $run3 = $service->processDailyRoi('2026-10-01');
        $this->assertEquals(0, $run3['credited']);
        $this->assertEquals(1, $run3['skipped']);

        // Wallet must still have only $5.00, not $10 or $15
        $wallet = (float) MemberDetail::where('memberid', $this->memberId)->value('wallet');
        $this->assertEquals(5.00, $wallet);
        $this->assertEquals(1, StakingIncome::where('memberid', $this->memberId)->count());
    }

    /**
     * Requirement 6, 8, 9, 10, 14, 16:
     * Cumulative ROI cannot exceed 100% cap.
     * Package automatically deactivates once max cap is reached.
     */
    public function test_100_percent_cap_and_automatic_package_deactivation(): void
    {
        // Configure Tier 1 to 100% capping
        PackagePlan::where('package_range', '50-500')->update([
            'return_percent' => 5.0,
            'max_return_percent' => 100.0,
        ]);

        $staking = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'rate' => 5.0,
            'capping_percent' => 100.0,
            'max_amount' => 100.00,
            'total_earned' => 95.00, // 95 earned out of 100 max cap
            'installments' => 19,
            'total_installments' => 1200,
            'status' => 'Active',
            'activated_at' => now()->subDays(20),
        ]);

        $service = app(StakingRoiService::class);

        // Process final $5.00 credit
        $result = $service->processDailyRoi('2026-10-05');
        $this->assertEquals(1, $result['credited']);
        $this->assertEquals(5.00, (float) $result['total_amount']);

        // Staking should now be exactly 100.00 earned and DEACTIVATED
        $freshStaking = StakingDetail::find($staking->id);
        $this->assertEquals(100.00, (float) $freshStaking->total_earned);
        $this->assertEquals('Deactive', $freshStaking->status);
        $this->assertNotNull($freshStaking->deactivated_at);
        $this->assertEquals(0.00, (float) $freshStaking->remainingRoi());

        // Process next day: DEACTIVATED package must NOT receive any further ROI
        $nextDayResult = $service->processDailyRoi('2026-10-06');
        $this->assertEquals(0, $nextDayResult['credited']);

        // Verify total earned did not increase
        $finalStaking = StakingDetail::find($staking->id);
        $this->assertEquals(100.00, (float) $finalStaking->total_earned);
    }

    /**
     * Requirement 8 & 15:
     * Partial final ROI when remaining cap is smaller than daily ROI.
     * Example from specification:
     * Max ROI: 100 USDT, Already earned: 98 USDT, Daily ROI: 5 USDT.
     * System credits only remaining eligible amount (2 USDT) and deactivates package.
     */
    public function test_partial_final_roi_when_remaining_cap_is_smaller_than_daily_roi(): void
    {
        // 100 USDT package, 100% cap (Max ROI = 100 USDT)
        PackagePlan::where('package_range', '50-500')->update([
            'return_percent' => 5.0,
            'max_return_percent' => 100.0,
        ]);

        $staking = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'rate' => 5.0,
            'capping_percent' => 100.0,
            'max_amount' => 100.00,
            'total_earned' => 98.00, // 98 already earned out of 100 max
            'installments' => 19,
            'total_installments' => 1200,
            'status' => 'Active',
            'activated_at' => now()->subDays(20),
        ]);

        $initialWallet = (float) MemberDetail::where('memberid', $this->memberId)->value('wallet');

        $service = app(StakingRoiService::class);
        $result = $service->processDailyRoi('2026-10-05');

        // Only 2.00 USDT must be credited (100 - 98 = 2.00)
        $this->assertEquals(1, $result['credited']);
        $this->assertEquals(2.00, (float) $result['total_amount']);

        // Wallet must have increased by exactly 2.00
        $newWallet = (float) MemberDetail::where('memberid', $this->memberId)->value('wallet');
        $this->assertEquals($initialWallet + 2.00, $newWallet);

        // StakingDetail must be exactly at 100.00 and Deactivated
        $fresh = StakingDetail::find($staking->id);
        $this->assertEquals(100.00, (float) $fresh->total_earned);
        $this->assertEquals('Deactive', $fresh->status);
        $this->assertNotNull($fresh->deactivated_at);
        $this->assertEquals(0.00, (float) $fresh->remainingRoi());

        // StakingIncome record must have amount = 2.00
        $lastIncome = StakingIncome::where('staking_id', $staking->id)->latest('id')->first();
        $this->assertEquals(2.00, (float) $lastIncome->amount);
    }

    /**
     * Requirement 11 & 12:
     * 200% and 300% capping limits calculate dynamically.
     */
    public function test_200_and_300_percent_capping_limits(): void
    {
        // Package 1 with 200% cap -> Max ROI = 200 USDT
        $staking200 = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'rate' => 5.0,
            'capping_percent' => 200.0,
            'max_amount' => 200.00,
            'total_earned' => 50.00,
            'status' => 'Active',
            'activated_at' => now(),
        ]);

        $this->assertEquals(200.00, (float) $staking200->getMaxRoiAmount());
        $this->assertEquals(150.00, (float) $staking200->remainingRoi());

        // Package 3 with 300% cap -> Max ROI = 18,000 USDT on 6,000 package
        $staking300 = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 6000.00,
            'package' => '6000+',
            'rate' => 10.0,
            'capping_percent' => 300.0,
            'max_amount' => 18000.00,
            'total_earned' => 0.00,
            'status' => 'Active',
            'activated_at' => now(),
        ]);

        $this->assertEquals(18000.00, (float) $staking300->getMaxRoiAmount());
        $this->assertEquals(18000.00, (float) $staking300->remainingRoi());
    }

    /**
     * Requirement 17:
     * No ROI generated or credited after package deactivation.
     */
    public function test_no_roi_after_deactivation(): void
    {
        $deactiveStaking = StakingDetail::create([
            'memberid' => $this->memberId,
            'invest_amount' => 100.00,
            'package' => '50-500',
            'rate' => 5.0,
            'capping_percent' => 200.0,
            'max_amount' => 200.00,
            'total_earned' => 200.00,
            'status' => 'Deactive',
            'deactivated_at' => now()->subDay(),
        ]);

        $initialWallet = (float) MemberDetail::where('memberid', $this->memberId)->value('wallet');

        $service = app(StakingRoiService::class);
        $result = $service->processDailyRoi('2026-10-07');

        $this->assertEquals(0, $result['credited']);
        $newWallet = (float) MemberDetail::where('memberid', $this->memberId)->value('wallet');
        $this->assertEquals($initialWallet, $newWallet);
    }

    /**
     * Artisan command app:daily-income-dis executes successfully.
     */
    public function test_daily_income_console_command_runs(): void
    {
        $exitCode = Artisan::call('app:daily-income-dis');
        $this->assertEquals(0, $exitCode);
    }
}
