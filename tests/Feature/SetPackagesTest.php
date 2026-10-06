<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SetPackagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
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
        $response->assertSee('Trading Wallet');
        $response->assertSee('Referral Bonus');
        $response->assertSee('Team Trading Profit');
        $response->assertSee('Team Performance Bonus');
        $response->assertSee('Hero of the Month');
        $response->assertSee('Save / Update');
    }

    public function test_admin_can_save_package_distribution(): void
    {
        // Clean any existing test rows if needed
        DB::table('package_distributions')->truncate();

        $postData = [
            'trading_wallet' => '70.00',
            'referral_bonus' => '10.00',
            'team_trading_profit' => '8.00',
            'team_performance_bonus' => '10.00',
            'hero_of_the_month' => '2.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertStatus(302);

        $this->assertDatabaseHas('package_distributions', [
            'trading_wallet' => 70.00,
            'referral_bonus' => 10.00,
            'team_trading_profit' => 8.00,
            'team_performance_bonus' => 10.00,
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
        $response->assertSee('value="10.00"', false);
        $response->assertSee('value="8.00"', false);
        $response->assertSee('value="2.00"', false);
    }

    public function test_updates_existing_record_without_creating_duplicates(): void
    {
        DB::table('package_distributions')->truncate();
        DB::table('package_distributions')->insert([
            'id' => 1,
            'trading_wallet' => 70.00,
            'referral_bonus' => 10.00,
            'team_trading_profit' => 8.00,
            'team_performance_bonus' => 10.00,
            'hero_of_the_month' => 2.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateData = [
            'trading_wallet' => '65.50',
            'referral_bonus' => '12.00',
            'team_trading_profit' => '7.50',
            'team_performance_bonus' => '11.00',
            'hero_of_the_month' => '4.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $updateData);

        $response->assertSessionHas('successMsg');
        $this->assertEquals(1, DB::table('package_distributions')->count());

        $this->assertDatabaseHas('package_distributions', [
            'trading_wallet' => 65.50,
            'referral_bonus' => 12.00,
            'team_trading_profit' => 7.50,
            'team_performance_bonus' => 11.00,
            'hero_of_the_month' => 4.00,
        ]);
    }

    public function test_validation_rejects_negative_or_invalid_values(): void
    {
        $invalidData = [
            'trading_wallet' => '-10',
            'referral_bonus' => 'invalid_text',
            'team_trading_profit' => '8.00',
            'team_performance_bonus' => '10.00',
            'hero_of_the_month' => '2.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $invalidData);

        $response->assertSessionHasErrors(['trading_wallet', 'referral_bonus']);
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
            'referral_bonus' => '11.25',
            'team_trading_profit' => '8.75',
            'team_performance_bonus' => '5.00',
            'hero_of_the_month' => '2.50',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/savePackages', $data)
            ->assertRedirect();

        $pageRefreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/set-packages');

        $pageRefreshResponse->assertStatus(200);
        $pageRefreshResponse->assertSee('value="72.50"', false);
        $pageRefreshResponse->assertSee('value="11.25"', false);
        $pageRefreshResponse->assertSee('value="8.75"', false);
        $pageRefreshResponse->assertSee('value="5.00"', false);
        $pageRefreshResponse->assertSee('value="2.50"', false);
    }
}
