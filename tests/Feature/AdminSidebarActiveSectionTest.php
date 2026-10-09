<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class AdminSidebarActiveSectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function getNavClassesForSection(string $html, string $titleSnippet): ?string
    {
        $pos = strpos($html, '<span>'.$titleSnippet);
        if ($pos === false) {
            $pos = strpos($html, $titleSnippet);
        }
        if ($pos === false) {
            return null;
        }

        $before = substr($html, 0, $pos);
        $divPos = strrpos($before, '<div');
        if ($divPos === false) {
            return null;
        }

        $divTag = substr($before, $divPos);
        if (preg_match('/class="([^"]+)"/i', $divTag, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function isSectionActiveOpen(string $html, string $titleSnippet): bool
    {
        $class = $this->getNavClassesForSection($html, $titleSnippet);
        $this->assertNotNull($class, "Could not find nav-item for section '{$titleSnippet}'");

        return str_contains($class, 'active') && str_contains($class, 'open');
    }

    public function test_income_referral_bonus_activates_only_income_section(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/referral-bonus');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Plan &amp; Income'),
            'Plan & Income Configuration must not be active/open on /admin/income/referral-bonus'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'ROI Section'),
            'ROI Section must not be active/open on /admin/income/referral-bonus'
        );
        $this->assertTrue(
            $this->isSectionActiveOpen($html, 'Income Section'),
            'Income Section should be active/open on /admin/income/referral-bonus'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Partnership Section'),
            'Partnership Section must not be active/open on /admin/income/referral-bonus'
        );

        // Child menu item verification
        $this->assertMatchesRegularExpression('/<a href="[^"]*admin\/income\/referral-bonus"[^>]*class="menu-item active"/', $html);
        $this->assertDoesNotMatchRegularExpression('/<a href="[^"]*admin\/referral-bonus"[^>]*class="menu-item active"/', $html);
    }

    public function test_roi_monthly_trading_profit_activates_only_roi_section(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/monthly-trading-profit');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Plan &amp; Income'),
            'Plan & Income Configuration must not be active/open on /admin/income/monthly-trading-profit'
        );
        $this->assertTrue(
            $this->isSectionActiveOpen($html, 'ROI Section'),
            'ROI Section should be active/open on /admin/income/monthly-trading-profit'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Income Section'),
            'Income Section must not be active/open on /admin/income/monthly-trading-profit'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Partnership Section'),
            'Partnership Section must not be active/open on /admin/income/monthly-trading-profit'
        );

        // Child menu item verification
        $this->assertMatchesRegularExpression('/<a href="[^"]*admin\/income\/monthly-trading-profit"[^>]*class="menu-item active"/', $html);
        $this->assertDoesNotMatchRegularExpression('/<a href="[^"]*admin\/monthly-trading-profit"[^>]*class="menu-item active"/', $html);
    }

    public function test_roi_details_activates_only_roi_section(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/roi-details');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertFalse($this->isSectionActiveOpen($html, 'Plan &amp; Income'));
        $this->assertTrue($this->isSectionActiveOpen($html, 'ROI Section'));
        $this->assertFalse($this->isSectionActiveOpen($html, 'Income Section'));
        $this->assertMatchesRegularExpression('/<a href="[^"]*admin\/income\/roi-details"[^>]*class="menu-item active"/', $html);
    }

    public function test_plan_config_monthly_trading_profit_activates_only_plan_config(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/monthly-trading-profit');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertTrue(
            $this->isSectionActiveOpen($html, 'Plan &amp; Income'),
            'Plan & Income Configuration should be active/open on /admin/monthly-trading-profit'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'ROI Section'),
            'ROI Section must not be active/open on /admin/monthly-trading-profit'
        );
        $this->assertFalse(
            $this->isSectionActiveOpen($html, 'Income Section'),
            'Income Section must not be active/open on /admin/monthly-trading-profit'
        );

        // Child menu item verification
        $this->assertMatchesRegularExpression('/<a href="[^"]*admin\/monthly-trading-profit"[^>]*class="menu-item active"/', $html);
        $this->assertDoesNotMatchRegularExpression('/<a href="[^"]*admin\/income\/monthly-trading-profit"[^>]*class="menu-item active"/', $html);
    }

    public function test_plan_config_referral_bonus_activates_only_plan_config(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/referral-bonus');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertTrue($this->isSectionActiveOpen($html, 'Plan &amp; Income'));
        $this->assertFalse($this->isSectionActiveOpen($html, 'Income Section'));
        $this->assertFalse($this->isSectionActiveOpen($html, 'ROI Section'));

        $this->assertMatchesRegularExpression('/<a href="[^"]*admin\/referral-bonus"[^>]*class="menu-item active"/', $html);
        $this->assertDoesNotMatchRegularExpression('/<a href="[^"]*admin\/income\/referral-bonus"[^>]*class="menu-item active"/', $html);
    }

    public function test_all_income_pages_activate_only_income_section(): void
    {
        $incomeUrls = [
            '/admin/income/referral-bonus',
            '/admin/income/team-trading-profit',
            '/admin/income/daily-team-investment-share',
            '/admin/income/hero-of-the-month',
        ];

        foreach ($incomeUrls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $this->assertSame(200, $response->getStatusCode(), "Failed loading {$url}");
            $html = $response->getContent();

            $this->assertFalse(
                $this->isSectionActiveOpen($html, 'Plan &amp; Income'),
                "Plan Config incorrectly active on {$url}"
            );
            $this->assertFalse(
                $this->isSectionActiveOpen($html, 'ROI Section'),
                "ROI Section incorrectly active on {$url}"
            );
            $this->assertTrue(
                $this->isSectionActiveOpen($html, 'Income Section'),
                "Income Section should be active on {$url}"
            );
        }
    }

    public function test_all_plan_config_pages_activate_only_plan_config(): void
    {
        $planUrls = [
            '/admin/set-packages',
            '/admin/trading-wallet-control',
            '/admin/monthly-trading-profit',
            '/admin/referral-bonus',
            '/admin/team-trading-profit',
            '/admin/daily-team-investment-share',
        ];

        foreach ($planUrls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $this->assertSame(200, $response->getStatusCode(), "Failed loading {$url}");
            $html = $response->getContent();

            $this->assertTrue(
                $this->isSectionActiveOpen($html, 'Plan &amp; Income'),
                "Plan Config should be active on {$url}"
            );
            $this->assertFalse(
                $this->isSectionActiveOpen($html, 'ROI Section'),
                "ROI Section incorrectly active on {$url}"
            );
            $this->assertFalse(
                $this->isSectionActiveOpen($html, 'Income Section'),
                "Income Section incorrectly active on {$url}"
            );
        }
    }

    public function test_partnership_pages_activate_only_partnership_section(): void
    {
        $partnershipUrls = [
            '/admin/income/partnership-incomes',
            '/admin/income/partnership-details',
        ];

        foreach ($partnershipUrls as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $this->assertSame(200, $response->getStatusCode(), "Failed loading {$url}");
            $html = $response->getContent();

            $this->assertFalse($this->isSectionActiveOpen($html, 'Plan &amp; Income'), "Plan Config incorrectly active on {$url}");
            $this->assertFalse($this->isSectionActiveOpen($html, 'ROI Section'), "ROI Section incorrectly active on {$url}");
            $this->assertFalse($this->isSectionActiveOpen($html, 'Income Section'), "Income Section incorrectly active on {$url}");
            $this->assertTrue($this->isSectionActiveOpen($html, 'Partnership Section'), "Partnership Section should be active on {$url}");
        }
    }

    public function test_daily_team_investment_share_page_title_displays_correct_title(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/income/daily-team-investment-share');

        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertMatchesRegularExpression('/<title>\s*Daily Team Investment Share\s*\|\s*Admin\s*<\/title>/i', $html);
        $this->assertStringNotContainsString('<title>Level Income', $html);
    }
}
