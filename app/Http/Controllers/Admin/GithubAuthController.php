<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('github')->redirect();
    }

    public function callback() {
        $githubUser = Socialite::driver('github')->user();

        $socialAccount = SocialAccount::where(['provider' => 'github', 'provider_id' => $githubUser->getId()])->first();

        if($socialAccount) {
            $socialAccount->update([
                'access_token' => $githubUser->token,
                'refresh_token' => $githubUser->refreshToken,
                'nickname' => $githubUser->getNickname(),
                'avatar' => $githubUser->getAvatar(),
            ]);

            Auth::login($socialAccount->user, true);

            return redirect()->route('admin.dashboard');
        }

        $user = null;
        if($githubUser->getEmail()) {
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $githubUser->getName()
                    ?: $githubUser->getNickname()
                    ?: 'GitHub User',

                'email' => $githubUser->getEmail(),
            ]);
        }

        $user->socialAccounts()->create([
            'provider' => 'github',
            'provider_id' => $githubUser->getId(),

            'access_token' => $githubUser->token,
            'refresh_token' => $githubUser->refreshToken,

            'nickname' => $githubUser->getNickname(),
            'avatar' => $githubUser->getAvatar(),
        ]);

        Auth::login($user, true);

        return redirect()->route('admin.dashboard');
    }
}
