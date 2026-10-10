<?php

namespace Tests\Feature;

use App\Models\DailyTeamInvestmentShareConfiction;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DailyTeamInvestmentShareDirectValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        // Configure sqlite in-memory for testing isolation
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        config(['session.driver' => 'array']);

        if (! Schema::hasTable('daily_team_investment_share_confiction')) {
            Artisan::call('migrate', [
                '--path' => 'database/migrations/2026_10_07_110000_create_daily_team_investment_share_confiction_table.php',
            ]);
        }
    }

    public function test_view_renders_all_10_levels_editable_without_readonly(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/daily-team-investment-share');

        $response->assertStatus(200);
        $content = $response->getContent();

        // Check that none of the level directs inputs have readonly or disabled
        for ($i = 1; $i <= 10; $i++) {
            $this->assertStringContainsString("id=\"level_{$i}_directs\"", $content);
            $this->assertStringContainsString("id=\"level_{$i}_rate\"", $content);
            $this->assertStringContainsString("id=\"level_{$i}_directs_client_error\"", $content);
        }

        $this->assertStringNotContainsString('readonly', $content);
        $this->assertStringNotContainsString('Auto-calculated: Previous level + 2', $content);
        $this->assertStringNotContainsString('recalculateDirectsChain', $content);
    }

    public function test_backend_accepts_valid_non_decreasing_sequence_and_saves_manually(): void
    {
        $data = [];
        $directsSequence = [1 => 4, 2 => 4, 3 => 6, 4 => 8, 5 => 8, 6 => 10, 7 => 12, 8 => 12, 9 => 15, 10 => 20];

        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.50 + ($i * 0.1);
            $data["level_{$i}_directs"] = $directsSequence[$i];
        }

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.dailyTeamInvestmentShare'));

        $setting = DailyTeamInvestmentShareConfiction::getActiveSetting();
        for ($i = 1; $i <= 10; $i++) {
            $this->assertEquals($directsSequence[$i], (int) $setting->{"level_{$i}_directs"});
        }
    }

    public function test_backend_rejects_decreasing_sequence_level_2_less_than_level_1(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.00;
            $data["level_{$i}_directs"] = 4;
        }

        // Invalid: Level 2 = 3 while Level 1 = 4
        $data['level_1_directs'] = 4;
        $data['level_2_directs'] = 3;

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasErrors(['level_2_directs']);

        $setting = DailyTeamInvestmentShareConfiction::getActiveSetting();
        $this->assertNotEquals(3, (int) $setting->level_2_directs);
    }

    public function test_backend_rejects_decreasing_sequence_mid_level(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.00;
            $data["level_{$i}_directs"] = $i * 2;
        }

        // Invalid: Level 1 = 4, Level 2 = 6, Level 3 = 5 (Level 3 < Level 2)
        $data['level_1_directs'] = 4;
        $data['level_2_directs'] = 6;
        $data['level_3_directs'] = 5;

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasErrors([
            'level_3_directs' => 'Level-3 Direct Referrals (5) must be greater than or equal to Level-2 Direct Referrals (6).',
        ]);
    }

    public function test_backend_allows_equal_values_in_sequence(): void
    {
        $data = [];
        // Equal values: 4, 4, 4, 5, 5, 6, 6, 6, 7, 7
        $directsSequence = [1 => 4, 2 => 4, 3 => 4, 4 => 5, 5 => 5, 6 => 6, 7 => 6, 8 => 6, 9 => 7, 10 => 7];

        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.00;
            $data["level_{$i}_directs"] = $directsSequence[$i];
        }

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.dailyTeamInvestmentShare'));

        $setting = DailyTeamInvestmentShareConfiction::getActiveSetting();
        for ($i = 1; $i <= 10; $i++) {
            $this->assertEquals($directsSequence[$i], (int) $setting->{"level_{$i}_directs"});
        }
    }

    public function test_backend_rejects_negative_directs(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.00;
            $data["level_{$i}_directs"] = $i * 2;
        }

        $data['level_2_directs'] = -1;

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasErrors(['level_2_directs']);
    }

    public function test_backend_rejects_rate_exceeding_100_percent(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data["level_{$i}_rate"] = 1.00;
            $data["level_{$i}_directs"] = $i * 2;
        }

        $data['level_1_rate'] = 105;

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $data);

        $response->assertSessionHasErrors(['level_1_rate']);
    }

    public function test_invalid_submission_does_not_partially_update_database(): void
    {
        // First save a known good baseline
        $baseline = [];
        for ($i = 1; $i <= 10; $i++) {
            $baseline["level_{$i}_rate"] = 2.00;
            $baseline["level_{$i}_directs"] = 5;
        }
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $baseline);

        // Attempt an invalid update where Level 1 and 2 are changed, but Level 3 decreases
        $invalidData = [];
        for ($i = 1; $i <= 10; $i++) {
            $invalidData["level_{$i}_rate"] = 9.99;
            $invalidData["level_{$i}_directs"] = 10;
        }
        $invalidData['level_3_directs'] = 2; // Invalid: Level 3 (2) < Level 2 (10)

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/daily-team-investment-share', $invalidData);

        $response->assertSessionHasErrors(['level_3_directs']);

        // Verify that database was NOT partially updated (level 1 rate must still be 2.00, not 9.99)
        $setting = DailyTeamInvestmentShareConfiction::getActiveSetting();
        $this->assertEquals(2.00, (float) $setting->level_1_rate);
        $this->assertEquals(5, (int) $setting->level_1_directs);
        $this->assertEquals(5, (int) $setting->level_2_directs);
        $this->assertEquals(5, (int) $setting->level_3_directs);
    }
}
