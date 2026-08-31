<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Google2FA;

class LoginController extends Controller
{
    /**
     * The Google2FA TOTP instance (resolved from the container for testability).
     */
    protected function google2fa(): Google2FA
    {
        return app('pragmarx.google2fa');
    }

    public function form()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Validate credentials WITHOUT authenticating the session. This keeps an
        // admin with 2FA enabled from ever being "partially logged in".
        if (! Auth::validate($credentials)) {
            return back()->withInput()->withErrors(['email' => 'Email atau password salah.']);
        }

        /** @var User|null $user */
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // OPTIONAL & NON-BLOCKING: only gate when the admin explicitly enabled 2FA.
        if ($user && $user->google2fa_enabled) {
            // Store the pending (validated) identity, but do NOT fully log in yet.
            $request->session()->put('2fa_pending_user', $user->getAuthIdentifier());

            return redirect()->route('admin.2fa.form');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show the 6-digit TOTP entry form for a pending (validated, not yet
     * authenticated) admin.
     */
    public function twoFactorForm(Request $request)
    {
        $pendingId = $request->session()->get('2fa_pending_user');

        if (! $pendingId || ! User::find($pendingId)?->google2fa_enabled) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.2fa');
    }

    /**
     * Verify the pending admin's TOTP (or a one-time recovery code). On success,
     * fully authenticate, regenerate the session id (anti-fixation), and clear
     * the pending state.
     */
    public function twoFactorVerify(Request $request)
    {
        $pendingId = $request->session()->get('2fa_pending_user');

        /** @var User|null $user */
        $user = $pendingId ? User::find($pendingId) : null;

        if (! $user || ! $user->google2fa_enabled) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = trim($data['code']);

        if (! $this->isValidRecoveryCode($user, $code)) {
            $google2fa = $this->google2fa();
            $secret = $user->google2fa_secret;

            if (! $secret || ! $google2fa->verifyKey($secret, $code)) {
                return back()->withErrors(['code' => 'Kode verifikasi tidak valid. Coba lagi.']);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('2fa_pending_user');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show the 2FA setup page: a fresh secret + QR + one-time recovery codes.
     */
    public function twoFactorSetup(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->google2fa_enabled) {
            return redirect()->route('admin.dashboard');
        }

        $this->ensureSetupSecret($request);

        $secret = $request->session()->get('2fa_setup_secret');
        $recoveryCodes = $request->session()->get('2fa_setup_recovery_codes', []);
        $qrCode = $this->google2fa()->getQRCodeInline('JoeyDev Studio', $user?->email ?? '', $secret);

        return view('admin.auth.2fa-setup', [
            'secret' => $secret,
            'qrCode' => $qrCode,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Persist the enabled 2FA secret + hashed recovery codes after confirming
     * the admin can produce a valid TOTP.
     */
    public function twoFactorEnable(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->google2fa_enabled) {
            return redirect()->route('admin.dashboard');
        }

        $this->ensureSetupSecret($request);

        $secret = $request->session()->get('2fa_setup_secret');
        $recoveryCodes = $request->session()->get('2fa_setup_recovery_codes', []);

        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $google2fa = $this->google2fa();

        if (! $google2fa->verifyKey($secret, trim($data['code']))) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid. 2FA tidak diaktifkan.'])
                ->withInput();
        }

        $user->forceFill([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
            'google2fa_recovery_codes' => $this->hashRecoveryCodes($recoveryCodes),
        ])->save();

        $request->session()->forget('2fa_setup_secret');
        $request->session()->forget('2fa_setup_recovery_codes');

        return redirect()->route('admin.dashboard')->with('status', 'Autentikasi dua faktor (2FA) berhasil diaktifkan.');
    }

    /**
     * Seed the setup session with a fresh secret + recovery codes once per
     * setup page load (never on an already-enabled account).
     */
    protected function ensureSetupSecret(Request $request): void
    {
        if (! $request->session()->has('2fa_setup_secret')) {
            $request->session()->put('2fa_setup_secret', $this->google2fa()->generateSecretKey());
            $request->session()->put('2fa_setup_recovery_codes', $this->generateRecoveryCodes(3));
        }
    }

    /**
     * @return array<int, string> plaintext codes shown once at setup
     */
    protected function generateRecoveryCodes(int $count): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $raw = Str::upper(Str::random(10));
            $codes[] = substr($raw, 0, 4).'-'.substr($raw, 4, 6);
        }

        return $codes;
    }

    /**
     * @param  array<int, string>  $plainCodes
     * @return array<int, string> bcrypt hashes (never plaintext)
     */
    protected function hashRecoveryCodes(array $plainCodes): array
    {
        return array_map(fn (string $code) => Hash::make($code), $plainCodes);
    }

    /**
     * Check whether the submitted value is a valid, unused recovery code. If it
     * matches, that code is consumed (removed) from the stored set.
     */
    protected function isValidRecoveryCode(User $user, string $code): bool
    {
        $hashes = $user->google2fa_recovery_codes ?? [];

        if (! is_array($hashes) || $hashes === []) {
            return false;
        }

        foreach ($hashes as $index => $hash) {
            if (Hash::check($code, $hash)) {
                unset($hashes[$index]);
                $user->forceFill(['google2fa_recovery_codes' => array_values($hashes)])->save();

                return true;
            }
        }

        return false;
    }
}
