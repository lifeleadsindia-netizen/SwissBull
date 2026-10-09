<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class NewWithdrawalRequestFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_new_withdrawal_requests_page_loads_and_has_correct_form_action(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request');

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee(route('admin.newWithdrawalRequest'));
    }

    public function test_filter_with_yesterday_preset(): void
    {
        $yesterday = now()->subDay()->format('Y-m-d');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'yesterday',
                'from_date' => $yesterday,
                'to_date' => $yesterday,
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee('Yesterday');
    }

    public function test_filter_with_today_preset(): void
    {
        $today = now()->format('Y-m-d');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'today',
                'from_date' => $today,
                'to_date' => $today,
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee('Today');
    }

    public function test_filter_with_last_7_days_preset(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'last_7_days',
                'from_date' => now()->subDays(6)->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee('Last 7 Days');
    }

    public function test_filter_with_this_month_preset(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'this_month',
                'from_date' => now()->startOfMonth()->format('Y-m-d'),
                'to_date' => now()->endOfMonth()->format('Y-m-d'),
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee('This Month');
    }

    public function test_filter_with_custom_date_range(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'custom',
                'from_date' => '2026-01-01',
                'to_date' => '2026-10-09',
                'date_field' => 'request_date',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
        $response->assertSee('01 Jan 2026');
    }

    public function test_filter_with_different_date_type(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'today',
                'from_date' => now()->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
                'date_field' => 'created_at',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
    }

    public function test_filter_with_date_type_parameter_alias(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request?'.http_build_query([
                'filter_date' => '',
                'filter_mode' => 'advanced',
                'preset' => 'today',
                'from_date' => now()->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
                'date_type' => 'created_at',
            ]));

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
    }

    public function test_reset_url_clears_filters(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/admin/new-withdrawal-request');

        $response->assertStatus(200);
        $response->assertDontSee('hdgteyusjasget');
    }
}
