<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;


class SocialAuthController extends Controller
{
    //Socialite github
    public function redirect(string $driver): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $e) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }
    }

    public function callback(string $driver): RedirectResponse
    {
        if ($driver != 'github') {
            throw new DomainException('Драйвер не поддерживается');
        }
        $githubUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $githubUser->id,
        ], [
            //Если в гитхаб нет имени, то емаил
            'name' => $githubUser->name ?? $githubUser->email,
            'email' => $githubUser->email,
            'password' => bcrypt(str()->random(20)),
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
