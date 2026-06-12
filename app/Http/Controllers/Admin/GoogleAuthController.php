<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        $googleUser = Socialite::driver('github')->user();

        $socialAccount = SocialAccount::where(['provider' => 'google', 'provider_id' => $googleUser->getId()])->first();
        dd($googleUser, $socialAccount);
    }
}
