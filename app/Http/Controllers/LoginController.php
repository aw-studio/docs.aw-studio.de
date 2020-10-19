<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class LoginController
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGithubProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        $user = $this->createUser($user, 'github');

        Auth::login($user);

        return redirect('/');
    }

    /**
     * Create user for the given service.
     *
     * @param  SocialiteUser $user
     * @param  string        $service
     * @return void
     */
    protected function createUser(SocialiteUser $user, $service)
    {
        return User::updateOrCreate([
            'nickname' => $user->nickname,
            'service'  => $service,
        ], [
            'name'          => $user->name ?: '',
            'email'         => $user->email,
            'avatar_url'    => $user->avatar,
            'payload'       => $user->user,
            'token'         => $user->token,
            'refresh_token' => $user->refreshToken,
        ]);
    }

    /**
     * Do logout.
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
