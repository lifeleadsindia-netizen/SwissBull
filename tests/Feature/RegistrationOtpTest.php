<?php

namespace Tests\Feature;

use App\Models\MemberDetail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationOtpTest extends TestCase
{
    /**
     * Test that registration view renders the corrected OTP URLs without whitespace.
     */
    public function test_registration_view_renders_correct_otp_urls(): void
    {
        $response = $this->withSession([
            'address' => '0x9999999999999999999999999999999999999999',
        ])->get('/member/register/1');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Must not contain the old malformed URLs
        $this->assertStringNotContainsString("url(' / send_register_otp ')", $content);
        $this->assertStringNotContainsString("url(' / verify_register_otp ')", $content);
        $this->assertStringNotContainsString('/ / send_register_otp', $content);
        $this->assertStringNotContainsString('/ / verify_register_otp', $content);

        // Must contain the corrected URLs
        $this->assertMatchesRegularExpression('#send_register_otp(?!\s)#', $content);
        $this->assertMatchesRegularExpression('#verify_register_otp(?!\s)#', $content);
    }

    /**
     * Test that POST /send_register_otp does not return HTTP 405 and generates OTP.
     */
    public function test_send_register_otp_does_not_return_405(): void
    {
        Mail::fake();

        $email = 'unit_test_' . time() . '@example.com';

        $response = $this->postJson('/send_register_otp', [
            'email' => $email,
        ]);

        $response->assertStatus(200);
        $this->assertNotEquals(405, $response->getStatusCode());

        $data = $response->json();
        $this->assertEquals(1, $data['code']);
        $this->assertArrayHasKey('otp', $data);
        $this->assertEquals($data['otp'], session('register_otp'));
        $this->assertEquals($email, session('register_email'));
    }

    /**
     * Test that POST /verify_register_otp works and verifies the code.
     */
    public function test_verify_register_otp_verifies_valid_code(): void
    {
        $otp = 847291;
        $email = 'verify_test@example.com';

        $response = $this->withSession([
            'register_otp' => $otp,
            'register_email' => $email,
            'register_otp_time' => time(),
        ])->postJson('/verify_register_otp', [
            'otp' => $otp,
        ]);

        $response->assertStatus(200);
        $this->assertNotEquals(405, $response->getStatusCode());

        $data = $response->json();
        $this->assertEquals(1, $data['code']);
    }

    /**
     * Test that POST /verify_register_otp rejects invalid code.
     */
    public function test_verify_register_otp_rejects_invalid_code(): void
    {
        $otp = 847291;
        $email = 'verify_test@example.com';

        $response = $this->withSession([
            'register_otp' => $otp,
            'register_email' => $email,
            'register_otp_time' => time(),
        ])->postJson('/verify_register_otp', [
            'otp' => 999999,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(0, $data['code']);
    }
}
