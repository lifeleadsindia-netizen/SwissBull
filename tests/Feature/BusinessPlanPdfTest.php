<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use Tests\TestCase;

class BusinessPlanPdfTest extends TestCase
{
    public function test_business_plan_pdf_shows_updated_incomes_for_all_three_languages(): void
    {
        $memberId = 'TEST'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Plan Tester',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'plan'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->get('/member/business-plan-pdf');

        $response->assertStatus(200);

        // Check that updated incomes appear
        $response->assertSee('Level Income on Activation');
        $response->assertSee('Level Income on Staking');
        $response->assertSee('Single Leg Income from 15 Stages');
        $response->assertSee('Team Withdrawal Commission');
        $response->assertSee('Partnership Income');
        $response->assertSee('Promotion Airdrop');

        // Check Chinese translated incomes
        $response->assertSee('账户激活层级收益');
        $response->assertSee('质押层级收益');
        $response->assertSee('15个阶段单线收益');
        $response->assertSee('团队提现佣金');
        $response->assertSee('合伙人分红收益');
        $response->assertSee('推广空投奖励');

        // Check Russian translated incomes
        $response->assertSee('уровневый доход от активации');
        $response->assertSee('уровневый доход от стейкинга');
        $response->assertSee('доход по одной линии из 15 этапов');
        $response->assertSee('комиссионные от вывода средств команды');
        $response->assertSee('партнерский доход');
        $response->assertSee('промо-аирдроп');

        // Check that old text "daily ROI returns" does NOT appear on the page
        $response->assertDontSee('daily ROI returns');

        $member->delete();
    }
}
