<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\CheckSamePassword;
// use Grimzy\LaravelMysqlSpatial\Types\Point;

class SettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
       $user = auth()->user();

       $this->validate($request, [
           'tagline' => ['required'],
           'name' => ['required'],
           'about' => ['required', 'string', 'min:20'],
           'formatted_address' => ['required']
       ]);

       $user->update([
           'tagline' => $request->tagline,
           'name' => $request->name,
           'about' => $request->about,
           'formatted_address' => $request->formatted_address,
           'available_to_hire' => $request->available_to_hire
       ]);

       return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        // rule kullanarak kullanicinin girdigi current password gercekten onun sifresimi kontrolu,
        // ayrica yine rule ile yeni girilen sifre eskisi ile ayni ise update etmiycez.
        //php artisan make:rule MatchOldPassword
        //php artisan make:rule CheckSamePassword


        $user = auth()->user();

        $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword],
            'password' =>  ['required', 'confirmed', 'min:6', new CheckSamePassword]
        ]);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Password updated successfully.'
        ], 200);
    }
}
