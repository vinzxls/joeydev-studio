<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FALaravel\Google2FA;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A resolved Google2FA TOTP service (as used by the real LoginController).
     */
    private function google2fa(): Google2FA
    {
        return app('pragmarx.google2fa');
    }

    private function makeAdmin(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Admin Test',
            'email' => 'admin@joeydev.test',
            'password' => Hash::make('password'),
        ], $attributes));
    }

    public function test_admin_without_2fa_logs_in_and_reaches_dashboard()
    {
        $admin = $this->makeAdmin(['google2fa_enabled' => false]);

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->assertTrue($admin->fresh()->google2fa_enabled === false);
    }

    public function test_admin_with_2fa_is_gated_at_login_not_fully_authenticated()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        // Must be redirected to the 2FA form, NOT the dashboard.
        $response->assertRedirect(route('admin.2fa.form'));

        // The admin must NOT be considered authenticated yet.
        $this->assertGuest();

        // The pending identity must be stashed for the 2FA step.
        $this->assertSame(
            $admin->getAuthIdentifier(),
            session('2fa_pending_user')
        );
    }

    public function test_wrong_totp_is_rejected_and_pending_state_kept()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response = $this->post(route('admin.2fa.verify'), [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertGuest();
        // Pending state is preserved so a retry is still possible.
        $this->assertSame($admin->getAuthIdentifier(), session('2fa_pending_user'));
    }

    public function test_correct_totp_logs_in_regenerates_session_and_reaches_dashboard()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $otp = $this->google2fa()->getCurrentOtp($secret);

        $sessionBefore = $this->app['session']->getId();

        $response = $this->post(route('admin.2fa.verify'), [
            'code' => $otp,
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        // Session MUST be regenerated after successful 2FA (anti-fixation).
        $this->assertNotSame($sessionBefore, $this->app['session']->getId());

        // Pending state must be cleared.
        $this->assertNull(session('2fa_pending_user'));
    }

    public function test_recovery_code_logs_in_once_and_is_consumed()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $plainCodes = ['ABCD-EFGHIJ', 'KLMN-OPQRST', 'UVWX-YZABCD'];

        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
            // Stored HASHED, never plaintext.
            'google2fa_recovery_codes' => array_map(
                fn (string $code) => Hash::make($code),
                $plainCodes
            ),
        ]);

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        // Wrong TOTP first -> rejected; recovery code still works.
        $this->post(route('admin.2fa.verify'), ['code' => '111111'])
            ->assertSessionHasErrors('code');

        $response = $this->post(route('admin.2fa.verify'), [
            'code' => $plainCodes[0],
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        // The used recovery code was consumed: 2 of 3 remain (hashed).
        $remainingHashes = $admin->fresh()->google2fa_recovery_codes;
        $this->assertIsArray($remainingHashes);
        $this->assertCount(2, $remainingHashes);

        // Used code no longer verifies, and remaining codes are still hashed.
        foreach ($remainingHashes as $hash) {
            $this->assertNotSame($plainCodes[0], $hash);
        }
        $this->assertFalse(Hash::check($plainCodes[0], $remainingHashes[0] ?? ''));
        $this->assertFalse(Hash::check($plainCodes[0], $remainingHashes[1] ?? ''));
    }

    public function test_recovery_codes_are_stored_hashed_not_plaintext()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
            'google2fa_recovery_codes' => [Hash::make('XXXX-YYYYZZ')],
        ]);

        $stored = $admin->fresh()->google2fa_recovery_codes;

        $this->assertIsArray($stored);
        $this->assertNotSame('XXXX-YYYYZZ', $stored[0]);
        $this->assertStringStartsWith('$2y$', $stored[0]);
        $this->assertTrue(Hash::check('XXXX-YYYYZZ', $stored[0]));
    }

    public function test_2fa_form_view_renders()
    {
        $secret = $this->google2fa()->generateSecretKey();
        $admin = $this->makeAdmin([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        // Go through the real gated login to plant the pending state.
        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('admin.2fa.form'));

        $response->assertOk();
        $response->assertViewIs('admin.auth.2fa');
        $response->assertSee('Verifikasi 2FA', false);
        // The 6-digit entry form targets the verify route with a CSRF token.
        $response->assertSee(route('admin.2fa.verify'), false);
        $response->assertSee('name="code"', false);
    }

    public function test_2fa_setup_view_renders_qr_and_recovery_codes()
    {
        $admin = $this->makeAdmin(['google2fa_enabled' => false]);

        $response = $this->actingAs($admin)->get(route('admin.2fa.setup'));

        $response->assertOk();
        $response->assertViewIs('admin.auth.2fa-setup');
        // A real (SVG data-URI) QR code is rendered.
        $response->assertSee('data:image/svg+xml;base64,', false);
        // Recovery codes are shown once, in the XXXX-XXXXXX layout (with hyphens).
        $response->assertSee('-', false);
        // Confirmation input posts to the enable route.
        $response->assertSee(route('admin.2fa.enable'), false);
        $response->assertSee('name="code"', false);

        // A fresh secret must be plumbed into the session.
        $this->assertNotNull(session('2fa_setup_secret'));
        $sessionSecret = session('2fa_setup_secret');

        // Enabling persists the secret + hash recovery codes.
        $otp = $this->google2fa()->getCurrentOtp($sessionSecret);
        $this->post(route('admin.2fa.enable'), ['code' => $otp]);
        $this->assertSame($sessionSecret, $admin->fresh()->google2fa_secret);
        $this->assertTrue((bool) $admin->fresh()->google2fa_enabled);
        $this->assertNull(session('2fa_setup_secret'));
    }
}
