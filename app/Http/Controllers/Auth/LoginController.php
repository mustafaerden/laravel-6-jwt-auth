<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function attemptLogin(Request $request)
    {
        $token = $this->guard()->attempt($this->credentials($request));

        if (!$token) {
            return false;
        }

        // get authenticated user;
        $user = $this->guard()->user();

        // email verify olmusmu;
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return false;
        }

        // set Token;
        $this->guard()->setToken($token);

        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        // get the token from the authentication guard(JWT);
        $token = (string)$this->guard()->getToken();

        //extract the expiry date of token;
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }

    protected function sendFailedLoginResponse()
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "verification" => "You need to verify your email account"
                ]
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => "Invalid credentials"
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
