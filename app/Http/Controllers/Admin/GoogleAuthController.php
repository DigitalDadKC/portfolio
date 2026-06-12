<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/gmail.send'])->with(['access_type' => 'offline', 'prompt' => 'consent'])->redirect();
    }

    public function callback() {
        $googleUser = Socialite::driver('google')->user();

        $socialAccount = SocialAccount::where(['provider' => 'google', 'provider_id' => $googleUser->getId()])->first();

        if($socialAccount) {
            $socialAccount->update([
                'access_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
                'nickname' => $googleUser->getNickname(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            Auth::login($socialAccount->user, true);

            return redirect()->route('admin.dashboard');
        }

        $user = null;
        if($googleUser->getEmail()) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName()
                    ?: $googleUser->getNickname()
                    ?: 'GitHub User',

                'email' => $googleUser->getEmail(),
            ]);
        }

        $user->socialAccounts()->create([
            'provider' => 'github',
            'provider_id' => $googleUser->getId(),

            'access_token' => $googleUser->token,
            'refresh_token' => $googleUser->refreshToken,

            'nickname' => $googleUser->getNickname(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::login($user, true);

        return redirect()->route('admin.dashboard');
    }
}
