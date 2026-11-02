<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class MemberChatbotTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_member_chatbot_renders_on_dashboard_with_live_member_data(): void
    {
        $memberId = 'TEST_BOT_'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Chatbot Test User',
            'email' => 'chatbot@example.com',
            'mobile' => '9876543299',
            'phonecode' => '91',
            'sponsorid' => 'ST1234567',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'wallet' => 250.75,
            'trading_wallet' => 700.00,
            'p2p_wallet' => 150.00,
            'pepe_wallet' => 5000,
            'package' => 1000,
            'package_name' => 'Package 2 (600 – 5,000 USDT)',
            'country' => 'India',
            'created_at' => Carbon::now(),
        ]);

        $session = ['MEMBER_ID' => $memberId, 'country' => 'India'];

        $res = $this->withSession($session)->get('/member/dashboard');
        $res->assertStatus(200);

        // Verify Chatbot container and components exist
        $res->assertSee('id="member-chatbot-widget"', false);
        $res->assertSee('id="member-chat-window"', false);
        $res->assertSee('id="mcbLauncherBtn"', false);
        $res->assertSee('Apollo', false);
        $res->assertSee('MEMBER AI', false);

        // Verify Live Member Information is dynamically embedded
        $res->assertSee($memberId, false);
        $res->assertSee('Chatbot Test User', false);
        $res->assertSee('250.75', false);
        $res->assertSee('700.00', false);

        // Verify 100% Real Project Facts exist in the chatbot engine
        $res->assertSee('50 – 500 USDT', false);
        $res->assertSee('600 – 5,000 USDT', false);
        $res->assertSee('6,000 USDT & Above', false);
        $res->assertSee('70% Trading Wallet', false);
        $res->assertSee('90 Days', false);
        $res->assertSee('Hero of the Month', false);
        $res->assertSee('PEPE (BEP-20) Token Promotion Airdrop', false);
        $res->assertSee('10 USDT', false);
    }

    public function test_member_chatbot_renders_on_other_member_views(): void
    {
        $memberId = 'TEST_BOT_'.rand(1000, 9999);
        MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Multi Page Test User',
            'email' => 'multibot@example.com',
            'mobile' => '9876543288',
            'phonecode' => '91',
            'sponsorid' => 'Root',
            'password' => bcrypt('password'),
            'status' => 'Active',
            'country' => 'India',
            'created_at' => Carbon::now(),
        ]);

        $session = ['MEMBER_ID' => $memberId, 'country' => 'India'];

        // 1. Business Plan Text
        $res1 = $this->withSession($session)->get('/member/business-plan-text');
        $res1->assertStatus(200);
        $res1->assertSee('id="member-chatbot-widget"', false);

        // 2. Trading Dashboard
        $res2 = $this->withSession($session)->get('/member/trading-dashboard');
        $res2->assertStatus(200);
        $res2->assertSee('id="member-chatbot-widget"', false);

        // 3. New Investment / Staking Page
        $res3 = $this->withSession($session)->get('/member/package/create');
        $res3->assertStatus(200);
        $res3->assertSee('id="member-chatbot-widget"', false);
    }
}
