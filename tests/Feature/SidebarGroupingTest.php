<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class SidebarGroupingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_sidebar_contains_plan_and_income_configuration_section(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Plan & Income Configuration');
        $response->assertSee(route('admin.setPackages'));
        $response->assertSee(route('admin.tradingWalletControl'));
        $response->assertSee(route('admin.monthlyTradingProfit'));
        $response->assertSee(route('admin.referralBonus'));
        $response->assertSee(route('admin.teamTradingProfit'));
        $response->assertSee(route('admin.dailyTeamInvestmentShare'));
    }

    public function test_section_has_active_open_when_visiting_configured_pages(): void
    {
        $pages = [
            '/admin/set-packages',
            '/admin/trading-wallet-control',
            '/admin/monthly-trading-profit',
            '/admin/referral-bonus',
            '/admin/team-trading-profit',
            '/admin/daily-team-investment-share',
        ];

        foreach ($pages as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $response->assertStatus(200);
            $response->assertSee('Plan & Income Configuration');
            $response->assertSee('active open', false);
        }
    }

    public function test_section_is_collapsed_on_dashboard(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $content = $response->getContent();

        // On dashboard, the Plan & Income Configuration nav-item should NOT have 'open'
        $this->assertDoesNotMatchRegularExpression(
            '/<div class="nav-item[^"]*open[^"]*has-sub">\s*<a href="#">\s*<i class="ik ik-sliders"><\/i><span>Plan &amp; Income Configuration<\/span>/s',
            $content
        );
    }

    public function test_no_duplicate_menu_items_for_the_six_routes_in_sidebar(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $content = $response->getContent();

        $routes = [
            route('admin.setPackages'),
            route('admin.tradingWalletControl'),
            route('admin.monthlyTradingProfit'),
            route('admin.referralBonus'),
            route('admin.teamTradingProfit'),
            route('admin.dailyTeamInvestmentShare'),
        ];

        foreach ($routes as $route) {
            $occurrences = substr_count($content, $route);
            $this->assertEquals(
                1,
                $occurrences,
                "Route $route appears $occurrences times in sidebar HTML instead of exactly once."
            );
        }
    }
}
