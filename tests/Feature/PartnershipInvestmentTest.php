<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\PartnershipDetail;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class PartnershipInvestmentTest extends TestCase
{
    public function test_view_create_partnership_page_shows_4_levels(): void
    {
        $memberId = 'PART'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Partner User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'partner'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'p2p_wallet' => 50000,
            'partnership_package' => 0,
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->get('/member/partnership/create-investment');

        $response->assertStatus(200);
        $response->assertSee('Partnership Program');
        $response->assertDontSee('14. Partnership Program');
        $response->assertSee('Silver – 1,000 USDT');
        $response->assertSee('Gold – 5,000 USDT');
        $response->assertSee('Platinum – 10,000 USDT');
        $response->assertSee('Diamond – 25,000 USDT');
        $response->assertSee('Only higher package will be applicable.');
    }

    public function test_create_silver_investment_success(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PART'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'sponsorid' => 'Root',
            'name' => 'Silver Investor',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'silver'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'p2p_wallet' => 15000,
            'partnership_package' => 0,
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->post('/member/partCreateInvest', [
                'memberid' => $memberId,
                'amount' => 1000,
            ]);

        $response->assertSessionHas('successMsg');

        $member->refresh();
        $this->assertEquals(14000, (float) $member->p2p_wallet);
        $this->assertEquals(1000, (float) $member->partnership_package);
        $this->assertEquals('Silver', $member->partnership_rank);

        $detail = PartnershipDetail::where('memberid', $memberId)->where('status', 'Active')->first();
        $this->assertNotNull($detail);
        $this->assertEquals(1000, (float) $detail->invest_amount);
        $this->assertEquals(2, (float) $detail->rate);
        $this->assertEquals(2000, (float) $detail->capping);
        $this->assertEquals('Silver', $detail->rank);
    }

    public function test_cannot_downgrade_or_repeat_same_package(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PART'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $memberId,
            'sponsorid' => 'Root',
            'name' => 'Existing Silver User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'same'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'p2p_wallet' => 30000,
            'partnership_package' => 1000,
            'partnership_rank' => 'Silver',
        ]);

        // Attempting to buy 1,000 again (same package)
        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->post('/member/partCreateInvest', [
                'memberid' => $memberId,
                'amount' => 1000,
            ]);

        $response->assertSessionHas('failedMsg', 'Only higher package will be applicable.');

        // Attempting to buy 500 (lower package)
        $response2 = $this->withSession(['MEMBER_ID' => $memberId])
            ->post('/member/partCreateInvest', [
                'memberid' => $memberId,
                'amount' => 500,
            ]);

        $response2->assertSessionHas('failedMsg');
    }

    public function test_upgrade_to_gold_replaces_silver(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PART'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'sponsorid' => 'Root',
            'name' => 'Upgrader User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'up'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'p2p_wallet' => 30000,
            'partnership_package' => 1000,
            'partnership_rank' => 'Silver',
        ]);

        // Existing active silver
        $silverDetail = PartnershipDetail::create([
            'memberid' => $memberId,
            'invest_date' => now(),
            'invest_amount' => 1000,
            'installments' => 1,
            'capping_x' => 2,
            'capping' => 2000,
            'rank' => 'Silver',
            'rate' => 2,
            'status' => 'Active',
        ]);

        // Upgrade to Gold (5,000 USDT)
        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->post('/member/partCreateInvest', [
                'memberid' => $memberId,
                'amount' => 5000,
            ]);

        $response->assertSessionHas('successMsg');

        $silverDetail->refresh();
        $this->assertEquals('Deactive', $silverDetail->status);

        $member->refresh();
        $this->assertEquals(25000, (float) $member->p2p_wallet);
        $this->assertEquals(5000, (float) $member->partnership_package);
        $this->assertEquals('Gold', $member->partnership_rank);

        $goldDetail = PartnershipDetail::where('memberid', $memberId)->where('status', 'Active')->first();
        $this->assertNotNull($goldDetail);
        $this->assertEquals(5000, (float) $goldDetail->invest_amount);
        $this->assertEquals(4, (float) $goldDetail->rate);
        $this->assertEquals(20000, (float) $goldDetail->capping);
        $this->assertEquals('Gold', $goldDetail->rank);
    }
}
