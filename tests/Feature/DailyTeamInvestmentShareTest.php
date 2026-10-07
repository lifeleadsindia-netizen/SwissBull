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
        $chain = DailyTeamInvestmentShareConfiction::calculateDirectsChain(4);
        DailyTeamInvestmentShareConfiction::updateOrCreate(['id' => 1], [
            'level_1_rate' => 1.00,
            'level_1_directs' => $chain[1],
            'level_2_rate' => 1.00,
            'level_2_directs' => $chain[2],
            'level_3_rate' => 1.00,
            'level_3_directs' => $chain[3],
            'level_4_rate' => 1.00,
            'level_4_directs' => $chain[4],
            'level_5_rate' => 1.00,
            'level_5_directs' => $chain[5],
            'level_6_rate' => 1.00,
            'level_6_directs' => $chain[6],
            'level_7_rate' => 1.00,
            'level_7_directs' => $chain[7],
            'level_8_rate' => 1.00,
            'level_8_directs' => $chain[8],
            'level_9_rate' => 1.00,
            'level_9_directs' => $chain[9],
            'level_10_rate' => 1.00,
            'level_10_directs' => $chain[10],
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

        // Verify Level 1 is editable and has default 4
        $response->assertSee('id="level_1_directs"', false);
        $response->assertSee('name="level_1_directs"', false);
        $response->assertSee('value="4"', false);
        $this->assertDoesNotMatchRegularExpression('/id="level_1_directs"[^>]*readonly/', $response->getContent());

        // Verify Level 2..10 are readonly and have auto chain values: 6, 8, 10... 22
        $this->assertMatchesRegularExpression('/id="level_2_directs"[^>]*value="6"[^>]*readonly/', $response->getContent());
        $this->assertMatchesRegularExpression('/id="level_3_directs"[^>]*value="8"[^>]*readonly/', $response->getContent());
        $this->assertMatchesRegularExpression('/id="level_10_directs"[^>]*value="22"[^>]*readonly/', $response->getContent());

        // Verify JS auto chain script exists
        $response->assertSee('recalculateDirectsChain', false);
    }

    public function test_admin_saves_level_1_as_4_and_chain_persists_6_8_10_to_22(): void
    {
        $postData = [
            'level_1_directs' => '4',
        ];
        for ($i = 1; $i <= 10; $i++) {
            $postData["level_{$i}_rate"] = '1.00';
        }

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/daily-team-investment-share');

        $this->assertDatabaseHas('daily_team_investment_share_confiction', [
            'level_1_rate' => 1.00,
            'level_1_directs' => 4,
            'level_2_directs' => 6,
            'level_3_directs' => 8,
            'level_4_directs' => 10,
            'level_5_directs' => 12,
            'level_6_directs' => 14,
            'level_7_directs' => 16,
            'level_8_directs' => 18,
            'level_9_directs' => 20,
            'level_10_directs' => 22,
        ]);

        $settings = DailyTeamInvestmentShareConfiction::getLevelSettings();
        $this->assertEquals(4, $settings[1]['directs']);
        $this->assertEquals(6, $settings[2]['directs']);
        $this->assertEquals(8, $settings[3]['directs']);
        $this->assertEquals(10, $settings[4]['directs']);
        $this->assertEquals(12, $settings[5]['directs']);
        $this->assertEquals(22, $settings[10]['directs']);
    }

    public function test_admin_saves_level_1_as_1_and_chain_persists_3_5_7_to_19(): void
    {
        $postData = [
            'level_1_directs' => '1',
        ];
        for ($i = 1; $i <= 10; $i++) {
            $postData["level_{$i}_rate"] = '1.50';
        }

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/daily-team-investment-share');

        $this->assertDatabaseHas('daily_team_investment_share_confiction', [
            'level_1_rate' => 1.50,
            'level_1_directs' => 1,
            'level_2_directs' => 3,
            'level_3_directs' => 5,
            'level_4_directs' => 7,
            'level_5_directs' => 9,
            'level_6_directs' => 11,
            'level_7_directs' => 13,
            'level_8_directs' => 15,
            'level_9_directs' => 17,
            'level_10_directs' => 19,
        ]);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('id="level_1_directs" name="level_1_directs" value="1"', false);
        $refreshResponse->assertSee('id="level_2_directs" name="level_2_directs" value="3"', false);
        $refreshResponse->assertSee('id="level_3_directs" name="level_3_directs" value="5"', false);
        $refreshResponse->assertSee('id="level_10_directs" name="level_10_directs" value="19"', false);
    }

    public function test_backend_enforces_chain_calculation_and_overrides_tampered_inputs(): void
    {
        $postData = [
            'level_1_directs' => '4',
            'level_2_directs' => '999', // Tampered value
            'level_3_directs' => '100', // Tampered value
        ];
        for ($i = 1; $i <= 10; $i++) {
            $postData["level_{$i}_rate"] = '2.00';
        }

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $this->assertDatabaseHas('daily_team_investment_share_confiction', [
            'level_1_directs' => 4,
            'level_2_directs' => 6, // Overridden to 6 by backend formula
            'level_3_directs' => 8, // Overridden to 8 by backend formula
        ]);
        $this->assertDatabaseMissing('daily_team_investment_share_confiction', [
            'level_2_directs' => 999,
        ]);
    }

    public function test_rate_fields_and_directs_persist_on_refresh(): void
    {
        $postData = [
            'level_1_rate' => '2.50',
            'level_1_directs' => '4',
            'level_2_rate' => '2.00',
            'level_3_rate' => '1.80',
            'level_4_rate' => '1.60',
            'level_5_rate' => '1.40',
            'level_6_rate' => '1.20',
            'level_7_rate' => '1.00',
            'level_8_rate' => '0.80',
            'level_9_rate' => '0.60',
            'level_10_rate' => '0.40',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $postData);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="2.50"', false);
        $refreshResponse->assertSee('value="2.00"', false);
        $refreshResponse->assertSee('value="0.40"', false);
        $refreshResponse->assertSee('id="level_1_directs" name="level_1_directs" value="4"', false);
        $refreshResponse->assertSee('id="level_2_directs" name="level_2_directs" value="6"', false);
        $refreshResponse->assertSee('id="level_10_directs" name="level_10_directs" value="22"', false);
    }

    public function test_validation_rejects_empty_or_invalid_values(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', [
                'level_1_rate' => '',
                'level_1_directs' => '-2',
                'level_2_rate' => '-5',
                'level_3_rate' => '120', // exceeds 100%
            ]);

        $response->assertSessionHasErrors([
            'level_1_rate',
            'level_1_directs',
            'level_2_rate',
            'level_3_rate',
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
