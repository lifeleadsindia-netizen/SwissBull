<?php

namespace Tests\Feature;

use App\Models\DailyTeamInvestmentShareConfiction;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DailyTeamInvestmentShareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        DailyTeamInvestmentShareConfiction::updateOrCreate(['id' => 1], [
            'level_1_rate' => 1.00,
            'level_1_directs' => 4,
            'level_2_rate' => 1.00,
            'level_2_directs' => 2,
            'level_3_rate' => 1.00,
            'level_3_directs' => 2,
            'level_4_rate' => 1.00,
            'level_4_directs' => 2,
            'level_5_rate' => 1.00,
            'level_5_directs' => 2,
            'level_6_rate' => 1.00,
            'level_6_directs' => 2,
            'level_7_rate' => 1.00,
            'level_7_directs' => 2,
            'level_8_rate' => 1.00,
            'level_8_directs' => 2,
            'level_9_rate' => 1.00,
            'level_9_directs' => 2,
            'level_10_rate' => 1.00,
            'level_10_directs' => 2,
        ]);
        parent::tearDown();
    }

    public function test_table_has_only_required_columns_and_no_capping_column(): void
    {
        $this->assertTrue(Schema::hasTable('daily_team_investment_share_confiction'));

        for ($i = 1; $i <= 10; $i++) {
            $this->assertTrue(Schema::hasColumn('daily_team_investment_share_confiction', "level_{$i}_rate"));
            $this->assertTrue(Schema::hasColumn('daily_team_investment_share_confiction', "level_{$i}_directs"));
        }

        $this->assertFalse(Schema::hasColumn('daily_team_investment_share_confiction', 'capping'));
        $this->assertFalse(Schema::hasColumn('daily_team_investment_share_confiction', 'capping_percent'));
        $this->assertFalse(Schema::hasColumn('daily_team_investment_share_confiction', 'level_11_rate'));
        $this->assertFalse(Schema::hasColumn('daily_team_investment_share_confiction', 'level_11_directs'));
    }

    public function test_guest_cannot_access_daily_team_investment_share_page(): void
    {
        $response = $this->get('/admin/daily-team-investment-share');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_daily_team_investment_share_page_and_all_10_levels_displayed(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $response->assertStatus(200);
        $response->assertViewIs('admin.daily-team-investment-share');
        $response->assertSee('Daily Team Investment Share');
        $response->assertSee('Save Configuration');

        for ($i = 1; $i <= 10; $i++) {
            $response->assertSee("Level-{$i} Rate (%)");
            $response->assertSee("name=\"level_{$i}_rate\"", false);
            $response->assertSee("name=\"level_{$i}_directs\"", false);
        }

        // Verify default rate 1.00% and direct referrals (4 for L1, 2 for L2..10)
        $response->assertSee('name="level_1_directs" value="4"', false);
        $response->assertSee('name="level_2_directs" value="2"', false);
        $response->assertSee('name="level_10_directs" value="2"', false);
        $response->assertSee('value="1.00"', false);
    }

    public function test_admin_can_save_and_update_rates_and_directs(): void
    {
        $postData = [
            'level_1_rate' => '1.50',
            'level_1_directs' => '5',
            'level_2_rate' => '1.25',
            'level_2_directs' => '3',
            'level_3_rate' => '1.20',
            'level_3_directs' => '3',
            'level_4_rate' => '1.10',
            'level_4_directs' => '3',
            'level_5_rate' => '1.05',
            'level_5_directs' => '2',
            'level_6_rate' => '1.00',
            'level_6_directs' => '2',
            'level_7_rate' => '0.90',
            'level_7_directs' => '2',
            'level_8_rate' => '0.80',
            'level_8_directs' => '2',
            'level_9_rate' => '0.70',
            'level_9_directs' => '2',
            'level_10_rate' => '0.50',
            'level_10_directs' => '2',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/daily-team-investment-share');

        $this->assertDatabaseHas('daily_team_investment_share_confiction', [
            'level_1_rate' => 1.50,
            'level_1_directs' => 5,
            'level_2_rate' => 1.25,
            'level_2_directs' => 3,
            'level_10_rate' => 0.50,
            'level_10_directs' => 2,
        ]);

        $settings = DailyTeamInvestmentShareConfiction::getLevelSettings();
        $this->assertEquals(1.50, $settings[1]['rate']);
        $this->assertEquals(5, $settings[1]['directs']);
        $this->assertEquals(1.25, $settings[2]['rate']);
        $this->assertEquals(3, $settings[2]['directs']);
        $this->assertEquals(0.50, $settings[10]['rate']);
        $this->assertEquals(2, $settings[10]['directs']);
    }

    public function test_saved_values_persist_on_refresh(): void
    {
        $postData = [
            'level_1_rate' => '2.00',
            'level_1_directs' => '6',
            'level_2_rate' => '1.80',
            'level_2_directs' => '4',
            'level_3_rate' => '1.50',
            'level_3_directs' => '3',
            'level_4_rate' => '1.40',
            'level_4_directs' => '3',
            'level_5_rate' => '1.30',
            'level_5_directs' => '2',
            'level_6_rate' => '1.20',
            'level_6_directs' => '2',
            'level_7_rate' => '1.10',
            'level_7_directs' => '2',
            'level_8_rate' => '1.00',
            'level_8_directs' => '2',
            'level_9_rate' => '0.80',
            'level_9_directs' => '1',
            'level_10_rate' => '0.60',
            'level_10_directs' => '1',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="2.00"', false);
        $refreshResponse->assertSee('name="level_1_directs" value="6"', false);
        $refreshResponse->assertSee('value="1.80"', false);
        $refreshResponse->assertSee('name="level_2_directs" value="4"', false);
        $refreshResponse->assertSee('value="0.60"', false);
        $refreshResponse->assertSee('name="level_10_directs" value="1"', false);
    }

    public function test_admin_can_edit_values_and_save_again(): void
    {
        // First save
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', [
                'level_1_rate' => '2.50',
                'level_1_directs' => '7',
                'level_2_rate' => '2.00',
                'level_2_directs' => '5',
                'level_3_rate' => '1.80',
                'level_3_directs' => '4',
                'level_4_rate' => '1.60',
                'level_4_directs' => '3',
                'level_5_rate' => '1.40',
                'level_5_directs' => '3',
                'level_6_rate' => '1.20',
                'level_6_directs' => '2',
                'level_7_rate' => '1.00',
                'level_7_directs' => '2',
                'level_8_rate' => '0.80',
                'level_8_directs' => '2',
                'level_9_rate' => '0.60',
                'level_9_directs' => '2',
                'level_10_rate' => '0.40',
                'level_10_directs' => '2',
            ]);

        // Second edit / save
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', [
                'level_1_rate' => '3.00',
                'level_1_directs' => '8',
                'level_2_rate' => '2.50',
                'level_2_directs' => '6',
                'level_3_rate' => '2.00',
                'level_3_directs' => '5',
                'level_4_rate' => '1.80',
                'level_4_directs' => '4',
                'level_5_rate' => '1.50',
                'level_5_directs' => '3',
                'level_6_rate' => '1.30',
                'level_6_directs' => '3',
                'level_7_rate' => '1.10',
                'level_7_directs' => '2',
                'level_8_rate' => '0.90',
                'level_8_directs' => '2',
                'level_9_rate' => '0.70',
                'level_9_directs' => '2',
                'level_10_rate' => '0.50',
                'level_10_directs' => '2',
            ]);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="3.00"', false);
        $refreshResponse->assertSee('name="level_1_directs" value="8"', false);
        $refreshResponse->assertSee('value="2.50"', false);
        $refreshResponse->assertSee('name="level_2_directs" value="6"', false);
        $refreshResponse->assertSee('value="0.50"', false);
        $refreshResponse->assertSee('name="level_10_directs" value="2"', false);

        $this->assertDatabaseHas('daily_team_investment_share_confiction', [
            'level_1_rate' => 3.00,
            'level_1_directs' => 8,
            'level_2_rate' => 2.50,
            'level_2_directs' => 6,
            'level_10_rate' => 0.50,
            'level_10_directs' => 2,
        ]);
    }

    public function test_validation_rejects_empty_or_invalid_values(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', [
                'level_1_rate' => '',
                'level_1_directs' => '-2',
                'level_2_rate' => '-5',
                'level_2_directs' => 'abc',
                'level_3_rate' => '120', // exceeds 100%
                'level_3_directs' => '',
            ]);

        $response->assertSessionHasErrors([
            'level_1_rate',
            'level_1_directs',
            'level_2_rate',
            'level_2_directs',
            'level_3_rate',
            'level_3_directs',
        ]);
    }

    public function test_sidebar_includes_daily_team_investment_share_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $response->assertStatus(200);
        $response->assertSee(route('admin.dailyTeamInvestmentShare'));
    }
}
