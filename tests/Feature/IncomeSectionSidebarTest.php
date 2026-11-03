<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class IncomeSectionSidebarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_income_section_is_collapsed_on_income_pages(): void
    {
        $pages = [
            '/hdgteyusjasget/income/referral-bonus',
            '/hdgteyusjasget/income/team-trading-profit',
            '/hdgteyusjasget/income/daily-team-investment-share',
            '/hdgteyusjasget/income/hero-of-the-month',
        ];

        foreach ($pages as $url) {
            $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
                ->get($url);

            $response->assertStatus(200);
            $content = $response->getContent();

            // Income section should NOT contain the 'open' class
            $this->assertDoesNotMatchRegularExpression(
                '/<div\s+class="[^"]*open[^"]*has-sub">\s*<a href="#">\s*<i class="ik ik-dollar-sign"><\/i><span>Income Section<\/span>/s',
                $content,
                "Income Section should be collapsed (no 'open' class) when visiting $url."
            );

            // Income section should still have 'active' class for parent highlighting
            $this->assertMatchesRegularExpression(
                '/<div\s+class="[^"]*active[^"]*has-sub">\s*<a href="#">\s*<i class="ik ik-dollar-sign"><\/i><span>Income Section<\/span>/s',
                $content,
                "Income Section should retain 'active' class when visiting $url."
            );
        }
    }

    public function test_income_section_is_collapsed_on_dashboard(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/dashboard');

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="[^"]*open[^"]*has-sub">\s*<a href="#">\s*<i class="ik ik-dollar-sign"><\/i><span>Income Section<\/span>/s',
            $content
        );
    }
}
