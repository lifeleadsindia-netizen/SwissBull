<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use App\Models\WithdrawalRequest;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class PepeRedeemHistoryTest extends TestCase
{
    public function test_unauthenticated_user_redirected_from_pepe_redeem_history(): void
    {
        $response = $this->get('/member/pepe/redeem-history');
        $response->assertRedirect('/member');
    }

    public function test_member_can_view_pepe_redeem_history_and_stats(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberId = 'PEPETEST'.rand(1000, 9999);
        $member = MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Pepe Holder',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'pepe'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'pepe_wallet' => 2000,
            'member_wallet' => '0x71C8fb866A370000000000000000000000000001',
        ]);

        // Create 1 Approved request and 1 Pending request
        $reqApproved = new WithdrawalRequest;
        $reqApproved->request_date = now();
        $reqApproved->request_id = 'PEPE'.rand(100000, 999999);
        $reqApproved->memberid = $memberId;
        $reqApproved->wallet_address = $member->member_wallet;
        $reqApproved->gross_amount = 500;
        $reqApproved->service_charge = 0;
        $reqApproved->net_amount = 500;
        $reqApproved->type = 'Airdrop Withdrawal';
        $reqApproved->status = 'Approved';
        $reqApproved->txnid = '0xabcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890';
        $reqApproved->save();

        $reqPending = new WithdrawalRequest;
        $reqPending->request_date = now();
        $reqPending->request_id = 'PEPE'.rand(100000, 999999);
        $reqPending->memberid = $memberId;
        $reqPending->wallet_address = $member->member_wallet;
        $reqPending->gross_amount = 300;
        $reqPending->service_charge = 0;
        $reqPending->net_amount = 300;
        $reqPending->type = 'Airdrop Withdrawal';
        $reqPending->status = 'Pending';
        $reqPending->save();

        $response = $this->withSession(['MEMBER_ID' => $memberId])
            ->get('/member/pepe/redeem-history');

        $response->assertStatus(200)
            ->assertSee('PEPE Tokens Redeem History')
            ->assertSee($reqApproved->request_id)
            ->assertSee($reqPending->request_id)
            ->assertSee('500 PEPE')
            ->assertSee('300 PEPE')
            ->assertSee('Approved')
            ->assertSee('Pending Approval');

        // Test Filter: Pending only
        $responseFilter = $this->withSession(['MEMBER_ID' => $memberId])
            ->get('/member/pepe/redeem-history?status=Pending');

        $responseFilter->assertStatus(200)
            ->assertSee($reqPending->request_id)
            ->assertDontSee($reqApproved->request_id);

        // Cleanup
        $member->delete();
        $reqApproved->delete();
        $reqPending->delete();
    }
}
