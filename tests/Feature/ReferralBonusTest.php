<?php

namespace Tests\Feature;

use App\Models\DirectIncome;
use App\Models\MemberDetail;
use App\Models\ReferralBonusConfiction;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReferralBonusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    protected function tearDown(): void
    {
        ReferralBonusConfiction::updateOrCreate(['id' => 1], [
            'level_1_rate' => 5.00,
            'level_2_rate' => 3.00,
            'level_3_rate' => 2.00,
        ]);
        parent::tearDown();
    }

    public function test_table_has_only_required_rate_columns_and_no_capping_column(): void
    {
        $this->assertTrue(Schema::hasTable('referral_bonus_confiction'));
        $this->assertTrue(Schema::hasColumn('referral_bonus_confiction', 'level_1_rate'));
        $this->assertTrue(Schema::hasColumn('referral_bonus_confiction', 'level_2_rate'));
        $this->assertTrue(Schema::hasColumn('referral_bonus_confiction', 'level_3_rate'));
        $this->assertFalse(Schema::hasColumn('referral_bonus_confiction', 'capping'));
        $this->assertFalse(Schema::hasColumn('referral_bonus_confiction', 'capping_percent'));
    }

    public function test_guest_cannot_access_referral_bonus_page(): void
    {
        $response = $this->get('/admin/referral-bonus');
        $response->assertStatus(302);
    }

    public function test_admin_can_access_referral_bonus_page_and_default_inputs_are_displayed(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/referral-bonus');

        $response->assertStatus(200);
        $response->assertViewIs('admin.referral-bonus');
        $response->assertSee('Referral Bonus');
        $response->assertSee('Level-1 Rate (%)');
        $response->assertSee('Level-2 Rate (%)');
        $response->assertSee('Level-3 Rate (%)');
        $response->assertSee('Save Configuration');

        // Check defaults are 5%, 3%, 2%
        $response->assertSee('value="5.00"', false);
        $response->assertSee('value="3.00"', false);
        $response->assertSee('value="2.00"', false);
    }

    public function test_admin_can_save_and_update_rates_in_referral_bonus_confiction(): void
    {
        $postData = [
            'level_1_rate' => '6.00',
            'level_2_rate' => '4.00',
            'level_3_rate' => '2.50',
        ];

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/referral-bonus', $postData);

        $response->assertSessionHas('successMsg');
        $response->assertRedirect('/admin/referral-bonus');

        $this->assertDatabaseHas('referral_bonus_confiction', [
            'level_1_rate' => 6.00,
            'level_2_rate' => 4.00,
            'level_3_rate' => 2.50,
        ]);

        $rates = ReferralBonusConfiction::getLevelRates();
        $this->assertEquals(6.00, $rates[1]);
        $this->assertEquals(4.00, $rates[2]);
        $this->assertEquals(2.50, $rates[3]);
    }

    public function test_saved_values_persist_on_refresh(): void
    {
        $postData = [
            'level_1_rate' => '7.50',
            'level_2_rate' => '3.50',
            'level_3_rate' => '1.50',
        ];

        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/referral-bonus', $postData);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/referral-bonus');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="7.50"', false);
        $refreshResponse->assertSee('value="3.50"', false);
        $refreshResponse->assertSee('value="1.50"', false);
    }

    public function test_admin_can_edit_values_and_save_again(): void
    {
        // First edit
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/referral-bonus', [
                'level_1_rate' => '8.00',
                'level_2_rate' => '4.00',
                'level_3_rate' => '2.00',
            ]);

        // Second edit
        $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/referral-bonus', [
                'level_1_rate' => '9.00',
                'level_2_rate' => '5.00',
                'level_3_rate' => '3.00',
            ]);

        $refreshResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/referral-bonus');

        $refreshResponse->assertStatus(200);
        $refreshResponse->assertSee('value="9.00"', false);
        $refreshResponse->assertSee('value="5.00"', false);
        $refreshResponse->assertSee('value="3.00"', false);

        $this->assertDatabaseHas('referral_bonus_confiction', [
            'level_1_rate' => 9.00,
            'level_2_rate' => 5.00,
            'level_3_rate' => 3.00,
        ]);
    }

    public function test_validation_rejects_empty_or_negative_rates(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/admin/referral-bonus', [
                'level_1_rate' => '',
                'level_2_rate' => '-5',
                'level_3_rate' => 'abc',
            ]);

        $response->assertSessionHasErrors(['level_1_rate', 'level_2_rate', 'level_3_rate']);
    }

    public function test_direct_income_helper_dynamically_uses_configured_rates(): void
    {
        // Set custom rates in referral_bonus_confiction
        $setting = ReferralBonusConfiction::getActiveSetting();
        $setting->level_1_rate = 6.00;
        $setting->level_2_rate = 4.00;
        $setting->level_3_rate = 2.00;
        $setting->save();

        // Setup 3-tier sponsor upline: Sponsor 3 -> Sponsor 2 -> Sponsor 1 -> Member
        $sponsor3Id = 'TEST_SP3_'.rand(1000, 9999);
        $sponsor2Id = 'TEST_SP2_'.rand(1000, 9999);
        $sponsor1Id = 'TEST_SP1_'.rand(1000, 9999);
        $memberId = 'TEST_MEM_'.rand(1000, 9999);

        MemberDetail::create([
            'memberid' => $sponsor3Id,
            'sponsorid' => 'Root',
            'name' => 'Sponsor Three',
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $sponsor2Id,
            'sponsorid' => $sponsor3Id,
            'name' => 'Sponsor Two',
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $sponsor1Id,
            'sponsorid' => $sponsor2Id,
            'name' => 'Sponsor One',
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        MemberDetail::create([
            'memberid' => $memberId,
            'sponsorid' => $sponsor1Id,
            'name' => 'New Member',
            'status' => 'Active',
            'wallet' => 0.00,
        ]);

        // Trigger directIncome with investment amount 1000
        directIncome($sponsor1Id, $memberId, 'New Member', 1000.00, 'Direct Income');

        // Level 1: 6% of 1000 = 60
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $sponsor1Id,
            'activatingid' => $memberId,
            'amount' => 60.00,
        ]);

        // Level 2: 4% of 1000 = 40
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $sponsor2Id,
            'activatingid' => $memberId,
            'amount' => 40.00,
        ]);

        // Level 3: 2% of 1000 = 20
        $this->assertDatabaseHas('direct_incomes', [
            'memberid' => $sponsor3Id,
            'activatingid' => $memberId,
            'amount' => 20.00,
        ]);

        // Clean up test members
        DirectIncome::where('activatingid', $memberId)->delete();
        MemberDetail::whereIn('memberid', [$sponsor3Id, $sponsor2Id, $sponsor1Id, $memberId])->delete();
    }

    public function test_sidebar_includes_referral_bonus_link(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/referral-bonus');

        $response->assertStatus(200);
        $response->assertSee(route('admin.referralBonus'));
    }
}
