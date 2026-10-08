<?php

namespace Tests\Feature;

use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use App\Models\WalletTransfer;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class DepositFundTest extends TestCase
{
    private string $memberId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        ImportFund::where('txnid', 'like', '0x_test_%')->delete();
        PackageDetail::where('txnid', 'like', '0x_test_%')->delete();
        StakingDetail::where('txnid', 'like', '0x_test_%')->delete();

        $this->memberId = 'TESTDEP'.rand(10000, 99999);
        MemberDetail::create([
            'memberid' => $this->memberId,
            'name' => 'Deposit Test User',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'depuser'.rand(1000, 9999).'@test.com',
            'password' => bcrypt('password123'),
            'p2p_wallet' => 100.00,
            'status' => 'Active',
        ]);
    }

    protected function tearDown(): void
    {
        ImportFund::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_test_%')->delete();
        PackageDetail::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_test_%')->delete();
        StakingDetail::where('memberid', $this->memberId)->orWhere('txnid', 'like', '0x_test_%')->delete();
        WalletTransfer::where('memberid', $this->memberId)->delete();
        MemberDetail::where('memberid', $this->memberId)->delete();
        parent::tearDown();
    }

    public function test_deposit_fund_page_renders_successfully(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->get('/member/fund/deposit-fund');

        $response->assertStatus(200);
        $response->assertSee('Deposit Fund');
        $response->assertSee('id="memberid"', false);
        $response->assertSee('id="amount"', false);
        $response->assertSee('name="wallet"', false);
    }

    public function test_valid_deposit_credits_100_percent_to_p2p_wallet(): void
    {
        $initialBalance = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $depositAmount = 250.00;

        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => $depositAmount,
                'txnid' => '0x_test_txn_250',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'amount' => $depositAmount,
            'txnid' => '0x_test_txn_250',
            'status' => 'Approved',
        ]);

        $newBalance = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $this->assertEquals($initialBalance + $depositAmount, $newBalance);

        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Fund Added',
            'debit' => $depositAmount,
        ]);
    }

    public function test_invalid_empty_amount(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => '',
                'txnid' => '0x_test_empty_amt',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_non_numeric_amount(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => 'abc',
                'txnid' => '0x_test_non_num',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_negative_amount(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => -50,
                'txnid' => '0x_test_neg_amt',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_duplicate_txnid_is_rejected(): void
    {
        $txnid = '0x_test_duplicate_txnid';

        $first = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => 100,
                'txnid' => $txnid,
            ]);
        $first->assertStatus(200);

        $second = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'amount' => 100,
                'txnid' => $txnid,
            ]);
        $second->assertStatus(422);
        $second->assertJson(['status' => false]);
    }

    public function test_package_distribution_configuration_reads_from_database(): void
    {
        $config = PackageDistribution::getDistributionConfig();

        $this->assertEquals(70.0, $config['p2p_wallet']);
        $this->assertArrayHasKey('referral_bonus', $config);
        $this->assertArrayHasKey('team_trading_profit', $config);
        $this->assertArrayHasKey('team_performance_bonus', $config);
        $this->assertArrayHasKey('hero_of_the_month', $config);
    }
}
