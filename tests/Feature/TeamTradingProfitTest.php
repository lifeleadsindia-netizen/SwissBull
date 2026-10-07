<?php

namespace Tests\Feature;

use App\Models\TeamTradingProfitConfiction;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TeamTradingProfitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        TeamTradingProfitConfiction::updateOrCreate(['id' => 1], [
            'level_1_rate' => 5.00,
            'level_2_rate' => 5.00,
            'level_3_rate' => 4.00,
            'level_4_rate' => 4.00,
            'level_5_rate' => 3.00,
            'level_6_rate' => 3.00,
            'level_7_rate' => 2.00,
            'level_8_rate' => 2.00,
            'level_9_rate' => 1.00,
            'level_10_rate' => 1.00,
        ]);
        parent::tearDown();
    }

    public function test_table_has_only_required_10_rate_columns_and_no_capping_column(): void
    {
        $this->assertTrue(Schema::hasTable('team_trading_profit_confiction'));

        for ($i = 1; $i <= 10; $i++) {
            $this->assertTrue(Schema::hasColumn('team_trading_profit_confiction', "level_{$i}_rate"));
        }

        $this->assertFalse(Schema::hasColumn('team_trading_profit_confiction', 'capping'));
        $this->assertFalse(Schema::hasColumn('team_trading_profit_confiction', 'capping_percent'));
        $this->assertFalse(Schema::hasColumn('team_trading_profit_confiction', 'level_11_rate'));
    }

    public function test_guest_cannot_access_team_trading_profit_page(): void
    {
        $response = $this->get('/admin/team-trading-profit');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_team_trading_profit_page_and_all_10_levels_are_displayed(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/team-trading-profit');

        $response->assertStatus(200);
        $response->assertViewIs('admin.team-trading-profit');
        $response->assertSee('Team Trading Profit');
        $response->assertSee('Save Configuration');

        for ($i = 1; $i <= 10; $i++) {
            $response->assertSee("Level-{$i} Rate (%)");
            $response->assertSee("name=\"level_{$i}_rate\"", false);
        }

        // Verify default rate values are displayed
        $response->assertSee('value="5.00"', false);
        $response->assertSee('value="4.00"', false);
        $response->assertSee('value="3.00"', false);
        $response->assertSee('value="2.00"', false);
        $response->assertSee('value="1.00"', false);
    }

    public function test_admin_can_save_and_update_rates_in_team_trading_profit_confiction(): void
    {
        $postData = [
            'level_1_rate' => '6.00',
            'level_2_rate' => '5.50',
            'level_3_rate' => '4.50',
            'level_4_rate' => '4.00',
            'level_5_rate' => '3.50',
            'level_6_rate' => '3.00',
            'level_7_rate' => '2.50',
            'level_8_rate' => '2.00',
            'level_9_rate' => '1.50',
            'level_10_rate' => '1.00',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/team-trading-profit', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/team-trading-profit');

        $this->assertDatabaseHas('team_trading_profit_confiction', [
            'level_1_rate' => 6.00,
            'level_2_rate' => 5.50,
            'level_3_rate' => 4.50,
            'level_4_rate' => 4.00,
            'level_5_rate' => 3.50,
            'level_6_rate' => 3.00,
            'level_7_rate' => 2.50,
            'level_8_rate' => 2.00,
            'level_9_rate' => 1.50,
            'level_10_rate' => 1.00,
        ]);

        $rates = TeamTradingProfitConfiction::getLevelRates();
        $this->assertEquals(6.00, $rates[1]);
        $this->assertEquals(5.50, $rates[2]);
        $this->assertEquals(4.50, $rates[3]);
        $this->assertEquals(4.00, $rates[4]);
        $this->assertEquals(3.50, $rates[5]);
        $this->assertEquals(3.00, $rates[6]);
        $this->assertEquals(2.50, $rates[7]);
        $this->assertEquals(2.00, $rates[8]);
        $this->assertEquals(1.50, $rates[9]);
        $this->assertEquals(1.00, $rates[10]);
    }

    public function test_saved_values_persist_on_refresh(): void
    {
        $postData = [
            'level_1_rate' => '7.00',
            'level_2_rate' => '6.00',
            'level_3_rate' => '5.00',
            'level_4_rate' => '4.50',
            'level_5_rate' => '3.50',
            'level_6_rate' => '3.00',
            'level_7_rate' => '2.50',
            'level_8_rate' => '2.00',
            'level_9_rate' => '1.50',
            'level_10_rate' => '0.50',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/team-trading-profit', $postData);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/team-trading-profit');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="7.00"', false);
        $refreshResponse->assertSee('value="6.00"', false);
        $refreshResponse->assertSee('value="5.00"', false);
        $refreshResponse->assertSee('value="4.50"', false);
        $refreshResponse->assertSee('value="3.50"', false);
        $refreshResponse->assertSee('value="3.00"', false);
        $refreshResponse->assertSee('value="2.50"', false);
        $refreshResponse->assertSee('value="2.00"', false);
        $refreshResponse->assertSee('value="1.50"', false);
        $refreshResponse->assertSee('value="0.50"', false);
    }

    public function test_admin_can_edit_values_and_save_again(): void
    {
        // First save
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/team-trading-profit', [
                'level_1_rate' => '8.00',
                'level_2_rate' => '7.00',
                'level_3_rate' => '6.00',
                'level_4_rate' => '5.00',
                'level_5_rate' => '4.00',
                'level_6_rate' => '3.00',
                'level_7_rate' => '2.00',
                'level_8_rate' => '1.50',
                'level_9_rate' => '1.00',
                'level_10_rate' => '0.50',
            ]);

        // Second save / edit
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/team-trading-profit', [
                'level_1_rate' => '9.00',
                'level_2_rate' => '8.00',
                'level_3_rate' => '7.00',
                'level_4_rate' => '6.00',
                'level_5_rate' => '5.00',
                'level_6_rate' => '4.00',
                'level_7_rate' => '3.00',
                'level_8_rate' => '2.00',
                'level_9_rate' => '1.00',
                'level_10_rate' => '0.25',
            ]);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/team-trading-profit');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="9.00"', false);
        $refreshResponse->assertSee('value="8.00"', false);
        $refreshResponse->assertSee('value="7.00"', false);
        $refreshResponse->assertSee('value="6.00"', false);
        $refreshResponse->assertSee('value="5.00"', false);
        $refreshResponse->assertSee('value="4.00"', false);
        $refreshResponse->assertSee('value="3.00"', false);
        $refreshResponse->assertSee('value="2.00"', false);
        $refreshResponse->assertSee('value="1.00"', false);
        $refreshResponse->assertSee('value="0.25"', false);

        $this->assertDatabaseHas('team_trading_profit_confiction', [
            'level_1_rate' => 9.00,
            'level_2_rate' => 8.00,
            'level_3_rate' => 7.00,
            'level_4_rate' => 6.00,
            'level_5_rate' => 5.00,
            'level_6_rate' => 4.00,
            'level_7_rate' => 3.00,
            'level_8_rate' => 2.00,
            'level_9_rate' => 1.00,
            'level_10_rate' => 0.25,
        ]);
    }

    public function test_validation_rejects_empty_or_invalid_rates(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/team-trading-profit', [
                'level_1_rate' => '',
                'level_2_rate' => '-5',
                'level_3_rate' => 'xyz',
                'level_4_rate' => '105', // exceeds 100%
            ]);

        $response->assertSessionHasErrors(['level_1_rate', 'level_2_rate', 'level_3_rate', 'level_4_rate']);
    }

    public function test_sidebar_includes_team_trading_profit_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/team-trading-profit');

        $response->assertStatus(200);
        $response->assertSee(route('admin.teamTradingProfit'));
    }
}
