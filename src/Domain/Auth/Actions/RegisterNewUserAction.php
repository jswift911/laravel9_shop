<?php

namespace Domain\Auth\Actions;

use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(string $name, string $email, string $password)
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);


        // Можно отлавливать события входа/выхода, когда пароль сброшен и т.д.
        event(new Registered($user));
        //Логиним юзера
        auth()->login($user);
    }
}
