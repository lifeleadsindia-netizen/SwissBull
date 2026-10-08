<?php

namespace Tests\Feature;

use App\Models\PackagePlan;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SetPackagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        DB::table('package_distributions')->updateOrInsert(['id' => 1], [
            'trading_wallet' => 70.00,
            'p2p_wallet' => 70.00,
            'referral_bonus' => 10.00,
            'team_trading_profit' => 8.00,
            'team_performance_bonus' => 10.00,
            'hero_of_the_month' => 2.00,
        ]);
        parent::tearDown();
    }

    public function test_guest_cannot_access_set_packages_page(): void
    {
        $response = $this->get('/admin/set-packages');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_set_packages_page(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/set-packages');

        $response->assertStatus(200);
        $response->assertViewIs('admin.set-packages');
        $response->assertSee('Set Packages');
        $response->assertSee('Trading Wallet Allocation');
        $response->assertSee('Hero of the Month');
        $response->assertSee('Package Plans & Investment Tiers');
        $response->assertSee('Save / Update');

        // Confirm legacy fields are completely removed from the page
        $response->assertDontSee('id="referral_bonus"', false);
        $response->assertDontSee('name="referral_bonus"', false);
        $response->assertDontSee('id="team_trading_profit"', false);
        $response->assertDontSee('name="team_trading_profit"', false);
        $response->assertDontSee('id="team_performance_bonus"', false);
        $response->assertDontSee('name="team_performance_bonus"', false);

        // Confirm 4 fields removed from package tier cards
        $response->assertDontSee('trading_wallet_percent');
        $response->assertDontSee('return_percent');
        $response->assertDontSee('max_return_percent');
        $response->assertDontSee('lock_days');
        $response->assertDontSee('Return Rate %');
        $response->assertDontSee('Max Return Limit %');
        $response->assertDontSee('Lock Period (Days)');
    }

    public function test_admin_can_save_package_distribution(): void
    {
        // Clean any existing test rows if needed
        DB::table('package_distributions')->truncate();

        $postData = [
            'trading_wallet' => '70.00',
            'hero_of_the_month' => '2.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertStatus(302);

        $this->assertDatabaseHas('package_distributions', [
            'trading_wallet' => 70.00,
            'hero_of_the_month' => 2.00,
        ]);

        $this->assertEquals(1, DB::table('package_distributions')->count());
    }

    public function test_existing_values_are_fetched_into_form(): void
    {
        DB::table('package_distributions')->truncate();
        DB::table('package_distributions')->insert([
            'id' => 1,
            'trading_wallet' => 70.00,
            'p2p_wallet' => 70.00,
            'referral_bonus' => 10.00,
            'team_trading_profit' => 8.00,
            'team_performance_bonus' => 10.00,
            'hero_of_the_month' => 2.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/set-packages');

        $response->assertStatus(200);
        $response->assertSee('value="70.00"', false);
        $response->assertSee('value="2.00"', false);

        // Confirm legacy fields are not present in form
        $response->assertDontSee('id="referral_bonus"', false);
        $response->assertDontSee('id="team_trading_profit"', false);
        $response->assertDontSee('id="team_performance_bonus"', false);
    }

    public function test_updates_existing_record_without_creating_duplicates(): void
    {
        DB::table('package_distributions')->truncate();
        DB::table('package_distributions')->insert([
            'id' => 1,
            'trading_wallet' => 70.00,
            'p2p_wallet' => 70.00,
            'hero_of_the_month' => 2.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateData = [
            'trading_wallet' => '65.50',
            'hero_of_the_month' => '4.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $updateData);

        $response->assertSessionHas('successMsg');
        $this->assertEquals(1, DB::table('package_distributions')->count());

        $this->assertDatabaseHas('package_distributions', [
            'trading_wallet' => 65.50,
            'hero_of_the_month' => 4.00,
        ]);
    }

    public function test_validation_rejects_negative_or_invalid_values(): void
    {
        $invalidData = [
            'trading_wallet' => '-10',
            'hero_of_the_month' => 'invalid_text',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $invalidData);

        $response->assertSessionHasErrors(['trading_wallet', 'hero_of_the_month']);
    }

    public function test_sidebar_contains_set_packages_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/set-packages');

        $response->assertStatus(200);
        $response->assertSee(route('admin.setPackages'));
        $response->assertSee('Set Packages');
    }

    public function test_saved_values_remain_visible_after_page_refresh(): void
    {
        DB::table('package_distributions')->truncate();

        $data = [
            'trading_wallet' => '72.50',
            'hero_of_the_month' => '2.50',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $data)
            ->assertRedirect();

        $pageRefreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/set-packages');

        $pageRefreshResponse->assertStatus(200);
        $pageRefreshResponse->assertSee('value="72.50"', false);
        $pageRefreshResponse->assertSee('value="2.50"', false);
        $pageRefreshResponse->assertDontSee('id="referral_bonus"', false);
        $pageRefreshResponse->assertDontSee('id="team_trading_profit"', false);
        $pageRefreshResponse->assertDontSee('id="team_performance_bonus"', false);
    }

    public function test_phase2_ten_step_verification(): void
    {
        $admin = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        // 1. Open /admin/set-packages
        $response1 = $this->withSession($admin)->get('/admin/set-packages');
        $response1->assertStatus(200);

        // 2. Confirm the 3 fields are completely gone
        $response1->assertDontSee('id="referral_bonus"', false);
        $response1->assertDontSee('name="referral_bonus"', false);
        $response1->assertDontSee('id="team_trading_profit"', false);
        $response1->assertDontSee('name="team_trading_profit"', false);
        $response1->assertDontSee('id="team_performance_bonus"', false);
        $response1->assertDontSee('name="team_performance_bonus"', false);

        // 3. Confirm remaining fields display correctly
        $response1->assertSee('Trading Wallet Allocation');
        $response1->assertSee('Hero of the Month');
        $response1->assertSee('Package Plans & Investment Tiers');

        // 4 & 5. Save Set Packages without the removed fields and confirm save works without validation errors
        $saveResponse = $this->withSession($admin)->post('/admin/savePackages', [
            'p2p_wallet' => '70.00',
            'hero_of_the_month' => '2.00',
        ]);
        $saveResponse->assertSessionHasNoErrors();
        $saveResponse->assertSessionHas('successMsg');
        $saveResponse->assertRedirect();

        // 6. Open Referral Bonus and confirm it still works
        $refResponse = $this->withSession($admin)->get('/admin/referral-bonus');
        $refResponse->assertStatus(200);
        $refResponse->assertSee('Referral Bonus');
        $refResponse->assertSee('Level-1 Rate');

        // 7. Open Team Trading Profit and confirm it still works
        $teamResponse = $this->withSession($admin)->get('/admin/team-trading-profit');
        $teamResponse->assertStatus(200);
        $teamResponse->assertSee('Team Trading Profit');
        $teamResponse->assertSee('Level-1 Rate');
        $teamResponse->assertSee('Level-10 Rate');

        // 8. Open Daily Team Investment Share and confirm it still works
        $dailyResponse = $this->withSession($admin)->get('/admin/daily-team-investment-share');
        $dailyResponse->assertStatus(200);
        $dailyResponse->assertSee('Daily Team Investment Share');
        $dailyResponse->assertSee('Direct Referrals');

        // 9. Refresh Set Packages and confirm no removed fields return
        $refreshResponse = $this->withSession($admin)->get('/admin/set-packages');
        $refreshResponse->assertStatus(200);
        $refreshResponse->assertDontSee('id="referral_bonus"', false);
        $refreshResponse->assertDontSee('id="team_trading_profit"', false);
        $refreshResponse->assertDontSee('id="team_performance_bonus"', false);
        $refreshResponse->assertSee('Trading Wallet Allocation');
        $refreshResponse->assertSee('Hero of the Month');

        // 10. Confirm database state
        $this->assertDatabaseHas('package_distributions', [
            'p2p_wallet' => 70.00,
            'hero_of_the_month' => 2.00,
        ]);
    }

    public function test_left_and_right_forms_have_dedicated_save_buttons(): void
    {
        $admin = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        $response = $this->withSession($admin)->get('/admin/set-packages');
        $response->assertStatus(200);

        // Confirm both forms exist
        $response->assertSee('id="formPackageDistribution"', false);
        $response->assertSee('id="formPackagePlans"', false);

        // Confirm common bottom button is gone
        $response->assertDontSee('Save / Update All Package Settings');

        // Confirm both forms have their dedicated Save / Update button
        $content = $response->getContent();
        $this->assertEquals(2, substr_count($content, 'Save / Update'));
    }

    public function test_left_form_saves_distribution_independently(): void
    {
        $admin = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        $response = $this->withSession($admin)->post('/admin/savePackages', [
            'p2p_wallet' => '65.00',
            'hero_of_the_month' => '3.50',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('successMsg', 'Package distribution configuration updated successfully.');
        $response->assertRedirect();

        $this->assertDatabaseHas('package_distributions', [
            'trading_wallet' => 65.00,
            'hero_of_the_month' => 3.50,
        ]);
    }

    public function test_right_form_saves_plans_independently(): void
    {
        $admin = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        $plan = PackagePlan::first();
        $this->assertNotNull($plan);

        $response = $this->withSession($admin)->post('/admin/savePackages', [
            'plans' => [
                [
                    'id' => $plan->id,
                    'name' => 'Updated Plan Name',
                    'min_amount' => $plan->min_amount,
                    'max_amount' => $plan->max_amount,
                    'status' => 'Active',
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('successMsg', 'Package investment tiers updated successfully.');
        $response->assertRedirect();

        $this->assertDatabaseHas('package_plans', [
            'id' => $plan->id,
            'name' => 'Updated Plan Name',
            'min_amount' => $plan->min_amount,
            'status' => 'Active',
        ]);
    }

    public function test_package_tier_saving_does_not_require_removed_fields_and_preserves_database_values(): void
    {
        $admin = ['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1];

        $plan = PackagePlan::first();
        $this->assertNotNull($plan);

        $originalReturnPercent = $plan->return_percent;
        $originalMaxReturnPercent = $plan->max_return_percent;
        $originalLockDays = $plan->lock_days;

        // Save plan without the 4 removed fields (trading_wallet_percent, return_percent, max_return_percent, lock_days)
        $response = $this->withSession($admin)->post('/admin/savePackages', [
            'plans' => [
                [
                    'id' => $plan->id,
                    'name' => 'Preserved Plan Name',
                    'min_amount' => 75.00,
                    'max_amount' => 550.00,
                    'status' => 'Active',
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $refreshedPlan = PackagePlan::find($plan->id);
        $this->assertEquals('Preserved Plan Name', $refreshedPlan->name);
        $this->assertEquals(75.00, (float) $refreshedPlan->min_amount);
        $this->assertEquals(550.00, (float) $refreshedPlan->max_amount);
        // Preserved database columns
        $this->assertEquals($originalReturnPercent, $refreshedPlan->return_percent);
        $this->assertEquals($originalMaxReturnPercent, $refreshedPlan->max_return_percent);
        $this->assertEquals($originalLockDays, $refreshedPlan->lock_days);
    }
}
