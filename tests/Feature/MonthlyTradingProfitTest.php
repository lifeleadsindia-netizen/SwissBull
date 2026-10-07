<?php

namespace Tests\Feature;

use App\Models\MonthlyTradingProfitConfiction;
use App\Models\PackagePlan;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MonthlyTradingProfitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_guest_cannot_access_monthly_trading_profit_page(): void
    {
        $response = $this->get('/admin/monthly-trading-profit');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_monthly_trading_profit_page_and_packages_are_loaded_dynamically(): void
    {
        $packages = PackagePlan::orderBy('min_amount', 'asc')->get();
        $this->assertCount(3, $packages);

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/monthly-trading-profit');

        $response->assertStatus(200);
        $response->assertViewIs('admin.monthly-trading-profit');
        $response->assertSee('Monthly Trading Profit');
        $response->assertSee('Monthly Trading Profit Capping (%)');
        $response->assertSee('Rate (%)');

        // Confirm package names and ranges from existing package data are present
        foreach ($packages as $package) {
            $response->assertSee($package->name);
            $response->assertSee($package->package_range);
        }
    }

    public function test_admin_can_save_configuration_to_monthly_trading_profit_confiction(): void
    {
        DB::table('monthly_trading_profit_confiction')->truncate();

        $packages = PackagePlan::orderBy('min_amount', 'asc')->get();

        $rates = [];
        $sampleRates = [5.00, 7.00, 10.00];
        foreach ($packages as $index => $package) {
            $rates[$package->id] = $sampleRates[$index] ?? 5.00;
        }

        $postData = [
            'rates' => $rates,
            'capping_percent' => 200.00,
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/monthly-trading-profit', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/monthly-trading-profit');

        // Verify data in table monthly_trading_profit_confiction
        $this->assertDatabaseCount('monthly_trading_profit_confiction', 3);

        foreach ($packages as $index => $package) {
            $this->assertDatabaseHas('monthly_trading_profit_confiction', [
                'package_id' => $package->id,
                'rate' => $sampleRates[$index],
                'capping_percent' => 200.00,
            ]);
            $this->assertEquals($sampleRates[$index], MonthlyTradingProfitConfiction::getRateForPackage($package->id));
        }

        $this->assertEquals(200.00, MonthlyTradingProfitConfiction::getCappingPercent());
    }

    public function test_saved_values_persist_and_are_displayed_on_page_refresh(): void
    {
        DB::table('monthly_trading_profit_confiction')->truncate();

        $packages = PackagePlan::orderBy('min_amount', 'asc')->get();

        $rates = [
            $packages[0]->id => '5.50',
            $packages[1]->id => '7.25',
            $packages[2]->id => '10.50',
        ];

        $postData = [
            'rates' => $rates,
            'capping_percent' => '250.00',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/monthly-trading-profit', $postData)
            ->assertRedirect();

        // Refresh/visit the page
        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/monthly-trading-profit');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="5.50"', false);
        $refreshResponse->assertSee('value="7.25"', false);
        $refreshResponse->assertSee('value="10.50"', false);
        $refreshResponse->assertSee('value="250.00"', false);
    }

    public function test_admin_can_edit_and_update_configuration_values(): void
    {
        DB::table('monthly_trading_profit_confiction')->truncate();

        $packages = PackagePlan::orderBy('min_amount', 'asc')->get();

        // Initial save
        $initialData = [
            'rates' => [
                $packages[0]->id => '4.00',
                $packages[1]->id => '6.00',
                $packages[2]->id => '9.00',
            ],
            'capping_percent' => '150.00',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/monthly-trading-profit', $initialData);

        $this->assertDatabaseCount('monthly_trading_profit_confiction', 3);

        // Edit and update values
        $updatedData = [
            'rates' => [
                $packages[0]->id => '6.50',
                $packages[1]->id => '8.50',
                $packages[2]->id => '12.00',
            ],
            'capping_percent' => '300.00',
        ];

        $updateResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/monthly-trading-profit', $updatedData);

        $updateResponse->assertSessionHas('successMsg');
        $this->assertDatabaseCount('monthly_trading_profit_confiction', 3);

        // Check updated values in database
        $this->assertDatabaseHas('monthly_trading_profit_confiction', [
            'package_id' => $packages[0]->id,
            'rate' => 6.50,
            'capping_percent' => 300.00,
        ]);
        $this->assertDatabaseHas('monthly_trading_profit_confiction', [
            'package_id' => $packages[1]->id,
            'rate' => 8.50,
            'capping_percent' => 300.00,
        ]);
        $this->assertDatabaseHas('monthly_trading_profit_confiction', [
            'package_id' => $packages[2]->id,
            'rate' => 12.00,
            'capping_percent' => 300.00,
        ]);

        // Check that updated values persist on GET request
        $refreshed = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/monthly-trading-profit');

        $refreshed->assertStatus(200);
        $refreshed->assertSee('value="6.50"', false);
        $refreshed->assertSee('value="8.50"', false);
        $refreshed->assertSee('value="12.00"', false);
        $refreshed->assertSee('value="300.00"', false);
    }

    public function test_validation_requires_capping_percent(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/monthly-trading-profit', [
                'capping_percent' => '',
            ]);

        $response->assertSessionHasErrors(['capping_percent']);
    }

    public function test_sidebar_includes_monthly_trading_profit_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/monthly-trading-profit');

        $response->assertStatus(200);
        $response->assertSee(route('admin.monthlyTradingProfit'));
    }

    protected function tearDown(): void
    {
        DB::table('monthly_trading_profit_confiction')->truncate();
        parent::tearDown();
    }
}
