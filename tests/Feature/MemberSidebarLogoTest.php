<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class MemberSidebarLogoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_member_sidebar_logo_renders_properly_on_dashboard(): void
    {
        $memberId = 'TEST_LOGO_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Logo Test User',
            'email' => 'logotest@example.com',
            'mobile' => '9876543211',
            'phonecode' => '91',
            'sponsorid' => 'Root',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 100,
            'p2p_wallet' => 50,
            'country' => 'India',
            'created_at' => Carbon::now(),
        ]);

        $session = ['MEMBER_ID' => $memberId, 'country' => 'India'];

        $res = $this->withSession($session)->get('/member/dashboard');
        $res->assertStatus(200);

        // Verify nav-header and brand-logo element exist
        $res->assertSee('nav-header', false);
        $res->assertSee('brand-logo', false);
        $res->assertSee('brand-logo-img', false);
        $res->assertSee('logo/name-logo.png', false);

        // Verify custom.css stylesheet is included with cache-buster
        $res->assertSee('uassets/css/custom.css?v=', false);
    }

    public function test_custom_css_contains_increased_logo_height_rules(): void
    {
        $cssPath = public_path('uassets/css/custom.css');
        $this->assertFileExists($cssPath);

        $css = file_get_contents($cssPath);

        // Main desktop logo height rules
        $this->assertStringContainsString('height: 66px !important;', $css);
        $this->assertStringContainsString('max-height: 68px !important;', $css);
        $this->assertStringContainsString('max-width: 220px !important;', $css);
        $this->assertStringContainsString('object-fit: contain !important;', $css);
        $this->assertStringContainsString('transform: translateY(2px) !important;', $css);

        // Collapsed logo rules
        $this->assertStringContainsString('max-height: 48px !important;', $css);
        $this->assertStringContainsString('height: 46px !important;', $css);

        // Mobile logo rules
        $this->assertStringContainsString('height: 44px !important;', $css);
        $this->assertStringContainsString('max-width: 74px !important;', $css);
    }
}
