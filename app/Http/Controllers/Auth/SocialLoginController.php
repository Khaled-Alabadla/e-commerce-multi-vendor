<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        try {

            $provider_user = Socialite::driver($provider)->user();

            // dd($provider_user);

            $user = User::where([
                'provider' => $provider,
                'provider_id' => $provider_user->id
            ])->first();


            if (!$user) {

                $user = User::create([
                    'name' => $provider_user->name,
                    'email' => fake()->safeEmail(),
                    'password' => Hash::make(Str::random(8)),
                    'provider' => $provider,
                    'provider_id' => $provider_user->id,
                    'provider_token' => $provider_user->token
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');

        } catch (InvalidStateException $e) {

            // throw $e;

            return redirect()->route('login');
        }
    }
}
