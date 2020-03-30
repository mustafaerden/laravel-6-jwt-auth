<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |forgot password emailini gonderdik. burda da reset password islemini yapiyoruz;
    |
    */

    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json([
            'status' => trans($response)
        ], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'email' => trans($response)
        ], 422);
    }
}
