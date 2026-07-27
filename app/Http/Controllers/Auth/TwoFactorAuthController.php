<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthController extends Controller
{
    /**
     * Show 2FA Verification / Setup Page.
     */
    public function show()
    {
        $userId = session('2fa_user_id') ?? Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $secret = $user->google2fa_secret;
        $isSetup = false;

        if (empty($secret)) {
            $isSetup = true;
            if (!session()->has('temp_2fa_secret')) {
                session(['temp_2fa_secret' => $this->generateSecretKey()]);
            }
            $secret = session('temp_2fa_secret');
        }

        return view('auth.two-factor', compact('user', 'isSetup', 'secret'));
    }

    /**
     * Verify 2FA 6-digit Code for Login or Signup Setup.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $userId = session('2fa_user_id') ?? Auth::id();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Session expired.'], 401);
        }

        $user = User::find($userId);
        $code = trim($request->input('code'));

        if (empty($user->google2fa_secret)) {
            // Registering initial 2FA secret
            $tempSecret = session('temp_2fa_secret');
            if (empty($tempSecret)) {
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Session expired. Please try again.'], 422);
                }
                return back()->withErrors(['code' => 'Session expired. Please try again.']);
            }

            if ($this->verifyKey($tempSecret, $code)) {
                $user->google2fa_secret = $tempSecret;
                $user->save();

                session()->forget('temp_2fa_secret');
                session()->forget('2fa_user_id');
                session(['2fa_verified' => true]);

                Auth::login($user);

                if ($request->wantsJson()) {
                    return response()->json(['status' => 'success', 'message' => '2FA verified & setup complete!']);
                }

                return redirect()->intended(route('dashboard', absolute: false));
            }
        } else {
            // Verifying existing 2FA code
            if ($this->verifyKey($user->google2fa_secret, $code)) {
                session()->forget('2fa_user_id');
                session(['2fa_verified' => true]);

                Auth::login($user);

                if ($request->wantsJson()) {
                    return response()->json(['status' => 'success', 'message' => '2FA authentication successful!']);
                }

                return redirect()->intended(route('dashboard', absolute: false));
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid 6-digit 2FA code.'], 422);
        }

        return back()->withErrors(['code' => 'The provided 6-digit verification code is invalid. Please try again.']);
    }

    /**
     * Generate Base32 Secret Key (16 characters).
     */
    public function generateSecretKey($length = 16)
    {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $base32chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Verify 6-digit TOTP code.
     */
    public function verifyKey($secret, $key, $drift = 1)
    {
        $decodedSecret = $this->base32Decode($secret);
        if (!$decodedSecret) {
            return false;
        }

        $currentTimeSlice = floor(time() / 30);

        for ($i = -$drift; $i <= $drift; $i++) {
            $timeSlice = $currentTimeSlice + $i;
            $timePacked = pack('N*', 0) . pack('N*', $timeSlice);
            $hash = hash_hmac('sha1', $timePacked, $decodedSecret, true);
            $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
            $truncatedHash = substr($hash, $offset, 4);
            $num = unpack('N', $truncatedHash)[1];
            $num = $num & 0x7FFFFFFF;
            $calculatedCode = str_pad($num % 1000000, 6, '0', STR_PAD_LEFT);

            if ($calculatedCode === trim($key)) {
                return true;
            }
        }

        return false;
    }

    private function base32Decode($secret)
    {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper($secret);
        $binaryString = '';

        for ($i = 0; $i < strlen($secret); $i++) {
            $char = $secret[$i];
            $pos = strpos($base32chars, $char);
            if ($pos === false) continue;
            $binaryString .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $bytes = '';
        for ($i = 0; $i + 8 <= strlen($binaryString); $i += 8) {
            $bytes .= chr(bindec(substr($binaryString, $i, 8)));
        }

        return $bytes;
    }
}
