<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $loginPath = '/mlogin';

    // Reset lewat tautan email membuktikan email aktif, jadi sekalian terverifikasi.
    // User tidak di-login otomatis; diminta login ulang dengan kata sandi baru.
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));
        $user->save();

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        event(new PasswordReset($user));

        $this->loginPath = $user->role === 'member' ? '/mlogin' : '/login';
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect()->route('password.success')->with('login_url', url($this->loginPath));
    }
}
