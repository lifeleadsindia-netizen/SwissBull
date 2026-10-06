<?php

namespace Tests\Feature;

use App\Models\ImportFund;
use App\Models\MemberDetail;
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
        ImportFund::where('memberid', $this->memberId)->delete();
        WalletTransfer::where('memberid', $this->memberId)->delete();
        MemberDetail::where('memberid', $this->memberId)->delete();
        parent::tearDown();
    }

    public function test_deposit_fund_page_contains_package_dropdown_and_options(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->get('/member/fund/deposit-fund');

        $response->assertStatus(200);
        $response->assertSee('name="package"', false);
        $response->assertSee('value="50-500"', false);
        $response->assertSee('value="600-5000"', false);
        $response->assertSee('value="6000+"', false);
        $response->assertSee('50 - 500');
        $response->assertSee('600 - 5000');
        $response->assertSee('6000 and above');
    }

    public function test_valid_deposit_package_1_boundary_50(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 50,
                'txnid' => '0x_test_txn_50',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '50-500',
            'amount' => 50,
            'txnid' => '0x_test_txn_50',
            'status' => 'Approved',
        ]);
    }

    public function test_valid_deposit_package_1_boundary_500(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 500,
                'txnid' => '0x_test_txn_500',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '50-500',
            'amount' => 500,
            'txnid' => '0x_test_txn_500',
        ]);
    }

    public function test_valid_deposit_package_2_boundary_600(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => 600,
                'txnid' => '0x_test_txn_600',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '600-5000',
            'amount' => 600,
        ]);
    }

    public function test_valid_deposit_package_2_boundary_5000(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => 5000,
                'txnid' => '0x_test_txn_5000',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '600-5000',
            'amount' => 5000,
        ]);
    }

    public function test_valid_deposit_package_3_boundary_6000(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '6000+',
                'amount' => 6000,
                'txnid' => '0x_test_txn_6000',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '6000+',
            'amount' => 6000,
        ]);
    }

    public function test_valid_deposit_package_3_above_6000(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '6000+',
                'amount' => 8500,
                'txnid' => '0x_test_txn_8500',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $this->assertDatabaseHas('import_funds', [
            'memberid' => $this->memberId,
            'package' => '6000+',
            'amount' => 8500,
        ]);
    }

    public function test_invalid_package_1_below_minimum_49(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 49,
                'txnid' => '0x_test_invalid_49',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_package_1_above_maximum_501(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => 501,
                'txnid' => '0x_test_invalid_501',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_package_2_below_minimum_599(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => 599,
                'txnid' => '0x_test_invalid_599',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_package_2_above_maximum_5001(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '600-5000',
                'amount' => 5001,
                'txnid' => '0x_test_invalid_5001',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_package_3_below_minimum_5999(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '6000+',
                'amount' => 5999,
                'txnid' => '0x_test_invalid_5999',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_empty_package(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '',
                'amount' => 100,
                'txnid' => '0x_test_empty_pkg',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_invalid_empty_amount(): void
    {
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
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
                'package' => '50-500',
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
                'package' => '50-500',
                'amount' => -50,
                'txnid' => '0x_test_neg_amt',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => false]);
    }

    public function test_wallet_balance_and_transfer_ledger_updated_properly(): void
    {
        $initialBalance = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');

        $depositAmount = 250.00;
        $response = $this->withSession(['MEMBER_ID' => $this->memberId])
            ->postJson(route('addFund'), [
                'memberid' => $this->memberId,
                'package' => '50-500',
                'amount' => $depositAmount,
                'txnid' => '0x_test_wallet_check',
            ]);

        $response->assertStatus(200);

        $newBalance = (float) MemberDetail::where('memberid', $this->memberId)->value('p2p_wallet');
        $this->assertEquals($initialBalance + $depositAmount, $newBalance);

        $this->assertDatabaseHas('wallet_transfers', [
            'memberid' => $this->memberId,
            'walletType' => 'Fund Added',
            'debit' => $depositAmount,
        ]);
    }
}
